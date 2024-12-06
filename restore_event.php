<?php
// Database connection (adjust the database name if necessary)
$conn = new mysqli("localhost", "root", "", "basf_events"); // Use correct database name
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the URL
$id = $_GET['id'];

// Update the event to set status back to active (assuming 'active' status is 1)
$sql = "UPDATE upcoming_events SET status = 1 WHERE id = $id";  // Adjust table name if needed

if ($conn->query($sql) === TRUE) {
    // Redirect to the archived events page after restoring the event
    header("Location: archived_events.php?message=restored");
    exit();
} else {
    echo "Error restoring event: " . $conn->error;
}

// Close the connection
$conn->close();
?>
