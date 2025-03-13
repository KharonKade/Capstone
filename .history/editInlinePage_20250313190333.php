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
                include 'handle_athletes.php';
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
                
                <!-- Add Athlete Form -->
                <form id="addAthleteForm" style="display:none;" method="post" action="handle_athletes.php" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Athlete Name" required>
                    <textarea name="bio" placeholder="Bio" required></textarea>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="number" name="wins" placeholder="Wins" required>
                    <input type="number" name="podium_finishes" placeholder="Podium Finishes" required>
                    <input type="number" name="years_active" placeholder="Years Active" required>
                    <input type="text" name="specialty" placeholder="Specialty" required>
                    <input type="file" name="image" required>

                    <!-- Achievements -->
                    <h3>Achievements</h3>
                    <div id="achievements-container">
                        <div class="achievement">
                            <input type="text" name="achievements[]" placeholder="Achievement Title" required>
                            <textarea name="achievements_descriptions[]" placeholder="Description" required></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addAchievement()">+ Add More Achievements</button>
                    <button type="button" onclick="removeAchievement()">Cancel</button>

                    <!-- Gallery -->
                    <h3>Gallery</h3>
                    <label>Upload Images:</label>
                    <div id="gallery-container">
                        <div class="gallery-item">
                            <input type="file" name="athlete_gallery[]" required>
                            <textarea name="gallery_descriptions[]" placeholder="Enter description for this image." required></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addGalleryImage()">+ Add More Images</button>
                    <button type="button" onclick="removeGalleryImage()">Cancel</button>

                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addAthleteForm')">Cancel</button>
                </form>

                <?php
                $result = $conn_content->query("SELECT * FROM top_athletes");
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
                    echo "<li><strong>Podium Finishes:</strong> {$row['podium_finishes']}</li>";
                    echo "<li><strong>Years Active:</strong> {$row['years_active']}</li>";
                    echo "<li><strong>Specialty:</strong> {$row['specialty']}</li>";
                    echo "</ul></div>";

                    // Achievements Section
                    echo "<section class='achievements'>";
                    echo "<h3>Achievements</h3>";
                    $achievements = $conn_content->query("SELECT title, description FROM athlete_achievements WHERE athlete_id='{$row['id']}'");
                    echo "<div class='achievement-list'>";
                    while ($ach = $achievements->fetch_assoc()) {
                        echo "<div class='achievement'>";
                        echo "<h4>{$ach['title']}</h4>";
                        echo "<p>{$ach['description']}</p>";
                        echo "</div>";
                    }
                    echo "</div></section>";

                    // Gallery Section
                    echo "<section class='gallery'>";
                    echo "<h3>Gallery</h3>";
                    echo "<div class='gallery-container'>";

                    // Retrieve gallery images and descriptions
                    $gallery = $conn_content->query("SELECT image, description FROM athlete_gallery WHERE athlete_id='{$row['id']}'");

                    if ($gallery->num_rows > 0) {
                        while ($img = $gallery->fetch_assoc()) {
                            echo "<div class='gallery-item'>";
                            echo "<img src='{$img['image']}' alt='Athlete Gallery Image'>";
                            echo "<p class='gallery-description'>{$img['description']}</p>"; // Display image description
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No gallery images available.</p>";
                    }

                    echo "</div>";
                    echo "</section>";

                    // Edit Athlete Form
                    echo "<form id='editAthleteForm{$row['id']}' style='display:none;' method='post' action='handle_athletes.php' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='id' value='{$row['id']}'>";
                    echo "<input type='text' name='name' value='{$row['name']}' required>";
                    echo "<textarea name='bio' required>{$row['bio']}</textarea>";
                    echo "<textarea name='description' required>{$row['description']}</textarea>";
                    echo "<input type='number' name='wins' value='{$row['wins']}' required>";
                    echo "<input type='number' name='podium_finishes' value='{$row['podium_finishes']}' required>";
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

            function addAchievement() {
                const container = document.getElementById('achievements-container');
                const newAchievement = document.createElement('div');
                newAchievement.classList.add('achievement');
                newAchievement.innerHTML = `
                    <input type="text" name="achievements[]" placeholder="Achievement Title" required>
                    <textarea name="achievements_descriptions[]" placeholder="Description" required></textarea>
                `;
                container.appendChild(newAchievement);
            }

            function removeAchievement() {
                const container = document.getElementById('achievements-container');
                if (container.children.length > 1) {
                    container.removeChild(container.lastElementChild);
                }
            }
            function addGalleryImage() {
                const container = document.getElementById('gallery-container');
                const newGalleryItem = document.createElement('div');
                newGalleryItem.classList.add('gallery-item');
                newGalleryItem.innerHTML = `
                    <input type="file" name="athlete_gallery[]" required>
                    <textarea name="gallery_descriptions[]" placeholder="Enter description for this image." required></textarea>
                `;
                container.appendChild(newGalleryItem);
            }

            function removeGalleryImage() {
                const container = document.getElementById('gallery-container');
                if (container.children.length > 1) {
                    container.removeChild(container.lastElementChild);
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