<?php
// Database configuration
$host = "localhost";      // Host name (usually localhost)
$username = "root";       // Default username for XAMPP/WAMP
$password = "";           // Default password is empty in XAMPP/WAMP
$database = "sql_php_db";  // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
?>