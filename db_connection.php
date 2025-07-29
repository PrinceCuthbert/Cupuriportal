<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP password is empty
$database = 'cupuri_portal';

$conn = new mysqli($host, $user, $password, $database);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
