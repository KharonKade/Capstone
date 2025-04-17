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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $thumbnail = "images/uploads/" . basename($_FILES["thumbnail"]["name"]);
    
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail);

    $sql = "INSERT INTO gallery (title, description, thumbnail) VALUES ('$title', '$description', '$thumbnail')";
    
    if ($conn->query($sql) === TRUE) {
        $gallery_id = $conn->insert_id;

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_path = "images/uploads/" . basename($_FILES['images']['name'][$key]);
            move_uploaded_file($tmp_name, $image_path);
            $conn->query("INSERT INTO gallery_images (gallery_id, image_path) VALUES ('$gallery_id', '$image_path')");
        }

        echo "<script>alert('Gallery item added successfully!'); window.location='admin_gallery.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/admin_gallery.css">
    <title>Add Gallery Item</title>
</head>
<body>

    <h1>Add New Gallery Item</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        
        <label>Description:</label>
        <textarea id="description" name="description"></textarea><br>
        
        <label>Thumbnail Image:</label>
        <input type="file" name="thumbnail" required><br>
        
        <label>Additional Images:</label>
        <input type="file" name="images[]" multiple required><br>
        
        <button type="submit">Add Gallery Item</button>
        <button type="button" class="button" onclick="history.back();">Cancel</button>
    </form>

</body>
</html>
