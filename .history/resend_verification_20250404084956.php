<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure PHPMailer is included

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'] ?? '';

if (empty($email)) {
    die("Please enter your email.");
}

$sql = "SELECT name, verification_token, status FROM event_registrations WHERE email = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['status'] === 'verified') {
        echo "This email is already verified. No need to resend.";
    } else {
        $name = $row['name'];
        $token = $row['verification_token'];
        $verification_link = "http://yourdomain.com/verify_email.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_email_password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Event Registration');
            $mail->addAddress($email, $name);
            $mail->Subject = 'Resend: Verify Your Email';
            $mail->Body = "Hello $name,\n\nHere is your verification link again:\n\n$verification_link\n\nThank you!";

            $mail->send();
            echo "Verification email has been resent. Please check your inbox.";
        } catch (Exception $e) {
            echo "Failed to resend email: {$mail->ErrorInfo}";
        }
    }
} else {
    echo "No registration found for that email.";
}

$stmt->close();
$conn->close();
?>
