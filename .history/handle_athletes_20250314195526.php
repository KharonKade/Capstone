<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["edit_id"]) ? $_POST["edit_id"] : null;
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "0";
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);

    // Handle achievements
    $achievements = !empty($_POST["achievements"]) ? $_POST["achievements"] : [];
    $descriptions = isset($_POST["achievements_descriptions"]) ? $_POST["achievements_descriptions"] : [];

    // Check if it's an edit or new entry
    $existing_image = "";
    if (!empty($id)) {
        // Fetch existing data
        $result = $conn->query("SELECT image FROM top_athletes WHERE id = '$id'");
        $row = $result->fetch_assoc();
        $existing_image = $row["image"];

        // Handle profile image upload
        if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
            $image = "images/uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        } else {
            $image = $existing_image; // Keep existing image
        }

        // Update athlete record
        $query = "UPDATE top_athletes SET 
                    name='$name', bio='$bio', description='$description', wins='$wins', 
                    podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', image='$image' 
                  WHERE id='$id'";

        if ($conn->query($query) === TRUE) {
            // Update achievements
            $conn->query("DELETE FROM achievements WHERE athlete_id='$id'");
            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = isset($descriptions[$index]) ? $conn->real_escape_string($descriptions[$index]) : "";
                $conn->query("INSERT INTO achievements (athlete_id, title, description) VALUES ('$id', '$achievement_title', '$achievement_desc')");
            }

            // Handle gallery images: Only remove if new ones are uploaded
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                $conn->query("DELETE FROM athlete_gallery WHERE athlete_id='$id'"); // Remove only if new images are added
                foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                    if ($_FILES["athlete_gallery"]["error"][$key] == 0) {
                        $gallery_image = "images/uploads/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image);

                        $gallery_description = isset($_POST["gallery_descriptions"][$key]) ? $conn->real_escape_string($_POST["gallery_descriptions"][$key]) : '';
                        $conn->query("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES ('$id', '$gallery_image', '$gallery_description')");
                    }
                }
            }

            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error updating athlete: " . $conn->error;
        }
    } else {
        // New athlete entry
        $image = "";
        if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
            $image = "images/uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        }

        // Insert into `top_athletes`
        $query = "INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) 
                  VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$image')";

        if ($conn->query($query) === TRUE) {
            $athlete_id = $conn->insert_id;

            // Insert achievements
            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = isset($descriptions[$index]) ? $conn->real_escape_string($descriptions[$index]) : "";
                $conn->query("INSERT INTO achievements (athlete_id, title, description) VALUES ('$athlete_id', '$achievement_title', '$achievement_desc')");
            }

            // Insert gallery images
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                    if ($_FILES["athlete_gallery"]["error"][$key] == 0) {
                        $gallery_image = "images/uploads/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image);

                        $gallery_description = isset($_POST["gallery_descriptions"][$key]) ? $conn->real_escape_string($_POST["gallery_descriptions"][$key]) : '';
                        $conn->query("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES ('$athlete_id', '$gallery_image', '$gallery_description')");
                    }
                }
            }

            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error inserting athlete: " . $conn->error;
        }
    }
}

$conn->close();
?>
