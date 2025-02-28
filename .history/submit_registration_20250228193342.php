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
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$category = $_POST['category'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($gender) || empty($category)) {
    echo "<script>alert('All fields are required!');</script>";
} else {
    // Update query with added fields
    $update_query = "UPDATE event_registrations SET name=?, email=?, phone=?, age=?, gender=?, category=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $name, $email, $phone, $age, $gender, $category, $registration_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Registration updated successfully!');
                window.location.href = 'view_event.php?id=" . $registration['event_id'] . "';
              </script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

