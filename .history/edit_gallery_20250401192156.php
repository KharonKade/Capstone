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
$sql = "SELECT * FROM gallery WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Fetch gallery images
$images_sql = "SELECT * FROM gallery_images WHERE gallery_id = $id";
$images_result = $conn->query($images_sql);
$gallery_images = [];
while ($image = $images_result->fetch_assoc()) {
    $gallery_images[] = $image;
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update Title & Description
    $conn->query("UPDATE gallery SET title='$title', description='$description' WHERE id=$id");

    // Update Thumbnail if new file is uploaded
    if (!empty($_FILES["thumbnail"]["name"])) {
        $thumbnail = "images/uploads/" . basename($_FILES["thumbnail"]["name"]);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail);
        $conn->query("UPDATE gallery SET thumbnail='$thumbnail' WHERE id=$id");
    }

    // Handle New Gallery Images Upload
    if (!empty($_FILES["gallery_images"]["name"][0])) {
        foreach ($_FILES["gallery_images"]["tmp_name"] as $key => $tmp_name) {
            $image_path = "images/uploads/" . basename($_FILES["gallery_images"]["name"][$key]);
            move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$key], $image_path);
            $conn->query("INSERT INTO gallery_images (gallery_id, image_path) VALUES ($id, '$image_path')");
        }
    }

    echo "<script>alert('Gallery item updated successfully!'); window.location='admin_gallery.php';</script>";
}

// Handle Deleting Individual Gallery Images
if (isset($_GET['delete_image'])) {
    $delete_id = $_GET['delete_image'];
    $conn->query("DELETE FROM gallery_images WHERE id=$delete_id");
    echo "<script>window.location='edit_gallery.php?id=$id';</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/admin_gallery.css">
    <title>Edit Gallery Item</title>
</head>
<body>

    <h1>Edit Gallery Item</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required><br>
        
        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea><br>
        
        <label>Thumbnail Image:</label>
        <input type="file" name="thumbnail"><br>
        
        <h3>Current Gallery Images:</h3>
        <div class="gallery-container">
            <?php foreach ($gallery_images as $image) { ?>
                <div class="gallery-item">
                    <img src="<?php echo $image['image_path']; ?>" alt="Gallery Image" width="100">
                    <a href="admin_gallery.php" onclick="removeElement(this)" class="button">Remove</a>
                </div>
            <?php } ?>
        </div>

        <label>Add New Gallery Images:</label>
        <input type="file" name="gallery_images[]" multiple><br>
        
        <button type="submit">Update Gallery Item</button>
    </form>
    <script>
        function removeElement(button) {
            button.parentElement.remove();
        }
    </script>

</body>
</html>
