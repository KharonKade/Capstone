<?php
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$event_name = $_POST['event_name'] ?? '';
$location = $_POST['location'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? 'All'; // Default to "All" category
$registration = isset($_POST['registration']) ? 1 : 0; // 1 for enabled, 0 for disabled
$registration_limit = isset($_POST['registration_limit']) ? (int)$_POST['registration_limit'] : NULL;

// Validate form inputs
if (empty($event_name) || empty($location) || empty($description)) {
    die("Error: Please fill all the required fields.");
}

// Insert main event data
$sql = "INSERT INTO upcoming_events (event_name, location, description, category, registration, registration_limit) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssis", $event_name, $location, $description, $category, $registration, $registration_limit);
$stmt->execute();
$event_id = $stmt->insert_id;
$stmt->close();

// Insert schedules
if (!empty($_POST['event_date'])) {
    $schedule_sql = "INSERT INTO event_schedules (event_id, event_date, start_time, end_time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($schedule_sql);
    foreach ($_POST['event_date'] as $index => $event_date) {
        $start_time = $_POST['start_time'][$index] ?? '';
        $end_time = $_POST['end_time'][$index] ?? '';
        if (!empty($event_date) && !empty($start_time) && !empty($end_time)) {
            $stmt->bind_param("isss", $event_id, $event_date, $start_time, $end_time);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Upload posters
if (!empty($_FILES['posters']['tmp_name'][0])) {
    $image_sql = "INSERT INTO event_images (event_id, image_path) VALUES (?, ?)";
    $stmt = $conn->prepare($image_sql);
    foreach ($_FILES['posters']['tmp_name'] as $index => $tmp_name) {
        $poster_path = "images/uploads/" . basename($_FILES['posters']['name'][$index]);
        if (move_uploaded_file($tmp_name, $poster_path)) {
            $stmt->bind_param("is", $event_id, $poster_path);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Upload sponsor logos
if (!empty($_FILES['sponsors']['tmp_name'][0])) {
    $sponsor_sql = "INSERT INTO sponsor_logos (event_id, logo_path) VALUES (?, ?)";
    $stmt = $conn->prepare($sponsor_sql);
    foreach ($_FILES['sponsors']['tmp_name'] as $index => $tmp_name) {
        $sponsor_path = "images/uploads/" . basename($_FILES['sponsors']['name'][$index]);
        if (move_uploaded_file($tmp_name, $sponsor_path)) {
            $stmt->bind_param("is", $event_id, $sponsor_path);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Display success message in JavaScript
echo "<script type='text/javascript'>
        alert('Event created successfully!');
        window.location.href = 'create_event.html'; // Redirect to admin dashboard after event is created
      </script>";

$conn->close();
?>
