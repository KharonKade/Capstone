<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch archived events
$sql = "
    SELECT 
        @rownum := @rownum + 1 AS row_num, 
        id, event_name, location, category, registration 
    FROM upcoming_events, (SELECT @rownum := 0) r 
    WHERE status = 'archived'
    ORDER BY id DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Events</title>
    <link rel="stylesheet" href="Css/archived_events.css">
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
        <main class="content">
            <h2>Archived Events</h2>

            <form method="post" action="delete_all_events.php" onsubmit="return confirm('Are you sure you want to delete all archived events?');">
                <button type="submit" name="delete_all" class="delete-all-btn">Delete All</button>
            </form>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Registration</th>
                            <th>Schedules</th>
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
                                <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo ucfirst($row['category']); ?></td>
                                <td><?php echo $row['registration'] == 1 ? 'Enabled' : 'Disabled'; ?></td>
                                <td>
                                    <?php
                                    // Fetch schedules for the event
                                    $schedule_sql = "SELECT * FROM event_schedules WHERE event_id = " . $row['id'];
                                    $schedule_result = $conn->query($schedule_sql);
                                    if ($schedule_result->num_rows > 0) {
                                        while ($schedule = $schedule_result->fetch_assoc()) {
                                            echo "Date: " . $schedule['event_date'] . "<br>";
                                            echo "Start: " . $schedule['start_time'] . "<br>";
                                            echo "End: " . $schedule['end_time'] . "<br><hr>";
                                        }
                                    } else {
                                        echo "No schedules found.";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="view_event.php?id=<?php echo $row['id']; ?>">View</a> |
                                    <a href="delete_archiveEvent.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a> |
                                    <a href="restore_event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to restore this event?');">Restore</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No archived events found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
