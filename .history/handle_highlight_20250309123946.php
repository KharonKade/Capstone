<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Handle Add Highlight
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["video"]) && isset($_POST["title"])) {
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $video = "images/uploads/" . basename($_FILES["video"]["name"]);

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $video)) {
        $stmt = $conn->prepare("INSERT INTO highlight_carousel (video, title, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $video, $title, $description);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $conn->real_escape_string($_POST["id"]);
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    
    // Check if video is uploaded
    if (!empty($_FILES["video"]["name"])) {
        $video = "images/uploads/" . basename($_FILES["video"]["name"]);
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $video)) {
            $stmt = $conn->prepare("UPDATE highlight_carousel SET video=?, title=?, description=? WHERE id=?");
            $stmt->bind_param("sssi", $video, $title, $description, $id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE highlight_carousel SET title=?, description=? WHERE id=?");
        $stmt->bind_param("ssi", $title, $description, $id);
    }

    $stmt->execute();
    $stmt->close();
}

// Handle Delete
if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    $conn->query("DELETE FROM highlight_carousel WHERE id='$id'");
}

$conn->close();
?>
