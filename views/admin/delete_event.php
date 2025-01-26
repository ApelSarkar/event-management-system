<?php
session_start();

require_once '../../db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventId = $_GET['id'];

    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('i', $eventId);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Event deleted successfully!";
            header('Location: admin_panel.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to delete the event. Please try again.";
            header('Location: admin_panel.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Failed to prepare the SQL statement.";
        header('Location: admin_panel.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid event ID.";
    header('Location: admin_panel.php');
    exit();
}
?>
