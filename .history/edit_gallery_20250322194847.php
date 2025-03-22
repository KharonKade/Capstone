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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES["thumbnail"]["name"])) {
        $thumbnail = "images/uploads/" . basename($_FILES["thumbnail"]["name"]);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail);
        $conn->query("UPDATE gallery SET thumbnail='$thumbnail' WHERE id=$id");
    }

    $conn->query("UPDATE gallery SET title='$title', description='$description' WHERE id=$id");

    echo "<script>alert('Gallery item updated successfully!'); window.location='admin_gallery.php';</script>";
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
        <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>
        
        <label>Description:</label>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br>
        
        <label>Thumbnail Image:</label>
        <input type="file" name="thumbnail"><br>
        
        <button type="submit">Update Gallery Item</button>
    </form>

</body>
</html>
