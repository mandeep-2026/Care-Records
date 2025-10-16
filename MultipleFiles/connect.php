<?php
// connect.php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'health';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    // Log the error safely (doesn't break output)
    error_log("Database connection failed: " . mysqli_connect_error());
    
    // Simple fallback alert + stop execution
    echo "<script>alert('Unable to connect to the database. Please try again later.');</script>";
    exit;
}

// Set charset for proper encoding
mysqli_set_charset($conn, "utf8mb4");
?>
