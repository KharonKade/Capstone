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
        
        // Fetch the associated images
        $image_sql = "SELECT * FROM news_images WHERE news_id = $news_id";
        $image_result = $conn->query($image_sql);
        $images = [];
        while ($image = $image_result->fetch_assoc()) {
            $images[] = $image; // Store all the image data in an array
        }
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
        // Update news data in the database using prepared statement
    $update_sql = "UPDATE news_announcements SET 
                    news_title = ?, 
                    news_content = ?, 
                    category = ?, 
                    publish_date = ?
                    WHERE news_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $news_title, $news_content, $category, $publish_date, $news_id);

    if ($stmt->execute()) {
        // ... image deletion/upload logic here ...
    } else {
        die("Error updating news: " . $stmt->error);
    }

    if ($conn->query($update_sql) === TRUE) {
        // Handle image deletions if any
        if (isset($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $image_id) {
                // Delete the image from the database
                $delete_image_sql = "SELECT image_path FROM news_images WHERE image_id = $image_id";
                $delete_image_result = $conn->query($delete_image_sql);
                if ($delete_image_result->num_rows > 0) {
                    $image = $delete_image_result->fetch_assoc();
                    // Delete the image file from the server
                    if (file_exists($image['image_path'])) {
                        unlink($image['image_path']);
                    }
                    // Delete the image record from the database
                    $conn->query("DELETE FROM news_images WHERE image_id = $image_id");
                }
            }
        }

        // Handle image deletions if any
        if (!empty($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $image_id) {
                // Get the image path before deleting it
                $delete_image_sql = "SELECT image_path FROM news_images WHERE image_id = ?";
                $stmt = $conn->prepare($delete_image_sql);
                $stmt->bind_param("i", $image_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $image = $result->fetch_assoc();
                    // Delete the image file from the server
                    if (file_exists($image['image_path'])) {
                        unlink($image['image_path']);
                    }

                    // Delete the image record from the database
                    $delete_sql = "DELETE FROM news_images WHERE image_id = ?";
                    $stmt = $conn->prepare($delete_sql);
                    $stmt->bind_param("i", $image_id);
                    $stmt->execute();
                }
            }
        }


        // Handle new image uploads if any
        if (!empty($_FILES['image']['tmp_name'])) {
            $upload_dir = "images/uploads/";

            // Ensure the directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

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
    <link rel="stylesheet" href="Css/edit_news.css">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>
<body>
    <div class="admin-container">
        <main class="content">
            <h2>Edit News & Announcements</h2>
            <form action="edit_news.php?id=<?php echo $news_id; ?>" method="POST" enctype="multipart/form-data">
                <label for="news_title">News Title:</label>
                <input type="text" id="news_title" name="news_title" value="<?php echo $news['news_title']; ?>" required>

                <label for="description">News Content:</label>
                <textarea id="description" name="description" ><?php echo $news['news_content']; ?></textarea>

                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="All" <?php echo ($news['category'] == 'All') ? 'selected' : ''; ?>>All</option>
                    <option value="Skateboard" <?php echo ($news['category'] == 'Skateboard') ? 'selected' : ''; ?>>Skateboard</option>
                    <option value="Inline" <?php echo ($news['category'] == 'Inline') ? 'selected' : ''; ?>>Inline</option>
                    <option value="BMX" <?php echo ($news['category'] == 'BMX') ? 'selected' : ''; ?>>BMX</option>
                </select>

                <label for="news_date">Publish Date:</label>
                <input type="date" id="news_date" name="news_date" value="<?php echo $news['publish_date']; ?>" required>

                <label for="image">Upload New Images:</label>
                <input type="file" id="image" name="image[]" multiple>

                <?php if (!empty($images)): ?>
                    <p>Existing Images:</p>
                    <ul>
                        <?php foreach ($images as $image): ?>
                            <li class="image-item">
                                <img src="<?php echo $image['image_path']; ?>" alt="News Image" width="100">
                                <input type="hidden" name="existing_images[]" value="<?php echo $image['image_id']; ?>">
                                <button type="button" onclick="removeElement(this)">Remove</button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <button type="submit">Update News</button>
            </form>
        </main>
    </div>
    <script>

    let editorInstance;

    ClassicEditor
    .create(document.querySelector('#description'))
    .then(editor => {
        // Show the textarea once CKEditor is fully initialized
        editor.ui.view.editable.element.parentElement.style.display = 'block';
    })
    .catch(error => {
        console.error(error);
    });


    // Listen for form submission and sync the editor content
    document.querySelector('form').addEventListener('submit', function (e) {
        try {
            if (editorInstance) {
                document.querySelector('#description').value = editorInstance.getData();
            }
        } catch (error) {
            console.error("CKEditor content sync failed:", error);
        }
    });
    
    function removeElement(button) {
        button.parentElement.remove();
    }

    function removeElement(button) {
    let listItem = button.closest(".image-item");
    let imageId = listItem.querySelector("input[name='existing_images[]']").value;

    // Add a hidden input to track deleted images
    let removeInput = document.createElement("input");
    removeInput.type = "hidden";
    removeInput.name = "delete_images[]"; // This must match PHP
    removeInput.value = imageId;
    document.querySelector("form").appendChild(removeInput);

    // Remove the image from the UI
    listItem.remove();
    }
    </script>
    
</body>
</html>
