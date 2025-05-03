<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Archive Action
if (isset($_GET['archive_id'])) {
    $archive_id = intval($_GET['archive_id']);
    $archive_sql = "UPDATE upcoming_events SET status = 'archived' WHERE id = $archive_id";

    if ($conn->query($archive_sql)) {
        header("Location: manage_upcoming.php?message=Event archived successfully");
        exit();
    } else {
        die("Error archiving event: " . $conn->error);
    }
}

$filter_category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$sql = "
    SELECT 
        @rownum := @rownum + 1 AS row_num, 
        id, event_name, location, category, registration, registration_limit
    FROM upcoming_events, (SELECT @rownum := 0) r 
    WHERE status = 'active'
";
if (!empty($filter_category) && $filter_category !== 'All') {
    $filter_category = $conn->real_escape_string($filter_category);
    $sql .= " AND category = '$filter_category'";
}
$sql . "ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Upcoming Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Css/manage_event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="create_event.html."><i class="fas fa-calendar-plus"></i> Create Event</a></li>
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
            <h2>Manage Upcoming Events</h2>
            <form method="GET" style="margin-bottom: 20px;">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">-- All Categories --</option>
                    <?php
                    $cat_result = $conn->query("SELECT DISTINCT category FROM upcoming_events WHERE status = 'active'");
                    while ($cat = $cat_result->fetch_assoc()) {
                        $selected = $filter_category == $cat['category'] ? 'selected' : '';
                        echo "<option value='{$cat['category']}' $selected>" . ucfirst($cat['category']) . "</option>";
                    }
                    ?>
                </select>
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
                        <th>Limit</th>
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
                        <td><?php echo $row_num++; ?></td> <!-- Display the row number -->
                        <td><?php echo $row['event_name']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo ucfirst($row['category']); ?></td>
                        <td><?php echo $row['registration'] == 1 ? 'Enabled' : 'Disabled'; ?></td>
                        <td><?php echo $row['registration_limit'] ?? 'Unlimited'; ?></td>
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
                            <a href="view_event.php?id=<?php echo $row['id']; ?>" title="View">
                                <i class="fas fa-eye"></i>
                            </a> |
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a> |
                            <a href="delete_event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a> |
                            <a href="archive_event.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Archive this event?');" title="Archive">
                                <i class="fas fa-archive"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No upcoming events found.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
