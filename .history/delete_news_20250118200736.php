<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is present in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $conn->real_escape_string($_GET['id']);

    // Start transaction for data integrity
    $conn->begin_transaction();

    try {
        // Delete related images
        $delete_images = $conn->query("DELETE FROM news_images WHERE news_id = $news_id");
        if (!$delete_images) {
            throw new Exception("Error deleting images: " . $conn->error);
        } else {
            echo "Images deleted successfully.<br>";
        }

        // Delete the news announcement
        $delete_news = $conn->query("DELETE FROM news_announcements WHERE news_id = $news_id");
        if (!$delete_news) {
            throw new Exception("Error deleting news: " . $conn->error);
        } else {
            echo "News deleted successfully.<br>";
        }

        // Commit transaction if both queries are successful
        $conn->commit();

        // Redirect to manage news page
        header("Location: manage_news.php?status=success");
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();

        // Display the error message
        echo "Error: " . $e->getMessage();

        // Redirect to manage news with error message
        header("Location: manage_news.php?status=error");
    }

    // Close the connection
    $conn->close();
} else {
    // Invalid ID
    echo "Invalid ID provided.<br>";
    header("Location: manage_news.php?status=invalid_id");
}
?>
