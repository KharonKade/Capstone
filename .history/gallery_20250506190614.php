<?php
// Database Connection
$host = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "basf_gallery"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Gallery Items
$galleryItems = [];
$sql = "SELECT * FROM gallery";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Fetch additional images for each gallery item
    $gallery_id = $row['id'];
    $images = [];
    $imgQuery = "SELECT image_path FROM gallery_images WHERE gallery_id = $gallery_id";
    $imgResult = $conn->query($imgQuery);
    
    while ($imgRow = $imgResult->fetch_assoc()) {
        $images[] = $imgRow['image_path'];
    }

    $galleryItems[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "description" => $row['description'],
        "thumbnail" => $row['thumbnail'],
        "images" => $images
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Page</title>
    <link rel="stylesheet" href="Css/gallery.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <img src="images/basflogo.png" alt="BASF Logo" class="logo">
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="spots.html">Spots</a></li>
                    <li><a href="event.php">Events</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="sponsorship.html">Sponsorship</a></li>
                    <li><a href="contactUs.html">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="event-hero">
        <div class="event-hero-content">
            <h1>Gallery</h1>
            <h2>Projects & Programs</h2>
        </div>
    </section>

    <div class="gallery-section">
        <h1 class="gallery-title animate-on-scroll">Our Gallery</h1>
        <div class="gallery-container animate-on-scroll">
            <?php foreach ($galleryItems as $item): ?>
                <div class="gallery-item" onclick="showDetails(<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)">
                    <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['title']; ?>">
                    <div class="gallery-overlay">
                        <p><?php echo $item['title']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Details Section -->
    <div id="galleryDetails" class="gallery-details" style="display: none;">
    <button class="close-gallery-details" onclick="closeGalleryDetails()">X</button>
        <div class="details-text">
            <h2 id="details-title"></h2>
            <p id="details-description"></p>
        </div>
        <div id="details-images" class="details-images">
            <img id="details-thumbnail" class="details-thumbnail" alt="Thumbnail">
        </div>
    </div>
    
    <div class="footer-ramp-icons animate-on-scroll">
        <img src="images/ramp.png" alt="Left Ramp" class="ramp-icon left">
        <img src="images/pyramid.png" alt="Center Pyramid Ramp" class="ramp-icon center">
        <img src="images/rampright.png" alt="Right Ramp" class="ramp-icon right">
    </div>

    <footer class="footer animate-on-scroll">
        <div class="footer-section logo-section">
            <img src="images/whitelogo.png" alt="BASF Logo" class="footer-logo">
        </div>
        <div class="footer-section explore-section">
            <h3>Explore Us</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skateboard.php">Skateboarding</a></li>
                <li><a href="inline.php">In-Line</a></li>
                <li><a href="bmx.php">BMX</a></li>
                <li><a href="spots.html">Spots</a></li>
                <li><a href="event.php">Events</a></li>
                <li><a href="gallery.php">Gallery</a></li>
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
                <a href="https://facebook.com"><img src="images/fbwhite.png" alt="Facebook"></a>
                <a href="https://instagram.com"><img src="images/igwhite.png" alt="Instagram"></a>
            </div>
        </div>
        <div class="footer-section supported-section">
            <h3>Supported by</h3>
            <img src="images/vanswhite.png" alt="Sponsor Logo" class="sponsor-logo">
        </div>
    </footer>

    <script>
        function showDetails(item) {
            document.getElementById("details-title").innerText = item.title;
            document.getElementById("details-description").innerHTML = item.description;

            let imageContainer = document.getElementById("details-images");
            imageContainer.innerHTML = ""; // Clear previous images

            // Add Thumbnail as the First Image
            let thumbnailImg = document.createElement("img");
            thumbnailImg.src = item.thumbnail;
            thumbnailImg.className = "details-img"; // Ensure it follows the same class as other images
            imageContainer.appendChild(thumbnailImg);

            // Add Remaining Images
            item.images.forEach(img => {
                let imgTag = document.createElement("img");
                imgTag.src = img;
                imgTag.className = "details-img";
                imageContainer.appendChild(imgTag);
            });

            document.getElementById("galleryDetails").style.display = "block";
        }
        function closeGalleryDetails() {
            document.getElementById('galleryDetails').style.display = 'none';
        }

        document.addEventListener("DOMContentLoaded", function () {
            const elements = document.querySelectorAll('.animate-on-scroll');

            elements.forEach(el => {
                el._fadeTimeout = null; // custom property for tracking timeout
            });

            function toggleVisibility() {
                elements.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    const inView = rect.top <= window.innerHeight * 0.85 && rect.bottom >= 0;

                    if (inView) {
                        clearTimeout(el._fadeTimeout); // cancel any pending hide
                        el.classList.add('visible');
                    } else {
                        // fade out first, then hide after transition
                        el.classList.remove('visible');
                        clearTimeout(el._fadeTimeout);
                        el._fadeTimeout = setTimeout(() => {
                            el.style.visibility = 'hidden';
                        }, 600); // must match transition duration
                    }

                    // Always reset visibility to visible if showing
                    if (inView) {
                        el.style.visibility = 'visible';
                    }
                });
            }

            window.addEventListener('scroll', toggleVisibility);
            window.addEventListener('resize', toggleVisibility);
            toggleVisibility(); // Run on load
        });
        
    </script>
</body>
</html>
