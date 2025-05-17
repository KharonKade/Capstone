<?php
session_start();

// Validate input
if (!isset($_POST['id']) || !isset($_POST['source'])) {
    echo "Invalid request";
    exit;
}

$videoId = (int) $_POST['id'];
$source = $_POST['source'];

// Mapping of source to database
$allowedSources = [
    'bmx' => 'basf_content_bmx',
    'inline' => 'basf_content',
    'skateboard' => 'basf_content_skateboard'
];

if (!isset($allowedSources[$source]) || $videoId <= 0) {
    echo "Invalid request";
    exit;
}

$dbname = $allowedSources[$source];
$servername = "localhost";
$username = "root";
$password = "";

// Connect
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "Connection failed";
    exit;
}

// Check and update views per session
$sessionKey = 'viewed_' . $source;

if (!isset($_SESSION[$sessionKey])) {
    $_SESSION[$sessionKey] = [];
}

if (!in_array($videoId, $_SESSION[$sessionKey])) {
    $conn->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");
    $_SESSION[$sessionKey][] = $videoId;
}

// Fetch updated view count
$result = $conn->query("SELECT views FROM highlight_carousel WHERE id = $videoId");
if ($row = $result->fetch_assoc()) {
    echo $row['views'];
} else {
    echo "0";
}

$conn->close();
?>
