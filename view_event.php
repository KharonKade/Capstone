<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = $_GET['id'];

// Fetch event details
$event_query = "SELECT * FROM upcoming_events WHERE id = $event_id";
$event = $conn->query($event_query)->fetch_assoc();

// Fetch schedules
$schedules_query = "SELECT * FROM event_schedules WHERE event_id = $event_id";
$schedules = $conn->query($schedules_query);

// Fetch poster images
$images_query = "SELECT * FROM event_images WHERE event_id = $event_id";
$images = $conn->query($images_query);

// Fetch sponsor logos
$sponsors_query = "SELECT * FROM sponsor_logos WHERE event_id = $event_id";
$sponsors = $conn->query($sponsors_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event</title>
    <link rel="stylesheet" href="Css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($event['category'])); ?></p>
        <p><strong>Registration:</strong> <?php echo $event['registration'] == 1 ? 'Enabled' : 'Disabled'; ?></p>

        <h3>Schedules</h3>
        <ul>
            <?php if ($schedules->num_rows > 0): ?>
                <?php while ($schedule = $schedules->fetch_assoc()): ?>
                <li>
                    <strong>Date:</strong> <?php echo $schedule['event_date']; ?> | 
                    <strong>Start:</strong> <?php echo $schedule['start_time']; ?> | 
                    <strong>End:</strong> <?php echo $schedule['end_time']; ?>
                </li>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No schedules available for this event.</p>
            <?php endif; ?>
        </ul>

        <h3>Posters</h3>
        <div>
            <?php if ($images->num_rows > 0): ?>
                <?php while ($image = $images->fetch_assoc()): ?>
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Poster" style="width: 100px; height: auto; margin: 5px;">
                <?php endwhile; ?>
            <?php else: ?>
                <p>No poster images uploaded for this event.</p>
            <?php endif; ?>
        </div>

        <h3>Sponsor Logos</h3>
        <div>
            <?php if ($sponsors->num_rows > 0): ?>
                <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                <img src="<?php echo htmlspecialchars($sponsor['logo_path']); ?>" alt="Sponsor Logo" style="width: 100px; height: auto; margin: 5px;">
                <?php endwhile; ?>
            <?php else: ?>
                <p>No sponsor logos available for this event.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
