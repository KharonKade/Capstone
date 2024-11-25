<?php
include('db_connection.php');  // Include the database connection

$sql = "SELECT * FROM create_event WHERE status = 'upcoming'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='event'>";
        echo "<h2>" . $row["event_name"] . "</h2>";
        echo "<p>" . $row["event_dates"] . "</p>";
        echo "<p>" . $row["event_start_time"] . " - " . $row["event_end_time"] . "</p>";
        echo "<p>" . $row["event_description"] . "</p>";
        echo "<img src='" . $row["event_images"] . "' alt='Event Poster'>";
        echo "</div>";
    }
} else {
    echo "No upcoming events.";
}

$conn->close();  // Close the connection
?>
