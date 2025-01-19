<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the news ID
$news_id = $_GET['id'];

// Prepared statement for fetching news details
$stmt = $conn->prepare("SELECT * FROM news_announcements WHERE id = ?");
$stmt->bind_param("i", $news_id); // 'i' indicates the type is integer
$stmt->execute();
$news_result = $stmt->get_result();
if ($news_result->num_rows === 0) {
    die("News not found.");
}
$news = $news_result->fetch_assoc();

// Prepared statement for fetching poster images
$images_stmt = $conn->prepare("SELECT * FROM news_images WHERE news_id = ?");
$images_stmt->bind_param("i", $news_id);
$images_stmt->execute();
$images_result = $images_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View News & Announcement</title>
    <link rel="stylesheet" href="Css/view_news.css">
</head>
<body>
<div class="admin-container">
    <!-- Left Side -->
    <div class="left-side">
        <p><strong>News Title:</strong> <?php echo htmlspecialchars($news['news_title']); ?></p>
        <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($news['category'])); ?></p>
        <p><strong>Published On:</strong> <?php echo htmlspecialchars($news['publish_date']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($news['news_content'])); ?></p>
    </div>

    <!-- Right Side (Posters and Images) -->
    <div class="right-side">
        <div class="media-container">
            <div class="image-container">
                <h3>Posters</h3>
                <?php if ($images_result->num_rows > 0): ?>
                    <?php while ($image = $images_result->fetch_assoc()): ?>
                        <img src="<?php echo htmlspecialchars($image['image_path']); ?>" 
                             alt="Poster" 
                             style="width: 100%; height: auto; margin-bottom: 10px;">
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No poster images uploaded for this news announcement.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<form method="post" action="javascript:history.back()">
    <button type="submit">Return</button>
</form>
</body>
</html>

<?php 
// Close the prepared statements and the connection
$stmt->close();
$images_stmt->close();
$conn->close(); 
?>
