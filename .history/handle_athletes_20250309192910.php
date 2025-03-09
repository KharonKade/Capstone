<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables with default values to prevent undefined warnings
$id = $name = $bio = $description = $wins = $podium_finishes = $years_active = $specialty = $achievements = "";

// Fetch existing athlete data if 'id' is set in GET
if (!empty($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM top_athletes WHERE id='$id'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $bio = $row['bio'];
        $description = $row['description'];
        $wins = $row['wins'];
        $podium_finishes = isset($row['podium_finishes']) ? $row['podium_finishes'] : ""; // Prevent undefined index
        $years_active = $row['years_active'];
        $specialty = $row['specialty'];
        $achievements = isset($row['achievements']) ? $row['achievements'] : ""; // Prevent undefined index
    } else {
        $id = "";
    }
}

// Insert New Athlete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && !isset($_POST["edit_id"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "0"; 
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);
    $achievements = isset($_POST["achievements"]) ? $conn->real_escape_string($_POST["achievements"]) : "";

    // Check if the column 'achievements' exists in the database
    $columnCheck = $conn->query("SHOW COLUMNS FROM top_athletes LIKE 'achievements'");
    if ($columnCheck->num_rows == 0) {
        die("Error: The column 'achievements' does not exist in the database.");
    }

    // Check if image is uploaded
    $image = "";
    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $query = "INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, achievements, image) 
              VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$achievements', '$image')";
    
    if ($conn->query($query) === TRUE) {
        header("Location: editInlinePage.php");
        exit();
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

$conn->close();
?>
