<?php
header('Content-Type: application/json');
include 'your_db_connection.php'; // Replace with your actual DB connection

$data = json_decode(file_get_contents("php://input"), true);
$videoId = isset($data['videoId']) ? (int)$data['videoId'] : 0;

if ($videoId > 0) {
    $conn_content->query("UPDATE highlight_carousel SET views = views + 1 WHERE id = $videoId");

    $result = $conn_content->query("SELECT views FROM highlight_carousel WHERE id = $videoId");
    $row = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'newViews' => (int)$row['views']
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>
