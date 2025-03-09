<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="Css/editInlinePage.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname_content = "basf_content";

                $conn_content = new mysqli($servername, $username, $password, "basf_content");

                if ($conn_content->connect_error) {
                    die("Connection failed: " . $conn_content->connect_error);
                }
            ?>
            <h2>Manage Inline Page</h2>
            <section>
                    <label>About Us:</label>
                    <?php
                    $result = $conn_content->query("SELECT content FROM content WHERE section='about_us'");
                    if ($row = $result->fetch_assoc()) {
                        echo '<p>' . $row['content'] . '</p>';
                    } else {
                        echo "<p>About Us content not found.</p>";
                    }
                    ?>
                    <button onclick="showEditForm('aboutUsForm')">Edit</button>
                    <form id="aboutUsForm" style="display:none;" method="post" action="handle_aboutus.php">
                        <textarea name="about_us" required></textarea>
                        <button type="submit">Update</button>
                        <button type="button" onclick="hideForm('aboutUsForm')">Cancel</button>
                    </form>
            </section>

            <section>
                <label>Highlight Carousel:</label>
                <button onclick="showAddForm('addHighlightForm')">Add</button>
                <form id="addHighlightForm" style="display:none;" method="post" action="handle_highlight.php" enctype="multipart/form-data">
                    <input type="file" name="video" required>
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addHighlightForm')">Cancel</button>
                </form>
                <?php
                $result = $conn_content->query("SELECT id, video, title, description FROM highlight_carousel");
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo '<video src="' . $row["video"] . '" autoplay muted loop></video>';
                    echo "<p>{$row['title']}</p><p>{$row['description']}</p>";
                    echo "<button onclick=\"showEditForm('editHighlightForm{$row['id']}')\">Edit</button>";
                    echo "<button onclick='deleteItem(\"handle_highlight.php?delete_id={$row['id']}\")'>Delete</button>";
                    echo "<form id='editHighlightForm{$row['id']}' style='display:none;' method='post' action='handle_highlight.php' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<input type='file' name='video'>";
                    echo "<input type='text' name='title' value='{$row['title']}' required>";
                    echo "<textarea name='description' required>{$row['description']}</textarea>";
                    echo "<button type='submit'>Update</button>";
                    echo "</form></div>";
                }
                ?>
            </section>

            <!-- Top Athletes Section -->
            <section>
                <label>Top Athletes:</label>
                <button onclick="showAddForm('addAthleteForm')">Add</button>
                <form id="addAthleteForm" style="display:none;" method="post" action="handle_athletes.php" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Athlete Name" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="file" name="image" required>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addAthleteForm')">Cancel</button>
                </form>
                <?php
                $result = $conn_content->query("SELECT id, name, description, image FROM top_athletes");
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                    echo "<p>{$row['name']}</p><p>{$row['description']}</p>";
                    echo "<button onclick=\"showEditForm('editAthleteForm{$row['id']}')\">Edit</button>";
                    echo "<button onclick=\"deleteItem('handle_athletes.php?id={$row['id']}')\">Delete</button>";
                    echo "<form id='editAthleteForm{$row['id']}' style='display:none;' method='post' action='update_athlete.php' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='id' value='{$row['id']}'>";
                    echo "<input type='text' name='name' value='{$row['name']}' required>";
                    echo "<textarea name='description' required>{$row['description']}</textarea>";
                    echo "<input type='file' name='image'>";
                    echo "<button type='submit'>Update</button>";
                    echo "</form></div>";
                }
                ?>
            </section>


            <section>
                <label class="section-heading">Community Leaders:</label>
                <button onclick="toggleForm('addLeaderForm')">Add Leader</button>
                <form id="addLeaderForm" style="display: none;" method="POST" action="handle_leaders.php" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="text" name="role" placeholder="Role" required>
                    <input type="file" name="image" required>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addLeaderForm')">Cancel</button>
                </form>
                <div class="leaders-container">
                    <?php
                    $result = $conn_content->query("SELECT id, name, role, image FROM community_leaders");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="leader">
                                    <div class="profile-pic">
                                        <img src="' . $row["image"] . '" alt="' . htmlspecialchars($row["name"]) . '" />
                                    </div>
                                    <div class="leader-info">
                                        <h3 class="leader-name">' . htmlspecialchars($row["name"]) . '</h3>
                                        <p class="leader-role">' . htmlspecialchars($row["role"]) . '</p>
                                        <button onclick="toggleForm(\'editLeaderForm' . $row['id'] . '\')">Edit</button>
                                        <form id="editLeaderForm' . $row['id'] . '" style="display: none;" method="POST" action="handle_leaders.php" enctype="multipart/form-data">
                                            <input type="hidden" name="edit_id" value="' . $row['id'] . '">
                                            <input type="text" name="name" value="' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '" required>
                                            <input type="text" name="role" value="' . htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8') . '" required>
                                            <input type="file" name="image"> 
                                            <button type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="handle_leaders.php">
                                            <input type="hidden" name="id" value="' . $row['id'] . '">
                                            <button type="submit" name="delete">Remove</button>
                                        </form>
                                    </div>
                                </div>';
                        }
                    } else {
                    }
                    ?>
                </div>
            </section>


            <section>
                <label>Partners & Sponsors:</label>
                <button onclick="toggleForm('addPartnerForm')">Add Partner</button>
                <form id="addPartnerForm" style="display: none;" method="POST" action="handle_partnerships.php" enctype="multipart/form-data">
                    <input type="file" name="logo" required>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addPartnerForm')">Cancel</button>
                </form>
                <div class="partner-logos">
                    <?php
                    $result = $conn_content->query("SELECT id, logo FROM partnerships");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="partner-item">
                                    <img src="' . htmlspecialchars($row["logo"]) . '" alt="Partner Logo" class="partner-logo">
                                    <form method="POST" action="handle_partnerships.php">
                                        <input type="hidden" name="id" value="' . $row['id'] . '">
                                        <button type="submit" name="delete">Remove</button>
                                    </form>
                                </div>';
                        }
                    } else {
                    }
                    ?>
                </div>
            </section>

            <script>
            function showEditForm(id) {
                document.getElementById(id).style.display = 'block';
            }
            function showAddForm(id) {
                document.getElementById(id).style.display = 'block';
            }
            function deleteItem(url) {
                if (confirm('Are you sure you want to delete this item?')) {
                    window.location.href = url;
                }
            }
            function toggleForm(formId) {
                var form = document.getElementById(formId);
                form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
            }

            function showAddForm(formId) {
            document.getElementById(formId).style.display = 'block';
            }

            function hideForm(formId) {
                document.getElementById(formId).style.display = 'none';
            }

            
            function deleteItem(url) {
                if (confirm("Are you sure you want to delete this item?")) {
                    window.location.href = url;
                }
            }
            </script>

            <?php
                $conn_content->close();
            ?>
        </main>
    </div>
</body>
</html>