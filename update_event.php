<?php
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID
$event_id = $_POST['event_id'];

// Update main event details
$event_name = $_POST['event_name'];
$location = $_POST['location'];
$description = $_POST['description'];
$category = $_POST['category'];
$registration = isset($_POST['registration']) ? 1 : 0;

$update_event_sql = "UPDATE upcoming_events 
                     SET event_name = '$event_name', 
                         location = '$location', 
                         description = '$description', 
                         category = '$category', 
                         registration = '$registration' 
                     WHERE id = $event_id";

if ($conn->query($update_event_sql) === FALSE) {
    die("Error updating event: " . $conn->error);
}

// Update schedules
$conn->query("DELETE FROM event_schedules WHERE event_id = $event_id");
foreach ($_POST['event_date'] as $index => $event_date) {
    $start_time = $_POST['start_time'][$index];
    $end_time = $_POST['end_time'][$index];
    $conn->query("INSERT INTO event_schedules (event_id, event_date, start_time, end_time) 
                  VALUES ('$event_id', '$event_date', '$start_time', '$end_time')");
}

// Handle posters
if (!empty($_POST['existing_posters'])) {
    $existing_posters = $_POST['existing_posters'];
} else {
    $existing_posters = [];
}

$existing_posters_placeholder = implode(",", array_map(function ($path) {
    return "'$path'";
}, $existing_posters));

$conn->query("DELETE FROM event_images WHERE event_id = $event_id AND image_path NOT IN ($existing_posters_placeholder)");

foreach ($_FILES['posters']['tmp_name'] as $index => $tmp_name) {
    if (!empty($tmp_name)) {
        $poster_path = "uploads/" . basename($_FILES['posters']['name'][$index]);
        if (move_uploaded_file($tmp_name, $poster_path)) {
            $conn->query("INSERT INTO event_images (event_id, image_path) VALUES ('$event_id', '$poster_path')");
        }
    }
}

// Handle sponsor logos
if (!empty($_POST['existing_sponsors'])) {
    $existing_sponsors = $_POST['existing_sponsors'];
} else {
    $existing_sponsors = [];
}

$existing_sponsors_placeholder = implode(",", array_map(function ($path) {
    return "'$path'";
}, $existing_sponsors));

$conn->query("DELETE FROM sponsor_logos WHERE event_id = $event_id AND logo_path NOT IN ($existing_sponsors_placeholder)");

foreach ($_FILES['sponsors']['tmp_name'] as $index => $tmp_name) {
    if (!empty($tmp_name)) {
        $sponsor_path = "uploads/" . basename($_FILES['sponsors']['name'][$index]);
        if (move_uploaded_file($tmp_name, $sponsor_path)) {
            $conn->query("INSERT INTO sponsor_logos (event_id, logo_path) VALUES ('$event_id', '$sponsor_path')");
        }
    }
}

echo "Event updated successfully!";
$conn->close();
header("Location: manage_upcoming.php");
exit();
?>
