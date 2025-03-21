<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $about_us = $conn->real_escape_string($_POST["about_us"]);
    $conn->query("UPDATE content SET content='$about_us' WHERE section='about_us'");
}
$conn->close();
header("Location: editInlinePage.php");
?>
