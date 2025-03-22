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

$sql = "SELECT * FROM gallery WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Gallery item not found.");
}

$gallery = $result->fetch_assoc();
$stmt->close(); // Close the first statement after use

// Get Multiple Images
$images_sql = "SELECT image_path FROM gallery_images WHERE gallery_id = ?";
$stmt = $conn->prepare($images_sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$images_result = $stmt->get_result();

$images = [];
while ($image = $images_result->fetch_assoc()) {
    $images[] = $image['image_path'];
}
$stmt->close();
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
        <img src="<?php echo 'images/uploads/' . basename($gallery['thumbnail']); ?>" alt="Thumbnail" class="view-thumbnail">
        <p class="view-description"><?php echo nl2br(htmlspecialchars($gallery['description'])); ?></p>

        <div class="gallery-container">
            <?php if (!empty($images)) { 
                foreach ($images as $image_path) { ?>
                    <p><?php echo 'images/uploads/' . basename($image_path); ?></p>
                    <img src="<?php echo 'images/uploads/' . basename($image_path); ?>" alt="Gallery Image">
                <?php } 
            } else { 
                echo "<p>No images found for this gallery.</p>";
            } ?>
        </div>

        <a href="admin_gallery.php">Back to Gallery</a>
    </div>

</body>
</html>
