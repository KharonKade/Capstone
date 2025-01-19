<?php
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$news_id = intval($_GET['archive_id']); // Retrieve the news ID from the URL

// Update the news status to 'archived'
$sql = "UPDATE news_announcements SET status = 'archived' WHERE news_id = $news_id";
if ($conn->query($sql) === TRUE) {
    // Redirect to the archived_news.php with a success message
    header("Location: manage_news.php?msg=News archived successfully");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
