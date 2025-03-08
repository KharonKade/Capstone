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
                <p><?php echo htmlspecialchars($aboutUs['content']); ?></p>
                <button onclick="toggleForm('about-us')">Edit</button>
                <form id="about-us-form" action="update_about_us.php" method="POST" style="display:none;">
                    <textarea name="content"><?php echo htmlspecialchars($aboutUs['content']); ?></textarea>
                    <button type="submit">Save</button>
                </form>
            </section>

            <!-- Highlight Carousel -->
            <section class="highlight-carousel">
                <h3>Highlight Videos</h3>
                <?php while ($row = $highlightVideos->fetch_assoc()): ?>
                    <div class='highlight-item'>
                        <video src='<?php echo htmlspecialchars($row['video']); ?>' controls></video>
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <form action="add_item.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="highlight">
                            <input type="text" name="title" placeholder="Title" required>
                            <textarea name="description" placeholder="Description" required></textarea>
                            <input type="file" name="video" required>
                            <button type="submit">Add Highlight</button>
                        </form>
                        <form action="edit_item.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="highlight">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="title" value="<?php echo $row['title']; ?>" required>
                            <textarea name="description" required><?php echo $row['description']; ?></textarea>
                            <input type="file" name="video">
                            <button type="submit">Save Changes</button>
                        </form>

                    </div>
                <?php endwhile; ?>
                <form action="add_highlight.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="title" placeholder="Title">
                    <textarea name="description" placeholder="Description"></textarea>
                    <input type="file" name="video">
                    <button type="submit">Add Video</button>
                </form>
            </section>

            <!-- Top Athletes -->
            <section class="top-athletes">
                <h3>Top Athletes</h3>
                <?php while ($row = $topAthletes->fetch_assoc()): ?>
                    <div class='athlete'>
                        <img src='<?php echo htmlspecialchars($row['image']); ?>' alt='<?php echo htmlspecialchars($row['name']); ?>'>
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <form action="delete_athlete.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <form action="add_athlete.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Name">
                    <textarea name="description" placeholder="Description"></textarea>
                    <input type="file" name="image">
                    <button type="submit">Add Athlete</button>
                </form>
            </section>

            <!-- Community Leaders -->
            <section class="community-leaders">
                <h3>Community Leaders</h3>
                <?php while ($row = $communityLeaders->fetch_assoc()): ?>
                    <div class='leader'>
                        <img src='<?php echo htmlspecialchars($row['image']); ?>' alt='<?php echo htmlspecialchars($row['name']); ?>'>
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p><?php echo htmlspecialchars($row['role']); ?></p>
                        <form action="delete_leader.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <form action="add_leader.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="role" placeholder="Role">
                    <input type="file" name="image">
                    <button type="submit">Add Leader</button>
                </form>
            </section>

            <!-- Partnerships -->
            <section class="partnerships">
                <h3>Partnerships</h3>
                <?php while ($row = $partnerships->fetch_assoc()): ?>
                    <div class='partner'>
                        <img src='<?php echo htmlspecialchars($row['logo']); ?>' alt='Partner Logo'>
                        <form action="delete_partner.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <form action="add_partner.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Partner Name">
                    <input type="file" name="logo">
                    <button type="submit">Add Partner</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
