<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Archive Action
if (isset($_GET['archive_id'])) {
    $archive_id = intval($_GET['archive_id']);
    $archive_sql = "UPDATE news_announcements SET status = 'archived' WHERE news_id = $archive_id";

    if ($conn->query($archive_sql)) {
        header("Location: manage_news.php?message=News archived successfully");
        exit();
    } else {
        die("Error archiving news: " . $conn->error);
    }
}

$filter_category = $_GET['category'] ?? '';


$sql = "
    SELECT 
        @rownum := @rownum + 1 AS row_num, 
        news_id, news_title, category, publish_date 
    FROM news_announcements, (SELECT @rownum := 0) r 
    WHERE status = 'active'
    ORDER BY news_id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News & Announcements</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Css/manage_event.css"> <!-- You can reuse the existing stylesheet -->
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
        <main class="content">
            <h2>Manage News & Announcements</h2>
            <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>News Title</th>
                        <th>Category</th>
                        <th>Publish Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Initialize a counter for row numbers
                    $row_num = 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_num++; ?></td> <!-- Display the row number -->
                        <td><?php echo $row['news_title']; ?></td>
                        <td><?php echo ucfirst($row['category']); ?></td>
                        <td><?php echo $row['publish_date']; ?></td>
                        <td>
                            <a href="view_news.php?id=<?php echo $row['news_id']; ?>" title="View">
                                <i class="fas fa-eye"></i>
                            </a> |
                            <a href="edit_news.php?id=<?php echo $row['news_id']; ?>" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a> |
                            <a href="delete_news.php?id=<?php echo $row['news_id']; ?>" onclick="return confirm('Are you sure you want to delete this news item?');" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a> |
                            <a href="archive_news.php?id=<?php echo $row['news_id']; ?>" onclick="return confirm('Archive this news item?');" title="Archive">
                                <i class="fas fa-archive"></i>
                            </a>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No news found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
