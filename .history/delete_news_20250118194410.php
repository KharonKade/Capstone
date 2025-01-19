<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $conn->real_escape_string($_GET['id']);

    // Start transaction for data integrity
    $conn->begin_transaction();

    try {
        // Delete related data (e.g., images)
        $delete_images = $conn->query("DELETE FROM news_images WHERE news_id = $news_id");

        if (!$delete_images) {
            throw new Exception("Failed to delete images: " . $conn->error);
        }

        // Delete the news and announcement
        $delete_news = $conn->query("DELETE FROM news_announcements WHERE id = $news_id");

        if (!$delete_news) {
            throw new Exception("Failed to delete news: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        // Redirect back to manage news page
        header("Location: manage_news.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();

        // Log the error (optional)
        error_log("Error deleting news: " . $e->getMessage());

        // Close the database connection
        $conn->close();

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
