<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

if (empty($name) || empty($email) || empty($event_id)) {
    die("Error: Please fill all the required fields.");
}

// Insert registration data into the database
$registration_sql = "INSERT INTO event_registrations (event_id, name, email) 
                     VALUES ('$event_id', '$name', '$email')";

if ($conn->query($registration_sql) === TRUE) {
    echo "<script type='text/javascript'>
            alert('Registration successful!');
            window.location.href = 'eventPages.php?id=' + $event_id;
          </script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
