<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname_content = "basf_content";

    $conn_content = new mysqli($servername, $username, $password, "basf_content");

    if ($conn_content->connect_error) {
        die("Connection failed: " . $conn_content->connect_error);
    }
?>

<section class="inline-content">
    <div class="middle-content">
        <h2>About Us</h2>
        <?php
        $result = $conn_content->query("SELECT content FROM content WHERE section='about_us'");
        if ($row = $result->fetch_assoc()) {
            echo '<p>' . $row['content'] . '</p>';
        } else {
            echo "<p>About Us content not found.</p>";
        }
        ?>
        <button onclick="showEditForm('aboutUsForm')">Edit</button>
        <form id="aboutUsForm" style="display:none;" method="post" action="update_aboutus.php">
            <textarea name="about_us" required></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</section>

<section>
    <h2>Highlight Carousel</h2>
    <button onclick="showAddForm('addHighlightForm')">Add</button>
    <form id="addHighlightForm" style="display:none;" method="post" action="add_highlight.php" enctype="multipart/form-data">
        <input type="file" name="video" required>
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit">Add</button>
    </form>
    <?php
    $result = $conn_content->query("SELECT id, video, title, description FROM highlight_carousel");
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo '<video src="' . $row["video"] . '" autoplay muted loop></video>';
        echo "<p>{$row['title']}</p><p>{$row['description']}</p>";
        echo "<button onclick=\"showEditForm('editHighlightForm{$row['id']}')\">Edit</button>";
        echo "<button onclick=\"deleteItem('delete_highlight.php?id={$row['id']}')\">Delete</button>";
        echo "<form id='editHighlightForm{$row['id']}' style='display:none;' method='post' action='update_highlight.php' enctype='multipart/form-data'>";
        echo "<input type='hidden' name='id' value='{$row['id']}'>";
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
    <h2>Top Athletes</h2>
    <button onclick="showAddForm('addAthleteForm')">Add</button>
    <form id="addAthleteForm" style="display:none;" method="post" action="add_athlete.php" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Athlete Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="image" required>
        <button type="submit">Add</button>
    </form>
    <?php
    $result = $conn_content->query("SELECT id, name, description, image FROM top_athletes");
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<img src='{$row['image']}' alt='{$row['name']}'>";
        echo "<p>{$row['name']}</p><p>{$row['description']}</p>";
        echo "<button onclick=\"showEditForm('editAthleteForm{$row['id']}')\">Edit</button>";
        echo "<button onclick=\"deleteItem('delete_athlete.php?id={$row['id']}')\">Delete</button>";
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


<section class="community-leaders-section">
    <h1 class="section-heading">Community Leaders</h1>
    <button onclick="toggleForm('addLeaderForm')">Add Leader</button>
    <form id="addLeaderForm" style="display: none;" method="POST" action="handle_leaders.php" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="role" placeholder="Role" required>
        <input type="file" name="image" required>
        <button type="submit">Add</button>
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
                            <button onclick="toggleForm('editLeaderForm<?php echo $row['id']; ?>')">Edit</button>
                            <form id="editLeaderForm<?php echo $row['id']; ?>" style="display: none;" method="POST" action="handle_leaders.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                <input type="text" name="role" value="<?php echo htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8'); ?>" required>
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
            echo '<p class="no-data">No community leaders available.</p>';
        }
        ?>
    </div>
</section>

<section class="partnership-section">
    <h2>Partners & Sponsors</h2>
    <button onclick="toggleForm('addPartnerForm')">Add Partner</button>
    <form id="addPartnerForm" style="display: none;" method="POST" action="handle_partnerships.php" enctype="multipart/form-data">
        <input type="file" name="logo" required>
        <button type="submit">Add</button>
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
            echo '<p class="no-data">No partners or sponsors available.</p>';
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
</script>

<?php
$conn_content->close();
?>