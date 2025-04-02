<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries</title>
    <link rel="stylesheet" href="Css/view_inquiries.css">
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
                <li><a href="create_news.html">Create News & Announcements</a></li>
                <li><a href="manage_news.php">Manage News & Announcements</a></li>
                <li><a href="archived_news.php">Archived News</a></li>
                <li><a href="admin_gallery.php">Manage Gallery Page</a></li>
                <li><a href="editInlinePage.php">Manage Inline Page</a></li>
                <li><a href="editBmxPage.php">Manage BMX Page</a></li>
                <li><a href="editSkateboardPage.php">Manage Skateboard Page</a></li>
                <li><a href="view_inquiries.php">Inquiries</a></li>
                <li><a href="archived_inquiries.php">Archived Inquiries</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            // Database connection
            $servername = "localhost";  // Your database host
            $username = "root";         // Your database username
            $password = "";             // Your database password
            $dbname = "contact_us";     // Your database name

            // Create a connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch contact inquiries from the database, ordered by ID DESC
            $sql = "SELECT id, full_name, email, contact_number, concerns, message, submitted_at, archived FROM contact_inquiries ORDER BY id DESC";
            $result = $conn->query($sql);

            echo "<div class='content-wrapper'>";

            if ($result->num_rows > 0) {
                echo "<h2>Contact Inquiries</h2>";
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

                // Initialize a counter for the row numbers
                $counter = 1;

                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    // Only show inquiries that are not archived (optional, can be modified)
                    if ($row["archived"] == 0) {
                        $shortMessage = strlen($row["message"]) > 25 ? substr($row["message"], 0, 25) . '...' : $row["message"];
                        echo "<tr>
                                <td>" . $counter . "</td>
                                <td>" . htmlspecialchars($row["full_name"]) . "</td>
                                <td>" . htmlspecialchars($row["email"]) . "</td>
                                <td>" . htmlspecialchars($row["contact_number"]) . "</td>
                                <td>" . htmlspecialchars($row["concerns"]) . "</td>
                                <td>" . htmlspecialchars($shortMessage) . "</td>
                                <td>" . htmlspecialchars($row["submitted_at"]) . "</td>
                                <td>
                                    <a href='view_message.php?id=" . $row["id"] . "'>View</a> |
                                    <a href='archive_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to archive this inquiry?\");'>Archive</a> |
                                    <a href='delete_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this inquiry?\");'>Delete</a>
                                </td>
                              </tr>";
                        $counter++;
                    }
                }

                echo "</tbody></table>";
            } else {
                echo "<p>No inquiries found.</p>";
            }

            echo "</div>";

            // Close the connection
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
