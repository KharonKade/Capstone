<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate uploaded images
function validateFileUpload($file, $allowed_types = ['image/jpeg', 'image/png', 'image/gif'], $max_size = 2 * 1024 * 1024) {
    if ($file['error'] !== 0) return false;
    if (!in_array($file['type'], $allowed_types)) return false;
    if ($file['size'] > $max_size) return false;
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["edit_id"]) ? (int)$_POST["edit_id"] : null;
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = (int)$_POST["wins"];
    $podium_finishes = isset($_POST["podium_finishes"]) ? (int)$_POST["podium_finishes"] : 0;
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);

    // Handle achievements
    $achievements = $_POST["achievements"] ?? [];
    $descriptions = $_POST["achievements_descriptions"] ?? [];

    // Handle profile image
    $image = "";
    if (!empty($_FILES["image"]["name"]) && validateFileUpload($_FILES["image"])) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    if ($id) {
        // **Update Existing Athlete**
        echo "Updating Athlete ID: $id <br>"; // Debugging

        if (!empty($image)) {
            $query = "UPDATE top_athletes SET name=?, bio=?, description=?, wins=?, podium_finishes=?, years_active=?, specialty=?, image=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiiissi", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image, $id);
        } else {
            $query = "UPDATE top_athletes SET name=?, bio=?, description=?, wins=?, podium_finishes=?, years_active=?, specialty=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiiisi", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $id);
        }

        if ($stmt->execute()) {
            echo "Athlete updated successfully! <br>";

            // **Update Achievements - Check First**
            $existing_achievements = [];
            $result = $conn->query("SELECT title FROM achievements WHERE athlete_id='$id'");
            while ($row = $result->fetch_assoc()) {
                $existing_achievements[] = $row['title'];
            }

            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = $conn->real_escape_string($descriptions[$index] ?? "");

                if (!in_array($achievement_title, $existing_achievements)) {
                    $stmt_achieve = $conn->prepare("INSERT INTO achievements (athlete_id, title, description) VALUES (?, ?, ?)");
                    $stmt_achieve->bind_param("iss", $id, $achievement_title, $achievement_desc);
                    $stmt_achieve->execute();
                }
            }

            // **Update Athlete Gallery - Check First**
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                $stmt_gallery = $conn->prepare("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES (?, ?, ?)");

                foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                    if (validateFileUpload($_FILES["athlete_gallery"][$key])) {
                        $gallery_image_path = "images/uploads/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image_path);
                        $gallery_description = $conn->real_escape_string($_POST["gallery_descriptions"][$key] ?? '');

                        // Check if image already exists
                        $existing_images = [];
                        $res = $conn->query("SELECT image FROM athlete_gallery WHERE athlete_id='$id'");
                        while ($row = $res->fetch_assoc()) {
                            $existing_images[] = $row['image'];
                        }

                        if (!in_array($gallery_image_path, $existing_images)) {
                            $stmt_gallery->bind_param("iss", $id, $gallery_image_path, $gallery_description);
                            $stmt_gallery->execute();
                        }
                    }
                }
            }

            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error updating athlete: " . $stmt->error;
        }
    } else {
        // **Insert New Athlete**
        $stmt = $conn->prepare("INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiiss", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image);

        if ($stmt->execute()) {
            $athlete_id = $conn->insert_id;
            echo "New Athlete Inserted with ID: $athlete_id <br>";

            // **Insert Achievements**
            $stmt_achieve = $conn->prepare("INSERT INTO achievements (athlete_id, title, description) VALUES (?, ?, ?)");

            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = $conn->real_escape_string($descriptions[$index] ?? "");
                $stmt_achieve->bind_param("iss", $athlete_id, $achievement_title, $achievement_desc);
                $stmt_achieve->execute();
            }

            // **Insert Gallery Images**
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                $stmt_gallery = $conn->prepare("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES (?, ?, ?)");

                foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                    if (validateFileUpload($_FILES["athlete_gallery"][$key])) {
                        $gallery_image_path = "images/uploads/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image_path);
                        $gallery_description = $conn->real_escape_string($_POST["gallery_descriptions"][$key] ?? '');
                        $stmt_gallery->bind_param("iss", $athlete_id, $gallery_image_path, $gallery_description);
                        $stmt_gallery->execute();
                    }
                }
            }

            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error inserting athlete: " . $stmt->error;
        }
    }
}
$conn->close();
?>
