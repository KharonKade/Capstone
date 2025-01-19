<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the news ID is set in the URL
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']); // Get the news ID from the URL

    // Delete the associated images from the database (if any)
    $image_sql = "SELECT image_path FROM news_images WHERE news_id = $news_id";
    $image_result = $conn->query($image_sql);
    if ($image_result->num_rows > 0) {
        while ($image = $image_result->fetch_assoc()) {
            // Delete the image files from the server
            $image_path = $image['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file
            }
        }
    }

    // Delete the news item from the database
    $delete_sql = "DELETE FROM news_announcements WHERE news_id = $news_id";
    if ($conn->query($delete_sql) === TRUE) {
        // Redirect with a success message
        header("Location: manage_news.php?message=News item deleted successfully");
        exit();
    } else {
        die("Error deleting news: " . $conn->error);
    }
} else {
    // If the news ID is not set, redirect to the manage news page
    header("Location: manage_news.php");
    exit();
}

$conn->close();
?>
