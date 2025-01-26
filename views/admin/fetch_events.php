<?php
session_start();

require_once '../../db.php';

$searchQuery = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

$query = "SELECT * FROM events WHERE name LIKE ? OR description LIKE ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $searchQuery, $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['max_capacity'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td>
                <a href='update_event.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm mb-1'>Edit</a>
                <a href='delete_event.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm mb-1'>Delete</a>
                <a href='admin_panel.php?download=csv&event_id=" . $row['id'] . "' class='btn btn-success btn-sm'>Download CSV</a>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No events found</td></tr>";
}
?>
