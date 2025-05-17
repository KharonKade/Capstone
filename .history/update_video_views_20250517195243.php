<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content_bmx";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$videoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($videoId > 0) {
    // Check if video was already viewed in this session
    if (!isset($_SESSION['viewed_videos'])) {
        $_SESSION['viewed_videos'] = [];
    }

    if (!in_array($videoId, $_SESSION['viewed_videos'])) {
        // Increment view count
        $conn->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");
        $_SESSION['viewed_videos'][] = $videoId; // Mark as viewed this session
    }

    // Return current view count
    $result = $conn->query("SELECT views FROM highlight_carousel WHERE id = $videoId");
    if ($row = $result->fetch_assoc()) {
        echo $row['views'];
    } else {
        echo "0";
    }
} else {
    echo "Invalid ID";
}
?>
