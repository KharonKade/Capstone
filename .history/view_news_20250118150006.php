<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the news ID
$news_id = $_GET['id'];

// Fetch news details
$news_query = "SELECT * FROM news_announcements WHERE id = $news_id";
$news = $conn->query($news_query);
if (!$news || $news->num_rows === 0) {
    die("News not found: " . $conn->error);
}
$news = $news->fetch_assoc();

// Fetch poster images for news (if any)
$images_query = "SELECT * FROM news_images WHERE news_id = $news_id";
$images = $conn->query($images_query);
if (!$images) {
    die("Error fetching images: " . $conn->error);
}
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
                <?php if ($images->num_rows > 0): ?>
                    <?php while ($image = $images->fetch_assoc()): ?>
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

<?php $conn->close(); ?>
