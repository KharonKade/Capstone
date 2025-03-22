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
<div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="admin.html">Create Event</a></li>
                <li><a href="manage_upcoming.php">Manage Events</a></li>
                <li><a href="archived_events.php">Archived Events</a></li>
                <li><a href="create_news.html">Create News & Announcements</a></li>
                <li><a href="manage_news.php">Manage News & Announcements</a></li>
                <li><a href="archived_news.php">Archived News</a></li>
                <li><a href="view_inquiries.php">Inquiries</a></li>
                <li><a href="archived_inquiries.php">Archived Inquiries</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>

        <div class="content">
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
                    <td><img src="<?php echo 'images/uploads/' . basename($row['thumbnail']); ?>" width="100"></td>
                    <td style="word-wrap: break-word; max-width: 200px;"><?php echo $row['title']; ?></td>
                    <td style="word-wrap: break-word; max-width: 200px;">
                    <?php 
                        $description = strip_tags($row['description']); // Remove any HTML tags
                        $words = explode(" ", $description);
                        $short_desc = implode(" ", array_slice($words, 0, 10)); // Show first 10 words
                        echo $short_desc . (count($words) > 10 ? "..." : ""); 
                    ?>
                    </td>
                    <td>
                        <a href="view_gallery.php?id=<?php echo $row['id']; ?>">View</a> | 
                        <a href="edit_gallery.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete_gallery.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
</div>

    </body>
    </html>

    <?php $conn->close(); ?>
