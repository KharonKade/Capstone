<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $news_id = $conn->real_escape_string($_GET['id']);
} else {
    echo "Invalid ID or no ID provided.";
    exit;
}

// Debugging: Check if the correct news_id is passed
echo "News ID to be deleted: " . $news_id . "<br>";

// Start transaction for data integrity
$conn->begin_transaction();

try {
    // Delete related data from news_images
    $query1 = "DELETE FROM news_images WHERE news_id = $news_id";
    if ($conn->query($query1) === TRUE) {
        echo "Successfully deleted from news_images.<br>";
    } else {
        echo "Error deleting from news_images: " . $conn->error . "<br>";
    }

    // Delete news and announcement
    $query2 = "DELETE FROM news_announcements WHERE news_id = $news_id";
    if ($conn->query($query2) === TRUE) {
        echo "Successfully deleted from news_announcements.<br>";
    } else {
        echo "Error deleting from news_announcements: " . $conn->error . "<br>";
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
