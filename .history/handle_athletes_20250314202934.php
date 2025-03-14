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
        updateAthlete($conn_content);
    } else {
        // Add new athlete
        addAthlete($conn_content);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    // Delete athlete
    deleteAthlete($conn_content, $_GET['delete_id']);
}

function addAthlete($conn) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $description = $_POST['description'];
    $wins = $_POST['wins'];
    $podium_finishes = $_POST['podium_finishes'];
    $years_active = $_POST['years_active'];
    $specialty = $_POST['specialty'];

    $image_path = uploadImage($_FILES['image'], 'uploads/athletes/');

    $stmt = $conn->prepare("INSERT INTO top_athletes (name, bio, description, wins, podium_finishes, years_active, specialty, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiiss", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image_path);
    $stmt->execute();
    $athlete_id = $stmt->insert_id;
    $stmt->close();

    addAchievements($conn, $athlete_id);
    addGalleryImages($conn, $athlete_id);
    
    header("Location: editInlinePage.php");
}

function updateAthlete($conn) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $description = $_POST['description'];
    $wins = $_POST['wins'];
    $podium_finishes = $_POST['podium_finishes'];
    $years_active = $_POST['years_active'];
    $specialty = $_POST['specialty'];

    $image_path = $_POST['existing_image'];
    if (!empty($_FILES['image']['name'])) {
        $image_path = uploadImage($_FILES['image'], 'uploads/athletes/');
    }

    $stmt = $conn->prepare("UPDATE top_athletes SET name=?, bio=?, description=?, wins=?, podium_finishes=?, years_active=?, specialty=?, image=? WHERE id=?");
    $stmt->bind_param("sssiiissi", $name, $bio, $description, $wins, $podium_finishes, $years_active, $specialty, $image_path, $id);
    $stmt->execute();
    $stmt->close();

    updateAchievements($conn, $id);
    updateGalleryImages($conn, $id);
    
    header("Location: editInlinePage.php");
}

function deleteAthlete($conn, $id) {
    $conn->query("DELETE FROM top_athletes WHERE id='$id'");
    $conn->query("DELETE FROM achievements WHERE athlete_id='$id'");
    $conn->query("DELETE FROM athlete_gallery WHERE athlete_id='$id'");
    
    header("Location: editInlinePage.php");
}

function addAchievements($conn, $athlete_id) {
    foreach ($_POST['achievements'] as $index => $title) {
        $description = $_POST['achievements_descriptions'][$index];
        $stmt = $conn->prepare("INSERT INTO achievements (athlete_id, title, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $athlete_id, $title, $description);
        $stmt->execute();
        $stmt->close();
    }
}

function updateAchievements($conn, $athlete_id) {
    foreach ($_POST['achievement_ids'] as $index => $ach_id) {
        $title = $_POST['achievements'][$index];
        $description = $_POST['achievements_descriptions'][$index];
        $stmt = $conn->prepare("UPDATE achievements SET title=?, description=? WHERE id=? AND athlete_id=?");
        $stmt->bind_param("ssii", $title, $description, $ach_id, $athlete_id);
        $stmt->execute();
        $stmt->close();
    }
}

function addGalleryImages($conn, $athlete_id) {
    foreach ($_FILES['athlete_gallery']['name'] as $index => $name) {
        $image_path = uploadImage($_FILES['athlete_gallery'], 'uploads/gallery/', $index);
        $description = $_POST['gallery_descriptions'][$index];
        $stmt = $conn->prepare("INSERT INTO athlete_gallery (athlete_id, image, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $athlete_id, $image_path, $description);
        $stmt->execute();
        $stmt->close();
    }
}

function updateGalleryImages($conn, $athlete_id) {
    foreach ($_POST['gallery_existing_images'] as $index => $existing_image) {
        $image_path = $existing_image;
        if (!empty($_FILES['athlete_gallery']['name'][$index])) {
            $image_path = uploadImage($_FILES['athlete_gallery'], 'uploads/gallery/', $index);
        }
        $description = $_POST['gallery_descriptions'][$index];
        $stmt = $conn->prepare("UPDATE athlete_gallery SET image=?, description=? WHERE id=? AND athlete_id=?");
        $stmt->bind_param("ssii", $image_path, $description, $_POST['gallery_ids'][$index], $athlete_id);
        $stmt->execute();
        $stmt->close();
    }
}

function uploadImage($file, $target_dir, $index = null) {
    $filename = $index !== null ? $file['name'][$index] : $file['name'];
    $target_file = $target_dir . basename($filename);
    move_uploaded_file($index !== null ? $file['tmp_name'][$index] : $file['tmp_name'], $target_file);
    return $target_file;
}
?>
