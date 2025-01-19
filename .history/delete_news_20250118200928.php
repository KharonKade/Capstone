<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $conn->real_escape_string($_GET['id']);

    // Start transaction for data integrity
    $conn->begin_transaction();

    try {
        // Delete related images from news_images table
        $conn->query("DELETE FROM news_images WHERE news_id = $news_id");

        // Delete the news announcement
        $conn->query("DELETE FROM news_announcements WHERE news_id = $news_id");

        // Commit transaction
        $conn->commit();

        // Close the database connection
        $conn->close();

        // Redirect back to manage news
        header("Location: manage_news.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();

        // Close the database connection
        $conn->close();

        // Log the error (optional)
        error_log("Error deleting news: " . $e->getMessage());

        // Redirect with error message
        header("Location: manage_news.php?status=error");
        exit();
    }
} else {
    // Close the database connection before redirecting
    $conn->close();

    // Redirect with invalid ID error
    header("Location: manage_news.php?status=invalid_id");
    exit();
}
?>
