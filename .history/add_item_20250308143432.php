<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type'])) {
    $type = $_POST['type'];

    // Define the table and fields based on type
    switch ($type) {
        case 'highlight':
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $target_file = "uploads/" . basename($_FILES["video"]["name"]);
            move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);
            $conn->query("INSERT INTO highlight_carousel (title, description, video) VALUES ('$title', '$description', '$target_file')");
            break;

        case 'athlete':
            $name = $conn->real_escape_string($_POST['name']);
            $description = $conn->real_escape_string($_POST['description']);
            $image = "uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            $conn->query("INSERT INTO top_athletes (name, description, image) VALUES ('$name', '$description', '$image')");
            break;

        case 'leader':
            $name = $conn->real_escape_string($_POST['name']);
            $role = $conn->real_escape_string($_POST['role']);
            $image = "uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
            $conn->query("INSERT INTO community_leaders (name, role, image) VALUES ('$name', '$role', '$image')");
            break;

        case 'partner':
            $logo = "uploads/" . basename($_FILES["logo"]["name"]);
            move_uploaded_file($_FILES["logo"]["tmp_name"], $logo);
            $conn->query("INSERT INTO partnerships (logo) VALUES ('$logo')");
            break;
    }
}

header("Location: editInlinePage.php");
?>
