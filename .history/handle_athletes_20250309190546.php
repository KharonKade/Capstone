<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Fetch existing athlete data
    $result = $conn->query("SELECT * FROM top_athletes WHERE id='$id'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Assign values to avoid undefined variable warnings
        $name = $row['name'];
        $bio = $row['bio'];
        $description = $row['description'];
        $wins = $row['wins'];
        $podium_finishes = $row['podium_finishes'];
        $years_active = $row['years_active'];
        $specialty = $row['specialty'];
        $achievements = $row['achievements'];
    } else {
        echo "Athlete not found.";
        exit;
    }
} else {
    echo "No athlete ID provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]); // Added bio field
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = $conn->real_escape_string($_POST["podium_finishes"]);
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);
    $achievements = $conn->real_escape_string($_POST["achievements"]);
    $image = "images/uploads/" . basename($_FILES["image"]["name"]);
    
    move_uploaded_file($_FILES["image"]["tmp_name"], $image);

    $conn->query("INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, achievements, image) 
                  VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$achievements', '$image')");
}

if (isset($_POST["edit_id"])) {
    $id = $_POST["edit_id"];
    $name = $conn->real_escape_string($_POST["name"]);
    $bio = $conn->real_escape_string($_POST["bio"]); // Added bio field
    $description = $conn->real_escape_string($_POST["description"]);
    $wins = $conn->real_escape_string($_POST["wins"]);
    $podium_finishes = $conn->real_escape_string($_POST["podium_finishes"]);
    $years_active = $conn->real_escape_string($_POST["years_active"]);
    $specialty = $conn->real_escape_string($_POST["specialty"]);
    $achievements = $conn->real_escape_string($_POST["achievements"]);

    $query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', podium_finishes='$podium_finishes', 
              years_active='$years_active', specialty='$specialty', achievements='$achievements' WHERE id='$id'";

    if (!empty($_FILES["image"]["name"])) {
        $image = "images/uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
        $query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', podium_finishes='$podium_finishes', 
                  years_active='$years_active', specialty='$specialty', achievements='$achievements', image='$image' WHERE id='$id'";
    }

    $conn->query($query);
}

if (isset($_POST["delete_id"])) {
    $id = $_POST["delete_id"];
    $conn->query("DELETE FROM top_athletes WHERE id='$id'");
}

$conn->close();
header("Location: editInlinePage.php");
?>
