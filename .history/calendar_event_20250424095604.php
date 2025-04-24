<?php
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "basf_events";    // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adjust this depending on how the two tables are linked
$query = $conn->query("
    SELECT ue.event_name, es.event_date 
    FROM upcoming_events ue
    INNER JOIN event_schedules es ON ue.id = es.event_id
    WHERE ue.status = 'active'
");

$events = [];

while ($row = $query->fetch_assoc()) {
    $events[] = [
       'title' => $row['event_name'],
        'start' => date('Y-m-d', strtotime($row['event_date'])), // No time part
        'allDay' => true  // Tells FullCalendar it's an all-day event
    ];
}

echo json_encode($events);

$conn->close();  // Close the database connection
?>
