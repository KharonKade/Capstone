<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname_content = "basf_content_bmx";

$conn_content = new mysqli($servername, $username, $password, $dbname_content);

if ($conn_content->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn_content->connect_error
    ]));
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);
$videoId = isset($data['videoId']) ? (int)$data['videoId'] : 0;

if ($videoId > 0) {
    // Increment view count
    $update = $conn_content->prepare("UPDATE highlight_carousel SET views = views + 1 WHERE id = ?");
    $update->bind_param("i", $videoId);
    $update->execute();
    $update->close();

    // Fetch updated view count
    $stmt = $conn_content->prepare("SELECT views FROM highlight_carousel WHERE id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    $stmt->bind_result($views);
    $stmt->fetch();
    $stmt->close();

    echo json_encode([
        'success' => true,
        'newViews' => (int)$views
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid video ID.'
    ]);
}

$conn_content->close();
?>
