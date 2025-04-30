<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "basf_events";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// reCAPTCHA verification
$recaptcha_secret = '6LezuAorAAAAADinMO5ygVph7jNNtovpEL2t42Tj';
$recaptcha_response = $_POST['g-recaptcha-response'];

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$response_data = json_decode($verify);

if (!$response_data->success) {
    die("reCAPTCHA verification failed. Please go back and try again.");
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$category = $_POST['category'] ?? '';
$event_id = $_POST['event_id'] ?? 0;

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($age) || empty($gender) || empty($category) || empty($event_id)) {
    die("Error: Please fill all the required fields.");
}

function generateToken($length = 6) {
    $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz'; // excludes 0, O, I, l, 1
    $charactersLength = strlen($characters);
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $token;
}

$token = generateToken();

// Insert registration data into the database
$registration_sql = "INSERT INTO event_registrations (event_id, name, email, phone, age, gender, category, token) 
                     VALUES ('$event_id', '$name', '$email', '$phone', '$age', '$gender', '$category', '$token')";

header('Content-Type: application/json');

if ($conn->query($registration_sql) === TRUE) {
    echo json_encode([
        "success" => true,
        "token" => $token
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $conn->error
    ]);
}
$conn->close();

?>
