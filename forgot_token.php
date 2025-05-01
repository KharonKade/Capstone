<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "basf_events";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Search for registration by email
    $sql = "SELECT token FROM event_registrations WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $token = $row['token'];

        echo json_encode([
            "success" => true,
            "token" => $token
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No registration found for that email address."
        ]);
    }

    $conn->close();
    exit;
}
?>

<!-- HTML form for retrieving token -->
<form id="forgotTokenForm">
    <label for="email">Enter your email to retrieve your token:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Retrieve Token</button>
</form>

<script>
    document.getElementById('forgotTokenForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);

        fetch('forgot_token.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (data.success) {
                alert('Your token is: ' + data.token);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Something went wrong. Please try again.');
        });
    });
</script>
