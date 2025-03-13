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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && !isset($_POST["edit_id"])) {
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
        $descriptions = isset($_POST["achievements_descriptions"]) ? $_POST["achievements_descriptions"] : [];
    }
    

    // Handle profile image upload
    $image = "";
    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    // Insert into `top_athletes` first
    $query = "INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) 
              VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$image')";
    
    if ($conn->query($query) === TRUE) {
        $athlete_id = $conn->insert_id; // Get the ID of the newly inserted athlete
        
        // Insert achievements into `athlete_achievements`
        if (!empty($achievements)) {
            foreach ($achievements as $index => $achievement) {
                $achievement_title = $conn->real_escape_string($achievement);
                $achievement_desc = isset($descriptions[$index]) ? $conn->real_escape_string($descriptions[$index]) : "";
                $conn->query("INSERT INTO achievements (athlete_id, title, description) VALUES ('$athlete_id', '$achievement_title', '$achievement_desc')");
            }
        }

        if (!empty($_FILES["athlete_gallery"]["name"][0])) {
            foreach ($_FILES["athlete_gallery"]["tmp_name"] as $key => $tmp_name) {
                if (!empty($_FILES["athlete_gallery"]["name"][$key]) && $_FILES["athlete_gallery"]["error"][$key] == 0) {
                    $gallery_image_path = "images/gallery/" . basename($_FILES["athlete_gallery"]["name"][$key]);
                    move_uploaded_file($tmp_name, $gallery_image_path);
                    
                    // Get the corresponding description for this image
                    $description = isset($_POST["gallery_descriptions"][$key]) ? $_POST["gallery_descriptions"][$key] : '';

                    // Insert image and description into the database
                    $stmt = $conn->prepare("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $athlete_id, $gallery_image_path, $description);
                    $stmt->execute();
                }
            }
        }


        header("Location: editInlinePage.php");
        exit();
    } else {
        echo "Error inserting athlete: " . $conn->error;
    }

    if (isset($_POST["edit_id"])) {
        $id = $_POST["edit_id"];
    
        // Fetch existing image
        $result = $conn->query("SELECT image FROM top_athletes WHERE id = '$id'");
        $row = $result->fetch_assoc();
        $existing_image = $row["image"];
    
        // Check if a new image was uploaded
        $image = $existing_image;
        if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
            $image = "images/uploads/" . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        }
    
        // Update query
        $query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', 
                  podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', image='$image' 
                  WHERE id='$id'";
        
        if ($conn->query($query) === TRUE) {
            // Don't delete existing gallery images if no new ones were uploaded
            if (!empty($_FILES["athlete_gallery"]["name"][0])) {
                $conn->query("DELETE FROM athlete_gallery WHERE athlete_id='$id'");
            }
    
            header("Location: editInlinePage.php");
            exit();
        } else {
            echo "Error updating athlete: " . $conn->error;
        }
    }
    
}

$conn->close();
?>
