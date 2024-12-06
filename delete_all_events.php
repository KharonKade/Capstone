<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete all archived events
if (isset($_POST['delete_all'])) {
    $delete_sql = "DELETE FROM upcoming_events WHERE status = 'archived'";
    if ($conn->query($delete_sql) === TRUE) {
        // Redirect to the archived events page after deletion
        header("Location: archived_events.php?deleted=true");
        exit();
    } else {
        echo "<p>Error deleting archived events: " . $conn->error . "</p>";
    }
}

$conn->close();
?>
