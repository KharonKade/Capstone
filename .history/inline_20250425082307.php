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

    <section class="hero">
        <div class="hero-content">
            <h1>Rollerblades</h1>
        </div>
    </section>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname_content = "basf_content";
    $dbname_events = "basf_events";

    $conn_content = new mysqli($servername, $username, $password, $dbname_content);
    $conn_events = new mysqli($servername, $username, $password, $dbname_events);

    if ($conn_content->connect_error || $conn_events->connect_error) {
        die("Connection failed: " . ($conn_content->connect_error ?: $conn_events->connect_error));
    }
    ?>

    <section class="inline-content animate-on-scroll">
        <div class="middle-content">
            <h2 id="about-us">About Us</h2>
            <?php
            $result = $conn_content->query("SELECT content FROM content WHERE section='about_us'");
            if ($row = $result->fetch_assoc()) {
                echo $row['content'];
            } else {
                echo "<p>About Us content not found.</p>";
            }            
            ?>
        </div>
        <div class="advertisement animate-on-scroll">
            <a href="https://www.vans.com/en-us/shoes-c00081/old-skool-shoe-pvn000d3hy28" target="_blank">
                <div class="ad-container">
                    <img src="images/vansads.png" alt="Advertisement">
                    <span class="ad-label">Ads</span>
                </div>
            </a>
        </div>
    </section>

    <section class="container event-container animate-on-scroll" id="events">
        <h2>Events & Activities</h2>
        <div class="event-carousel" id="eventCarousel">
            <?php
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
            
            $result = $conn_events->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="event-item">
                            <a href="eventPages.php?id=' . $row['id'] . '">
                                <div class="flip-card">
                                    <div class="flip-card-inner">
                                        <div class="flip-card-front">
                                            <img src="' . $row["image_path"] . '" alt="' . $row["event_name"] . '">
                                        </div>
                                        <div class="flip-card-back" style="background-image: url(' . "'" . $row["image_path"] . "'" . ')">
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
            ?>
        </div>
    </section>

    <div class="highlight-carousel-section animate-on-scroll">
        <h1 class="carousel-heading">Highlight</h1>
        <div class="carousel-container">
            <div class="carousel">
                <?php
                $result = $conn_content->query("SELECT id, video, title, description FROM highlight_carousel");

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $video = htmlspecialchars($row["video"], ENT_QUOTES);
                            $title = htmlspecialchars($row["title"], ENT_QUOTES);
                            $description = htmlspecialchars($row["description"], ENT_QUOTES);

                            echo '<div class="carousel-item">
                                <video src="' . htmlspecialchars($row["video"], ENT_QUOTES) . '" autoplay muted loop
                                    onclick="openModal(this, \'' . addslashes(htmlspecialchars($row["title"], ENT_QUOTES)) . '\', \'' . addslashes(htmlspecialchars($row["description"], ENT_QUOTES)) . '\')">
                                </video>
                            </div>';
                            
                            // Debugging Output
                            echo "<!-- DEBUG: ID=" . $row["id"] . ", Video=" . $video . ", Title=" . $title . ", Desc=" . $description . " -->";
                        }
                    } else {
                        echo '<p class="no-videos">No highlight videos available at the moment.</p>';
                    }

                ?>
            </div>
        </div>
    </div>

    <!-- Video Popup Modal -->
    <div class="video-modal animate-on-scroll" id="videoModal">
        <div class="video-modal-content">
            <button class="close-btn" id="closeModalBtn">&times;</button>
            <video id="modalVideo" controls autoplay></video>
            <div class="video-details">
                <h3 id="videoTitle"></h3>
                <p id="videoDescription"></p>
            </div>
        </div>
    </div>



    <div class="players animate-on-scroll" id="top-athletes">
        <h2>Top Athletes</h2>
        <div class="slider">
            <?php
            $result = $conn_content->query("SELECT id, name, description, image FROM top_athletes");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="slides" style="background-image: url(\'' . $row["image"] . '\');">
                    <div class="content">
                        <h1>' . $row["name"] . '</h1>
                        <p>' . $row["description"] . '</p>
                        <button class="explore-btn">
                            <a href="playerPage.php?id=' . $row['id'] . '">Explore</a>
                        </button>
                    </div>
                  </div>';            
                }
            } else {
                echo '<p class="no-data">No athletes have been added yet.</p>';
            }
            ?>
        </div>
        <div class="buttons">
            <span class="prev"></span>
            <span class="next"></span>
        </div>
    </div>


    <div class="community-leaders-section animate-on-scroll">
        <h1 class="section-heading">Community Leaders</h1>
        <div class="leaders-container">
            <?php
            // Fetch community leaders from the database
            $result = $conn_content->query("SELECT name, role, image FROM community_leaders");

            // Check if there are community leaders
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="leader">
                            <div class="profile-pic">
                                <img src="' . $row["image"] . '" alt="' . htmlspecialchars($row["name"]) . '" />
                            </div>
                            <div class="leader-info">
                                <h3 class="leader-name">' . htmlspecialchars($row["name"]) . '</h3>
                                <p class="leader-role">' . htmlspecialchars($row["role"]) . '</p>
                            </div>
                        </div>';
                }
            } else {
                // Message when there are no leaders
                echo '<p class="no-data">No community leaders available.</p>';
            }
            ?>
        </div>
    </div>


    <section class="partnership-section animate-on-scroll">
        <h2>Partners & Sponsors</h2>
        <div class="partner-logos">
            <?php
            // Fetch partner logos from the 'partnerships' table
            $result = $conn_content->query("SELECT logo FROM partnerships");

            // Check if there are any partners
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<img src="' . htmlspecialchars($row["logo"]) . '" alt="Partner Logo" class="partner-logo">';
                }
            } else {
                // Display message when there are no partners
                echo '<p class="no-data">No partners or sponsors available at the moment.</p>';
            }
            ?>
        </div>
    </section>


    <?php
    $conn_content->close();
    $conn_events->close();
    ?>

    <div class="footer-ramp-icons animate-on-scroll">
        <img src="images/ramp.png" alt="Left Ramp" class="ramp-icon left">
        <img src="images/pyramid.png" alt="Center Pyramid Ramp" class="ramp-icon center">
        <img src="images/rampright.png" alt="Right Ramp" class="ramp-icon right">
    </div>

    <footer class="footer animate-on-scroll">
        <!-- BASF Logo Section -->
        <div class="footer-section logo-section">
            <img src="images/whitelogo.png" alt="BASF Logo" class="footer-logo">
        </div>
    
        <!-- Explore Us Section -->
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
                <a href="https://facebook.com"><img src="images/fbwhite.png" alt="Facebook"></a>
                <a href="https://instagram.com"><img src="images/igwhite.png" alt="Instagram"></a>
            </div>
        </div>
    
        <!-- Supported by Section -->
        <div class="footer-section supported-section">
            <h3>Supported by</h3>
            <img src="images/vanswhite.png" alt="Sponsor Logo" class="sponsor-logo">
        </div>
    </footer>


    <script src="jsScript/players.js"></script>
    <script src="jsScript/event.js"></script>
    <script src="jsScript/videoplay.js"></script>
    <script>
    // Open video in modal
    function openModal(video, title, description) {
        const modal = document.getElementById("videoModal");
        const modalVideo = document.getElementById("modalVideo");
        const modalTitle = document.getElementById("videoTitle");
        const modalDescription = document.getElementById("videoDescription");

        // Show the modal
        modal.style.opacity = "1";
        modal.style.visibility = "visible";
        
        // Set the modal video source
        modalVideo.src = video.src;

        // Set the title and description in the modal
        modalTitle.innerText = title;
        modalDescription.innerText = description;
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

        slides.forEach((slide, index) => {
            slide.addEventListener('click', () => {
                if (index !== currentIndex) {
                    currentIndex = index;
                    updateSlides();
                }
            });
        });

        updateSlides();

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