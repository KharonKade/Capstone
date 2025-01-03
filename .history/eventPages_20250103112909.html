<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Event Details
$event = null;
$image_result = null;
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($event_id > 0) {
    $event_sql = "SELECT e.event_name, e.description, e.event_date, e.location 
                  FROM upcoming_events e 
                  WHERE e.id = $event_id AND e.status = 'active'";
    $event_result = $conn->query($event_sql);
    if ($event_result && $event_result->num_rows > 0) {
        $event = $event_result->fetch_assoc();
        $image_sql = "SELECT image_path FROM event_images WHERE event_id = $event_id";
        $image_result = $conn->query($image_sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="Css/eventPages.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="images/logo.png" alt="BASF Logo" class="logo">
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="spots.html">Spots</a></li>
                    <li><a href="event.php">Events</a></li>
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="sponsorship.html">Sponsorship</a></li>
                    <li><a href="contactUs.html">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <?php if ($event): ?>
        <section class="event-hero">
            <div class="event-hero-content">
                <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
            </div>
        </section>

        <div class="event-page">
            <div class="left-section">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        if ($image_result && $image_result->num_rows > 0) {
                            while ($image = $image_result->fetch_assoc()) {
                                echo '<div class="swiper-slide">
                                        <img src="' . htmlspecialchars($image['image_path']) . '" alt="Event Poster" class="event-poster" onclick="openModal(\'' . htmlspecialchars($image['image_path']) . '\')">
                                      </div>';
                            }
                        } else {
                            echo "<p>No images available for this event.</p>";
                        }
                        ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            <div class="right-section">
                <div class="event-details">
                    <p><strong>Dates & Times:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                </div>
                <h2>Partners & Sponsors</h2>
                <div class="sponsors">
                    <img src="images/vanlogo.png" alt="Sponsor 1 Logo">
                    <img src="images/vanlogo.png" alt="Sponsor 2 Logo">
                    <img src="images/vanlogo.png" alt="Sponsor 3 Logo">
                </div>
            </div>
        </div>
    <?php else: ?>
        <section class="event-hero">
            <div class="event-hero-content">
                <h1>Event Not Found</h1>
            </div>
        </section>
        <div class="event-page">
            <p>The event you're looking for does not exist or is inactive.</p>
        </div>
    <?php endif; ?>

    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <footer class="footer">
        <!-- Footer content -->
    </footer>

    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });

        function openModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
