<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries</title>
    <link rel="stylesheet" href="Css/admin.css">
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
                <li><a href="/create-news">Create News & Announcements</a></li>
                <li><a href="/manage-news">Manage News & Announcements</a></li>
                <li><a href="view_inquiries.php">Inquiries</a></li>
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

            // Fetch contact inquiries from the database
            $sql = "SELECT id, full_name, email, contact_number, concerns, message, submitted_at FROM contact_inquiries ORDER BY submitted_at DESC";
            $result = $conn->query($sql);

            echo "<div class='content-wrapper'>";
            if ($result->num_rows > 0) {
                echo "<h2>Contact Inquiries</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Concerns</th>
                            <th>Message</th>
                            <th>Submitted At</th>
                        </tr>";

                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["full_name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . $row["contact_number"] . "</td>
                            <td>" . $row["concerns"] . "</td>
                            <td>" . $row["message"] . "</td>
                            <td>" . $row["submitted_at"] . "</td>
                          </tr>";
                }

                echo "</table>";
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
