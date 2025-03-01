<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content"; // General database for Inline, BMX, and Skateboard

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing data
$sql = "SELECT * FROM inline_page_content LIMIT 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $about_us = $_POST['about_us'];
    $highlight_carousel = $_POST['highlight_carousel'];
    $top_athletes = $_POST['top_athletes'];
    $community_leaders = $_POST['community_leaders'];
    $partnerships = $_POST['partnerships'];

    $sql = "UPDATE inline_page_content SET 
            about_us = '$about_us', 
            highlight_carousel = '$highlight_carousel',
            top_athletes = '$top_athletes',
            community_leaders = '$community_leaders',
            partnerships = '$partnerships'";

    if ($conn->query($sql) === TRUE) {
        echo "Content updated successfully";
    } else {
        echo "Error updating content: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inline Page</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h2>Edit Inline Page</h2>
    <form method="POST">
        <label>About Us:</label>
        <textarea name="about_us" rows="5"><?php echo $row['about_us']; ?></textarea>

        <label>Highlight Carousel (Video URLs, comma-separated):</label>
        <input type="text" name="highlight_carousel" value="<?php echo $row['highlight_carousel']; ?>">

        <label>Top Athletes (JSON format):</label>
        <textarea name="top_athletes" rows="5"><?php echo $row['top_athletes']; ?></textarea>

        <label>Community Leaders (JSON format):</label>
        <textarea name="community_leaders" rows="5"><?php echo $row['community_leaders']; ?></textarea>

        <label>Partnerships (Image URLs, comma-separated):</label>
        <input type="text" name="partnerships" value="<?php echo $row['partnerships']; ?>">

        <button type="submit">Update</button>
    </form>
</body>
</html>
