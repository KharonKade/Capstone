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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && !isset($_POST["edit_id"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "0";
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);

    // Check if achievements are provided
    if (!empty($_POST["achievements"])) {
        $achievements = $_POST["achievements"]; // Expecting an array of achievements
    }

    // Handle image upload
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
        
        // Insert achievements into the separate `achievements` table
        if (!empty($achievements)) {
            foreach ($achievements as $achievement) {
                $achievement = $conn->real_escape_string($achievement);
                $conn->query("INSERT INTO achievements (athlete_id, achievement) VALUES ('$athlete_id', '$achievement')");
            }
        }

        header("Location: editInlinePage.php");
        exit();
    } else {
        echo "Error inserting athlete: " . $conn->error;
    }
}

$conn->close();
?>
