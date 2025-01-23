<?php

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = [];

    if ($_POST['action'] === 'fetchEvents') {
        $result = $conn->query("SELECT id, name, max_capacity, (SELECT COUNT(*) FROM event_attendees WHERE event_id = events.id) AS current_capacity FROM events");
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        echo json_encode($events);
        exit;
    }

    if ($_POST['action'] === 'register') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
        $event_ids = $_POST['event_ids'];

        $stmt = $conn->prepare("SELECT COUNT(*) FROM attendees WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email_exists);
        $stmt->fetch();
        $stmt->close();

        if ($email_exists) {
            $response['error'][] = "Email already exists.";
        }

        if (!empty($response['error'])) {
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO attendees (name, email, phone_number) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $phone_number);
        $stmt->execute();
        $attendee_id = $stmt->insert_id;
        $stmt->close();


        foreach ($event_ids as $event_id) {

            $check = $conn->prepare("SELECT name, max_capacity, (SELECT COUNT(*) FROM event_attendees WHERE event_id = ?) AS current_capacity FROM events WHERE id = ?");
            $check->bind_param("ii", $event_id, $event_id);
            $check->execute();
            $check->bind_result($event_name, $max_capacity, $current_capacity);
            $check->fetch();
            $check->close();

            if ($current_capacity < $max_capacity) {
                $register = $conn->prepare("INSERT INTO event_attendees (event_id, attendee_id, created_at) VALUES (?, ?, now())");
                $register->bind_param("ii", $event_id, $attendee_id);
                $register->execute();
                $register->close();
                $response['success'][] = "Successfully registered for event: " . htmlspecialchars($event_name);
            } else {
                $response['error'][] = "Event '{$event_name}' is full.";
            }
        }
        echo json_encode($response);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event</title>
    <link href="../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js">
    </script>
    <style>
    body {
        background-color: #f8f9fa;
    }

    .form-container {
        max-width: 600px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .form-title {
        text-align: center;
        margin-bottom: 20px;
        color: #495057;
    }

    #events {
        max-width: 100%;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        font-size: 1rem;
    }

    #events option {
        padding: 10px;
        font-size: 0.95rem;
    }

    #events option:checked {
        background-color: #007bff;
        color: #fff;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h3 class="form-title">Register for Events</h3>
            <form id="registrationForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name"
                        required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
                        required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                        placeholder="Enter your phone number" required>
                </div>

                <div class="mb-3">
                    <label for="events" class="form-label">Select Events</label>
                    <select name="event_ids[]" id="events" class="form-select" aria-label="Select Events" multiple
                        required>
                        <option value="" disabled selected>Select events</option> <!-- Placeholder option -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div id="responseMessage" class="mt-3"></div>
        </div>
    </div>

    <script>
    $(document).ready(function() {

        $.post('register_event.php', {
            action: 'fetchEvents'
        }, function(data) {
            const events = JSON.parse(data);
            let options =
                '<option value="" disabled selected>Select events</option>'; // 

            events.forEach(event => {
                options += `
                    <option value="${event.id}">
                        ${event.name} (Capacity: ${event.current_capacity}/${event.max_capacity})
                    </option>`;
            });

            $('#events').html(options);
        });

        $('#registrationForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize() + '&action=register';

            $.post('register_event.php', formData, function(response) {
                const data = JSON.parse(response);
                let message = '';

                if (data.success) {
                    message +=
                        `<div class="alert alert-success">${data.success.join('<br>')}</div>`;
                }

                if (data.error) {
                    message +=
                        `<div class="alert alert-danger">${data.error.join('<br>')}</div>`;
                }

                $('#responseMessage').html(message);

                if (data.success) {
                    $('#registrationForm')[0].reset();
                }
            }).fail(function() {
                $('#responseMessage').html(
                    '<div class="alert alert-danger">An error occurred. Please try again.</div>'
                );
            });
        });
    });
    </script>
</body>

</html>