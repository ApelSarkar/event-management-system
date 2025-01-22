<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

require_once '../db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    $createdBy = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
    $stmt->bind_param("ii", $event_id, $createdBy);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete event.";
    }
}

header("Location: dashboard.php");
exit;
