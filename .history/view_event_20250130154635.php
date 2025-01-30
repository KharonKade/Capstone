<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "basf_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID
$event_id = $_GET['id'];

// Fetch event details
$event_query = "SELECT * FROM upcoming_events WHERE id = $event_id";
$event = $conn->query($event_query);
if (!$event || $event->num_rows === 0) {
    die("Event not found: " . $conn->error);
}
$event = $event->fetch_assoc();

// Fetch schedules
$schedules_query = "SELECT * FROM event_schedules WHERE event_id = $event_id";
$schedules = $conn->query($schedules_query);

// Fetch poster images
$images_query = "SELECT * FROM event_images WHERE event_id = $event_id";
$images = $conn->query($images_query);
if (!$images) {
    die("Error fetching images: " . $conn->error);
}

// Fetch sponsor logos
$sponsors_query = "SELECT * FROM sponsor_logos WHERE event_id = $event_id";
$sponsors = $conn->query($sponsors_query);
if (!$sponsors) {
    die("Error fetching sponsors: " . $conn->error);
}

// Fetch registered users
$registrations_query = "SELECT * FROM event_registrations WHERE event_id = $event_id";
$registrations = $conn->query($registrations_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event</title>
    <link rel="stylesheet" href="Css/view_event.css">
</head>
<body>
<div class="admin-container">
    <div class="left-side">
        <p><strong>Event Name:</strong> <?php echo htmlspecialchars($event['event_name']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <p><strong>Category:</strong> <?php echo ucfirst(htmlspecialchars($event['category'])); ?></p>
        <p><strong>Registration:</strong> <?php echo $event['registration'] == 1 ? 'Enabled' : 'Disabled'; ?></p>

        <h3>Schedules</h3>
        <ul>
            <?php if ($schedules->num_rows > 0): ?>
                <?php while ($schedule = $schedules->fetch_assoc()): ?>
                    <li>
                        <strong>Date:</strong> <?php echo htmlspecialchars($schedule['event_date']); ?> | 
                        <strong>Start:</strong> <?php echo htmlspecialchars($schedule['start_time']); ?> | 
                        <strong>End:</strong> <?php echo htmlspecialchars($schedule['end_time']); ?>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No schedules available for this event.</p>
            <?php endif; ?>
        </ul>

    <!-- Right Side -->
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
                    <p>No poster images uploaded for this event.</p>
                <?php endif; ?>
            </div>

            <div class="sponsor-container">
                <h3>Sponsor Logos</h3>
                <?php if ($sponsors->num_rows > 0): ?>
                    <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                        <img src="<?php echo htmlspecialchars($sponsor['logo_path']); ?>" 
                             alt="Sponsor Logo" 
                             style="width: 100%; height: auto; margin-bottom: 10px;">
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No sponsor logos available for this event.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div>
    <h3>Registered Users</h3>
    <?php if ($registrations->num_rows > 0): ?>
        <table border="1" cellspacing="0" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($registration = $registrations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($registration['id']); ?></td>
                        <td><?php echo htmlspecialchars($registration['name']); ?></td>
                        <td><?php echo htmlspecialchars($registration['email']); ?></td>
                        <td><?php echo htmlspecialchars($registration['phone']); ?></td>
                        <td>
                            <a href="edit_registration.php?id=<?php echo $registration['id']; ?>">Edit</a> |
                            <a href="delete_registration.php?id=<?php echo $registration['id']; ?>" 
                            onclick="return confirm('Are you sure you want to delete this registration?');">
                            Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users have registered for this event yet.</p>
    <?php endif; ?>
</div>

<form method="post" action="javascript:history.back()">
    <button type="submit">Return</button>
</form>
</body>
</html>

<?php $conn->close(); ?>
