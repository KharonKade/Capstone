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
            <title>Registration Successful</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 50px; text-align: center; }
                .token-box {
                    background: #fff;
                    padding: 20px;
                    display: inline-block;
                    border-radius: 10px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                .token {
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: #2c3e50;
                    margin: 10px 0;
                }
                .copy-btn {
                    background: #3498db;
                    color: #fff;
                    border: none;
                    padding: 10px 15px;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .copy-btn:hover {
                    background: #2980b9;
                }
            </style>
        </head>
        <body>
            <div class='token-box'>
                <h2>Registration Successful!</h2>
                <p>Your token is:</p>
                <div class='token' id='tokenText'>$token</div>
                <button class='copy-btn' onclick='copyToken()'>Copy Token</button>
                <br><br>
                <a href='eventPages.php?id=$event_id'>Back to Event Page</a>
            </div>

            <script>
                function copyToken() {
                    const token = document.getElementById('tokenText').textContent;
                    navigator.clipboard.writeText(token).then(function() {
                        alert('Token copied to clipboard!');
                    }, function(err) {
                        alert('Failed to copy token: ', err);
                    });
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
