<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Restore Action
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']); // Get the news ID from the URL
    $restore_sql = "UPDATE news_announcements SET status = 'active' WHERE news_id = $news_id";

    if ($conn->query($restore_sql)) {
        header("Location: archived_news.php?message=News restored successfully");
        exit();
    } else {
        die("Error restoring news: " . $conn->error);
    }
} else {
    die("No news ID provided.");
}

$conn->close();
?>
