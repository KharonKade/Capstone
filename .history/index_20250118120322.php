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
                    <div class="news-item">
                        <img src="images/sumvac.png" alt="SumVac 2024">
                        <div class="news-item-content">
                            <a href="your-link-here.html">
                                <h3>SumVac 2024 "Quench 1"</h3>
                                <p>Competition Event</p>
                                <p>April 15, 2024</p>
                            </a>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="images/adreall.png" alt="Adrenaline Alley">
                        <div class="news-item-content">
                            <a href="your-link-here.html">
                                <h3>August Adrenaline Alley</h3>
                                <p>Competition Event</p>
                                <p>August 23, 2024</p>
                            </a>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="images/adreall.png" alt="Adrenaline Alley">
                        <div class="news-item-content">
                            <a href="your-link-here.html">
                                <h3>August Adrenaline Alley</h3>
                                <p>Competition Event</p>
                                <p>August 23, 2024</p>
                            </a>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="images/adreall.png" alt="Adrenaline Alley">
                        <div class="news-item-content">
                            <a href="your-link-here.html">
                                <h3>August Adrenaline Alley</h3>
                                <p>Competition Event </p>
                                <p>August 23, 2024</p>
                            </a>
                        </div>
                    </div>
                    <div class="news-item">
                        <img src="images/adreall.png" alt="Adrenaline Alley">
                        <div class="news-item-content">
                            <a href="your-link-here.html">
                                <h3>August Adrenaline Alley</h3>
                                <p>Competition Event</p>
                                <p>August 23, 2024</p>
                            </a>
                        </div>
                    </div>
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

    <script src="jsScript/index.js"></script>
</body>

</html>