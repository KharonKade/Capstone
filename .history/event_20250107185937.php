<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="Css/event.css">
</head>

<body>
<header>
        <nav class="navbar">
            <img src="images/logo.png" alt="BASF Logo" class="logo">
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="spots.html">Spots</a></li>
                    <li><a href="event.html">Events</a></li>
                    <li><a href="gallery.html">Gallery</a></li>
                    <li><a href="sponsorship.html">Sponsorship</a></li>
                    <li><a href="contactUs.html">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Events</h1>
            <h2>Event Lists</h2>
        </div>
    </section>

    <section class="event-navigation"> 
        <div class="advertisement">
            <a href="https://www.vans.com/en-us/shoes-c00081/old-skool-shoe-pvn000d3hy28" target="_blank">
                <div class="ad-container">
                    <img src="images/vansads.png" alt="Advertisement">
                    <span class="ad-label">Ads</span>
                </div>
            </a>
        </div>
    </section>

    <section class="container event-container" id="upcoming">
        <h2>Events & Activities</h2>
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
                e.id,           -- Add the event ID here
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
                e.status = 'active'   -- Fetch only active events
            GROUP BY 
                e.id
            ORDER BY 
                s.event_date ASC";
        
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    echo '<div class="event-item">
                    <a href="eventPages.html">
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
        <div class="carousel-arrows">
            <button class="arrow" onclick="scrollRight1()">&#8594;</button>
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

    <script src="jsScript/event.js"></script>
</body>
</html>
