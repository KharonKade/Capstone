<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_POST['token'] ?? null;
$registration_id = $_GET['id'] ?? null;
$event_id = $_GET['event_id'] ?? null;

$registration = null;

if ($token && $event_id) {
    // Ensure the token matches a registration from the specified event
    $registration_sql = "SELECT * FROM event_registrations WHERE token = ? AND event_id = ?";
    $stmt = $conn->prepare($registration_sql);
    $stmt->bind_param("si", $token, $event_id);
    $stmt->execute();
    $registration_result = $stmt->get_result();
    $registration = $registration_result->fetch_assoc();
} elseif ($registration_id) {
    // Admin or direct access via ID
    $registration_sql = "SELECT * FROM event_registrations WHERE id = ?";
    $stmt = $conn->prepare($registration_sql);
    $stmt->bind_param("i", $registration_id);
    $stmt->execute();
    $registration_result = $stmt->get_result();
    $registration = $registration_result->fetch_assoc();
    if ($registration) {
        $event_id = $registration['event_id']; // Assign event ID for the back button
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Registration</title>
    <link rel="stylesheet" href="Css/manage_registration.css">
</head>
<body>
    <div class="registration-container">
        <?php if ($registration): ?>
            <h3>Your Registration</h3>
            <table border="1" cellspacing="0" cellpadding="8">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
                        <th>Age</th><th>Gender</th><th>Category</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $registration['id']; ?></td>
                        <td><?= htmlspecialchars($registration['name']); ?></td>
                        <td><?= htmlspecialchars($registration['email']); ?></td>
                        <td><?= htmlspecialchars($registration['phone']); ?></td>
                        <td><?= htmlspecialchars($registration['age']); ?></td>
                        <td><?= htmlspecialchars($registration['gender']); ?></td>
                        <td><?= htmlspecialchars($registration['category']); ?></td>
                        <td>
                            <a href="edit_registration.php?id=<?= $registration['id']; ?>">Edit</a> |
                            <a href="delete_registration.php?id=<?= $registration['id']; ?> "
                            onclick="return confirm('Are you sure you want to delete this registration?');">
                            Delete
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: red;">Invalid token or no registration found.</p>
            <p style="color: red;">Make sure the token is correct or you are in the correct event.</p>
        <?php endif; ?>
        <?php if (isset($event_id)): ?>
            <div class="return-btn">
                <a href="eventPages.php?id=<?= $event_id; ?>">
                    Return to Event Page
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
