<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the news ID is set in the URL
if (isset($_GET['id'])) {
    $news_id = intval($_GET['id']); // Get the news ID from the URL

    // Fetch the current news data from the database
    $sql = "SELECT * FROM news_announcements WHERE news_id = $news_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $news = $result->fetch_assoc(); // Fetch the news data
    } else {
        die("Error: News item not found.");
    }
} else {
    // If no news ID is provided, redirect back to manage news page
    header("Location: manage_news.php");
    exit();
}

// Handle form submission (update news)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $news_title = $_POST['news_title'] ?? '';
    $news_content = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'General';
    $publish_date = $_POST['news_date'] ?? '';

    // Validate form inputs
    if (empty($news_title) || empty($news_content) || empty($publish_date)) {
        die("Error: Please fill all the required fields.");
    }

    // Update news data in the database
    $update_sql = "UPDATE news_announcements SET 
                    news_title = '$news_title', 
                    news_content = '$news_content', 
                    category = '$category', 
                    publish_date = '$publish_date'
                    WHERE news_id = $news_id";
    if ($conn->query($update_sql) === TRUE) {
        // Handle image uploads if any
        if (!empty($_FILES['image']['tmp_name'])) {
            $upload_dir = "images/uploads/";

            // Ensure the directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Delete existing images from the database and server
            $delete_images_sql = "SELECT image_path FROM news_images WHERE news_id = $news_id";
            $delete_images_result = $conn->query($delete_images_sql);
            while ($image = $delete_images_result->fetch_assoc()) {
                // Delete the image file from the server
                if (file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }
            }

            // Delete the images from the database
            $conn->query("DELETE FROM news_images WHERE news_id = $news_id");

            // Handle new image uploads
            if (is_array($_FILES['image']['tmp_name'])) {
                foreach ($_FILES['image']['tmp_name'] as $index => $tmp_name) {
                    $image_name = basename($_FILES['image']['name'][$index]);
                    $image_path = $upload_dir . $image_name;
                    if (move_uploaded_file($tmp_name, $image_path)) {
                        // Insert the image into the database
                        $image_sql = "INSERT INTO news_images (news_id, image_path) VALUES ('$news_id', '$image_path')";
                        $conn->query($image_sql);
                    }
                }
            } else {
                // For single image uploads
                $image_name = basename($_FILES['image']['name']);
                $image_path = $upload_dir . $image_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    // Insert the image into the database
                    $image_sql = "INSERT INTO news_images (news_id, image_path) VALUES ('$news_id', '$image_path')";
                    $conn->query($image_sql);
                }
            }
        }

        // Redirect with a success message
        header("Location: manage_news.php?message=News item updated successfully");
        exit();
    } else {
        die("Error updating news: " . $conn->error);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News & Announcements</title>
    <link rel="stylesheet" href="Css/manage_event.css"> <!-- Use existing styles -->
</head>
<body>
    <div class="admin-container">
        <main class="content">
            <h2>Edit News & Announcements</h2>
            <form action="edit_news.php?id=<?php echo $news_id; ?>" method="POST" enctype="multipart/form-data">
                <label for="news_title">News Title:</label>
                <input type="text" id="news_title" name="news_title" value="<?php echo $news['news_title']; ?>" required>

                <label for="description">News Content:</label>
                <textarea id="description" name="description" required><?php echo $news['news_content']; ?></textarea>

                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="General" <?php echo ($news['category'] == 'General') ? 'selected' : ''; ?>>General</option>
                    <option value="Sports" <?php echo ($news['category'] == 'Sports') ? 'selected' : ''; ?>>Sports</option>
                    <option value="Updates" <?php echo ($news['category'] == 'Updates') ? 'selected' : ''; ?>>Updates</option>
                    <!-- Add more categories as needed -->
                </select>

                <label for="news_date">Publish Date:</label>
                <input type="date" id="news_date" name="news_date" value="<?php echo $news['publish_date']; ?>" required>

                <label for="image">Upload New Images (Optional):</label>
                <input type="file" id="image" name="image[]" multiple>

                <button type="submit">Update News</button>
            </form>
        </main>
    </div>
</body>
</html>
