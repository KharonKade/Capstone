<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'] ?? '';

if (!empty($token)) {
    // Verify token and update status
    $sql = "UPDATE event_registrations SET status='verified' WHERE verification_token='$token'";
    if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
        echo "Your email has been verified! You are now officially registered.";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}

$conn->close();
?>
