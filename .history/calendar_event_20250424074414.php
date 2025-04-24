<?php
require 'connection/conn.php';

$query = $conn_events->query("SELECT event_name, start_date FROM basf_events WHERE status = 'active'");
$events = [];

while ($row = $query->fetch_assoc()) {
    $events[] = [
        'title' => $row['event_name'],
        'start' => $row['start_date']
    ];
}

echo json_encode($events);
?>
