<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_content";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Update athlete
        $id = $_POST['id'];
        $name = $_POST['name'];
        $bio = $_POST['bio'];
        $description = $_POST['description'];
        $wins = $_POST['wins'];
        $podium_finishes = $_POST['podium_finishes'];
        $years_active = $_POST['years_active'];
        $specialty = $_POST['specialty'];
        
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
            $update_query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty', image='$image' WHERE id='$id'";
        } else {
            $update_query = "UPDATE top_athletes SET name='$name', bio='$bio', description='$description', wins='$wins', podium_finishes='$podium_finishes', years_active='$years_active', specialty='$specialty' WHERE id='$id'";
        }
        
        $conn_content->query($update_query);
        
        // Update achievements
        if (!empty($_POST['achievement_ids'])) {
            foreach ($_POST['achievement_ids'] as $index => $ach_id) {
                $ach_title = $_POST['achievements'][$index];
                $ach_desc = $_POST['achievements_descriptions'][$index];
                $conn_content->query("UPDATE achievements SET title='$ach_title', description='$ach_desc' WHERE id='$ach_id'");
            }
        }

        // Update gallery images
        if (!empty($_POST['gallery_ids'])) {
            foreach ($_POST['gallery_ids'] as $index => $gallery_id) {
                if (!empty($_FILES['athlete_gallery']['name'][$index])) {
                    $gallery_image = 'uploads/' . basename($_FILES['athlete_gallery']['name'][$index]);
                    move_uploaded_file($_FILES['athlete_gallery']['tmp_name'][$index], $gallery_image);
                    $gallery_desc = $_POST['gallery_descriptions'][$index];
                    $conn_content->query("UPDATE athlete_gallery SET image='$gallery_image', description='$gallery_desc' WHERE id='$gallery_id'");
                }
            }
        }
    } else {
        // Add new athlete
        $name = $_POST['name'];
        $bio = $_POST['bio'];
        $description = $_POST['description'];
        $wins = $_POST['wins'];
        $podium_finishes = $_POST['podium_finishes'];
        $years_active = $_POST['years_active'];
        $specialty = $_POST['specialty'];
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
        
        $conn_content->query("INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) VALUES ('$name', '$bio', '$description', '$wins', '$podium_finishes', '$years_active', '$specialty', '$image')");
        $athlete_id = $conn_content->insert_id;
        
        // Add achievements
        foreach ($_POST['achievements'] as $index => $ach_title) {
            $ach_desc = $_POST['achievements_descriptions'][$index];
            $conn_content->query("INSERT INTO achievements (athlete_id, title, description) VALUES ('$athlete_id', '$ach_title', '$ach_desc')");
        }
        
        // Add gallery images
        foreach ($_FILES['athlete_gallery']['name'] as $index => $filename) {
            $gallery_image = 'uploads/' . basename($filename);
            move_uploaded_file($_FILES['athlete_gallery']['tmp_name'][$index], $gallery_image);
            $gallery_desc = $_POST['gallery_descriptions'][$index];
            $conn_content->query("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES ('$athlete_id', '$gallery_image', '$gallery_desc')");
        }
    }
    header('Location: athletes_page.php');
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    // Delete athlete
    $id = $_GET['delete'];
    $conn_content->query("DELETE FROM top_athletes WHERE id='$id'");
    $conn_content->query("DELETE FROM achievements WHERE athlete_id='$id'");
    $conn_content->query("DELETE FROM athlete_gallery WHERE athlete_id='$id'");
    header('Location: athletes_page.php');
    exit();
}
$conn->close();
?>
