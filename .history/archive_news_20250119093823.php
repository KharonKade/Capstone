<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Archive Action
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']); // Get the news ID from the URL
    $archive_sql = "UPDATE news_announcements SET status = 'archived' WHERE news_id = $news_id";

    if ($conn->query($archive_sql)) {
        header("Location: manage_news.php?message=News archived successfully");
        exit();
    } else {
        die("Error archiving news: " . $conn->error);
    }
} else {
    die("No news ID provided.");
}

$conn->close();
?>
