<?php
session_start();

$videoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$source = isset($_POST['source']) ? $_POST['source'] : '';

$sourceMap = [
    'bmx' => 'basf_content_bmx',
    'inline' => 'basf_content',
    'skateboard' => 'basf_content_skateboard'
];

if ($videoId <= 0 || !isset($sourceMap[$source])) {
    echo "Invalid request";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = $sourceMap[$source];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sessionKey = 'viewed_' . $source;

if (!isset($_SESSION[$sessionKey])) {
    $_SESSION[$sessionKey] = [];
}

if (!in_array($videoId, $_SESSION[$sessionKey])) {
    $conn->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");
    $_SESSION[$sessionKey][] = $videoId;
}

// Return current view count
$result = $conn->query("SELECT views FROM highlight_carousel WHERE id = $videoId");
if ($row = $result->fetch_assoc()) {
    echo $row['views'];
} else {
    echo "0";
}

$conn->close();
?>
