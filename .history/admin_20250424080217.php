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

// Analyze registrations for predictive analysis
$day_freq = [];
$month_freq = [];

$day_query = $conn_events->query("SELECT registration_date FROM event_registrations");
while ($row = $day_query->fetch_assoc()) {
    $date = new DateTime($row['registration_date']);
    $day = $date->format('l');      // Full weekday name
    $month = $date->format('F');    // Full month name

    // Count frequency
    $day_freq[$day] = ($day_freq[$day] ?? 0) + 1;
    $month_freq[$month] = ($month_freq[$month] ?? 0) + 1;
}

// Sort to get top active days and months
arsort($day_freq);
arsort($month_freq);

// Get top 2 days and top 3 months
$top_days = array_slice(array_keys($day_freq), 0, 2);
$top_months = array_slice(array_keys($month_freq), 0, 3);


// Track visits
$visit_conn = new mysqli("localhost", "root", "", "basf_visits");

if ($visit_conn->connect_error) {
    die("Connection failed: " . $visit_conn->connect_error);
}

$visit_result = $visit_conn->query("SELECT COUNT(*) as total FROM visit_counter");
$visit_count = $visit_result->fetch_assoc()['total'];

$visit_conn->close();

// Get Monday to Sunday of current week
$monday = new DateTime('monday this week');
$monday->setTime(0, 0, 0);
$sunday = new DateTime('sunday this week');
$sunday->setTime(23, 59, 59);

$start_date = $monday->format('Y-m-d H:i:s');
$end_date = $sunday->format('Y-m-d H:i:s');

$activities = [];

// Collect events
$result_events = $conn_events->query("SELECT event_name AS title, created_at AS time FROM upcoming_events WHERE status = 'active' AND created_at BETWEEN '$start_date' AND '$end_date'");
while ($row = $result_events->fetch_assoc()) {
    $activities[] = ['type' => 'Event', 'title' => $row['title'], 'time' => $row['time'], 'emoji' => '✅'];
}

// Collect news
$result_news = $conn_news->query("SELECT news_title AS title, publish_date AS time FROM news_announcements WHERE status = 'active' AND publish_date BETWEEN '$start_date' AND '$end_date'");
while ($row = $result_news->fetch_assoc()) {
    $activities[] = ['type' => 'News', 'title' => $row['title'], 'time' => $row['time'], 'emoji' => '📰'];
}

// Collect gallery
$result_gallery = $conn_gallery->query("SELECT title, uploaded_at AS time FROM gallery WHERE uploaded_at BETWEEN '$start_date' AND '$end_date'");
while ($row = $result_gallery->fetch_assoc()) {
    $activities[] = ['type' => 'Gallery', 'title' => $row['title'], 'time' => $row['time'], 'emoji' => '📸'];
}

// Collect inquiries
$result_inquiry = $conn_contact->query("SELECT full_name AS title, submitted_at AS time FROM contact_inquiries WHERE archived = 0 AND submitted_at BETWEEN '$start_date' AND '$end_date'");
while ($row = $result_inquiry->fetch_assoc()) {
    $activities[] = ['type' => 'Inquiry', 'title' => $row['title'], 'time' => $row['time'], 'emoji' => '❓'];
}

// Sort all activities by time DESC
usort($activities, fn($a, $b) => strtotime($b['time']) - strtotime($a['time']));

// Group by day (Monday–Sunday)
$grouped = [];
foreach ($activities as $activity) {
    $date = date('l, F j', strtotime($activity['time']));
    $grouped[$date][] = $activity;
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
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
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

        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-calendar-day"></i>
                <h3>Most Active Days</h3>
                <p><?php echo implode(' and ', $top_days); ?></p>
            </div>
            <div class="card">
                <i class="fas fa-calendar-alt"></i>
                <h3>Peak Months</h3>
                <p><?php echo implode(', ', $top_months); ?></p>
            </div>
        </div>

        <div id="calendar" style="margin-top: 40px;"></div>

        <div class="recent-activity">
            <h3>Recent Activity (<?= $monday->format('F j') ?> – <?= $sunday->format('F j, Y') ?>)</h3>
            <ul>
                <?php foreach ($grouped as $date => $activities): ?>
                    <li>
                        <ul>
                            <!-- First 5 activities -->
                            <?php for ($i = 0; $i < min(5, count($activities)); $i++): ?>
                                <li>
                                    <?php
                                        $a = $activities[$i];
                                        echo $a['emoji'] . ' ' . $a['type'] . ' "' . htmlspecialchars($a['title']) . '" was added on ' . date("M d, Y", strtotime($a['time']));
                                    ?>
                                </li>
                            <?php endfor; ?>

                            <!-- Extra activities toggle -->
                            <?php if (count($activities) > 5): ?>
                                <div class="toggle-container">
                                    <button onclick="toggleActivities(this)">⬇ Show More</button>
                                    <ul class="extra-activities" style="display: none;">
                                        <?php for ($i = 5; $i < count($activities); $i++): ?>
                                            <li>
                                                <?php
                                                    $a = $activities[$i];
                                                    echo $a['emoji'] . ' ' . $a['type'] . ' "' . htmlspecialchars($a['title']) . '" was added on ' . date("M d, Y", strtotime($a['time']));
                                                ?>
                                            </li>
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

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: 'calendar_event.php', // path to your PHP file that echoes JSON
            eventColor: '#3788d8'
            });

            calendar.render();
        });
        </script>
</body>
</html>
