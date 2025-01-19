<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch archived news
$sql = "
    SELECT 
        @rownum := @rownum + 1 AS row_num, 
        news_id, news_title, category, publish_date 
    FROM news_announcements, (SELECT @rownum := 0) r 
    WHERE status = 'archived'
    ORDER BY news_id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived News</title>
    <link rel="stylesheet" href="Css/archived_news.css"> <!-- Update the path as needed -->
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="create_event.html">Create Event</a></li>
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
        <main class="content">
            <h2>Archived News</h2>

            <form method="post" action="delete_all_news.php" onsubmit="return confirm('Are you sure you want to delete all archived news?');">
                <button type="submit" name="delete_all" class="delete-all-btn">Delete All</button>
            </form>

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
                                <td><?php echo $row_num++; ?></td>
                                <td><?php echo htmlspecialchars($row['news_title']); ?></td>
                                <td><?php echo ucfirst($row['category']); ?></td>
                                <td><?php echo $row['publish_date']; ?></td>
                                <td>
                                    <a href="view_news.php?id=<?php echo $row['news_id']; ?>">View</a> |
                                    <a href="delete_archiveNews.php?id=<?php echo $row['news_id']; ?>" onclick="return confirm('Are you sure you want to delete this news item?');">Delete</a> |
                                    <a href="restore_news.php?id=<?php echo $row['news_id']; ?>" onclick="return confirm('Are you sure you want to restore this news item?');">Restore</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No archived news found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
