<?php
$token = $_GET['token'] ?? '';

// Check if the token exists in the database
$sql = "SELECT * FROM event_registrations WHERE token = '$token'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $registration = $result->fetch_assoc();

    // Allow the user to edit or delete the registration based on their token
    echo "<h2>Manage Registration</h2>";
    echo "<form action='edit_registration.php' method='POST'>";
    echo "<input type='hidden' name='registration_id' value='{$registration['id']}'>";
    echo "<label for='name'>Name:</label><input type='text' name='name' value='{$registration['name']}'>";
    // Add more form fields to edit other details (like email, phone, etc.)
    echo "<button type='submit'>Update Registration</button>";
    echo "</form>";

    echo "<form action='delete_registration.php' method='POST'>";
    echo "<input type='hidden' name='registration_id' value='{$registration['id']}'>";
    echo "<button type='submit'>Delete Registration</button>";
    echo "</form>";
} else {
    echo "Invalid token.";
}
?>
