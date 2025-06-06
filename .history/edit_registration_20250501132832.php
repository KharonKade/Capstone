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
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $category = $_POST['category'] ?? '';

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
                    window.location.href = 'manage_registration.php?id=" . $registration['event_id'] . "';
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
    <form method="post">
        <h2>Edit Registration</h2>
        <label>Name:</label>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($registration_id); ?>">

        <input type="text" name="name" value="<?php echo htmlspecialchars($registration['name']); ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($registration['email']); ?>" required><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($registration['phone']); ?>" required><br>

        <label>Age:</label>
        <input type="number" name="age" value="<?php echo htmlspecialchars($registration['age']); ?>" required><br>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?php echo ($registration['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($registration['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select><br>

        <label>Category:</label>
        <select name="category" required>
            <option value="Skateboard" <?php echo ($registration['category'] == 'Skateboard') ? 'selected' : ''; ?>>Skateboard</option>
            <option value="Inline" <?php echo ($registration['category'] == 'Inline') ? 'selected' : ''; ?>>Inline</option>
            <option value="BMX" <?php echo ($registration['category'] == 'BMX') ? 'selected' : ''; ?>>BMX</option>
        </select><br>

        <button type="submit">Update</button>
        <a href="javascript:void(0);" onclick="history.back();">Cancel</a>

    </form>
</body>
</html>
