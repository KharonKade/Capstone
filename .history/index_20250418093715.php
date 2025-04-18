<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="Css/index.css">
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

    <!-- First Container -->
    <section class="container sports-container">
        <h1>Sports</h1>
        <div class="sports-buttons">
            <button onclick="window.location.href='inline.php'">In-Line</button>
            <button onclick="window.location.href='skateboard.php'">Skateboard</button>
            <button onclick="window.location.href='bmx.php'">BMX</button>
        </div>
    </section>

    <!-- Second Container -->
    <!-- News Section -->
        <section class="news-container">
            <h2>News & Announcements</h2>
            <div class="news-carousel-wrapper">
                <div class="news-carousel">
                <?php
                    // Database connection
                    $conn = new mysqli("localhost", "root", "", "basf_news");

                    // Check if the connection was successful
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch news data from the database
                    $sql = "SELECT * FROM news_announcements WHERE status = 'active' ORDER BY publish_date DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Loop through and display news items
                        while ($row = $result->fetch_assoc()) {
                            // Fetch news data
                            $news_title = $row['news_title'];
                            $news_content = $row['news_content'];
                            $publish_date = $row['publish_date'];
                            $image_path = ''; // Default image path in case there's no image

                            // Fetch image associated with the news if any
                            $news_id = $row['news_id']; // Change to match your actual column name for news ID
                            $image_sql = "SELECT * FROM news_images WHERE news_id = '$news_id' LIMIT 1";
                            $image_result = $conn->query($image_sql);
                            if ($image_result->num_rows > 0) {
                                $image_row = $image_result->fetch_assoc();
                                $image_path = $image_row['image_path'];
                            }

                            echo '
                            <div class="news-item">
                                <img src="' . $image_path . '" alt="' . $news_title . '">
                                <div class="news-item-content">
                                    <!-- Dynamically create link to the specific news page -->
                                    <a href="newsPages.php?id=' . $news_id . '">
                                        <h3>' . $news_title . '</h3>
                                        <p>' . substr($news_content, 0, 100) . '...</p>
                                        <p>' . $publish_date . '</p>
                                    </a>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>No news available at the moment.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </section>


        <!-- Advertisement Section -->
        <div class="advertisement">
            <a href="https://www.vans.com/en-us/shoes-c00081/old-skool-shoe-pvn000d3hy28" target="_blank">
                <div class="ad-container">
                    <img src="images/vansads.png" alt="Advertisement">
                    <span class="ad-label">Ads</span>
                </div>
            </a>
        </div>
    </section>

    <section class="partnership-section">
        <h2>In Partnership With</h2>
        <div class="partner-logos">
            <img src="images/vanlogo.png" alt="Partner 1" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 2" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 3" class="partner-logo">
            <img src="images/vanlogo.png" alt="Partner 4" class="partner-logo">
        </div>
    </section>

    <footer class="footer">
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

    <script src="jsScript/index.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector(".news-carousel");

        if (carousel) {
            carousel.addEventListener("wheel", function (event) {
                event.preventDefault(); // Prevent vertical scrolling
                carousel.scrollLeft += event.deltaY * 2; // Adjust multiplier for speed
            });
        }
    });
    </script>
</body>
</html>
