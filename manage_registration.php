<?php
// Establish connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_POST['token'] ?? '';

// Fetch the user's registration based on the token
$registration_sql = "SELECT * FROM event_registrations WHERE token = '$token'";
$registration_result = $conn->query($registration_sql);

if ($registration_result->num_rows > 0) {
    // Display the user's registration details
    $registration = $registration_result->fetch_assoc();
    ?>

    <h3>Your Registration</h3>
    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $registration['id']; ?></td>
                <td><?php echo htmlspecialchars($registration['name']); ?></td>
                <td><?php echo htmlspecialchars($registration['email']); ?></td>
                <td><?php echo htmlspecialchars($registration['phone']); ?></td>
                <td><?php echo htmlspecialchars($registration['age']); ?></td>
                <td><?php echo htmlspecialchars($registration['gender']); ?></td>
                <td><?php echo htmlspecialchars($registration['category']); ?></td>
                <td>
                    <a href="edit_registration.php?id=<?php echo $registration['id']; ?>">Edit</a> |
                    <a href="delete_registration.php?id=<?php echo $registration['id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this registration?');">
                       Delete
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <?php
} else {
    echo "Invalid token or no registration found.";
}

$conn->close();
?>
