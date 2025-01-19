<?php
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = intval($_GET['id']);

// Update the event status to 'archived'
$sql = "UPDATE upcoming_events SET status = 'archived' WHERE id = $event_id";
if ($conn->query($sql) === TRUE) {
    header("Location: manage_news.php?msg=Event archived successfully");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
