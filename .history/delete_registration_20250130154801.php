<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get registration ID from URL
$registration_id = $_GET['id'] ?? null;

if (!$registration_id) {
    die("Invalid registration ID.");
}

// Fetch event ID before deletion
$event_query = "SELECT event_id FROM event_registrations WHERE id = ?";
$stmt = $conn->prepare($event_query);
$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Registration not found.");
}

$event = $result->fetch_assoc();
$event_id = $event['event_id'];

// Delete registration
$delete_query = "DELETE FROM event_registrations WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $registration_id);

if ($stmt->execute()) {
    echo "<script>
            alert('Registration deleted successfully!');
            window.location.href = 'view_event.php?id=" . $event_id . "';
          </script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
