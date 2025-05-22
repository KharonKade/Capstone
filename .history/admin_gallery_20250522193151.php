<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Css/admin_gallery.css">
</head>
<body>
<div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="create_event.html"><i class="fas fa-calendar-plus"></i> Create Event</a></li>
                <li><a href="manage_upcoming.php"><i class="fas fa-calendar-check"></i> Manage Events</a></li>
                <li><a href="archived_events.php"><i class="fas fa-archive"></i> Archived Events</a></li>
                <li><a href="create_news.html"><i class="fas fa-newspaper"></i> Create News & Announcements</a></li>
                <li><a href="manage_news.php"><i class="fas fa-edit"></i> Manage News & Announcements</a></li>
                <li><a href="archived_news.php"><i class="fas fa-history"></i> Archived News</a></li>
                <li><a href="admin_gallery.php"><i class="fas fa-images"></i> Manage Gallery Page</a></li>
                <li><a href="editInlinePage.php"><i class="fas fa-skating"></i> Manage Inline Page</a></li>
                <li><a href="editBmxPage.php"><i class="fas fa-bicycle"></i> Manage BMX Page</a></li>
                <li><a href="editSkateboardPage.php"><i class="fas fa-snowboarding"></i> Manage Skateboard Page</a></li>
                <li><a href="view_inquiries.php"><i class="fas fa-question-circle"></i> Inquiries</a></li>
                <li><a href="archived_inquiries.php"><i class="fas fa-archive"></i> Archived Inquiries</a></li>
                <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                    <td>
                        <div style="word-wrap: break-word; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;">
                            <?php echo $row['title']; ?>
                        </div>
                    </td>
                    <td>
                        <div style="word-wrap: break-word; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px;">
                            <?php echo strtok($row['description'], '.'); ?> <!-- Displays only the first sentence -->
                        </div>
                    </td>
                    <td>
                        <a href="view_gallery.php?id=<?php echo $row['id']; ?>" title="View">
                            <i class="fas fa-eye"></i>
                        </a> |
                        <a href="edit_gallery.php?id=<?php echo $row['id']; ?>" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a> |
                        <a href="delete_gallery.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>

                </tr>
            <?php endwhile; ?>

            </table>
        </div>
</div>

    </body>
    </html>

    <?php $conn->close(); ?>
