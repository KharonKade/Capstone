<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "basf_gallery";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Delete images first
$conn->query("DELETE FROM gallery_images WHERE gallery_id = $id");

// Delete the gallery item
$conn->query("DELETE FROM gallery WHERE id = $id");

echo "<script>alert('Gallery item deleted successfully!'); window.location='admin_gallery.php';</script>";

$conn->close();
?>
