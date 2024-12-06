<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "contact_us");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the inquiry ID from the URL
$id = $_GET['id'];

// Update the inquiry to set archived = 0 (restore it)
$sql = "UPDATE contact_inquiries SET archived = 0 WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // Redirect to the archived inquiries page after restoring the inquiry
    header("Location: archived_inquiries.php?message=restored");
    exit();
} else {
    echo "Error restoring inquiry: " . $conn->error;
}

// Close the connection
$conn->close();
?>
