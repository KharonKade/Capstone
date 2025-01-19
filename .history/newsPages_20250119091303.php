<?php
// Establish a connection to the database
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "basf_news";     // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the news ID from the URL
$news_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($news_id > 0) {
    // Fetch news details based on the news ID
    $news_sql = "
        SELECT n.news_title, n.news_content, n.category, n.publish_date
        FROM news_announcements n
        WHERE n.news_id = $news_id";

    $news_result = $conn->query($news_sql);

    // Check if the news result is valid and contains data
    if ($news_result && $news_result->num_rows > 0) {
        $news = $news_result->fetch_assoc();
    } else {
        echo "News not found.";
        exit;
    }

    // Fetch news images based on the news ID
    $image_sql = "SELECT image_path FROM news_images WHERE news_id = $news_id";
    $image_result = $conn->query($image_sql);

    // Check if the image result is valid and contains data
    if ($image_result && $image_result->num_rows > 0) {
        $images = $image_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $images = [];
    }
} else {
    echo "Invalid news ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Page</title>
    <link rel="stylesheet" href="Css/newsPages.css">
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

    <section class="news-hero">
        <div class="news-hero-content">
            <h1><?php echo isset($news['news_title']) ? $news['news_title'] : 'News not found'; ?></h1>
        </div>
    </section>

    <div class="news-page">
        <!-- Left Section (Image Carousel) -->
        <div class="left-section">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Display news images
                    if (!empty($images)) {
                        foreach ($images as $image) {
                            echo '<div class="swiper-slide">
                                    <img src="' . $image['image_path'] . '" alt="News Image" class="news-image" onclick="openModal(\'' . $image['image_path'] . '\')">
                                  </div>';
                        }
                    } else {
                        echo "<p>No images available for this news.</p>";
                    }
                    ?>
                </div>
                <!-- Pagination dots (optional) -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Right Section (News Details) -->
        <div class="right-section">
            <div class="news-details">
                <p><strong>Category:</strong> <?php echo isset($news['category']) ? $news['category'] : 'General'; ?></p>
                <p><strong>Published on:</strong> <?php echo isset($news['publish_date']) ? $news['publish_date'] : 'Not available'; ?></p>
                <p><strong>Content:</strong> <?php echo isset($news['news_content']) ? $news['news_content'] : 'Not available'; ?></p>
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
