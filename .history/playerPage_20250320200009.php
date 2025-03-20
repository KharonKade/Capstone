<?php
// Include the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get the athlete ID from the query string
$athlete_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the athlete's information from the database
$query = "SELECT * FROM top_athletes WHERE id = $athlete_id";
$athlete_result = $conn->query($query);
$athlete = $athlete_result->fetch_assoc();

// Fetch the athlete's achievements
$achievements_query = "SELECT title, description FROM achievements WHERE athlete_id = $athlete_id";
$achievements_result = $conn->query($achievements_query);

// Fetch the athlete's gallery images
$gallery_query = "SELECT image, description FROM athlete_gallery WHERE athlete_id = $athlete_id";
$gallery_result = $conn->query($gallery_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athlete Profile</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="Css/playerPage.css">
</head>
<body>

    <div class="athlete-profile">
        <?php if ($athlete): ?>
            <img src="<?php echo $athlete['image']; ?>" alt="<?php echo $athlete['name']; ?>">

            <h2><?php echo $athlete['name']; ?></h2>

            <div class="bio">
                <h3>About the Athlete</h3>
                <p><?php echo $athlete['bio']; ?></p>
            </div>

            <p><?php echo $athlete['description']; ?></p>

            <div class="stats">
                <h3>Player Statistics</h3>
                <ul>
                    <li><strong>Wins:</strong> <?php echo $athlete['wins']; ?></li>
                    <li><strong>Podium Finishes:</strong> <?php echo $athlete['podium_finishes']; ?></li>
                    <li><strong>Years Active:</strong> <?php echo $athlete['years_active']; ?></li>
                    <li><strong>Specialty:</strong> <?php echo $athlete['specialty']; ?></li>
                </ul>
            </div>

            <!-- Achievements Section -->
            <section class="achievements">
                <h3>Achievements</h3>
                <div class="achievement-list">
                    <?php while ($ach = $achievements_result->fetch_assoc()): ?>
                        <div class="achievement">
                            <h4><?php echo $ach['title']; ?></h4>
                            <p><?php echo $ach['description']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- Gallery Section -->
            <section class="gallery">
                <h3>Gallery</h3>
                <div class="gallery-container">
                    <?php while ($img = $gallery_result->fetch_assoc()): ?>
                        <div class="gallery-item">
                            <img src="<?php echo $img['image']; ?>" alt="Gallery Image">
                            <p class="gallery-description"><?php echo $img['description']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php else: ?>
            <p>No athlete found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
