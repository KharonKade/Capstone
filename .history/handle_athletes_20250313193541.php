<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables to prevent "Undefined array key" warnings
$id = $name = $bio = $description = $wins = $podium_finishes = $years_active = $specialty = "";
$achievements = [];
$athlete_gallery = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "0";
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);

    // Handle achievements
    if (!empty($_POST["achievements"])) {
        $achievements = $_POST["achievements"];
        $descriptions = $_POST["achievements_descriptions"];
    }

    // Handle profile image upload
    $image = "";
    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    // Check if editing or adding new athlete
    if (isset($_POST["edit_id"])) {
        // Update existing athlete
        $id = $_POST["edit_id"];
        $query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty'";
        
        if (!empty($image)) {
            $query .= ", image='$image'";
        }
        
        $query .= " WHERE id='$id'";
        
        if ($conn->query($query) === TRUE) {
            // Update achievements
            $conn->query("DELETE FROM achievements WHERE athlete_id='$id'");
            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = isset($descriptions[$index]) ? $conn->real_escape_string($descriptions[$index]) : "";
                $conn->query("INSERT INTO achievements (athlete_id, title, description) VALUES ('$id', '$achievement_title', '$achievement_desc')");
            }

            // Update athlete gallery
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                $conn->query("DELETE FROM athlete_gallery WHERE athlete_id='$id'");
                foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                    if ($_FILES["athlete_gallery"]["error"][$key] == 0) {
                        $gallery_image_path = "images/gallery/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image_path);
                        $description = isset($_POST["gallery_descriptions"][$key]) ? $_POST["gallery_descriptions"][$key] : '';
                        $conn->query("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES ('$id', '$gallery_image_path', '$description')");
                    }
                }
            }
            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error updating athlete: " . $conn->error;
        }
    } else {
        // Insert new athlete
        $query = "INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$image')";
        
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
                        $gallery_image_path = "images/gallery/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                        move_uploaded_file($tmp_name, $gallery_image_path);
                        $description = isset($_POST["gallery_descriptions"][$key]) ? $_POST["gallery_descriptions"][$key] : '';
                        $conn->query("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES ('$athlete_id', '$gallery_image_path', '$description')");
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
