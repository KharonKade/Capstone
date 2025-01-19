<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug: Check if ID is passed correctly
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $conn->real_escape_string($_GET['id']);
    echo "News ID: " . $news_id; // Debugging line
} else {
    echo "No valid ID provided";
    exit;
}

// Start transaction for data integrity
$conn->begin_transaction();

try {
    // Delete related data (e.g., images associated with the news)
    if (!$conn->query("DELETE FROM news_images WHERE news_id = $news_id")) {
        echo "Error deleting from news_images: " . $conn->error;
        exit;
    }

    // Delete the news and announcement
    if (!$conn->query("DELETE FROM news_announcements WHERE news_id = $news_id")) {
        echo "Error deleting from news_announcements: " . $conn->error;
        exit;
    }

    // Commit transaction
    $conn->commit();

    // Close the database connection
    $conn->close();

    // Redirect back to manage news page
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
?>
