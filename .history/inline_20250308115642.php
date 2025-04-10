<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch About Us Content
$about_us_query = "SELECT title, content FROM basf_content WHERE section = 'about_us'";
$about_us_result = $conn->query($about_us_query);
$about_us = $about_us_result->fetch_assoc();

// Fetch Top Athletes
$athletes_query = "SELECT name, description, image_path FROM basf_content WHERE section = 'top_athletes'";
$athletes_result = $conn->query($athletes_query);

// Fetch Community Leaders
$leaders_query = "SELECT name, role, image_path FROM basf_content WHERE section = 'community_leaders'";
$leaders_result = $conn->query($leaders_query);

// Fetch Sponsors & Partners
$sponsors_query = "SELECT image_path FROM basf_content WHERE section = 'sponsors'";
$sponsors_result = $conn->query($sponsors_query);

$conn->close();

// Fetch Events (Different Database)
$event_conn = new mysqli($servername, $username, $password, "basf_events");

if ($event_conn->connect_error) {
    die("Connection failed: " . $event_conn->connect_error);
}

$events_query = "
SELECT 
    e.id, e.event_name, e.category, s.event_date, MIN(i.image_path) AS image_path
FROM 
    upcoming_events e
JOIN 
    event_schedules s ON e.id = s.event_id
JOIN 
    event_images i ON e.id = i.event_id
WHERE 
    e.status = 'active'
GROUP BY 
    e.id
ORDER BY 
    s.event_date ASC";

$events_result = $event_conn->query($events_query);
$event_conn->close();
?>





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
            <p>
                We're a crew of individual skaters, united by our passion for Inline Skating and a determination to shape a vibrant community in the stunning city of Baguio. <br>
            </p>

            <p>
                Our mission is simple: to connect, inspire, and motivate one another through our shared passion for skating. Whether you're a seasoned pro or just starting out. <br>
            </p>
            <p>
                Join us for regular skate meetups, where we hit the streets and skate parks & rinks of Baguio to shred together, share tips and tricks, and push each other to new heights. We're firm believers that skating isn't just a sport; it's a lifestyle. Committed to igniting the flame of enthusiasm across our city.
            </p>
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
            <?php
            // Database connection
            $servername = "localhost"; // Your database host
            $username = "root";        // Your database username
            $password = "";            // Your database password
            $dbname = "basf_events";    // Your database name

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "
            SELECT 
                e.id,           
                e.event_name, 
                e.category, 
                s.event_date, 
                MIN(i.image_path) AS image_path
            FROM 
                upcoming_events e
            JOIN 
                event_schedules s ON e.id = s.event_id
            JOIN 
                event_images i ON e.id = i.event_id
            WHERE 
                e.status = 'active'   
                AND (e.category = 'All' OR e.category = 'Inline')  -- Filter category
            GROUP BY 
                e.id
            ORDER BY 
                s.event_date ASC";

        
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo '<div class="event-item">
                     <a href="eventPages.php?id=' . $row['id'] . '">
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <img src="' . $row["image_path"] . '" alt="' . $row["event_name"] . '">
                                </div>
                                <div class="flip-card-back" style="background-image: url(' . "'" . $row["image_path"] . "'" . ');">
                                    <div class="back-content">
                                        <p>' . $row["event_name"] . '</p>
                                        <p>Category: ' . $row["category"] . '</p>
                                        <p>Date: ' . $row["event_date"] . '</p>
                                        <br>
                                        <p>Click for more...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>';

                }
            } else {
                echo "<p>No upcoming events found.</p>";
            }
            $conn->close();
            ?>
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
            <div class="slides" style="--img: url('../images/inline-players/markjulius.png')">
                <div class="content">
                    <h1>Title 1</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum, ipsum. Veritatis ut, consequatur iusto repellat laborum ab totam dolore impedit voluptatibus est odio magni reprehenderit repellendus sequi doloremque sapiente! Quam.</p>
                    <button class="explore-btn">Explore</button>
                </div>
            </div>
            <div class="slides" style="--img: url('../images/inline-players/xad.png')">
                <div class="content">
                    <h1>Title 2</h1>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nostrum, ipsum. Veritatis ut, consequatur iusto repellat laborum ab totam dolore impedit voluptatibus est odio magni reprehenderit repellendus sequi doloremque sapiente! Quam.</p>
                    <button class="explore-btn">Explore</button>
                </div>
            </div>
        </div>
        <div class="buttons">
            <span class="prev"></span>
            <span class="next"></span>
        </div>
    </div>

    <div class="community-leaders-section">
        <h1 class="section-heading">Community Leaders</h1>
        <div class="leaders-container">
          <div class="leader">
            <div class="profile-pic">
              <img src="leader1.jpg" alt="Leader 1" />
            </div>
            <div class="leader-info">
              <h3 class="leader-name">Leader 1</h3>
              <p class="leader-role">Role 1</p>
            </div>
          </div>
          </div>
        </div>
      </div>

      <section class="partnership-section">
        <h2>Partners & Sponsors</h2>
        <div class="partner-logos">
            <img src="images/vanlogo.png" alt="Partner 1" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 2" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 3" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 4" class="partner-logo">
            <!-- Add more partner logos as needed -->
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