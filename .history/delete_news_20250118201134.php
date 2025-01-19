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
        $image_sql = "SELECT image_path FROM news_images WHERE news_id = $news_id";
        $result = $conn->query($image_sql);

        if ($result->num_rows > 0) {
            // Delete image files from server (if needed)
            while ($row = $result->fetch_assoc()) {
                $image_path = $row['image_path'];
                if (file_exists($image_path)) {
                    unlink($image_path);  // Delete the image file from the server
                }
            }

            // Delete images from the news_images table
            $conn->query("DELETE FROM news_images WHERE news_id = $news_id");
        }

        // Delete the news item from the news_announcements table
        $conn->query("DELETE FROM news_announcements WHERE news_id = $news_id");

        // Commit transaction
        $conn->commit();

        // Redirect back to manage news with success status
        header("Location: manage_news.php?status=success");
        exit();
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();

        // Log the error (optional)
        error_log("Error deleting news item: " . $e->getMessage());

        // Redirect back to manage news with error status
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
