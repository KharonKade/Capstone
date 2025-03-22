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

    <div class="view-container">
    <h1><?php echo htmlspecialchars($gallery['title']); ?></h1>
    <img src="<?php echo htmlspecialchars($gallery['thumbnail']); ?>" alt="Thumbnail" class="view-thumbnail">
    <p class="view-description"><?php echo nl2br(htmlspecialchars($gallery['description'])); ?></p>

    <div class="gallery-container">
        <?php while ($image = mysqli_fetch_assoc($images_result)) { ?>
            <img src="uploads/<?php echo htmlspecialchars($image['image_path']); ?>" alt="Gallery Image">
        <?php } ?>
    </div>

    <a href="admin_gallery.php">Back to Gallery</a>

</body>
</html>
