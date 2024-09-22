<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';

// Use TLS or SSL
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // For TLS
$mail->Port = 587; // TLS port (465 for SSL)

// Authentication
$mail->Username = 'no.replyguesssgrid@gmail.com';
$mail->Password = 'rezd gjsi szqj uylb';

// Email format
$mail->isHTML(true);

// Return the PHPMailer instance
return $mail;
