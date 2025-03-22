<?php
// Database Connection
$host = "localhost";
$username = "root";
$password = "";
$database = "basf_gallery";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Gallery Item
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM gallery WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Gallery item not found.");
}

$gallery = $result->fetch_assoc();

// Get Multiple Images
$images_sql = "SELECT image_path FROM gallery_images WHERE gallery_id = $id";
$images_result = $conn->query($images_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Gallery Item</title>
    <link rel="stylesheet" href="Css/admin_gallery.css">
</head>
<body>

    <h1><?php echo htmlspecialchars($gallery['title']); ?></h1>
    <img src="<?php echo $gallery['thumbnail']; ?>" width="300">
    <p><?php echo nl2br(htmlspecialchars($gallery['description'])); ?></p>

    <h2>Additional Images</h2>
    <div class="gallery-container">
        <?php while ($image = $images_result->fetch_assoc()): ?>
            <img src="<?php echo $image['image_path']; ?>" width="200">
        <?php endwhile; ?>
    </div>

    <a href="admin_gallery.php">Back to Gallery</a>

</body>
</html>
