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
            <img src="images/logo.png" alt="BASF Logo" class="logo">
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
        <h1 class="gallery-title">Our Gallery</h1>
        <div class="gallery-container">
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


    <footer class="footer">
        <div class="footer-section logo-section">
            <img src="images/logo.png" alt="BASF Logo" class="footer-logo">
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

        
    </script>
</body>
</html>
