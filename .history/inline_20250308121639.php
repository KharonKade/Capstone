<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inline Page</title>
    <link rel="stylesheet" href="Css/inline.css">
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
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="sponsorship.html">Sponsorship</a></li>
                    <li><a href="contactUs.html">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>In-Line / <br> Aggressive In-Line</h1>
        </div>
    </section>

    <section class="inline-content">
        <div class="middle-content">
            <h2 id="about-us">About Us</h2>
            <p><?= nl2br($about_us['content']) ?></p>
        </div>

        <div class="advertisement">
            <a href="https://www.vans.com/en-us/shoes-c00081/old-skool-shoe-pvn000d3hy28" target="_blank">
                <div class="ad-container">
                    <img src="images/vansads.png" alt="Advertisement">
                    <span class="ad-label">Ads</span>
                </div>
            </a>
        </div>
    </section>

    <section class="container event-container" id="events">
        <h2>Events & Activities</h2>
        <!-- News Carousel -->
        <div class="event-carousel" id="eventCarousel">
            <?php if ($events_result->num_rows > 0): ?>
                <?php while ($row = $events_result->fetch_assoc()): ?>
                    <div class="event-item">
                        <a href="eventPages.php?id=<?= $row['id'] ?>">
                            <div class="flip-card">
                                <div class="flip-card-inner">
                                    <div class="flip-card-front">
                                        <img src="<?= $row['image_path'] ?>" alt="<?= $row['event_name'] ?>">
                                    </div>
                                    <div class="flip-card-back" style="background-image: url('<?= $row['image_path'] ?>');">
                                        <div class="back-content">
                                            <p><?= $row['event_name'] ?></p>
                                            <p>Category: <?= $row['category'] ?></p>
                                            <p>Date: <?= $row['event_date'] ?></p>
                                            <br>
                                            <p>Click for more...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming events found.</p>
            <?php endif; ?>
        </div>
    </section>

    <div class="highlight-carousel-section">
        <h1 class="carousel-heading">Highlight</h1>
        <div class="carousel-container">
            <div class="carousel">
                <div class="carousel-item">
                    <video src="images/video ads.mp4" autoplay muted loop onclick="openModal(this)"></video>
                </div>
                <div class="carousel-item">
                    <video src="images/video ads.mp4" autoplay muted loop onclick="openModal(this)"></video>
                </div>
                <div class="carousel-item">
                    <video src="images/video ads.mp4" autoplay muted loop onclick="openModal(this)"></video>
            </div>
        </div>
    </div>

    <!-- Video Popup Modal -->
    <div class="video-modal" id="videoModal">
        <div class="video-modal-content">
            <button class="close-btn" id="closeModalBtn">&times;</button>
            <video id="modalVideo" controls autoplay></video>
            <div class="video-details">
                <h3 id="videoTitle">Video Title</h3>
                <p id="videoDescription">Description of the video goes here.</p>
            </div>
        </div>
    </div>

    <div class="players" id="top-athletes">
        <h2>Top Athletes</h2>
        <div class="slider">
            <?php
            $result = $conn_content->query("SELECT name, description, image_path FROM basf_content WHERE section='top_athletes'");
            while ($row = $result->fetch_assoc()) {
                echo '<div class="slides" style="--img: url(' . $row["image_path"] . ')">
                        <div class="content">
                            <h1>' . $row["name"] . '</h1>
                            <p>' . $row["description"] . '</p>
                            <button class="explore-btn">Explore</button>
                        </div>
                    </div>';
            }
            ?>
        </div>
        <div class="buttons">
            <span class="prev"></span>
            <span class="next"></span>
        </div>
    </div>


    <div class="community-leaders-section">
        <h1 class="section-heading">Community Leaders</h1>
        <div class="leaders-container">
            <?php while ($leader = $leaders_result->fetch_assoc()): ?>
                <div class="leader">
                    <div class="profile-pic">
                        <img src="<?= $leader['image_path'] ?>" alt="<?= $leader['name'] ?>" />
                    </div>
                    <div class="leader-info">
                        <h3 class="leader-name"><?= $leader['name'] ?></h3>
                        <p class="leader-role"><?= $leader['role'] ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>


    <section class="partnership-section">
        <h2>Partners & Sponsors</h2>
        <div class="partner-logos">
            <?php while ($sponsor = $sponsors_result->fetch_assoc()): ?>
                <img src="<?= $sponsor['image_path'] ?>" alt="Partner" class="partner-logo">
            <?php endwhile; ?>
        </div>
    </section>

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


    <script src="jsScript/players.js"></script>
    <script src="jsScript/event.js"></script>
    <script src="jsScript/videoplay.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector(".event-carousel");

        carousel.addEventListener("wheel", (e) => {
            e.preventDefault(); // Prevent vertical scrolling

            let scrollAmount = e.deltaY * 1.5; // Adjust sensitivity
            scrollAmount = Math.sign(scrollAmount) * Math.max(10, Math.abs(scrollAmount)); // Ensure a minimum scroll step

            carousel.scrollLeft += scrollAmount;
        });
    });

        // Open video in modal
    function openModal(video) {
        const modal = document.getElementById("videoModal");
        const modalVideo = document.getElementById("modalVideo");

        modal.style.opacity = "1";
        modal.style.visibility = "visible";
        modalVideo.src = video.src; // Set the modal video source
    }

    // Close modal when clicking the close button
    document.getElementById("closeModalBtn").addEventListener("click", function () {
        const modal = document.getElementById("videoModal");
        const modalVideo = document.getElementById("modalVideo");

        modal.style.opacity = "0";
        modal.style.visibility = "hidden";
        modalVideo.pause(); // Pause video when closing modal
        modalVideo.src = ""; // Reset video source
    });

    </script>

    <script>
        const slides = document.querySelectorAll('.slides');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        let currentIndex = 0;

        function updateSlides() {
            slides.forEach((slide, index) => {
                if (index === currentIndex) {
                    slide.style.transform = 'translateX(0)';
                    slide.style.opacity = '1';
                } else if (index < currentIndex) {
                    slide.style.transform = 'translateX(-100%)';
                    slide.style.opacity = '0';
                } else {
                    slide.style.transform = `translateX(${(index - currentIndex) * 240}px)`;
                    slide.style.opacity = index - currentIndex > 3 ? '0' : '1';
                }
            });
        }

        nextBtn.addEventListener('click', () => {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
                updateSlides();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlides();
            }
        });

        slides.forEach((slide, index) => {
            slide.addEventListener('click', () => {
                if (index !== currentIndex) {
                    currentIndex = index;
                    updateSlides();
                }
            });
        });

        updateSlides();

    </script>
</body>
</html>