<?php
if (isset($_POST['submit'])) {
    include('db_connection.php');  // Include the database connection file

    // Collect form data
    $event_name = $_POST['event_name'];
    $event_dates = $_POST['event_dates'];
    $event_start_time = $_POST['event_start_time'];
    $event_end_time = $_POST['event_end_time'];
    $event_description = $_POST['event_description'];
    $event_location = $_POST['event_location'];
    $event_category = $_POST['event_category'];
    $event_registration = $_POST['event_registration'];

    // Handle file upload
    $target_dir = "uploads/";  // Directory to store uploaded files
    $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
    move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file);
    $event_image = $target_file;

    // Insert event details into the database
    $sql = "INSERT INTO create_event (event_name, event_dates, event_start_time, event_end_time, event_description, event_location, event_category, event_images, event_registration, status)
            VALUES ('$event_name', '$event_dates', '$event_start_time', '$event_end_time', '$event_description', '$event_location', '$event_category', '$event_image', '$event_registration', 'upcoming')";

    if ($conn->query($sql) === TRUE) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();  // Close the database connection
}
?>
