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
    <link rel="stylesheet" href="Css/archived_inquiries.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="admin.html">Create Event</a></li>
                <li><a href="manage_upcoming.php">Manage Events</a></li>
                <li><a href="archived_events.php">Archived Events</a></li>
                <li><a href="/create-news">Create News & Announcements</a></li>
                <li><a href="/manage-news">Manage News & Announcements</a></li>
                <li><a href="view_inquiries.php">Inquiries</a></li>
                <li><a href="archived_inquiries.php">Archived Inquiries</a></li>
                <li><a href="/logout">Logout</a></li>
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
