<?php
include 'db_connection.php'; // Include database connection

// Fetch About Us content
$aboutUs = $conn->query("SELECT content FROM about_us LIMIT 1")->fetch_assoc();

// Fetch Highlight Videos
$highlightVideos = $conn->query("SELECT * FROM highlight_carousel");

// Fetch Top Athletes
$topAthletes = $conn->query("SELECT * FROM top_athletes");

// Fetch Community Leaders
$communityLeaders = $conn->query("SELECT * FROM community_leaders");

// Fetch Partnerships
$partnerships = $conn->query("SELECT * FROM partnerships");
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
                <p><?php echo $aboutUs['content']; ?></p>
                <button onclick="toggleForm('about-us')">Edit</button>
                <form id="about-us-form" action="update_about_us.php" method="POST" style="display:none;">
                    <textarea name="content"><?php echo $aboutUs['content']; ?></textarea>
                    <button type="submit">Save</button>
                </form>
            </section>

            <!-- Highlight Carousel -->
            <section class="highlight-carousel">
                <h3>Highlight Videos</h3>
                <?php while ($row = $highlightVideos->fetch_assoc()): ?>
                    <div class='highlight-item'>
                        <video src='<?php echo $row['video']; ?>' controls></video>
                        <h4><?php echo $row['title']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                        <button onclick="toggleForm('edit-highlight-<?php echo $row['id']; ?>')">Edit</button>
                        <button>Delete</button>
                    </div>
                <?php endwhile; ?>
                <button onclick="toggleForm('add-highlight')">Add Video</button>
            </section>

            <!-- Top Athletes -->
            <section class="top-athletes">
                <h3>Top Athletes</h3>
                <?php while ($row = $topAthletes->fetch_assoc()): ?>
                    <div class='athlete'>
                        <img src='<?php echo $row['image']; ?>' alt='<?php echo $row['name']; ?>'>
                        <h4><?php echo $row['name']; ?></h4>
                        <p><?php echo $row['description']; ?></p>
                        <button onclick="toggleForm('edit-athlete-<?php echo $row['id']; ?>')">Edit</button>
                        <button>Delete</button>
                    </div>
                <?php endwhile; ?>
                <button onclick="toggleForm('add-athlete')">Add Athlete</button>
            </section>

            <!-- Community Leaders -->
            <section class="community-leaders">
                <h3>Community Leaders</h3>
                <?php while ($row = $communityLeaders->fetch_assoc()): ?>
                    <div class='leader'>
                        <img src='<?php echo $row['image']; ?>' alt='<?php echo $row['name']; ?>'>
                        <h4><?php echo $row['name']; ?></h4>
                        <p><?php echo $row['role']; ?></p>
                        <button onclick="toggleForm('edit-leader-<?php echo $row['id']; ?>')">Edit</button>
                        <button>Delete</button>
                    </div>
                <?php endwhile; ?>
                <button onclick="toggleForm('add-leader')">Add Leader</button>
            </section>

            <!-- Partnerships -->
            <section class="partnerships">
                <h3>Partnerships</h3>
                <?php while ($row = $partnerships->fetch_assoc()): ?>
                    <div class='partner'>
                        <img src='<?php echo $row['logo']; ?>' alt='Partner Logo'>
                        <button onclick="toggleForm('edit-partner-<?php echo $row['id']; ?>')">Edit</button>
                        <button>Delete</button>
                    </div>
                <?php endwhile; ?>
                <button onclick="toggleForm('add-partner')">Add Partner</button>
            </section>
        </main>
    </div>
</body>
</html>