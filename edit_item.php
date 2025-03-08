<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type']) && isset($_POST['id'])) {
    $type = $_POST['type'];
    $id = intval($_POST['id']);

    switch ($type) {
        case 'highlight':
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $query = "UPDATE highlight_carousel SET title='$title', description='$description' WHERE id=$id";
            if (!empty($_FILES["video"]["name"])) {
                $target_file = "uploads/" . basename($_FILES["video"]["name"]);
                move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);
                $query = "UPDATE highlight_carousel SET title='$title', description='$description', video='$target_file' WHERE id=$id";
            }
            break;

        case 'athlete':
            $name = $conn->real_escape_string($_POST['name']);
            $description = $conn->real_escape_string($_POST['description']);
            $query = "UPDATE top_athletes SET name='$name', description='$description' WHERE id=$id";
            if (!empty($_FILES["image"]["name"])) {
                $image = "uploads/" . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $image);
                $query = "UPDATE top_athletes SET name='$name', description='$description', image='$image' WHERE id=$id";
            }
            break;

        case 'leader':
            $name = $conn->real_escape_string($_POST['name']);
            $role = $conn->real_escape_string($_POST['role']);
            $query = "UPDATE community_leaders SET name='$name', role='$role' WHERE id=$id";
            if (!empty($_FILES["image"]["name"])) {
                $image = "uploads/" . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $image);
                $query = "UPDATE community_leaders SET name='$name', role='$role', image='$image' WHERE id=$id";
            }
            break;

        case 'partner':
            if (!empty($_FILES["logo"]["name"])) {
                $logo = "uploads/" . basename($_FILES["logo"]["name"]);
                move_uploaded_file($_FILES["logo"]["tmp_name"], $logo);
                $query = "UPDATE partnerships SET logo='$logo' WHERE id=$id";
            }
            break;
    }

    $conn->query($query);
}

header("Location: editInlinePage.php");
?>
