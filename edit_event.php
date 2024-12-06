<?php
$conn = new mysqli("localhost", "root", "", "basf_events");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = $_GET['id'];

// Fetch event details
$event = $conn->query("SELECT * FROM upcoming_events WHERE id = $event_id")->fetch_assoc();

// Fetch schedules
$schedules = $conn->query("SELECT * FROM event_schedules WHERE event_id = $event_id");

// Fetch posters
$images = $conn->query("SELECT * FROM event_images WHERE event_id = $event_id");

// Fetch sponsors
$sponsors = $conn->query("SELECT * FROM sponsor_logos WHERE event_id = $event_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="Css/admin.css">
</head>
<body>
    <div class="admin-container">
        <form action="update_event.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

            <label>Event Name:</label>
            <input type="text" name="event_name" value="<?php echo $event['event_name']; ?>" required>

            <label>Location:</label>
            <input type="text" name="location" value="<?php echo $event['location']; ?>" required>

            <label>Category:</label>
            <select name="category">
                <option value="skateboard" <?php if ($event['category'] == "skateboard") echo "selected"; ?>>Skateboard</option>
                <option value="inline" <?php if ($event['category'] == "inline") echo "selected"; ?>>Inline</option>
                <option value="bmx" <?php if ($event['category'] == "bmx") echo "selected"; ?>>BMX</option>
                <option value="all" <?php if ($event['category'] == "all") echo "selected"; ?>>All</option>
            </select>

            <label>Description:</label>
            <textarea name="description" required><?php echo $event['description']; ?></textarea>

            <label>Registration:</label>
            <input type="checkbox" name="registration" <?php if ($event['registration']) echo "checked"; ?>>

            <h3>Schedules:</h3>
            <div id="schedule-container">
                <?php while ($schedule = $schedules->fetch_assoc()): ?>
                <div>
                    <label>Date:</label>
                    <input type="date" name="event_date[]" value="<?php echo $schedule['event_date']; ?>" required>
                    <label>Start Time:</label>
                    <input type="time" name="start_time[]" value="<?php echo $schedule['start_time']; ?>" required>
                    <label>End Time:</label>
                    <input type="time" name="end_time[]" value="<?php echo $schedule['end_time']; ?>" required>
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                </div>
                <?php endwhile; ?>
            </div>
            <button type="button" id="add-schedule">Add Schedule</button>

            <h3>Posters:</h3>
            <div>
                <?php while ($image = $images->fetch_assoc()): ?>
                <img src="<?php echo $image['image_path']; ?>" alt="Poster" style="width: 100px; height: auto;">
                <input type="hidden" name="existing_posters[]" value="<?php echo $image['image_path']; ?>">
                <button type="button" onclick="this.parentElement.remove()">Remove</button>
                <?php endwhile; ?>
            </div>
            <label>Upload New Posters:</label>
            <input type="file" name="posters[]" multiple>

            <h3>Sponsor Logos:</h3>
            <div>
                <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                <img src="<?php echo $sponsor['logo_path']; ?>" alt="Sponsor" style="width: 100px; height: auto;">
                <input type="hidden" name="existing_sponsors[]" value="<?php echo $sponsor['logo_path']; ?>">
                <button type="button" onclick="this.parentElement.remove()">Remove</button>
                <?php endwhile; ?>
            </div>
            <label>Upload New Sponsors:</label>
            <input type="file" name="sponsors[]" multiple>

            <button type="submit">Update Event</button>
        </form>
    </div>
    <script>
        document.getElementById('add-schedule').addEventListener('click', function () {
    const container = document.getElementById('schedule-container');
    const scheduleDiv = document.createElement('div');
    scheduleDiv.innerHTML = `
        <label>Date:</label>
        <input type="date" name="event_date[]" required>
        <label>Start Time:</label>
        <input type="time" name="start_time[]" required>
        <label>End Time:</label>
        <input type="time" name="end_time[]" required>
        <button type="button" onclick="this.parentElement.remove()">Remove</button>
            `;
            container.appendChild(scheduleDiv);
        });

    </script>
</body>
</html>

<?php $conn->close(); ?>