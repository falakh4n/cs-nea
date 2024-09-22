<?php

$token = $_GET["token"];

// Hash the token using MD5
$token_hash = md5($token);

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

// Prepare and execute the query
$sql = "SELECT * FROM users WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

// Bind the parameters
$stmt->bind_param("s", $token_hash);

$stmt->execute();

// Fetch the result
$result = $stmt->get_result();

$user = $result->fetch_assoc();

// Check if the user was found
if ($user === null) {
    die("Token not found");
}

// Check if the token has expired
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="register.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <form method="post" action="process-reset-password.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your new password">

            <label for="password_confirmation">Repeat Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat your new password">

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
