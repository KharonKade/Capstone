<?php
// Include the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content_skateboard";
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
    <title>Player Profile</title>
    <link rel="stylesheet" href="Css/playerPageSkateboard.css">
</head>
<body>
    <header class="player-header">
        <div class="overlay"></div>
        <div class="player-info">
            <?php if ($athlete): ?>
                <img src="<?php echo $athlete['image']; ?>" alt="<?php echo $athlete['name']; ?>" class="player-img">
                <h1 class="player-name"><?php echo $athlete['name']; ?></h1>
                <p class="player-role"><?php echo $athlete['specialty']; ?></p>
            <?php else: ?>
                <p>Athlete not found.</p>
            <?php endif; ?>
        </div>
    </header>
    
    <section class="player-details">
        <div class="bio">
            <h2>About the Player</h2>
            <?php if ($athlete): ?>
                <p><?php echo $athlete['bio']; ?></p>
            <?php else: ?>
                <p>No bio available.</p>
            <?php endif; ?>
        </div>
        
        <div class="stats">
            <h2>Player Statistics</h2>
            <ul>
                <?php if ($athlete): ?>
                    <li><strong>Wins:</strong> <?php echo $athlete['wins']; ?></li>
                    <li><strong>Podium Finishes:</strong> <?php echo $athlete['podium_finishes']; ?></li>
                    <li><strong>Years Active:</strong> <?php echo $athlete['years_active']; ?></li>
                    <li><strong>Specialty:</strong> <?php echo $athlete['specialty']; ?></li>
                <?php else: ?>
                    <li><strong>Data not available</strong></li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
    
    <section class="achievements">
        <h2 style="color: white";>Achievements</h2>
        <div class="achievement-list">
            <?php while ($ach = $achievements_result->fetch_assoc()): ?>
                <div class="achievement">
                    <h3><?php echo $ach['title']; ?></h3>
                    <p><?php echo $ach['description']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    
    <section class="gallery">
        <h2>Gallery</h2>
        <div class="gallery-container">
            <?php while ($img = $gallery_result->fetch_assoc()): ?>
                <div class="gallery-item">
                    <img src="<?php echo $img['image']; ?>" alt="Gallery Image">
                    <p class="gallery-description"><?php echo $img['description']; ?></p> <!-- Display description here -->
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <div class="return-container">
        <button onclick="history.back()" class="return-btn">Go Back</button>
    </div>

    
    <footer class="footer">
        <!-- BASF Logo Section -->
        <div class="footer-section logo-section">
            <img src="images/logo.png" alt="BASF Logo" class="footer-logo">
        </div>
    
        <!-- Explore Us Section -->
        <div class="footer-section explore-section">
            <h3>Explore Us</h3>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="skateboard.html">Skateboarding</a></li>
                <li><a href="inline.html">In-Line</a></li>
                <li><a href="bmx.html">BMX</a></li>
                <li><a href="spots.html">Spots</a></li>
                <li><a href="event.html">Events</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="sponsorship.html">Sponsorship</a></li>
                <li><a href="contactUs.html">Contact Us</a></li>
            </ul>
        </div>
    
        <!-- Contact Us Section -->
        <div class="footer-section contact-section">
            <h3>Contact Us</h3>
            <ul>
                <li>09094431201</li>
                <li>09348913502</li>
                <li>09761816282</li>
                <li>basf@gmail.com</li>
            </ul>
        </div>
    
        <!-- Connect with Us Section -->
        <div class="footer-section social-section">
            <h3>Connect with us</h3>
            <div class="social-icons">
                <a href="https://facebook.com"><img src="images/fblogo.png" alt="Facebook"></a>
                <a href="https://instagram.com"><img src="images/iglogo.png" alt="Instagram"></a>
            </div>
        </div>
    
        <!-- Supported by Section -->
        <div class="footer-section supported-section">
            <h3>Supported by</h3>
            <img src="images/vanlogo.png" alt="Sponsor Logo" class="sponsor-logo">
        </div>
    </footer>

    
</body>
</html>
