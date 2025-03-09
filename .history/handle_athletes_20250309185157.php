<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Athlete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = isset($_POST["name"]) ? $conn->real_escape_string($_POST["name"]) : "";
    $bio = isset($_POST["bio"]) ? $conn->real_escape_string($_POST["bio"]) : "";
    $description = isset($_POST["description"]) ? $conn->real_escape_string($_POST["description"]) : "";
    $wins = isset($_POST["wins"]) ? $conn->real_escape_string($_POST["wins"]) : "";
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "";
    $years_active = isset($_POST["years_active"]) ? $conn->real_escape_string($_POST["years_active"]) : "";
    $specialty = isset($_POST["specialty"]) ? $conn->real_escape_string($_POST["specialty"]) : "";
    $achievements = isset($_POST["achievements"]) ? $conn->real_escape_string($_POST["achievements"]) : "";
    $image = "";

    // Handle file upload if an image is provided
    if (!empty($_FILES["image"]["name"])) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
            die("Error uploading image.");
        }
    }

    // Insert data into database
    $sql = "INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, achievements, image) 
            VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$achievements', '$image')";

    if (!$conn->query($sql)) {
        die("Error inserting data: " . $conn->error);
    }
}

// Handle Edit Athlete
if (isset($_POST["edit_id"])) {
    $id = $conn->real_escape_string($_POST["edit_id"]);
    $name = isset($_POST["name"]) ? $conn->real_escape_string($_POST["name"]) : "";
    $bio = isset($_POST["bio"]) ? $conn->real_escape_string($_POST["bio"]) : "";
    $description = isset($_POST["description"]) ? $conn->real_escape_string($_POST["description"]) : "";
    $wins = isset($_POST["wins"]) ? $conn->real_escape_string($_POST["wins"]) : "";
    $podium_finishes = isset($_POST["podium_finishes"]) ? $conn->real_escape_string($_POST["podium_finishes"]) : "";
    $years_active = isset($_POST["years_active"]) ? $conn->real_escape_string($_POST["years_active"]) : "";
    $specialty = isset($_POST["specialty"]) ? $conn->real_escape_string($_POST["specialty"]) : "";
    $achievements = isset($_POST["achievements"]) ? $conn->real_escape_string($_POST["achievements"]) : "";
    $image = "";

    // Update query without image
    $query = "UPDATE top_athletes SET 
                name='$name', bio='$bio', description='$description', wins='$wins', 
                podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', 
                achievements='$achievements' WHERE id='$id'";

    // Handle image update if a new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
            $query = "UPDATE top_athletes SET 
                        name='$name', bio='$bio', description='$description', wins='$wins', 
                        podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', 
                        achievements='$achievements', image='$image' WHERE id='$id'";
        }
    }

    if (!$conn->query($query)) {
        die("Error updating data: " . $conn->error);
    }
}

// Handle Delete Athlete
if (isset($_POST["delete_id"])) {
    $id = $conn->real_escape_string($_POST["delete_id"]);
    $sql = "DELETE FROM top_athletes WHERE id='$id'";

    if (!$conn->query($sql)) {
        die("Error deleting data: " . $conn->error);
    }
}

// Close connection
$conn->close();

// Redirect to avoid form resubmission issues
header("Location: editInlinePage.php");
exit();
?>
