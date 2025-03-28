<?php
// Establish a connection to the database
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "basf_events";   // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the URL
$event_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($event_id > 0) {
    // Fetch event details based on the event ID
    $event_sql = "SELECT e.event_name, e.description, e.event_date, e.location
                  FROM upcoming_events e
                  WHERE e.id = $event_id AND e.status = 'active'";

    $event_result = $conn->query($event_sql);
    $event = $event_result->fetch_assoc();

    // Fetch event images based on the event ID
    $image_sql = "SELECT image_path FROM event_images WHERE event_id = $event_id";
    $image_result = $conn->query($image_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="Css/eventPages.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Swiper JS -->
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

    <section class="event-hero">
        <div class="event-hero-content">
            <h1><?php echo $event['event_name']; ?></h1>
        </div>
    </section>

    <div class="event-page">
        <!-- Left Section (Image Carousel) -->
        <div class="left-section">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Display event images
                    if ($image_result->num_rows > 0) {
                        while ($image = $image_result->fetch_assoc()) {
                            echo '<div class="swiper-slide">
                                    <img src="' . $image['image_path'] . '" alt="Event Poster" class="event-poster" onclick="openModal(\'' . $image['image_path'] . '\')">
                                  </div>';
                        }
                    } else {
                        echo "<p>No images available for this event.</p>";
                    }
                    ?>
                </div>
                <!-- Pagination dots (optional) -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Right Section (Event Details) -->
        <div class="right-section">
            <div class="event-details">
                <p><strong>Dates & Times:</strong> <?php echo $event['event_date']; ?></p>
                <p><strong>Description:</strong> <?php echo $event['description']; ?></p>
                <p><strong>Location:</strong> <?php echo $event['location']; ?></p>
            </div>
            <h2>Partners & Sponsors</h2>
            <div class="sponsors">
                <img src="images/vanlogo.png" alt="Sponsor 1 Logo">
                <img src="images/vanlogo.png" alt="Sponsor 2 Logo">
                <img src="images/vanlogo.png" alt="Sponsor 3 Logo">
            </div>
        </div>
    </div>

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <footer class="footer">
        <div class="footer-section logo-section">
            <img src="images/logo.png" alt="BASF Logo" class="footer-logo">
        </div>
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
        <div class="footer-section contact-section">
            <h3>Contact Us</h3>
            <ul>
                <li>09094431201</li>
                <li>09348913502</li>
                <li>09761816282</li>
                <li>basf@gmail.com</li>
            </ul>
        </div>
        <div class="footer-section social-section">
            <h3>Connect with us</h3>
            <div class="social-icons">
                <a href="https://facebook.com"><img src="images/fblogo.png" alt="Facebook"></a>
                <a href="https://instagram.com"><img src="images/iglogo.png" alt="Instagram"></a>
            </div>
        </div>
        <div class="footer-section supported-section">
            <h3>Supported by</h3>
            <img src="images/vanlogo.png" alt="Sponsor Logo" class="sponsor-logo">
        </div>
    </footer>

    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true, // Enables infinite scrolling
            pagination: {
                el: '.swiper-pagination',
                clickable: true, // Enables clickable pagination dots
            },
            autoplay: false, // Autoplay off
        });

        // Open Modal Function
        function openModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImage.src = src;
        }

        // Close Modal Function
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
