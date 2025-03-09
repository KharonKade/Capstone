<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing athlete data if 'id' is set in GET

$id = $name = $bio = $description = $wins = $podium_finishes = $years_active = $specialty = $achievements = "";

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $result = $conn->query("SELECT * FROM top_athletes WHERE id='$id'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $bio = $row['bio'];
        $description = $row['description'];
        $wins = $row['wins'];
        $podium_finishes = $row['podium_finishes'];
        $years_active = $row['years_active'];
        $specialty = $row['specialty'];
        $achievements = $row['achievements'];
    } else {
        $id = ""
    }
}

// Insert New Athlete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && !isset($_POST["edit_id"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = $conn->real_escape_string($_POST["podium_finishes"]);
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);
    $achievements = $conn->real_escape_string($_POST["achievements"]);
    
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

// Update Athlete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_id"])) {
    $id = $conn->real_escape_string($_POST["edit_id"]);
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = $conn->real_escape_string($_POST["podium_finishes"]);
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);
    $achievements = $conn->real_escape_string($_POST["achievements"]);

    $query = "UPDATE top_athletes SET 
              name='$name', bio='$bio', description='$description', wins='$wins', 
              podium_finishes='$podium_finishes', years_active='$years_active', 
              specialty='$specialty', achievements='$achievements' 
              WHERE id='$id'";

    // Check if image is uploaded
    if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["error"] == 0) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        $query = "UPDATE top_athletes SET 
                  name='$name', bio='$bio', description='$description', wins='$wins', 
                  podium_finishes='$podium_finishes', years_active='$years_active', 
                  specialty='$specialty', achievements='$achievements', image='$image' 
                  WHERE id='$id'";
    }

    if ($conn->query($query) === TRUE) {
        header("Location: editInlinePage.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete Athlete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $id = $conn->real_escape_string($_POST["delete_id"]);
    $query = "DELETE FROM top_athletes WHERE id='$id'";
    
    if ($conn->query($query) === TRUE) {
        header("Location: editInlinePage.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
