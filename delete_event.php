<?php
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];

    include('db_connection.php');  // Include the database connection

    // Delete event from the database
    $sql = "DELETE FROM create_event WHERE id = $event_id";
    if ($conn->query($sql) === TRUE) {
        echo "Event deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();  // Close the connection
}
?>
