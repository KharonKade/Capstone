<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ADD NEW RECORD (INSERT)
if (isset($_POST["id"])) { 
    $id = $_POST["id"];
    $title = $conn->real_escape_string($_POST["title"]);
    $raw_description = trim($_POST["description"]);
    $description = $conn->real_escape_string(preg_replace('/\s+/', ' ', $raw_description));

    if (!empty($_FILES["video"]["name"])) {
        $video = "images/uploads/" . basename($_FILES["video"]["name"]);
        move_uploaded_file($_FILES["video"]["tmp_name"], $video);
        $query = "UPDATE highlight_carousel SET video='$video', title='$title', description='$description' WHERE id='$id'";
    } else {
        $query = "UPDATE highlight_carousel SET title='$title', description='$description' WHERE id='$id'";
    }

    $conn->query($query);
}


// UPDATE EXISTING RECORD
if (isset($_POST["id"])) { 
    $id = $_POST["id"];
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);

    // If a new video is uploaded, update it
    if (!empty($_FILES["video"]["name"])) {
        $video = "images/uploads/" . basename($_FILES["video"]["name"]);
        move_uploaded_file($_FILES["video"]["tmp_name"], $video);
        $query = "UPDATE highlight_carousel SET video='$video', title='$title', description='$description' WHERE id='$id'";
    } else {
        $query = "UPDATE highlight_carousel SET title='$title', description='$description' WHERE id='$id'";
    }

    $conn->query($query);
}

// DELETE RECORD
if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    $conn->query("DELETE FROM highlight_carousel WHERE id='$id'");
}

$conn->close();
header("Location: editInlinePage.php");
exit(); // Ensure script stops execution
?>
