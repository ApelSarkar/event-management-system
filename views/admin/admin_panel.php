<?php
session_start();

require_once '../../db.php';

$query = "SELECT * FROM events ORDER BY created_at DESC";
$result = $conn->query($query);

// Handle CSV download
if (isset($_GET['download']) && isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']); 

    $attendeesQuery = "SELECT a.id, a.name, a.email, a.phone
                       FROM event_attendees ea
                       JOIN attendees a ON ea.attendee_id = a.id
                       WHERE ea.event_id = ?";
    $attendeesStmt = $conn->prepare($attendeesQuery);
    if (!$attendeesStmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $attendeesStmt->bind_param('i', $event_id);
    $attendeesStmt->execute();

    $attendeesResult = $attendeesStmt->get_result();
    if ($attendeesResult->num_rows == 0) {
        die("No attendees found for this event.");
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_event_' . $event_id . '.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Attendee ID', 'Name', 'Email', 'Phone'], ',', '"'); 

    while ($row = $attendeesResult->fetch_assoc()) {
        fputcsv($output, $row, ',', '"'); 
    }

    fclose($output);
    exit();
}

    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }

    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management Dashboard</title>
    <link href="../../../../assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="admin_panel.php">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="admin_panel.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
    </nav>
    <div class="container">
        <h2 class="my-4">Event Management Dashboard</h2>

        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Search Events">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="eventTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Max Capacity</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be inserted here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            function fetchFilteredEvents(searchQuery = '') {
                $.ajax({
                    url: 'fetch_events.php',
                    method: 'GET',
                    data: { search: searchQuery },
                    success: function(response) {
                        $('#eventTable tbody').html(response);
                    }
                });
            }

            $('#searchInput').keyup(function() {
                var searchQuery = $(this).val();
                fetchFilteredEvents(searchQuery);
            });

            fetchFilteredEvents();
        });
    </script>
</body>
</html>
