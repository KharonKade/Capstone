<?php
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $image = "uploads/" . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    $conn->query("INSERT INTO top_athletes (name, description, image) VALUES ('$name', '$description', '$image')");
}
if (isset($_POST["edit_id"])) {
    $id = $_POST["edit_id"];
    $name = $conn->real_escape_string($_POST["name"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $query = "UPDATE top_athletes SET name='$name', description='$description' WHERE id='$id'";
    if (!empty($_FILES["image"]["name"])) {
        $image = "uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        $query = "UPDATE top_athletes SET name='$name', description='$description', image='$image' WHERE id='$id'";
    }
    $conn->query($query);
}
if (isset($_GET["delete_id"])) {
    $id = $_GET["delete_id"];
    $conn->query("DELETE FROM top_athletes WHERE id='$id'");
}
$conn->close();
header("Location: editInlinePage.php");
?>