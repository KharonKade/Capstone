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

// Fetch Gallery Items
$sql = "SELECT * FROM gallery";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery</title>
    <link rel="stylesheet" href="Css/admin_gallery.css">
</head>
<body>

    <h1>Manage Gallery</h1>
    <a href="add_gallery.php" class="btn">Add New Gallery Item</a>

    <table border="1">
        <tr>
            <th>Thumbnail</th>
            <th>Title</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><img src="<?php echo $row['thumbnail']; ?>" width="100"></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
                <a href="edit_gallery.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_gallery.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>
