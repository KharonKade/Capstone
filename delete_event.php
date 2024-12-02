<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $conn->real_escape_string($_GET['id']);

    // Start transaction for data integrity
    $conn->begin_transaction();

    try {
        // Delete related data
        $conn->query("DELETE FROM event_schedules WHERE event_id = $event_id");
        $conn->query("DELETE FROM event_images WHERE event_id = $event_id");
        $conn->query("DELETE FROM sponsor_logos WHERE event_id = $event_id");

        // Delete the event
        $conn->query("DELETE FROM upcoming_events WHERE id = $event_id");

        // Commit transaction
        $conn->commit();

        // Close the database connection
        $conn->close();

        // Redirect back to manage upcoming events
        header("Location: manage_upcoming.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();

        // Close the database connection
        $conn->close();

        // Log the error (optional)
        error_log("Error deleting event: " . $e->getMessage());

        // Redirect with error message
        header("Location: manage_upcoming.php?status=error");
        exit();
    }
} else {
    // Close the database connection before redirecting
    $conn->close();

    // Redirect with invalid ID error
    header("Location: manage_upcoming.php?status=invalid_id");
    exit();
}
?>
