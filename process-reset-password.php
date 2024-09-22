<?php
// Fetch the email from POST request
$email = $_POST["email"];
$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];

// Hash the token
$token_hash = md5($_POST["token"]); // Use MD5 for token hashing

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

// Prepare and execute the query to find the user by token
$sql = "SELECT id, reset_token_expires_at FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();
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

// Validate the new password
if (strlen($password) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $password)) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $password)) {
    die("Password must contain at least one number");
}

if ($password !== $password_confirmation) {
    die("Passwords must match");
}

// Hash the new password
$password_hash = md5($password); // Use MD5 for password hashing

// Prepare and execute the query to update the password
$sql = "UPDATE users
        SET password = ?, 
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind parameters (password_hash is a string, id is an integer)
$stmt->bind_param("si", $password_hash, $user["id"]);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        // Password updated successfully
        // Redirect to login page
        header("Location: index.php"); // Adjust the path if necessary
        exit();
    } else {
        echo "Password update failed or no changes made.";
    }
} else {
    echo "Error updating password: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$mysqli->close();
?>
