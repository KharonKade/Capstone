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

// Fetch registration details
$registration_query = "SELECT * FROM event_registrations WHERE id = ?";
$stmt = $conn->prepare($registration_query);
$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Registration not found.");
}

$registration = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($name) || empty($email) || empty($phone)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        $update_query = "UPDATE event_registrations SET name=?, email=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $name, $email, $phone, $registration_id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration updated successfully!');
                    window.location.href = 'view_event.php?id=" . $registration['event_id'] . "';
                  </script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Registration</title>
    <link rel="stylesheet" href="Css/edit_registration.css"></link>
</head>
<body>
    <h2>Edit Registration</h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($registration['name']); ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($registration['email']); ?>" required><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($registration['phone']); ?>" required><br>

        <button type="submit">Update</button>
        <a href="view_event.php?id=<?php echo $registration['event_id']; ?>">Cancel</a>
    </form>
</body>
</html>
