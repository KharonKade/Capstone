<?php
$host = "localhost"; // Your database host (e.g., localhost)
$user = "root"; // Your database username
$pass = ""; // Your database password (leave empty if none)
$dbname = "basf_content"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inline Page</title>
    <link rel="stylesheet" href="Css/editInlinePage.css">
    <script>
        function toggleForm(section) {
            var form = document.getElementById(section + '-form');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="editInlinePage.php">Manage Inline Page</a></li>
            </ul>
        </nav>
        <main class="content">
            <h2>Manage Inline Page</h2>
            
            <!-- About Us Section -->
            <section class="about-us">
                <h3>About Us</h3>
                <?php
                $result = $conn->query("SELECT content FROM about_us LIMIT 1");
                $aboutUs = $result->fetch_assoc();
                echo "<p>{$aboutUs['content']}</p>";
                ?>
                <button onclick="toggleForm('about-us')">Edit</button>
                <form id="about-us-form" action="update_about_us.php" method="POST" style="display:none;">
                    <textarea name="content"> <?php echo $aboutUs['content']; ?> </textarea>
                    <button type="submit">Save</button>
                </form>
            </section>

            <!-- Highlight Carousel -->
            <section class="highlight-carousel">
                <h3>Highlight Videos</h3>
                <?php
                $result = $conn->query("SELECT * FROM highlight_carousel");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='highlight-item'>";
                    echo "<video src='{$row['video']}' controls></video>";
                    echo "<h4>{$row['title']}</h4>";
                    echo "<p>{$row['description']}</p>";
                    echo "<button onclick=\"toggleForm('edit-highlight-{$row['id']}')\">Edit</button>";
                    echo "<button>Delete</button>";
                    echo "</div>";
                }
                ?>
                <button onclick="toggleForm('add-highlight')">Add Video</button>
                <form id="add-highlight-form" action="add_highlight.php" method="POST" style="display:none;">
                    <input type="text" name="title" placeholder="Title">
                    <input type="text" name="description" placeholder="Description">
                    <input type="file" name="video">
                    <button type="submit">Upload</button>
                </form>
            </section>

            <!-- Top Athletes -->
            <section class="top-athletes">
                <h3>Top Athletes</h3>
                <?php
                $result = $conn->query("SELECT * FROM top_athletes");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='athlete'>";
                    echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                    echo "<h4>{$row['name']}</h4>";
                    echo "<p>{$row['description']}</p>";
                    echo "<button onclick=\"toggleForm('edit-athlete-{$row['id']}')\">Edit</button>";
                    echo "<button>Delete</button>";
                    echo "</div>";
                }
                ?>
                <button onclick="toggleForm('add-athlete')">Add Athlete</button>
                <form id="add-athlete-form" action="add_athlete.php" method="POST" style="display:none;">
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="description" placeholder="Description">
                    <input type="file" name="image">
                    <button type="submit">Add</button>
                </form>
            </section>

            <!-- Community Leaders -->
            <section class="community-leaders">
                <h3>Community Leaders</h3>
                <?php
                $result = $conn->query("SELECT * FROM community_leaders");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='leader'>";
                    echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                    echo "<h4>{$row['name']}</h4>";
                    echo "<p>{$row['role']}</p>";
                    echo "<button onclick=\"toggleForm('edit-leader-{$row['id']}')\">Edit</button>";
                    echo "<button>Delete</button>";
                    echo "</div>";
                }
                ?>
                <button onclick="toggleForm('add-leader')">Add Leader</button>
                <form id="add-leader-form" action="add_leader.php" method="POST" style="display:none;">
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="role" placeholder="Role">
                    <input type="file" name="image">
                    <button type="submit">Add</button>
                </form>
            </section>

            <!-- Partnerships -->
            <section class="partnerships">
                <h3>Partnerships</h3>
                <?php
                $result = $conn->query("SELECT * FROM partnerships");
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='partner'>";
                    echo "<img src='{$row['logo']}' alt='Partner Logo'>";
                    echo "<button onclick=\"toggleForm('edit-partner-{$row['id']}')\">Edit</button>";
                    echo "<button>Delete</button>";
                    echo "</div>";
                }
                ?>
                <button onclick="toggleForm('add-partner')">Add Partner</button>
                <form id="add-partner-form" action="add_partner.php" method="POST" style="display:none;">
                    <input type="file" name="logo">
                    <button type="submit">Add</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>

