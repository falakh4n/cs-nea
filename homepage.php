<?php
session_start();
include 'connect.php'; // Include your database connection file

// Handle login
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Note: MD5 is not recommended for production

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['email'] = $email;
        header("Location: wordle.php"); // Redirect to the Wordle game page
        exit();
    } else {
        $loginError = "Incorrect Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to login CSS -->
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign In" name="signIn">
            <?php if (isset($loginError)) echo "<p>$loginError</p>"; ?>
        </form>
    </div>
</body>
</html>
