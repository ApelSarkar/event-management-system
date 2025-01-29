<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

require_once '../db.php';

$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

if ($eventId) {
    // Fetch attendees information for the event from the event_attendees and attendees table
    $attendeesStmt = $conn->prepare("SELECT attendees.name, attendees.email, attendees.phone, attendees.created_at
                                     FROM event_attendees 
                                     INNER JOIN attendees ON event_attendees.attendee_id = attendees.id 
                                     WHERE event_attendees.event_id = ?");
    $attendeesStmt->bind_param("i", $eventId);
    $attendeesStmt->execute();
    $attendeesResult = $attendeesStmt->get_result();

    if ($attendeesResult->num_rows == 0) {
        $noAttendees = true;
    }
} else {
    echo "Invalid event ID.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendees</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Attendees for Event ID: <?php echo htmlspecialchars($eventId); ?></h3>
            </div>
            <div class="card-body">
                <?php if (isset($noAttendees) && $noAttendees): ?>
                    <p>No attendees have registered for this event yet.</p>
                <?php else: ?>
                    <h5>Attendees:</h5>
                    <?php if ($attendeesResult->num_rows > 0): ?>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($attendee = $attendeesResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($attendee['name']); ?></td>
                                        <td><?php echo htmlspecialchars($attendee['email']); ?></td>
                                        <td><?php echo htmlspecialchars($attendee['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($attendee['created_at']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No attendees have registered for this event yet.</p>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
