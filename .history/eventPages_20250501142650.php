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

    <section class="event-hero">
        <div class="event-hero-content">
            <h1><?php echo isset($event['event_name']) ? $event['event_name'] : 'Event not found'; ?></h1>
        </div>
    </section>

    <div class="event-page animate-on-scroll">
        <!-- Left Section (Image Carousel) -->
        <div class="left-section animate-on-scroll">
            <!-- Wrapper around swiper-container -->
            <div class="swiper-wrapper-container">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
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
                </div>
            </div>
            <!-- Pagination moved outside swiper-container -->
            <div class="swiper-pagination"></div>
        </div>

        <!-- Right Section (Event Details) -->
        <div class="right-section animate-on-scroll">
            <div class="event-details">
                <strong>Dates & Times:</strong>
                    <?php
                    // Display event schedules
                    if (!empty($schedules)) {
                        foreach ($schedules as $schedule) {
                            // Convert event_date to a readable format
                            $event_date = new DateTime($schedule['event_date']);
                            $formatted_date = $event_date->format('l, F j, Y'); // E.g., "Monday, May 1, 2025"

                            // Convert start_time and end_time to a readable format
                            $start_time = new DateTime($schedule['start_time']);
                            $formatted_start_time = $start_time->format('g:i A'); // E.g., "3:00 PM"

                            $end_time = new DateTime($schedule['end_time']);
                            $formatted_end_time = $end_time->format('g:i A'); // E.g., "5:00 PM"

                            // Output the formatted schedule
                            echo '<li>' . $formatted_date . ' at ' . $formatted_start_time . ' to ' . $formatted_end_time . '</li>';
                        }
                    } else {
                        echo "<li>No schedule available for this event.</li>";
                    }
                    ?>
                <p><strong>Description:</strong> <?php echo isset($event['description']) ? $event['description'] : 'Not available'; ?></p>
                <p><strong>Location:</strong> <?php echo isset($event['location']) ? $event['location'] : 'Not available'; ?></p>

                <?php if ($event['registration'] == 1): ?>
                    <button id="registerBtn" class="register-btn">Register</button>
                    <br></br>
                    <a href="#" onclick="showTokenModal()">Already Registered? Click Here!</a>
                    <br></br>
                    <div class="event-popularity">
                        <span class="popularity-badge" style="background-color: #4CAF50;"><strong><?php echo $popularity_status; ?> - <?php echo $registration_count; ?> Participants Registered</strong></span>
                    </div>
                <?php endif; ?>
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

    <button onclick="window.location.href='event.php';" class="return-btn animate-on-scroll">Return</button>


   <!-- Registration Form Modal -->
    <div id="registrationModal" class="registration-modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeRegistrationModal()">&times;</span>
            <h2>Register for the Event</h2>
            <form id="registrationForm" action="submit_registration.php" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="age" required>

                <label for="gender">Gender:</label>
                <select name="gender" id="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="Skateboard">Skateboard</option>
                    <option value="Inline">Inline</option>
                    <option value="BMX">BMX</option>
                </select>

                <!-- reCAPTCHA widget -->
                <div class="g-recaptcha" data-sitekey="6LezuAorAAAAAN_jcei_sHBW0gNq_im-TA4oZ8wI"></div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- Token Display Modal -->
    <div id="tokenSuccessModal" class="registration-modal" style="display:none;" onclick="closeTokenSuccessModal(event)">
        <div class="modal-content" onclick="event.stopPropagation();">
            <span class="close" onclick="closeTokenSuccessModal()">&times;</span>
            <h2>Registration Successful!</h2>
            <p>Your token is:</p>
            <div class="token" id="generatedTokenText" style="font-weight: bold; font-size: 1.2rem; margin: 10px 0;"></div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <button onclick="copyGeneratedToken()" style="background: #3498db; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Copy</button>
                <div id="flashMessage" style="display: none; color: green; font-weight: bold;"></div>
                <button onclick="closeTokenSuccessModal()" style="background: #2ecc71; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Okay</button>
            </div>
        </div>
    </div>
    
    <div id="tokenModal" class="registration-modal" style="display:none;" onclick="closeTokenModal(event)">
        <div class="modal-content" onclick="event.stopPropagation();">
            <span class="close" onclick="closeTokenModal()">&times;</span>
            <h2>Enter Your Token</h2>

            <!-- Main Registration Form -->
            <form id="tokenForm" action="manage_registration.php" method="POST">
                <input type="text" id="token" name="token" required placeholder="Enter your token here">
                <button type="submit">Submit</button>
                <a href="javascript:void(0);" id="forgotTokenLink" onclick="showForgotTokenForm()">Forgot your token?</a>
            </form>

            <!-- Forgot Token Form (Initially Hidden) -->
            <div id="forgotTokenForm" style="display:none;">
                <h3>Retrieve Your Token</h3>
                <form id="retrieveTokenForm">
                    <label for="email">Enter your email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                    <button type="submit">Retrieve Token</button>
                </form>
                <p id="retrieveTokenMessage" style="color:red; display:none;"></p>
            </div>
        </div>
    </div>


    


    <!-- Add this script before closing body -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>




    <!-- Modal for Image Preview -->
    <div id="imageModal" class="image-modal" onclick="closeModal()">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img class="modal-content-img" id="modalImage" />
        </div>
    </div>
    
    <div class="footer-ramp-icons animate-on-scroll">
        <img src="images/ramp.png" alt="Left Ramp" class="ramp-icon left">
        <img src="images/pyramid.png" alt="Center Pyramid Ramp" class="ramp-icon center">
        <img src="images/rampright.png" alt="Right Ramp" class="ramp-icon right">
    </div>

    <footer class="footer animate-on-scroll">
        <div class="footer-section logo-section">
            <img src="images/whitelogo.png" alt="BASF Logo" class="footer-logo">
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
                <a href="https://facebook.com"><img src="images/fbwhite.png" alt="Facebook"></a>
                <a href="https://instagram.com"><img src="images/igwhite.png" alt="Instagram"></a>
            </div>
        </div>
        <div class="footer-section supported-section">
            <h3>Supported by</h3>
            <img src="images/vanswhite.png" alt="Sponsor Logo" class="sponsor-logo">
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

