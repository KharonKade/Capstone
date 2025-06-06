<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "contact_us");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the inquiry by ID
$id = $_GET['id'];

// Fetch all inquiries to calculate sequential numbering
$sql_all_inquiries = "SELECT id FROM contact_inquiries ORDER BY id DESC";
$result_all = $conn->query($sql_all_inquiries);

// Find the position of the current inquiry
$counter = 1;
$inquiry_position = 0;
while($row_all = $result_all->fetch_assoc()) {
    if ($row_all['id'] == $id) {
        $inquiry_position = $counter;
        break;
    }
    $counter++;
}

// Now fetch the detailed inquiry based on the ID
$sql = "SELECT * FROM contact_inquiries WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiry Message</title>
    <link rel="stylesheet" href="Css/view_message.css">
</head>
<body>
    <div class="admin-container">
        <div class="main-content">
            <h2>Inquiry Details</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $inquiry_position; ?></td>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                </tr>
                <tr>
                    <th>Concerns</th>
                    <td><?php echo htmlspecialchars($row['concerns']); ?></td>
                </tr>

                <?php if (strtolower(trim($row['concerns'])) === 'sponsorship inquiry'): ?>
                <tr>
                    <th>Company Name</th>
                    <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Message</th>
                    <td class="message-content"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                </tr>
                <tr>
                    <th>Submitted At</th>
                    <td><?php echo $row['submitted_at']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <form method="post" action="javascript:history.back()">
        <button type="submit">Return</button>
    </form>
</body>
</html>

<?php $conn->close(); ?>
