<?php

$conn = new mysqli("localhost", "root", "", "basf_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$news_title = $_POST['news_title'] ?? '';
$news_content = $_POST['description'] ?? '';  // Change this to 'description'
$category = $_POST['category'] ?? 'General';
$publish_date = $_POST['news_date'] ?? '';  // Also match the correct field name

// Validate form inputs
if (empty($news_title) || empty($news_content) || empty($publish_date)) {
    die("Error: Please fill all the required fields.");
}

// Insert main news data
$sql = "INSERT INTO news_announcements (news_title, news_content, category, publish_date) 
        VALUES ('$news_title', '$news_content', '$category', '$publish_date')";
if ($conn->query($sql) === TRUE) {
    $news_id = $conn->insert_id;

    // Upload posters (news images)
    if (!empty($_FILES['image']['tmp_name'])) {  // Corrected field name from 'news_images' to 'image'
        // Loop through uploaded files (if multiple images are allowed)
        foreach ($_FILES['image']['tmp_name'] as $index => $tmp_name) {
            // Define the image path
            $image_path = "images/uploads/" . basename($_FILES['image']['name']);  // Corrected file path construction
    
            // Debugging: Check if file is uploaded successfully
            if (move_uploaded_file($tmp_name, $image_path)) {
                echo "File uploaded: " . $image_path;  // Debugging line: Prints the uploaded file path
                
                // Insert image into the database
                $image_sql = "INSERT INTO news_images (news_id, image_path) VALUES ('$news_id', '$image_path')";
                if ($conn->query($image_sql) === TRUE) {
                    echo "Image inserted into database successfully!";
                } else {
                    echo "Error inserting image: " . $conn->error;  // Error message if the insert fails
                }
            } else {
                echo "Failed to upload: " . $_FILES['image']['name'];  // Debugging line: Prints the failed file name
            }
        }
    }
    


    // Display success message in JavaScript
    echo "<script type='text/javascript'>
            alert('News and Announcement created successfully!');
            window.location.href = 'admin.html'; // Redirect to admin dashboard after creation
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