function showTokenModal() {
    document.getElementById('tokenModal').style.display = 'block';
}

function closeTokenModal(event) {
        // Check if the click was outside the modal content (on the overlay)
        if (event.target === document.getElementById('tokenModal')) {
            document.getElementById('tokenModal').style.display = 'none';
        }
    }

</script>
<script>
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
<script>
    document.getElementById("registrationForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent normal form submission

        const form = event.target;
        const formData = new FormData(form);

        fetch("submit_registration.php", {
            method: "POST",
            body: formData
        })
        .then(async response => {
            const text = await response.text(); // Get raw response text
            console.log("Raw response:", text); // Log raw response

            try {
                const data = JSON.parse(text); // Try to parse the response
                console.log("Parsed response:", data); // Log parsed response

                if (data.success) {
                    showTokenSuccessModal(data.token);  // Show the token modal
                    closeRegistrationModal();  // Close registration modal
                } else {
                    showTokenFailureModal(data.message || "Registration failed.");
                }
            } catch (e) {
                console.error("JSON parse error:", e, "Original response:", text);
                showTokenFailureModal("Something went wrong. Please try again.");
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            showTokenFailureModal("Something went wrong. Please try again.");
        });
    });


    // Show the token success modal and set the token
    // Show the token success modal and set the token
    function showTokenSuccessModal(token) {
        // Set the token in the modal
        document.getElementById('generatedTokenText').textContent = token;

        // Show the success modal
        document.getElementById('tokenSuccessModal').style.display = 'block';

        // Close the registration modal
        document.getElementById('registrationModal').style.display = 'none';

        // Reset the registration form
        const form = document.getElementById('registrationForm');
        if (form) {
            form.reset();
            form.querySelector('[name="event_id"]').value = ''; // Optional
        }

        // Reset reCAPTCHA if available
        if (typeof grecaptcha !== "undefined") {
            grecaptcha.reset();
        }

        // Clear flash message if any
        const flash = document.getElementById('flashMessage');
        if (flash) flash.style.display = 'none';
    }

    // Close the token success modal and ensure everything resets
    function closeTokenSuccessModal() {
        // Hide the success modal
        document.getElementById('tokenSuccessModal').style.display = 'none';

        // Also make sure the registration modal is hidden
        document.getElementById('registrationModal').style.display = 'none';

        // Reset both forms if needed
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.reset();
            registrationForm.querySelector('[name="event_id"]').value = ''; // Optional
        }

        const tokenForm = document.getElementById('tokenForm');
        if (tokenForm) {
            tokenForm.reset();
        }

        const forgotForm = document.getElementById('retrieveTokenForm');
        if (forgotForm) {
            forgotForm.reset();
        }

        // Reset reCAPTCHA if available
        if (typeof grecaptcha !== "undefined") {
            grecaptcha.reset();
        }

        // Hide flash messages if present
        const flash = document.getElementById('flashMessage');
        if (flash) flash.style.display = 'none';
    }



    // Copy the generated token to clipboard
    function copyGeneratedToken() {
        const token = document.getElementById('generatedTokenText').textContent;
        navigator.clipboard.writeText(token).then(() => {
            const flash = document.getElementById('flashMessage');
            flash.textContent = "Token copied to clipboard!";
            flash.style.display = 'block';
        }).catch(() => {
            const flash = document.getElementById('flashMessage');
            flash.textContent = "Failed to copy token.";
            flash.style.display = 'block';
        });

    }

    // Registration modal functionality
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

    // Modal close on clicking outside the modal content
    function closeTokenModal(event) {
        // Check if the click was outside the modal content (on the overlay)
        if (event.target === document.getElementById('tokenModal')) {
            document.getElementById('tokenModal').style.display = 'none';
        }
    }


    function closeTokenSuccessModal() {
        document.getElementById('tokenSuccessModal').style.display = 'none';
    }

    // Show the Forgot Token form when the link is clicked
    function showForgotTokenForm() {
        document.getElementById('tokenForm').style.display = 'none';  // Hide the main token input form
        document.getElementById('forgotTokenForm').style.display = 'block';  // Show the forgot token form
    }

    // Close the modal
    function closeTokenModal(event) {
        // Close modal if clicked outside modal content
        if (event) {
            event.stopPropagation();
        }
        document.getElementById('tokenModal').style.display = 'none';
        document.getElementById('forgotTokenForm').style.display = 'none';  // Hide the forgot token form when closing modal
        document.getElementById('tokenForm').style.display = 'block';  // Show the main token input form again
    }

    // Submit the email and get the token from forgot_token.php
    document.getElementById('retrieveTokenForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        fetch('forgot_token.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (data.success) {
                alert('Your token is: ' + data.token);
                closeTokenModal(); // Close modal after retrieval
            } else {
                document.getElementById('retrieveTokenMessage').textContent = data.message;
                document.getElementById('retrieveTokenMessage').style.display = 'block';
            }
        })
        .catch(error => {
            alert('Something went wrong. Please try again.');
        });
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
