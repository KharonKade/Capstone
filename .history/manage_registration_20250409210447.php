<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_POST['token'] ?? '';
$registration_sql = "SELECT * FROM event_registrations WHERE token = '$token'";
$registration_result = $conn->query($registration_sql);
$registration = $registration_result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- 🔗 Link to CSS -->
</head>
<body>
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
                        <a href="delete_registration.php?id=<?= $registration['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this registration?');">
                           Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: red;">Invalid token or no registration found.</p>
    <?php endif; ?>
</body>
</html>
