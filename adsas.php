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
                    <label>Upload Images:</label>
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
                    $achievements = $conn_content->query("SELECT title, description FROM achievements WHERE athlete_id='{$row['id']}'");
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
                    echo "<button type='button' onclick=\"hideForm('editAthleteForm{$row['id']}')\">Cancel</button>";
                    echo "</form></div>";

                }
                ?>
            </section>