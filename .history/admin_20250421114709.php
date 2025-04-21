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
        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-calendar-plus"></i>
                <h3>Total Events</h3>
                <p>25</p>
            </div>
            <div class="card">
                <i class="fas fa-newspaper"></i>
                <h3>News Articles</h3>
                <p>12</p>
            </div>
            <div class="card">
                <i class="fas fa-images"></i>
                <h3>Gallery Items</h3>
                <p>48</p>
            </div>
            <div class="card">
                <i class="fas fa-question-circle"></i>
                <h3>New Inquiries</h3>
                <p>5</p>
            </div>
        </div>

        <div class="recent-activity">
            <h3>Recent Activity</h3>
            <ul>
                <li>✅ Event "Go Skate Day 2025" was added</li>
                <li>📸 5 new gallery images uploaded</li>
                <li>📰 News article "Local Skater Wins" posted</li>
                <li>❓ Inquiry from "John Doe" received</li>
            </ul>
        </div>
        </main>
    </div>
</body>
</html>
