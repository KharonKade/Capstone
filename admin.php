<?php
// Include database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data here

    // Gather event data from POST request
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_location = $_POST['event_location'];
    $event_category = $_POST['event_category'];
    $event_dates = json_encode($_POST['event_dates']); // Assume it's an array of date/time pairs
    $event_images = json_encode($_FILES['event_images']['name']);
    $sponsors_logos = json_encode($_FILES['sponsors_logos']['name']);
    $registration = isset($_POST['registration']) ? 1 : 0;

    // Insert event data into database
    $query = "INSERT INTO events (event_name, event_description, event_location, event_dates, event_category, event_images, sponsors_logos, registration)
              VALUES ('$event_name', '$event_description', '$event_location', '$event_dates', '$event_category', '$event_images', '$sponsors_logos', '$registration')";
    mysqli_query($conn, $query);

    // Get the last inserted event ID for further actions
    $event_id = mysqli_insert_id($conn);

    // Add event to corresponding category
    if ($event_category == 'skateboarding' || $event_category == 'inline' || $event_category == 'bmx') {
        $table = strtolower($event_category) . "_events";
        mysqli_query($conn, "INSERT INTO $table (event_id) VALUES ('$event_id')");
    } else {
        // Add event to all categories
        mysqli_query($conn, "INSERT INTO upcoming_events (event_id) VALUES ('$event_id')");
    }

    // Redirect to event management page or success page
    header("Location: manage_events.php");
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" required><br>

    <label for="event_description">Event Description:</label>
    <textarea name="event_description" required></textarea><br>

    <label for="event_location">Event Location:</label>
    <input type="text" name="event_location" required><br>

    <label for="event_dates">Event Dates & Times:</label>
    <input type="text" name="event_dates[]" placeholder="Date 1" required><br>
    <input type="text" name="event_dates[]" placeholder="Date 2"><br>
    <!-- More input fields for dates can be added dynamically -->
    
    <label for="event_category">Event For:</label>
    <select name="event_category" required>
        <option value="skateboarding">Skateboarding</option>
        <option value="inline">Inline</option>
        <option value="bmx">BMX</option>
        <option value="all">All Categories</option>
    </select><br>

    <label for="event_images">Event Images:</label>
    <input type="file" name="event_images[]" multiple><br>

    <label for="sponsors_logos">Sponsor Logos:</label>
    <input type="file" name="sponsors_logos[]" multiple><br>

    <label for="registration">Registration Required:</label>
    <input type="checkbox" name="registration"><br>

    <button type="submit">Create Event</button>
</form>
