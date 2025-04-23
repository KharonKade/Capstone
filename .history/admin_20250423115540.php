<?php
// Database connection (adjust credentials accordingly)
$conn_events = new mysqli("localhost", "root", "", "basf_events");
$conn_news = new mysqli("localhost", "root", "", "basf_news");
$conn_gallery = new mysqli("localhost", "root", "", "basf_gallery");
$conn_contact = new mysqli("localhost", "root", "", "contact_us");

// Total Events (only active)
$events_result = $conn_events->query("SELECT COUNT(*) as total FROM upcoming_events WHERE status = 'active'");
$events_count = $events_result->fetch_assoc()['total'];

// News Articles (only active)
$news_result = $conn_news->query("SELECT COUNT(*) as total FROM news_announcements WHERE status = 'active'");
$news_count = $news_result->fetch_assoc()['total'];

// Gallery Items
$gallery_result = $conn_gallery->query("SELECT COUNT(*) as total FROM gallery");
$gallery_count = $gallery_result->fetch_assoc()['total'];

// New Inquiries (archived = 0)
$inquiry_result = $conn_contact->query("SELECT COUNT(*) as total FROM contact_inquiries WHERE archived = 0");
$inquiry_count = $inquiry_result->fetch_assoc()['total'];

// Total Registrations
$registration_result = $conn_events->query("SELECT COUNT(*) as total FROM event_registrations");
$registration_count = $registration_result->fetch_assoc()['total'];

// Track visits
$visit_conn = new mysqli("localhost", "root", "", "basf_visits");
$visit_conn->query("INSERT INTO visit_counter (visited_at) VALUES (NOW())"); // Log each visit
$visit_result = $visit_conn->query("SELECT COUNT(*) as total FROM visit_counter");
$visit_count = $visit_result->fetch_assoc()['total'];

$recent_activities = [];

// 1. EVENTS
$events = $conn_events->query("SELECT event_name, created_at FROM upcoming_events WHERE status = 'active' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY created_at DESC");
while ($row = $events->fetch_assoc()) {
    $date = date("M d, Y", strtotime($row['created_at']));
    $recent_activities[] = [
        'type' => '✅ Event',
        'title' => $row['event_name'],
        'date' => $date
    ];
}

// 2. GALLERY
$galleries = $conn_gallery->query("SELECT title, uploaded_at FROM gallery WHERE uploaded_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY uploaded_at DESC");
while ($row = $galleries->fetch_assoc()) {
    $date = date("M d, Y", strtotime($row['uploaded_at']));
    $recent_activities[] = [
        'type' => '📸 Gallery',
        'title' => $row['title'],
        'date' => $date
    ];
}

// 3. NEWS
$news_articles = $conn_news->query("SELECT news_title, publish_date FROM news_announcements WHERE status = 'active' AND publish_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY publish_date DESC");
while ($row = $news_articles->fetch_assoc()) {
    $date = date("M d, Y", strtotime($row['publish_date']));
    $recent_activities[] = [
        'type' => '📰 News',
        'title' => $row['news_title'],
        'date' => $date
    ];
}

// 4. INQUIRIES
$inquiries = $conn_contact->query("SELECT full_name, submitted_at FROM contact_inquiries WHERE archived = 0 AND submitted_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY submitted_at DESC");
while ($row = $inquiries->fetch_assoc()) {
    $date = date("M d, Y", strtotime($row['submitted_at']));
    $recent_activities[] = [
        'type' => '❓ Inquiry',
        'title' => $row['full_name'],
        'date' => $date
    ];
}

// Sort all activities by date descending
usort($recent_activities, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

// Group by date
$grouped = [];
foreach ($recent_activities as $activity) {
    $grouped[$activity['date']][] = $activity;
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="Css/dashboard.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="create_event.html"><i class="fas fa-calendar-plus"></i> Create Event</a></li>
                <li><a href="manage_upcoming.php"><i class="fas fa-calendar-check"></i> Manage Events</a></li>
                <li><a href="archived_events.php"><i class="fas fa-archive"></i> Archived Events</a></li>
                <li><a href="create_news.html"><i class="fas fa-newspaper"></i> Create News & Announcements</a></li>
                <li><a href="manage_news.php"><i class="fas fa-edit"></i> Manage News & Announcements</a></li>
                <li><a href="archived_news.php"><i class="fas fa-history"></i> Archived News</a></li>
                <li><a href="admin_gallery.php"><i class="fas fa-images"></i> Manage Gallery Page</a></li>
                <li><a href="editInlinePage.php"><i class="fas fa-skating"></i> Manage Inline Page</a></li>
                <li><a href="editBmxPage.php"><i class="fas fa-bicycle"></i> Manage BMX Page</a></li>
                <li><a href="editSkateboardPage.php"><i class="fas fa-snowboarding"></i> Manage Skateboard Page</a></li>
                <li><a href="view_inquiries.php"><i class="fas fa-question-circle"></i> Inquiries</a></li>
                <li><a href="archived_inquiries.php"><i class="fas fa-archive"></i> Archived Inquiries</a></li>
                <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
        <main class="content" id="dashboard">
        <h2>Welcome to the Admin Dashboard</h2>
        <div class="dashboard-cards">
        <div class="card">
            <i class="fas fa-calendar-plus"></i>
            <h3>Total Events</h3>
            <p><?php echo $events_count; ?></p>
        </div>
        <div class="card">
            <i class="fas fa-newspaper"></i>
            <h3>News Articles</h3>
            <p><?php echo $news_count; ?></p>
        </div>
        <div class="card">
            <i class="fas fa-images"></i>
            <h3>Gallery Items</h3>
            <p><?php echo $gallery_count; ?></p>
        </div>
        <div class="card">
            <i class="fas fa-question-circle"></i>
            <h3>New Inquiries</h3>
            <p><?php echo $inquiry_count; ?></p>
        </div>
        <div class="card">
            <i class="fas fa-users"></i>
            <h3>Total Registration</h3>
            <p><?php echo $registration_count; ?></p>
        </div>
        <div class="card">
            <i class="fas fa-eye"></i>
            <h3>Total Visits</h3>
            <p><?php echo $visit_count; ?></p>
        </div>
        </div>

        <div class="recent-activity">
            <h3>Recent Activity (Past Week)</h3>
            <ul>
                <?php foreach ($grouped as $date => $activities): ?>
                    <li>
                        <strong><?php echo $date; ?></strong>
                        <ul>
                            <li><?php echo $activities[0]['type'] . ' "' . $activities[0]['title'] . '"'; ?></li>
                            <?php if (count($activities) > 1): ?>
                                <div class="toggle-container">
                                    <button onclick="toggleActivities(this)">⬇ Show More</button>
                                    <ul class="extra-activities" style="display: none;">
                                        <?php for ($i = 1; $i < count($activities); $i++): ?>
                                            <li><?php echo $activities[$i]['type'] . ' "' . $activities[$i]['title'] . '"'; ?></li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        </main>
    </div>
    <script>
        function toggleActivities(button) {
            const extra = button.nextElementSibling;
            if (extra.style.display === "none") {
                extra.style.display = "block";
                button.textContent = "⬆ Show Less";
            } else {
                extra.style.display = "none";
                button.textContent = "⬇ Show More";
            }
        }
        </script>
</body>
</html>
