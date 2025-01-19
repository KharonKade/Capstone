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
$registration = isset($_POST['registration']) ? 1 : 0;

// Validate form inputs
if (empty($event_name) || empty($location) || empty($description)) {
    die("Error: Please fill all the required fields.");
}

// Insert main event data
$sql = "INSERT INTO upcoming_events (event_name, location, description, category, registration) 
        VALUES ('$event_name', '$location', '$description', '$category', '$registration')";
if ($conn->query($sql) === TRUE) {
    $event_id = $conn->insert_id;

    // Insert schedules
    if (!empty($_POST['event_date'])) {
        foreach ($_POST['event_date'] as $index => $event_date) {
            $start_time = $_POST['start_time'][$index] ?? '';
            $end_time = $_POST['end_time'][$index] ?? '';
            if (!empty($event_date) && !empty($start_time) && !empty($end_time)) {
                $schedule_sql = "INSERT INTO event_schedules (event_id, event_date, start_time, end_time) 
                                 VALUES ('$event_id', '$event_date', '$start_time', '$end_time')";
                $conn->query($schedule_sql);
            }
        }
    }

    // Upload posters
    if (!empty($_FILES['posters']['tmp_name'][0])) {
        foreach ($_FILES['posters']['tmp_name'] as $index => $tmp_name) {
            $poster_path = "images/uploads" . basename($_FILES['posters']['name'][$index]);
            if (move_uploaded_file($tmp_name, $poster_path)) {
                $image_sql = "INSERT INTO event_images (event_id, image_path) VALUES ('$event_id', '$poster_path')";
                $conn->query($image_sql);
            }
        }
    }

    // Upload sponsor logos
    if (!empty($_FILES['sponsors']['tmp_name'][0])) {
        foreach ($_FILES['sponsors']['tmp_name'] as $index => $tmp_name) {
            $sponsor_path = "images/uploads" . basename($_FILES['sponsors']['name'][$index]);
            if (move_uploaded_file($tmp_name, $sponsor_path)) {
                $sponsor_sql = "INSERT INTO sponsor_logos (event_id, logo_path) VALUES ('$event_id', '$sponsor_path')";
                $conn->query($sponsor_sql);
            }
        }
    }

    // Display success message in JavaScript
    echo "<script type='text/javascript'>
            alert('Event created successfully!');
            window.location.href = 'admin.html'; // Redirect to admin dashboard after event is created
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
