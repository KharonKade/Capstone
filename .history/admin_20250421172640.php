<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="Css/dashboard.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <div class="chart-section">
            <h3>Event Forecast (Past & Predicted)</h3>
            <canvas id="forecastChart" width="400" height="200"></canvas>
        </div>


        <div class="chart-section">
            <h3>Event Stats Overview</h3>
            <canvas id="eventChart" width="200" height="100"></canvas>
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

    <script>
        const ctx = document.getElementById('eventChart').getContext('2d');
        const eventChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Skateboard', 'BMX', 'Inline'],
                datasets: [{
                    label: 'Total Events by Sport',
                    data: [12, 7, 6], // Example data — replace with PHP or AJAX dynamically
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e67e22'
                    ],
                    borderColor: [
                        '#2980b9',
                        '#27ae60',
                        '#d35400'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('forecastChart').getContext('2d');

            const pastData = [5, 7, 6, 9];
            const avg = Math.round((pastData.slice(-3).reduce((a, b) => a + b, 0)) / 3);
            const predictedData = [avg, avg + 1, avg + 2];

            const forecastChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Past Events',
                            data: pastData,
                            borderColor: '#2980b9',
                            backgroundColor: 'transparent',
                            borderWidth: 2
                        },
                        {
                            label: 'Predicted Events',
                            data: [...new Array(pastData.length).fill(null), ...predictedData],
                            borderColor: '#e67e22',
                            borderDash: [5, 5],
                            backgroundColor: 'transparent',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>



</body>
</html>
