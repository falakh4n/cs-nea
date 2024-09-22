<?php

// Fetch the email from POST request
$email = $_POST["email"];

// Generate a random token and hash it with MD5
$token = bin2hex(random_bytes(16));
$token_hash = md5($token); // Changed to MD5

// Set token expiry time (30 minutes from now)
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'login';

$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Prepare the SQL statement
$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);

// Bind parameters and execute statement
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

// Include CSS for styling
echo '<link rel="stylesheet" type="text/css" href="message.css">';

// Check for successful update
if ($stmt->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";
    
    $mail->setFrom("no-reply@yourdomain.com", "Guess Grid");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset for Guess Grid";
    $mail->Body = <<<END
    Click <a href="http://localhost/login/reset-password.php?token=$token">here</a> 
    to reset your password.
    END;

    try {
        $mail->send();
        // Display success message and a link to go back to login
        echo '
        <body>
            <div class="message-container">
                <div class="message">
                    Message sent, please check your inbox.
                </div>
                <a href="index.php" class="back-button">Back to Login</a>
            </div>
        </body>';
    } catch (Exception $e) {
        // Display error message and a link to go back to login
        echo '
        <body>
            <div class="message-container">
                <div class="message error">
                    Message could not be sent. Mailer error: ' . $mail->ErrorInfo . '
                </div>
                <a href="index.php" class="back-button">Back to Login</a>
            </div>
        </body>';
    }
} else {
    // Display error message if the database update failed
    echo '
    <body>
        <div class="message-container">
            <div class="message error">
                Could not update the user details. Please try again later.
            </div>
            <a href="login.html" class="back-button">Back to Login</a>
        </div>
    </body>';
}

?>
