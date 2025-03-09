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

            <section>
                <label>Top Athletes:</label>
                <button onclick="showAddForm('addAthleteForm')">Add</button>
                <form id="addAthleteForm" style="display:none;" method="post" action="handle_athletes.php" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Athlete Name" required>
                    <textarea name="bio" placeholder="Bio" required></textarea>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="number" name="wins" placeholder="Wins" required>
                    <input type="number" name="podiums" placeholder="Podium Finishes" required>
                    <input type="number" name="years_active" placeholder="Years Active" required>
                    <input type="text" name="specialty" placeholder="Specialty" required>
                    <input type="file" name="image" required>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addAthleteForm')">Cancel</button>
                </form>
                
                <?php
               $query = "UPDATE top_athletes 
               SET name='$name', bio='$bio', description='$description', wins='$wins', 
                   podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', 
                   achievements='$achievements'
               WHERE id='$id'";

     
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='athlete-profile'>";
                    echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                    echo "<h2>{$row['name']}</h2>";
                    
                    echo "<div class='bio'>";
                    echo "<h3>About the Athlete</h3>";
                    echo "<p>{$row['bio']}</p>";
                    echo "</div>";
                    
                    echo "<p>{$row['description']}</p>";
                    
                    echo "<div class='stats'>";
                    echo "<h3>Player Statistics</h3>";
                    echo "<ul>";
                    echo "<li><strong>Wins:</strong> {$row['wins']}</li>";
                    echo "<li><strong>Podium Finishes:</strong> {$row['podiums']}</li>";
                    echo "<li><strong>Years Active:</strong> {$row['years_active']}</li>";
                    echo "<li><strong>Specialty:</strong> {$row['specialty']}</li>";
                    echo "</ul></div>";
                    
                    echo "<div class='achievements'>";
                    echo "<h3>Achievements</h3>";
                    $achievements = $conn_content->query("SELECT title, description FROM athlete_achievements WHERE athlete_id='{$row['id']}'");
                    while ($ach = $achievements->fetch_assoc()) {
                        echo "<div class='achievement'>";
                        echo "<h4>{$ach['title']}</h4>";
                        echo "<p>{$ach['description']}</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                    
                    echo "<div class='gallery'>";
                    echo "<h3>Gallery</h3>";
                    echo "<div class='gallery-container'>";
                    $gallery = $conn_content->query("SELECT image_path FROM athlete_gallery WHERE athlete_id='{$row['id']}'");
                    while ($img = $gallery->fetch_assoc()) {
                        echo "<img src='{$img['image_path']}' alt='Athlete Image'>";
                    }
                    echo "</div></div>";
                    
                    echo "<button onclick=\"showEditForm('editAthleteForm{$row['id']}')\">Edit</button>";
                    echo "<button onclick=\"deleteItem('handle_athletes.php?id={$row['id']}')\">Delete</button>";
                    
                    echo "<form id='editAthleteForm{$row['id']}' style='display:none;' method='post' action='handle_athlete.php' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='id' value='{$row['id']}'>";
                    echo "<input type='text' name='name' value='{$row['name']}' required>";
                    echo "<textarea name='bio' required>{$row['bio']}</textarea>";
                    echo "<textarea name='description' required>{$row['description']}</textarea>";
                    echo "<input type='number' name='wins' value='{$row['wins']}' required>";
                    echo "<input type='number' name='podiums' value='{$row['podiums']}' required>";
                    echo "<input type='number' name='years_active' value='{$row['years_active']}' required>";
                    echo "<input type='text' name='specialty' value='{$row['specialty']}' required>";
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