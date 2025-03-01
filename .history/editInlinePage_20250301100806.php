<?php
session_start();

// Check if the admin is logged in (Add authentication logic as needed)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions for different sections
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_highlight'])) {
        $video_path = $_POST['video_path'];
        $conn->query("INSERT INTO highlights (video_path) VALUES ('$video_path')");
    }
    if (isset($_POST['add_athlete'])) {
        $name = $_POST['athlete_name'];
        $bio = $_POST['athlete_bio'];
        $image = $_POST['athlete_image'];
        $conn->query("INSERT INTO top_athletes (name, bio, image) VALUES ('$name', '$bio', '$image')");
    }
    if (isset($_POST['add_leader'])) {
        $name = $_POST['leader_name'];
        $role = $_POST['leader_role'];
        $image = $_POST['leader_image'];
        $conn->query("INSERT INTO community_leaders (name, role, image) VALUES ('$name', '$role', '$image')");
    }
}

// Fetch existing data
$highlights = $conn->query("SELECT * FROM highlights");
$athletes = $conn->query("SELECT * FROM top_athletes");
$leaders = $conn->query("SELECT * FROM community_leaders");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Inline Page</title>
    <link rel="stylesheet" href="Css/admin.css">
</head>
<body>
    <h1>Edit Inline Page</h1>
    
    <section>
        <h2>Manage Highlights</h2>
        <form method="post">
            <input type="text" name="video_path" placeholder="Video URL" required>
            <button type="submit" name="add_highlight">Add Highlight</button>
        </form>
        <ul>
            <?php while ($row = $highlights->fetch_assoc()) { echo "<li>{$row['video_path']}</li>"; } ?>
        </ul>
    </section>
    
    <section>
        <h2>Manage Top Athletes</h2>
        <form method="post">
            <input type="text" name="athlete_name" placeholder="Athlete Name" required>
            <textarea name="athlete_bio" placeholder="Athlete Bio" required></textarea>
            <input type="text" name="athlete_image" placeholder="Image URL" required>
            <button type="submit" name="add_athlete">Add Athlete</button>
        </form>
        <ul>
            <?php while ($row = $athletes->fetch_assoc()) { echo "<li>{$row['name']} - {$row['bio']}</li>"; } ?>
        </ul>
    </section>
    
    <section>
        <h2>Manage Community Leaders</h2>
        <form method="post">
            <input type="text" name="leader_name" placeholder="Leader Name" required>
            <input type="text" name="leader_role" placeholder="Role" required>
            <input type="text" name="leader_image" placeholder="Image URL" required>
            <button type="submit" name="add_leader">Add Leader</button>
        </form>
        <ul>
            <?php while ($row = $leaders->fetch_assoc()) { echo "<li>{$row['name']} - {$row['role']}</li>"; } ?>
        </ul>
    </section>
</body>
</html>
