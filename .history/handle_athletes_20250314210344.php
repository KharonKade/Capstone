<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate and upload an image
function uploadImage($file, $existingImage = "") {
    $uploadDir = "images/uploads/";
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!empty($file["name"]) && $file["error"] === 0) {
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes)) {
            return $existingImage; // Keep existing image if invalid file type
        }
        if ($file["size"] > $maxSize) {
            return $existingImage; // Keep existing image if file is too large
        }
        
        $uniqueName = uniqid("athlete_", true) . ".$ext";
        $destination = $uploadDir . $uniqueName;
        if (move_uploaded_file($file["tmp_name"], $destination)) {
            return $destination;
        }
    }
    return $existingImage;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["edit_id"]) ? (int) $_POST["edit_id"] : null;
    $name = $_POST["name"] ?? "";
    $bio = $_POST["bio"] ?? "";
    $description = $_POST["description"] ?? "";
    $wins = $_POST["wins"] ?? "0";
    $podium_finishes = $_POST["podium_finishes"] ?? "0";
    $years_active = $_POST["years_active"] ?? "";
    $specialty = $_POST["specialty"] ?? "";
    $achievements = $_POST["achievements"] ?? [];
    $descriptions = $_POST["achievements_descriptions"] ?? [];

    // Fetch existing image if updating
    $existingImage = "";
    if ($id) {
        $stmt = $conn->prepare("SELECT image FROM top_athletes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $existingImage = $row["image"];
        }
        $stmt->close();
    }

    $image = uploadImage($_FILES["image"], $existingImage);

    if ($id) {
        // Update existing athlete
        $stmt = $conn->prepare("UPDATE top_athletes SET name=?, bio=?, description=?, wins=?, podium_finishes=?, years_active=?, specialty=?, image=? WHERE id=?");
        $stmt->bind_param("ssssisssi", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image, $id);
    } else {
        // Insert new athlete
        $stmt = $conn->prepare("INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image);
    }

    foreach ($achievements as $index => $achievement) {
        $achievement_title = $achievement;
        $achievement_desc = $descriptions[$index] ?? "";
    
        $stmt = $conn->prepare("SELECT id FROM achievements WHERE athlete_id = ? AND title = ?");
        $stmt->bind_param("is", $athlete_id, $achievement_title);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            // Update existing achievement
            $stmt = $conn->prepare("UPDATE achievements SET description = ? WHERE athlete_id = ? AND title = ?");
            $stmt->bind_param("sis", $achievement_desc, $athlete_id, $achievement_title);
        } else {
            // Insert new achievement
            $stmt = $conn->prepare("INSERT INTO achievements (athlete_id, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $athlete_id, $achievement_title, $achievement_desc);
        }
        $stmt->execute();
    }
    $stmt->close();
        
        // Handle gallery images
        if (!empty($_FILES["athlete_gallery"]["name"][0])) {
            $stmt = $conn->prepare("SELECT image FROM athlete_gallery WHERE athlete_id = ?");
            $stmt->bind_param("i", $athlete_id);
            $stmt->execute();
            $existing_gallery = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        
            $existing_images = array_column($existing_gallery, 'image');
        
            $stmt = $conn->prepare("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES (?, ?, ?)");
            foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                if ($_FILES["athlete_gallery"]["error"][$key] == 0) {
                    $gallery_image = uploadImage(["name" => $_FILES["athlete_gallery"]["name"][$key], "tmp_name" => $tmp_name, "size" => $_FILES["athlete_gallery"]["size"][$key], "error" => $_FILES["athlete_gallery"]["error"][$key]]);
        
                    if (!in_array($gallery_image, $existing_images)) {
                        $gallery_description = $_POST["gallery_descriptions"][$key] ?? "";
                        $stmt->bind_param("iss", $athlete_id, $gallery_image, $gallery_description);
                        $stmt->execute();
                    }
                }
            }
            $stmt->close();
        }

        header("Location: editInlinePage.php");
        exit();
    } else {
        error_log("Database Error: " . $conn->error);
        echo "Error processing request.";
    }

$conn->close();
?>