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

if ($conn->query($registration_sql) === TRUE) {
    echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Token Popup</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: rgba(0,0,0,0.4);
                    margin: 0;
                }
                .popup {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
                    text-align: center;
                    z-index: 1000;
                }
                .popup h2 {
                    margin-top: 0;
                }
                .token {
                    font-weight: bold;
                    margin: 10px 0;
                    font-size: 1.2rem;
                }
                .copy-btn {
                    background: #3498db;
                    color: #fff;
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .copy-btn:hover {
                    background: #2980b9;
                }
                .ok-btn {
                    margin-top: 15px;
                    padding: 8px 20px;
                    background: #2ecc71;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .ok-btn:hover {
                    background: #27ae60;
                }
            </style>
        </head>
        <body>
            <div class='popup'>
                <h2>Registration Successful!</h2>
                <p>Your token is:</p>
                <div class='token' id='tokenText'>$token</div>
                <button class='copy-btn' onclick='copyToken()'>Copy</button><br>
                <button class='ok-btn' onclick='goBack()'>OK</button>
            </div>

            <script>
                function copyToken() {
                    const token = document.getElementById('tokenText').textContent;
                    navigator.clipboard.writeText(token)
                        .then(() => alert('Token copied to clipboard!'))
                        .catch(() => alert('Failed to copy token.'));
                }
                function goBack() {
                    window.location.href = 'eventPages.php?id=$event_id';
                }
            </script>
        </body>
        </html>
        ";

} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
