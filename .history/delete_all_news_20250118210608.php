<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete All Action
if (isset($_POST['delete_all'])) {
    $delete_sql = "DELETE FROM news_announcements WHERE status = 'archived'";

    if ($conn->query($delete_sql)) {
        header("Location: archived_news.php?message=All archived news deleted successfully");
        exit();
    } else {
        die("Error deleting archived news: " . $conn->error);
    }
}

$conn->close();
?>
