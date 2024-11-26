<?php
include('db_connection.php');
$id = $_GET['id'];

// Move event from upcoming to previous events
mysqli_query($conn, "INSERT INTO previous_events (event_id) SELECT event_id FROM upcoming_events WHERE event_id = $id");
mysqli_query($conn, "DELETE FROM upcoming_events WHERE event_id = $id");

header("Location: manage_events.php");
?>
