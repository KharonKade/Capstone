<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "contact_us");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete all archived inquiries
$sql = "DELETE FROM contact_inquiries WHERE archived = 1";

if ($conn->query($sql) === TRUE) {
    // Redirect to the archived inquiries page with a success message
    header("Location: archived_inquiries.php?message=deleted_all");
    exit();
} else {
    echo "Error deleting inquiries: " . $conn->error;
}

// Close the connection
$conn->close();
?>
