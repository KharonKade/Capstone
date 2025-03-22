<?php
// Database Connection
$host = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "basf"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Gallery Items
$galleryItems = [];
$sql = "SELECT * FROM gallery";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Fetch additional images for each gallery item
    $gallery_id = $row['id'];
    $images = [];
    $imgQuery = "SELECT image_path FROM gallery_images WHERE gallery_id = $gallery_id";
    $imgResult = $conn->query($imgQuery);
    
    while ($imgRow = $imgResult->fetch_assoc()) {
        $images[] = $imgRow['image_path'];
    }

    $galleryItems[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "description" => $row['description'],
        "thumbnail" => $row['thumbnail'],
        "images" => $images
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Page</title>
    <link rel="stylesheet" href="Css/gallery.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="sponsorship.html">Sponsorship</a></li>
                    <li><a href="contactUs.html">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <section class="event-hero">
        <div class="event-hero-content">
            <h1>Gallery</h1>
            <h2>Projects & Programs</h2>
        </div>
    </section>

    <div class="gallery-section">
        <h1 class="gallery-title">Our Gallery</h1>
        <div class="gallery-container">
            <?php foreach ($galleryItems as $item): ?>
                <div class="gallery-item" onclick="openModal(<?php echo $item['id']; ?>)">
                    <img src="<?php echo $item['thumbnail']; ?>" alt="<?php echo $item['title']; ?>">
                    <div class="gallery-overlay">
                        <p><?php echo $item['title']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title"></h2>
            <p id="modal-description"></p>
            <div id="modal-images"></div>
        </div>
    </div>

    <script>
        let galleryItems = <?php echo json_encode($galleryItems); ?>;

        function openModal(id) {
            let item = galleryItems.find(g => g.id === id);
            if (item) {
                document.getElementById("modal-title").innerText = item.title;
                document.getElementById("modal-description").innerText = item.description;
                let imageContainer = document.getElementById("modal-images");
                imageContainer.innerHTML = "";
                item.images.forEach(img => {
                    let imgTag = document.createElement("img");
                    imgTag.src = img;
                    imgTag.className = "modal-img";
                    imageContainer.appendChild(imgTag);
                });
                document.getElementById("galleryModal").style.display = "block";
            }
        }

        function closeModal() {
            document.getElementById("galleryModal").style.display = "none";
        }
    </script>

</body>
</html>
