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
                <h3>Highlight Carousel</h3>
                <button onclick="showAddForm('addHighlightForm')">Add</button>
                <form id="addHighlightForm" style="display:none;" method="post" action="handle_highlight.php" enctype="multipart/form-data">
                    <input type="file" name="video" required>
                    <input type="text" name="title" placeholder="Title" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addHighlightForm')">Cancel</button>
                </form>

                <table border="1">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn_content->query("SELECT id, video, title, description FROM highlight_carousel");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . basename($row["video"]) . "</td>";
                            echo "<td>{$row['title']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td>
                                    <button onclick=\"showEditForm('editHighlightForm{$row['id']}')\">Edit</button>
                                    <button onclick='deleteItem(\"handle_highlight.php?delete_id={$row['id']}\")'>Delete</button>
                                </td>";
                            echo "</tr>";

                            // Hidden Edit Form (Appears when "Edit" is clicked)
                            echo "<tr id='editHighlightForm{$row['id']}' style='display:none; background:#f9f9f9;'>";
                            echo "<td colspan='4'>
                                    <form method='post' action='handle_highlight.php' enctype='multipart/form-data'>
                                        <input type='hidden' name='id' value='{$row['id']}'>
                                        <label>New Video File (optional):</label>
                                        <input type='file' name='video'>
                                        <label>Title:</label>
                                        <input type='text' name='title' value='{$row['title']}' required>
                                        <label>Description:</label>
                                        <textarea name='description' required>{$row['description']}</textarea>
                                        <button type='submit'>Update</button>
                                        <button type='button' onclick=\"hideForm('editHighlightForm{$row['id']}')\">Cancel</button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
                    <button type="button" onclick="addNewAchievement()">+ Add More Achievements</button>

                    <!-- Gallery -->
                    <h3>Gallery</h3>
                    <div id="gallery-container">
                        <div class="gallery-item">
                            <input type="file" name="athlete_gallery[]" required>
                            <textarea name="gallery_descriptions[]" placeholder="Enter description for this image." required></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="addNewGalleryImage()">+ Add More Images</button>

                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addAthleteForm')">Cancel</button>
                </form>

                <!-- Athlete Table -->
                <table border="1">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Bio</th>
                            <th>Description</th>
                            <th>Wins</th>
                            <th>Podium Finishes</th>
                            <th>Years Active</th>
                            <th>Specialty</th>
                            <th>Achievements</th>
                            <th>Gallery</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn_content->query("SELECT * FROM top_athletes");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . basename($row['image']) . "</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['bio']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td>{$row['wins']}</td>";
                            echo "<td>{$row['podium_finishes']}</td>";
                            echo "<td>{$row['years_active']}</td>";
                            echo "<td>{$row['specialty']}</td>";

                            // Achievements
                            echo "<td>";
                            $achievements = $conn_content->query("SELECT title, description FROM achievements WHERE athlete_id='{$row['id']}'");
                            while ($ach = $achievements->fetch_assoc()) {
                                echo "<li>{$ach['title']}</li>";
                                echo "<li>{$ach['description']}</li>";
                            }
                            echo "</td>";

                            // Gallery
                            echo "<td>";
                            $gallery = $conn_content->query("SELECT image, description FROM athlete_gallery WHERE athlete_id='{$row['id']}'");
                            while ($img = $gallery->fetch_assoc()) {
                                echo "<li>" . basename($img['image']) . "</li>";
                                echo "<li>" . basename($img['description']) . "</li>";
                            }
                            echo "</td>";

                            echo "<td><button onclick=\"showEditForm('editAthleteModal{$row['id']}')\">Edit</button></td>";

                            echo "</tr>";

                            // Edit Modal
                            echo "<button onclick=\"showEditForm('editAthleteForm{$row['id']}')\">Edit</button>";
                            echo "<form id='editAthleteForm{$row['id']}' style='display:none;' method='post' action='handle_athletes.php' enctype='multipart/form-data'>";
                            echo "<input type='hidden' name='edit_id' value='{$row['id']}'>";
                            echo "<input type='text' name='name' value='{$row['name']}' required>";
                            echo "<textarea name='bio' required>{$row['bio']}</textarea>";
                            echo "<textarea name='description' required>{$row['description']}</textarea>";
                            echo "<input type='number' name='wins' value='{$row['wins']}' required>";
                            echo "<input type='number' name='podium_finishes' value='{$row['podium_finishes']}' required>";
                            echo "<input type='number' name='years_active' value='{$row['years_active']}' required>";
                            echo "<input type='text' name='specialty' value='{$row['specialty']}' required>";
                            echo "<input type='hidden' name='existing_image' value='{$row['image']}'>";
                            echo "<input type='file' name='image'>";

                            // Achievements Section
                            echo "<h3>Achievements</h3>";
                            echo "<div id='achievements-container-{$row['id']}'>";
                            $achievements = $conn_content->query("SELECT id, title, description FROM achievements WHERE athlete_id='{$row['id']}'");
                            while ($ach = $achievements->fetch_assoc()) {
                                echo "<div class='achievement'>";
                                echo "<input type='hidden' name='achievement_ids[]' value='{$ach['id']}'>";
                                echo "<input type='text' name='achievements[]' value='{$ach['title']}' required>";
                                echo "<textarea name='achievements_descriptions[]' required>{$ach['description']}</textarea>";
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "<button type='button' onclick=\"addAchievement('achievements-container-{$row['id']}')\">+ Add More Achievements</button>";
                            echo "<button type='button' onclick=\"removeLastAchievement('achievements-container-{$row['id']}')\">Cancel</button>";

                            // Gallery Section
                            echo "<h3>Gallery</h3>";
                            echo "<div id='gallery-container-{$row['id']}'>";
                            echo "<input type='hidden' name='deleted_images' id='deleted_images_{$row['id']}'>";
                            $gallery = $conn_content->query("SELECT id, image, description FROM athlete_gallery WHERE athlete_id='{$row['id']}'");

                            while ($img = $gallery->fetch_assoc()) {
                                echo "<div class='gallery-item'>";
                                echo "<input type='hidden' name='gallery_image_ids[]' value='{$img['id']}'>"; 
                                echo "<img src='{$img['image']}' alt='Athlete Gallery Image' width='100'>";
                                echo "<input type='hidden' name='gallery_existing_images[]' value='{$img['image']}'>"; 
                                echo "<input type='file' name='athlete_gallery[]'>"; 
                                echo "<textarea name='gallery_descriptions[]' required>{$img['description']}</textarea>";
                                echo "</div>";
                            }
                            echo "</div>";
                            echo "<button type='button' onclick=\"addGalleryImage('gallery-container-{$row['id']}')\">+ Add More Images</button>";
                            echo "<button type='button' onclick=\"removeLastGalleryImage('gallery-container-{$row['id']}')\">Cancel</button>";

                            echo "<button type='submit'>Update</button>";
                            echo "<button type='button' onclick=\"hideForm('editAthleteModal{$row['id']}')\">Cancel</button>";
                            echo "</form>";
                            echo "</div>";
                        }
                        ?>
                    </tbody>
                </table>

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

                <table border="1">
                    <thead>
                        <tr>
                            <th>Profile Picture File</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn_content->query("SELECT id, name, role, image FROM community_leaders");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $imageFileName = basename($row["image"]); // Extract file name
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($imageFileName) . '</td>';
                                echo '<td>' . htmlspecialchars($row["name"]) . '</td>';
                                echo '<td>' . htmlspecialchars($row["role"]) . '</td>';
                                echo '<td>
                                        <button onclick="toggleForm(\'editLeaderForm' . $row['id'] . '\')">Edit</button>
                                        <form id="editLeaderForm' . $row['id'] . '" style="display: none;" method="POST" action="handle_leaders.php" enctype="multipart/form-data">
                                            <input type="hidden" name="edit_id" value="' . $row['id'] . '">
                                            <input type="text" name="name" value="' . htmlspecialchars($row['name']) . '" required>
                                            <input type="text" name="role" value="' . htmlspecialchars($row['role']) . '" required>
                                            <input type="file" name="image">
                                            <button type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="handle_leaders.php">
                                            <input type="hidden" name="id" value="' . $row['id'] . '">
                                            <button type="submit" name="delete">Remove</button>
                                        </form>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">No community leaders added yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </section>




            <section>
                <label>Partners & Sponsors:</label>
                <button onclick="toggleForm('addPartnerForm')">Add Partner</button>

                <form id="addPartnerForm" style="display: none;" method="POST" action="handle_partnerships.php" enctype="multipart/form-data">
                    <input type="file" name="logo" required>
                    <button type="submit">Add</button>
                    <button type="button" onclick="hideForm('addPartnerForm')">Cancel</button>
                </form>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Logo File Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn_content->query("SELECT id, logo FROM partnerships");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $logoFileName = basename($row["logo"]); // Extract file name
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($logoFileName) . '</td>';
                                echo '<td>
                                        <form method="POST" action="handle_partnerships.php">
                                            <input type="hidden" name="id" value="' . $row['id'] . '">
                                            <button type="submit" name="delete">Remove</button>
                                        </form>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2">No partners or sponsors added yet.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
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

            function showEditForm(formId) {
                document.getElementById(formId).style.display = 'block';
            }

            function hideForm(formId) {
                document.getElementById(formId).style.display = 'none';
            }

                        // Functions for the "Add Athlete" Form
            function addNewAchievement() {
                const container = document.getElementById('achievements-container');
                if (!container) return; // Ensure the container exists
                const newAchievement = document.createElement('div');
                newAchievement.classList.add('achievement');
                newAchievement.innerHTML = `
                    <input type="text" name="achievements[]" placeholder="Achievement Title" required>
                    <textarea name="achievements_descriptions[]" placeholder="Description" required></textarea>
                    <button type="button" onclick="this.parentNode.remove()">Remove</button>
                `;
                container.appendChild(newAchievement);
            }

            function addNewGalleryImage() {
                const container = document.getElementById('gallery-container');
                if (!container) return;
                const newGalleryItem = document.createElement('div');
                newGalleryItem.classList.add('gallery-item');
                newGalleryItem.innerHTML = `
                    <input type="file" name="athlete_gallery[]" accept="image/*" required>
                    <textarea name="gallery_descriptions[]" placeholder="Enter description for this image." required></textarea>
                    <button type="button" onclick="this.parentNode.remove()">Remove</button>
                `;
                container.appendChild(newGalleryItem);
            }


            // Cancel entire form (hides it)
            function hideForm(formId) {
                const form = document.getElementById(formId);
                if (form) {
                    form.style.display = 'none';
                }
            }

            // Functions for the "Edit Athlete" Form
            function addAchievement(containerId) {
                let container = document.getElementById(containerId);
                let newAchievement = document.createElement("div");
                newAchievement.classList.add("achievement");
                newAchievement.innerHTML = `
                    <input type="hidden" name="achievement_ids[]" value="new">
                    <input type="text" name="achievements[]" placeholder="Title" required>
                    <textarea name="achievements_descriptions[]" placeholder="Description" required></textarea>
                `;
                container.appendChild(newAchievement);
            }

            function removeLastAchievement(containerId) {
                let container = document.getElementById(containerId);
                let achievements = container.getElementsByClassName("achievement");
                if (achievements.length > 0) {
                    container.removeChild(achievements[achievements.length - 1]);
                }
            }

            function addGalleryImage(containerId) {
                let container = document.getElementById(containerId);
                let newImageDiv = document.createElement("div");
                newImageDiv.classList.add("gallery-item");
                newImageDiv.innerHTML = `
                    <input type="hidden" name="gallery_existing_ids[]" value="new">
                    <input type="file" name="athlete_gallery[]" accept="image/*" required>
                    <textarea name="gallery_descriptions[]" placeholder="Image Description" required></textarea>
                `;
                container.appendChild(newImageDiv);
            }

            function removeLastGalleryImage(containerId) {
                let container = document.getElementById(containerId);
                let galleryItems = container.getElementsByClassName("gallery-item");

                if (galleryItems.length > 0) {
                    let lastItem = galleryItems[galleryItems.length - 1];
                    
                    // Check if the last item contains a hidden input for ID
                    let imageIdInput = lastItem.querySelector("input[name='gallery_image_ids[]']");
                    if (imageIdInput) {
                        let deletedImagesInput = document.getElementById('deleted_images');
                        if (!deletedImagesInput) {
                            deletedImagesInput = document.createElement("input");
                            deletedImagesInput.type = "hidden";
                            deletedImagesInput.name = "deleted_images";
                            deletedImagesInput.id = "deleted_images";
                            container.appendChild(deletedImagesInput);
                        }
                        // Append the ID to deleted images
                        deletedImagesInput.value += deletedImagesInput.value ? `,${imageIdInput.value}` : imageIdInput.value;
                    }

                    // Remove from UI
                    container.removeChild(lastItem);
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