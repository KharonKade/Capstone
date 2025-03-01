<?php
session_start();

// Check if the user is an admin (implement your own authentication system)
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions (Add, Edit, Delete)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == "edit") {
            $id = $_POST['id'];
            $content = $_POST['content'];
            $sql = "UPDATE inline_content SET content = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $content, $id);
            $stmt->execute();
        } elseif ($action == "delete") {
            $id = $_POST['id'];
            $sql = "DELETE FROM inline_content WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif ($action == "add") {
            $content = $_POST['content'];
            $sql = "INSERT INTO inline_content (content) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $content);
            $stmt->execute();
        }
    }
    header("Location: admin_inline.php");
    exit();
}

// Fetch content
$sql = "SELECT * FROM inline_content";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Inline Page</title>
    <link rel="stylesheet" href="Css/inline.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <h1>Admin Dashboard - Manage Inline Page</h1>
        </nav>
    </header>

    <section class="inline-content">
        <h2>Manage Inline Content</h2>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="content-item">
                <p><?php echo htmlspecialchars($row['content']); ?></p>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="edit">
                    <input type="text" name="content" value="<?php echo htmlspecialchars($row['content']); ?>">
                    <button type="submit">Edit</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit">Delete</button>
                </form>
            </div>
        <?php } ?>

        <h3>Add New Section</h3>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <textarea name="content" required></textarea>
            <button type="submit">Add</button>
        </form>
    </section>
</body>
</html>

<?php
$conn->close();
?>
