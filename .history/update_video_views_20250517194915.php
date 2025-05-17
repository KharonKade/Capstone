<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content_bmx"; // or your actual content DB

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$videoId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($videoId > 0) {
    $conn->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");
    
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
