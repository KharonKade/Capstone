<?php
require 'connection/conn.php';

// Adjust this depending on how the two tables are linked
$query = $conn->query("
    SELECT ue.event_name, es.start_date 
    FROM upcoming_events ue
    INNER JOIN event_schedules es ON ue.event_id = es.event_id
    WHERE ue.status = 'active'
");

$events = [];

while ($row = $query->fetch_assoc()) {
    $events[] = [
        'title' => $row['event_name'],
        'start' => $row['start_date']
    ];
}

echo json_encode($events);
?>
