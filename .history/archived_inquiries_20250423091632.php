<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "contact_us");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch archived contact inquiries from the database
$sql = "SELECT id, full_name, email, contact_number, concerns, message, submitted_at, archived FROM contact_inquiries WHERE archived = 1 ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Inquiries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Css/archived_inquiries.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
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

        <!-- Main Content -->
        <div class="content">
            <h2>Archived Inquiries</h2>

            <form action="delete_all_inquiries.php" method="POST" onsubmit="return confirm('Are you sure you want to delete all archived inquiries?');">
                <button type="submit" class="delete-all-btn">Delete All</button>
            </form>

            <?php
            if ($result->num_rows > 0) {
                $counter = 1; // Numbering the inquiries
                echo "<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Concerns</th>
                                <th>Message</th>
                                <th>Submitted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>";

                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $shortMessage = strlen($row["message"]) > 25 ? substr($row["message"], 0, 25) . '...' : $row["message"];
                    echo "<tr>
                            <td>" . $counter++ . "</td> <!-- Display the numbering -->
                            <td>" . htmlspecialchars($row["full_name"]) . "</td>
                            <td>" . htmlspecialchars($row["email"]) . "</td>
                            <td>" . htmlspecialchars($row["contact_number"]) . "</td>
                            <td>" . htmlspecialchars($row["concerns"]) . "</td>
                            <td>" . $shortMessage . "</td>
                            <td>" . htmlspecialchars($row["submitted_at"]) . "</td>
                            <td>
                                <a href='view_message.php?id=" . $row["id"] . "'>View</a> |
                                <a href='restore_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to restore this inquiry?\");'>Restore</a> |
                                <a href='delete_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this inquiry?\");'>Delete</a>
                            </td>
                          </tr>";
                }

                echo "</tbody>
                      </table>";
            } else {
                echo "<p>No archived inquiries found.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
