<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $role = $conn->real_escape_string($_POST["role"]);
    $image = "uploads/" . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    $conn->query("INSERT INTO community_leaders (name, role, image) VALUES ('$name', '$role', '$image')");
}
if (isset($_POST["edit_id"])) {
    $id = $_POST["edit_id"];
    $name = $conn->real_escape_string($_POST["name"]);
    $role = $conn->real_escape_string($_POST["role"]);
    $conn->query("UPDATE community_leaders SET name='$name', role='$role' WHERE id='$id'");
}
if (isset($_POST["delete"])) {
    $id = $_POST["id"];
    $conn->query("DELETE FROM community_leaders WHERE id='$id'");
}
$conn->close();
header("Location: editInlinePage.php");
?>