<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please log in.']);
    exit;
}


try {
    // Fetch events from the database
    $query = "SELECT id, name, description, date, max_capacity, created_at FROM events";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $events]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No events found.']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 
    
?>
