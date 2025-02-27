<?php
// Database connection
$servername = "localhost";  // Your database host
$username = "root";         // Your database username
$password = "";             // Your database password
$dbname = "contact_us";    // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);
    $concerns = htmlspecialchars($_POST['concerns']);
    $message = htmlspecialchars($_POST['message']);

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_inquiries (full_name, email, contact_number, concerns, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullName, $email, $contactNumber, $concerns, $message);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Your message has been submitted successfully!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('There was an error submitting your message. Please try again later.'); window.location.href='contact.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>
