<?php
// Database connection parameters
$servername = "localhost"; // Host where your MySQL server is running
$username = "root"; // Your MySQL username (default 'root' for XAMPP)
$password = ""; // Your MySQL password (default '' for XAMPP)
$dbname = "basf_events"; // Replace with the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
