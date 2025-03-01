<?php
$host = "localhost"; // Change if using a different host
$username = "root"; // Change if using a different username
$password = ""; // Change if you have a password set
$database = "basf_content"; // The database name you created

$conn = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update About Us
    $about_us = $_POST['about_us'];
    mysqli_query($conn, "UPDATE page_content SET content='$about_us' WHERE section='about_us'");

    // Insert Highlight Carousel Videos
    if (!empty($_FILES['highlight_video']['name'])) {
        $video = $_FILES['highlight_video']['name'];
        $video_tmp = $_FILES['highlight_video']['tmp_name'];
        move_uploaded_file($video_tmp, "uploads/videos/$video");
        $title = $_POST['highlight_title'];
        $description = $_POST['highlight_description'];
        mysqli_query($conn, "INSERT INTO highlight_carousel (video, title, description) VALUES ('$video', '$title', '$description')");
    }

    // Insert Top Athletes
    if (!empty($_FILES['athlete_image']['name'])) {
        $athlete_img = $_FILES['athlete_image']['name'];
        move_uploaded_file($_FILES['athlete_image']['tmp_name'], "uploads/athletes/$athlete_img");
        $athlete_name = $_POST['athlete_name'];
        $athlete_desc = $_POST['athlete_description'];
        mysqli_query($conn, "INSERT INTO top_athletes (image, name, description) VALUES ('$athlete_img', '$athlete_name', '$athlete_desc')");
    }

    // Insert Community Leaders
    if (!empty($_FILES['leader_image']['name'])) {
        $leader_img = $_FILES['leader_image']['name'];
        move_uploaded_file($_FILES['leader_image']['tmp_name'], "uploads/leaders/$leader_img");
        $leader_name = $_POST['leader_name'];
        $leader_role = $_POST['leader_role'];
        mysqli_query($conn, "INSERT INTO community_leaders (image, name, role) VALUES ('$leader_img', '$leader_name', '$leader_role')");
    }

    // Insert Partnerships
    if (!empty($_FILES['partner_logo']['name'])) {
        $partner_logo = $_FILES['partner_logo']['name'];
        move_uploaded_file($_FILES['partner_logo']['tmp_name'], "uploads/partners/$partner_logo");
        mysqli_query($conn, "INSERT INTO partnerships (logo) VALUES ('$partner_logo')");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="Css/editInlinePage.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
                    <h2>Admin Dashboard</h2>
                    <ul>
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li><a href="admin.html">Create Event</a></li>
                        <li><a href="manage_upcoming.php">Manage Events</a></li>
                        <li><a href="archived_events.php">Archived Events</a></li>
                        <li><a href="create_news.html">Create News & Announcements</a></li>
                        <li><a href="manage_news.php">Manage News & Announcements</a></li>
                        <li><a href="archived_news.php">Archived News</a></li>
                        <li><a href="view_inquiries.php">Inquiries</a></li>
                        <li><a href="archived_inquiries.php">Archived Inquiries</a></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </nav>
            <h2>Manage Inline Page</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <h3>About Us</h3>
                <textarea name="about_us" required></textarea>
                
                <h3>Highlight Carousel</h3>
                <input type="file" name="highlight_video" accept="video/*" required>
                <input type="text" name="highlight_title" placeholder="Video Title" required>
                <textarea name="highlight_description" placeholder="Video Description" required></textarea>
                
                <h3>Top Athletes</h3>
                <input type="file" name="athlete_image" accept="image/*" required>
                <input type="text" name="athlete_name" placeholder="Athlete Name" required>
                <textarea name="athlete_description" placeholder="Athlete Description" required></textarea>

                <h3>About the Player</h3>
                <textarea name="athlete_bio" placeholder="Write about the athlete" required></textarea>

                <h4>Player Statistics</h4>
                <input type="number" name="athlete_wins" placeholder="Total Wins" required>
                <input type="number" name="athlete_podium" placeholder="Podium Finishes" required>
                <input type="number" name="athlete_years_active" placeholder="Years Active" required>
                <input type="text" name="athlete_specialty" placeholder="Specialty (e.g., Speed Skating, Freestyle)" required>

                <h3>Achievements</h3>
                <div id="achievements-container">
                    <div class="achievement-item">
                        <input type="text" name="achievement_title[]" placeholder="Achievement Title" required>
                        <textarea name="achievement_desc[]" placeholder="Achievement Description" required></textarea>
                    </div>
                </div>
                <button type="button" id="add-achievement">Add More Achievements</button>

                <h3>Gallery</h3>
                <div id="gallery-container">
                    <div class="gallery-item">
                        <input type="file" name="athlete_gallery[]" accept="image/*" required>
                        <input type="text" name="gallery_description[]" placeholder="Image Description" required>
                    </div>
                </div>
                <button type="button" id="add-gallery">Add More Images</button>
                
                <h3>Community Leaders</h3>
                <div id="leaders-container">
                    <div class="leader-item">
                        <input type="file" name="leader_image[]" accept="image/*" required>
                        <input type="text" name="leader_name[]" placeholder="Leader Name" required>
                        <input type="text" name="leader_role[]" placeholder="Leader Role" required>
                        <textarea name="leader_bio[]" placeholder="Leader Bio" required></textarea>
                    </div>
                </div>
                <button type="button" id="add-leader">Add More Leaders</button>
                
                <h3>Partnerships</h3>
                <div id="partnerships-container">
                    <div class="partnership-item">
                        <input type="file" name="partner_logo[]" accept="image/*" required>
                        <input type="text" name="partner_name[]" placeholder="Partner Name" required>
                        <input type="text" name="partner_website[]" placeholder="Partner Website (Optional)">
                    </div>
                </div>
                <button type="button" id="add-partner">Add More Partners</button>
                                
                <button type="submit">Save Changes</button>
            </form>
    </div>
    <script>
        document.getElementById("add-achievement").addEventListener("click", function () {
            let container = document.getElementById("achievements-container");
            let newAchievement = document.createElement("div");
            newAchievement.classList.add("achievement-item");
            newAchievement.innerHTML = `
                <input type="text" name="achievement_title[]" placeholder="Achievement Title" required>
                <textarea name="achievement_desc[]" placeholder="Achievement Description" required></textarea>
                <button type="button" class="remove-achievement">Remove</button>
            `;
            container.appendChild(newAchievement);
        });

        document.getElementById("add-gallery").addEventListener("click", function () {
            let container = document.getElementById("gallery-container");
            let newGallery = document.createElement("div");
            newGallery.classList.add("gallery-item");
            newGallery.innerHTML = `
                <input type="file" name="athlete_gallery[]" accept="image/*" required>
                <button type="button" class="remove-gallery">Remove</button>
            `;
            container.appendChild(newGallery);
        });

        // Remove achievement entry
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-achievement")) {
                e.target.parentElement.remove();
            }
        });

        // Remove gallery entry
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-gallery")) {
                e.target.parentElement.remove();
            }
        });

        document.getElementById("add-leader").addEventListener("click", function () {
        let container = document.getElementById("leaders-container");
        let newLeader = document.createElement("div");
        newLeader.classList.add("leader-item");
        newLeader.innerHTML = `
            <input type="file" name="leader_image[]" accept="image/*" required>
            <input type="text" name="leader_name[]" placeholder="Leader Name" required>
            <input type="text" name="leader_role[]" placeholder="Leader Role" required>
            <textarea name="leader_bio[]" placeholder="Leader Bio" required></textarea>
            <button type="button" class="remove-leader">Remove</button>
        `;
        container.appendChild(newLeader);
    });

    // Remove leader entry
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-leader")) {
            e.target.parentElement.remove();
        }
    });

    document.getElementById("add-partner").addEventListener("click", function () {
        let container = document.getElementById("partnerships-container");
        let newPartner = document.createElement("div");
        newPartner.classList.add("partnership-item");
        newPartner.innerHTML = `
            <input type="file" name="partner_logo[]" accept="image/*" required>
            <input type="text" name="partner_name[]" placeholder="Partner Name" required>
            <input type="text" name="partner_website[]" placeholder="Partner Website (Optional)">
            <button type="button" class="remove-partner">Remove</button>
        `;
        container.appendChild(newPartner);
    });

    // Remove partnership entry
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-partner")) {
            e.target.parentElement.remove();
        }
    });
    </script>
</body>
</html>
