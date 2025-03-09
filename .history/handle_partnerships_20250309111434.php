<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["logo"])) {
    $logo = "uploads/" . basename($_FILES["logo"]["name"]);
    move_uploaded_file($_FILES["logo"]["tmp_name"], $logo);
    $conn->query("INSERT INTO partnerships (logo) VALUES ('$logo')");
}
if (isset($_POST["delete"])) {
    $id = $_POST["id"];
    $conn->query("DELETE FROM partnerships WHERE id='$id'");
}
$conn->close();
header("Location: editInlinePage.php");
?>
