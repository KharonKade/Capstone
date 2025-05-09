<?php
header('Content-Type: application/json');
ob_start(); // Start output buffering early

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// reCAPTCHA verification
$recaptcha_secret = '6LezuAorAAAAADinMO5ygVph7jNNtovpEL2t42Tj';
$recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

$verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$captcha_data = json_decode($verify_response, true);

if (!$captcha_data['success']) {
    echo json_encode(["success" => false, "message" => "reCAPTCHA verification failed."]);
    exit;
}

// Get and validate form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$category = $_POST['category'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($gender) || empty($category) || empty($event_id)) {
    echo json_encode(["success" => false, "message" => "Please fill all the required fields."]);
    exit;
}

// Generate unique token
function generateToken($length = 6) {
    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz';
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $token;
}

$token = generateToken();

// Use prepared statements to avoid SQL injection
$stmt = $conn->prepare("INSERT INTO event_registrations (event_id, name, email, phone, age, gender, category, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssisss", $event_id, $name, $email, $phone, $age, $gender, $category, $token);

if ($stmt->execute()) {
    ob_end_clean(); // Clean output buffer before sending JSON
    echo json_encode(["success" => true, "token" => $token]);
    exit;
} else {
    ob_end_clean();
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    exit;
}

$stmt->close();
$conn->close();
?>
