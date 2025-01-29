<?php

$servername = "localhost";
$username = "root"; // Removed trailing space
$password = "";     // No trailing space
$dbname = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
