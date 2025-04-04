<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS');
    $mail->SMTPSecure = getenv('SMTP_SECURE');
    $mail->Port = getenv('SMTP_PORT');

    // Email setup
    $mail->setFrom(getenv('SMTP_USER'), 'Event Registration');
    $mail->addAddress($email, $name);
    $mail->Subject = 'Verify Your Email';
    $mail->Body = "Hello $name,\n\nPlease click the link below to verify your email:\n\nhttp://yourdomain.com/verify_email.php?token=$verification_token\n\nThank you!";
    
    $mail->send();
    echo "A verification email has been sent to $email. Please check your inbox.";
} catch (Exception $e) {
    echo "Email sending failed: " . $mail->ErrorInfo;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$category = $_POST['category'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($gender) || empty($category) || empty($event_id)) {
    die("Error: Please fill all the required fields.");
}

// Generate a unique verification token
$verification_token = bin2hex(random_bytes(32));

// Insert user data into database with status 'pending'
$sql = "INSERT INTO event_registrations (event_id, name, email, phone, age, gender, category, status, verification_token) 
        VALUES ('$event_id', '$name', '$email', '$phone', '$age', '$gender', '$category', 'pending', '$verification_token')";

if ($conn->query($sql) === TRUE) {
    $verification_link = "http://yourdomain.com/verify_email.php?token=$verification_token";

    // Send verification email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change to your mail server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@gmail.com'; // Your email
        $mail->Password = 'your_email_password'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Event Registration');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Verify Your Email';
        $mail->Body = "Hello $name,\n\nPlease verify your email by clicking the link below:\n\n$verification_link\n\nThank you!";

        $mail->send();
        echo "A verification email has been sent. Please check your inbox.";
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
