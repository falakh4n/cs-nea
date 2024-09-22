<?php
session_start();
include("connect.php");

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signUp'])) {
        // Registration process
        $fName = sanitize_input($_POST['fName']);
        $lName = sanitize_input($_POST['lName']);
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the query
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fName, $lName, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($_POST['signIn'])) {
        // Login process
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT id, firstName, lastName, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Password is correct
                $_SESSION['email'] = $email;
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];
                header("Location: homepage.php");
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
