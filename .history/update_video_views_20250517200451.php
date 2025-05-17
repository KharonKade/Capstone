<?php
session_start();

$videoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

// List of databases to check
$databases = [
    'basf_content_bmx',
    'basf_content',
    'basf_content_skateboard'
];

$servername = "localhost";
$username = "root";
$password = "";
$found = false;

if ($videoId <= 0) {
    echo "Invalid ID";
    exit;
}

foreach ($databases as $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) continue;

    // Check if the video exists in this DB
    $check = $conn->query("SELECT views FROM highlight_carousel WHERE id = $videoId");

    if ($check && $check->num_rows > 0) {
        $found = true;
        $sessionKey = 'viewed_' . $dbname;

        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = [];
        }

        if (!in_array($videoId, $_SESSION[$sessionKey])) {
            $conn->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");
            $_SESSION[$sessionKey][] = $videoId;
        }

        // Get and return updated view count
        $result = $conn->query("SELECT views FROM highlight_carousel WHERE id = $videoId");
        if ($row = $result->fetch_assoc()) {
            echo $row['views'];
        } else {
            echo "0";
        }

        $conn->close();
        break; // Stop looping after the video is found and processed
    }

    $conn->close();
}

if (!$found) {
    echo "Video not found";
}
?>
