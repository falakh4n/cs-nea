<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot.css"> <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form method="post" action="send-password-reset.php">
            <div class="input-wrapper">
                <i class="fas fa-key"></i>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>
            <button type="submit"><i class="fas fa-paper-plane"></i> Send</button>
        </form>
    </div>
</body>
</html>
