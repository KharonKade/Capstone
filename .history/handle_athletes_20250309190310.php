<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);


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
