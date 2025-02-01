<?php
// Establish a connection to the database
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "basf_events";   // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);
  
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the URL
$event_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id > 0) {
    // Fetch event details based on the event ID
    $event_sql = "
        SELECT e.event_name, e.description, e.location, e.registration
        FROM upcoming_events e
        WHERE e.id = $event_id AND e.status = 'active'";

    $event_result = $conn->query($event_sql);

    // Check if the event result is valid and contains data
    if ($event_result && $event_result->num_rows > 0) {
        $event = $event_result->fetch_assoc();
    } else {
        echo "Event not found or no active event available.";
        exit;
    }

    // Fetch the registration count for the event
    $registration_count_sql = "SELECT COUNT(*) AS total FROM event_registrations WHERE event_id = $event_id";
    $registration_count_result = $conn->query($registration_count_sql);

    if ($registration_count_result && $registration_count_result->num_rows > 0) {
        $registration_count_data = $registration_count_result->fetch_assoc();
        $registration_count = $registration_count_data['total'];
    } else {
        $registration_count = 0;
    }

    // Determine the event's popularity
    $max_capacity = 100; // Define a max capacity for the event (you can adjust this number)
    $popularity_status = 'Available';
    $popularity_color = 'green';

    if ($registration_count >= 0.75 * $max_capacity) {
        $popularity_status = 'Filling Fast';
        $popularity_color = 'yellow';
    } 
    if ($registration_count >= $max_capacity) {
        $popularity_status = 'Almost Full';
        $popularity_color = 'red';
    }


    // Fetch event schedules based on the event ID
    $schedule_sql = "SELECT event_date, start_time, end_time FROM event_schedules WHERE event_id = $event_id";
    $schedule_result = $conn->query($schedule_sql);

    // Check if the schedule result is valid and contains data
    if ($schedule_result && $schedule_result->num_rows > 0) {
        $schedules = $schedule_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $schedules = [];
    }

    // Fetch sponsor logos based on the event ID
    $sponsor_sql = "SELECT logo_path FROM sponsor_logos WHERE event_id = $event_id";
    $sponsor_result = $conn->query($sponsor_sql);

    // Check if the sponsor result is valid and contains data
    if ($sponsor_result && $sponsor_result->num_rows > 0) {
        $sponsors = $sponsor_result->fetch_all(MYSQLI_ASSOC);
    } else {
        $sponsors = [];
    }

    // Fetch event images based on the event ID
    $image_sql = "SELECT image_path FROM event_images WHERE event_id = $event_id";
    $image_result = $conn->query($image_sql);

    // Check if the image result is valid and contains data
    if ($image_result && $image_result->num_rows > 0) {
        $images = $image_result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "No images available for this event.";
        $images = [];
    }
} else {
    echo "Invalid event ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="Css/eventPages.css">
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

    <section class="event-hero">
        <div class="event-hero-content">
            <h1><?php echo isset($event['event_name']) ? $event['event_name'] : 'Event not found'; ?></h1>
        </div>
    </section>

    <div class="event-page">
        <!-- Left Section (Image Carousel) -->
        <div class="left-section">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Display event images
                    if (!empty($images)) {
                        foreach ($images as $image) {
                            echo '<div class="swiper-slide">
                                    <img src="' . $image['image_path'] . '" alt="Event Poster" class="event-poster" onclick="openModal(\'' . $image['image_path'] . '\')">
                                  </div>';
                        }
                    } else {
                        echo "<p>No images available for this event.</p>";
                    }
                    ?>
                </div>
                <!-- Pagination dots (optional) -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <!-- Right Section (Event Details) -->
        <div class="right-section">
            <div class="event-details">
            <strong>Dates & Times:</strong>
                <ul>
                    <?php
                    // Display event schedules
                    if (!empty($schedules)) {
                        foreach ($schedules as $schedule) {
                            echo '<li>' . $schedule['event_date'] . ' - ' . $schedule['start_time'] . ' to ' . $schedule['end_time'] . '</li>';
                        }
                    } else {
                        echo "<li>No schedule available for this event.</li>";
                    }
                    ?>
                </ul>
                <p><strong>Description:</strong> <?php echo isset($event['description']) ? $event['description'] : 'Not available'; ?></p>
                <p><strong>Location:</strong> <?php echo isset($event['location']) ? $event['location'] : 'Not available'; ?></p>

                <?php if ($event['registration'] == 1): ?>
                    <button id="registerBtn" class="register-btn">Register</button>
                <?php endif; ?>
                <div class="event-popularity">
                    <span class="popularity-badge" style="background-color: #4CAF50;"><strong><?php echo $popularity_status; ?> - <?php echo $registration_count; ?> Participants Registered</strong></p></span>
                </div>
            </div>

            <h2>Partners & Sponsors</h2>
            <div class="sponsors">
                <?php
                if (!empty($sponsors)) {
                    foreach ($sponsors as $sponsor) {
                        echo '<img src="' . $sponsor['logo_path'] . '" alt="Sponsor Logo">';
                    }
                } else {
                    echo "<p>No sponsor logos available for this event.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    

    <!-- Registration Form Modal -->
    <div id="registrationModal" class="registration-modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeRegistrationModal()">&times;</span>
            <h2>Register for the Event</h2>
            <form id="registrationForm" action="submit_registration.php" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="image-modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img class="modal-content-img" id="modalImage" />
        </div>
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

            // Store the image array and current index in global variables
            window.currentImages = images;
            window.currentIndex = images.indexOf(src);
        }

        // Close Modal Function
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    const registerBtn = document.getElementById('registerBtn');
    const registrationModal = document.getElementById('registrationModal');
    const closeModalBtn = document.querySelector('.close');

    if (registerBtn) {
        registerBtn.onclick = function() {
            registrationModal.style.display = 'block';
        };
    }

    if (closeModalBtn) {
        closeModalBtn.onclick = function() {
            registrationModal.style.display = 'none';
        };
    }

    // If the user clicks anywhere outside the modal, close it
    window.onclick = function(event) {
        if (event.target === registrationModal) {
            registrationModal.style.display = 'none';
        }
    };
});
</script>
</body>
</html>

<?php
$conn->close();
?>
