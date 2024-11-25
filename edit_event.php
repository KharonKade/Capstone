<?php
include('db_connection.php');

if (isset($_GET['edit'])) {
    $event_id = $_GET['edit'];

    // Fetch the event data
    $sql = "SELECT * FROM create_event WHERE id = $event_id";
    $result = $conn->query($sql);
    $event = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    // Collect form data
    $event_name = $_POST['event_name'];
    $event_dates = $_POST['event_dates'];
    $event_start_time = $_POST['event_start_time'];
    $event_end_time = $_POST['event_end_time'];
    $event_description = $_POST['event_description'];
    $event_location = $_POST['event_location'];
    $event_category = $_POST['event_category'];

    // Update event in the database
    $sql = "UPDATE create_event SET 
            event_name = '$event_name', 
            event_dates = '$event_dates', 
            event_start_time = '$event_start_time', 
            event_end_time = '$event_end_time', 
            event_description = '$event_description', 
            event_location = '$event_location', 
            event_category = '$event_category' 
            WHERE id = $event_id";

    if ($conn->query($sql) === TRUE) {
        echo "Event updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<form method="POST">
    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" value="<?php echo $event['event_name']; ?>" required>

    <label for="event_dates">Event Dates:</label>
    <input type="text" name="event_dates" value="<?php echo $event['event_dates']; ?>" required>

    <!-- Add other form fields similarly -->

    <button type="submit" name="update">Update Event</button>
</form>
