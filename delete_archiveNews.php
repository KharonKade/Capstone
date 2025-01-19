<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Action
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']); // Get the news ID from the URL
    $delete_sql = "DELETE FROM news_announcements WHERE news_id = $news_id";

    if ($conn->query($delete_sql)) {
        header("Location: archived_news.php?message=News deleted successfully");
        exit();
    } else {
        die("Error deleting news: " . $conn->error);
    }
} else {
    die("No news ID provided.");
}

$conn->close();
?>
