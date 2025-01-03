<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'information');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event ID from URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch event details
$event_query = "SELECT * FROM upcoming_events WHERE id = ?";
$stmt = $conn->prepare($event_query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event_result = $stmt->get_result();
$event = $event_result->fetch_assoc();

// Fetch event images
$image_query = "SELECT image_path FROM event_images WHERE event_id = ?";
$stmt = $conn->prepare($image_query);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$image_result = $stmt->get_result();
$images = $image_result->fetch_all(MYSQLI_ASSOC);

// Close statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['event_name']); ?> - Event Details</title>
    <link rel="stylesheet" href="Css/eventPages.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
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
        <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
    </div>
</section>

<div class="event-page">
    <!-- Left Section: Poster Carousel -->
    <div class="left-section">
        <div class="poster-carousel">
            <?php foreach ($images as $index => $image): ?>
                <div class="poster-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Event Poster" class="event-poster">
                </div>
            <?php endforeach; ?>
            <button class="prev-btn">Previous</button>
            <button class="next-btn">Next</button>
        </div>
    </div>

    <!-- Right Section: Event Details -->
    <div class="right-section">
        <div class="event-details">
            <h1 class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></h1>
            <p class="event-datetime"><strong>Dates & Times:</strong> <?php echo htmlspecialchars($event['datetime']); ?></p>
            <p class="event-description"><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
            <p class="event-location"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        </div>
        <h2>Partners & Sponsors</h2>
        <div class="sponsors">
            <img src="images/vanlogo.png" alt="Sponsor Logo">
        </div>
    </div>
</div>

<footer class="footer">
    <!-- Footer sections (unchanged) -->
</footer>

<!-- Modal -->
<div id="modal" class="modal">
    <span class="close-btn">&times;</span>
    <img class="modal-content" id="modal-image">
</div>

<script>
// Carousel functionality
const slides = document.querySelectorAll('.poster-slide');
const nextBtn = document.querySelector('.next-btn');
const prevBtn = document.querySelector('.prev-btn');
let currentSlide = 0;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
}

nextBtn.addEventListener('click', () => {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
});

prevBtn.addEventListener('click', () => {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
});

// Modal functionality
const modal = document.getElementById('modal');
const modalImage = document.getElementById('modal-image');

slides.forEach(slide => {
    slide.addEventListener('click', () => {
        modal.style.display = 'block';
        modalImage.src = slide.querySelector('img').src;
    });
});

document.querySelector('.close-btn').addEventListener('click', () => {
    modal.style.display = 'none';
});
</script>
</body>
</html>
