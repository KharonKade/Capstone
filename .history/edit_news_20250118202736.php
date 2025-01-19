<?php
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$news_id = $_GET['id'];

// Fetch news details
$news = $conn->query("SELECT * FROM news WHERE id = $news_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit News</title>
    <link rel="stylesheet" href="Css/admin.css">
</head>
<body>
    <div class="admin-container">
        <form action="update_news.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">

            <label>Title:</label>
            <input type="text" name="title" value="<?php echo $news['title']; ?>" required>

            <label>Content:</label>
            <textarea name="content" required><?php echo $news['content']; ?></textarea>

            <label>Author:</label>
            <input type="text" name="author" value="<?php echo $news['author']; ?>" required>

            <label>Publication Date:</label>
            <input type="date" name="pub_date" value="<?php echo $news['pub_date']; ?>" required>

            <label>Featured Image:</label>
            <div>
                <?php if ($news['image_path']): ?>
                    <img src="<?php echo $news['image_path']; ?>" alt="Featured Image" style="width: 100px; height: auto;">
                    <input type="hidden" name="existing_image" value="<?php echo $news['image_path']; ?>">
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                <?php endif; ?>
            </div>
            <input type="file" name="image">

            <button type="submit">Update News</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>
