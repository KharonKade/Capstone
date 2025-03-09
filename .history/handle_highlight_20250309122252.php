<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (isset($_POST["title"])) {
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $video = "uploads/" . basename($_FILES["video"]["name"]);
    move_uploaded_file($_FILES["video"]["tmp_name"], $video);
    $conn->query("INSERT INTO highlight_carousel (video, title, description) VALUES ('$video', '$title', '$description')");
}
if (isset($_POST["edit_id"])) {
    $id = $_POST["edit_id"];
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $query = "UPDATE highlight_carousel SET title='$title', description='$description' WHERE id='$id'";
    if (!empty($_FILES["video"]["name"])) {
        $video = "uploads/" . basename($_FILES["video"]["name"]);
        move_uploaded_file($_FILES["video"]["tmp_name"], $video);
        $query = "UPDATE highlight_carousel SET video='$video', title='$title', description='$description' WHERE id='$id'";
    }
    $conn->query($query);
}
if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    $conn->query("DELETE FROM highlight_carousel WHERE id='$id'");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $conn->real_escape_string($_POST["id"]);
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);

    // Default update query without video update
    $query = "UPDATE highlight_carousel SET title='$title', description='$description' WHERE id='$id'";

    // Handle file upload only if a new file is selected
    if (!empty($_FILES["video"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $video = $target_dir . basename($_FILES["video"]["name"]);
        if (move_uploaded_file($_FILES["video"]["tmp_name"], $video)) {
            $query = "UPDATE highlight_carousel SET video='$video', title='$title', description='$description' WHERE id='$id'";
        } else {
            echo "Error uploading file.";
        }
    }

    $conn->query($query);
}

$conn->close();
header("Location: editInlinePage.php");
?>