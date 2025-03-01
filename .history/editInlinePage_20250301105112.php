<?php
$host = "localhost"; // Change if using a different host
$username = "root"; // Change if using a different username
$password = ""; // Change if you have a password set
$database = "basf_content"; // The database name you created

$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update About Us
    $about_us = $_POST['about_us'];
    mysqli_query($conn, "UPDATE page_content SET content='$about_us' WHERE section='about_us'");

    // Insert Highlight Carousel Videos
    if (!empty($_FILES['highlight_video']['name'])) {
        $video = $_FILES['highlight_video']['name'];
        $video_tmp = $_FILES['highlight_video']['tmp_name'];
        move_uploaded_file($video_tmp, "uploads/videos/$video");
        $title = $_POST['highlight_title'];
        $description = $_POST['highlight_description'];
        mysqli_query($conn, "INSERT INTO highlight_carousel (video, title, description) VALUES ('$video', '$title', '$description')");
    }

    // Insert Top Athletes
    if (!empty($_FILES['athlete_image']['name'])) {
        $athlete_img = $_FILES['athlete_image']['name'];
        move_uploaded_file($_FILES['athlete_image']['tmp_name'], "uploads/athletes/$athlete_img");
        $athlete_name = $_POST['athlete_name'];
        $athlete_desc = $_POST['athlete_description'];
        mysqli_query($conn, "INSERT INTO top_athletes (image, name, description) VALUES ('$athlete_img', '$athlete_name', '$athlete_desc')");
    }

    // Insert Community Leaders
    if (!empty($_FILES['leader_image']['name'])) {
        $leader_img = $_FILES['leader_image']['name'];
        move_uploaded_file($_FILES['leader_image']['tmp_name'], "uploads/leaders/$leader_img");
        $leader_name = $_POST['leader_name'];
        $leader_role = $_POST['leader_role'];
        mysqli_query($conn, "INSERT INTO community_leaders (image, name, role) VALUES ('$leader_img', '$leader_name', '$leader_role')");
    }

    // Insert Partnerships
    if (!empty($_FILES['partner_logo']['name'])) {
        $partner_logo = $_FILES['partner_logo']['name'];
        move_uploaded_file($_FILES['partner_logo']['tmp_name'], "uploads/partners/$partner_logo");
        mysqli_query($conn, "INSERT INTO partnerships (logo) VALUES ('$partner_logo')");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Inline Page</title>
</head>
<body>
    <h2>Manage Inline Page</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <h3>About Us</h3>
        <textarea name="about_us" required></textarea>
        
        <h3>Highlight Carousel</h3>
        <input type="file" name="highlight_video" accept="video/*" required>
        <input type="text" name="highlight_title" placeholder="Video Title" required>
        <textarea name="highlight_description" placeholder="Video Description" required></textarea>
        
        <h3>Top Athletes</h3>
        <input type="file" name="athlete_image" accept="image/*" required>
        <input type="text" name="athlete_name" placeholder="Athlete Name" required>
        <textarea name="athlete_description" placeholder="Athlete Description" required></textarea>
        
        <h3>Community Leaders</h3>
        <input type="file" name="leader_image" accept="image/*" required>
        <input type="text" name="leader_name" placeholder="Leader Name" required>
        <input type="text" name="leader_role" placeholder="Leader Role" required>
        
        <h3>Partnerships</h3>
        <input type="file" name="partner_logo" accept="image/*" required>
        
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
