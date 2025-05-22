<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="Css/view_inquiries.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="create_event.php"><i class="fas fa-calendar-plus"></i> Create Event</a></li>
                <li><a href="manage_upcoming.php"><i class="fas fa-calendar-check"></i> Manage Events</a></li>
                <li><a href="archived_events.php"><i class="fas fa-archive"></i> Archived Events</a></li>
                <li><a href="create_news.php"><i class="fas fa-newspaper"></i> Create News & Announcements</a></li>
                <li><a href="manage_news.php"><i class="fas fa-edit"></i> Manage News & Announcements</a></li>
                <li><a href="archived_news.php"><i class="fas fa-history"></i> Archived News</a></li>
                <li><a href="admin_gallery.php"><i class="fas fa-images"></i> Manage Gallery Page</a></li>
                <li><a href="editInlinePage.php"><i class="fas fa-skating"></i> Manage Inline Page</a></li>
                <li><a href="editBmxPage.php"><i class="fas fa-bicycle"></i> Manage BMX Page</a></li>
                <li><a href="editSkateboardPage.php"><i class="fas fa-snowboarding"></i> Manage Skateboard Page</a></li>
                <li><a href="view_inquiries.php"><i class="fas fa-question-circle"></i> Inquiries</a></li>
                <li><a href="archived_inquiries.php"><i class="fas fa-archive"></i> Archived Inquiries</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

                // Optional: Fetch distinct concerns for the dropdown
                $concernResult = $conn->query("SELECT DISTINCT concerns FROM contact_inquiries");
                $concernOptions = '';
                while ($cRow = $concernResult->fetch_assoc()) {
                    $selected = ($filter === $cRow['concerns']) ? 'selected' : '';
                    $concernOptions .= "<option value='" . htmlspecialchars($cRow['concerns']) . "' $selected>" . htmlspecialchars($cRow['concerns']) . "</option>";
                }

                // Filtered SQL query
                $sql = "SELECT id, full_name, email, contact_number, concerns, message, submitted_at, archived FROM contact_inquiries";
                if (!empty($filter)) {
                    $sql .= " WHERE concerns = '" . $conn->real_escape_string($filter) . "'";
                }
                $sql .= " ORDER BY id DESC";
                $result = $conn->query($sql);

                // Filter Form
                echo "
                <form method='get' style='margin-bottom: 20px;'>
                    <label for='filter'><strong>Filter by Concern:</strong></label>
                    <select name='filter' id='filter' onchange='this.form.submit()'>
                        <option value=''>All</option>
                        $concernOptions
                    </select>
                </form>
                ";
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
                                    <a href='view_message.php?id=" . $row["id"] . "' title='View'>
                                        <i class='fas fa-eye'></i>
                                    </a> |
                                    <a href='archive_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to archive this inquiry?\");' title='Archive'>
                                        <i class='fas fa-box-archive'></i>
                                    </a> |
                                    <a href='delete_inquiry.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this inquiry?\");' title='Delete'>
                                        <i class='fas fa-trash'></i>
                                    </a>
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
