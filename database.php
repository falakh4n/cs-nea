<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'login';

// Create a new mysqli instance
$mysqli = new mysqli($host, $user, $pass, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Return the mysqli instance
return $mysqli;
?>
