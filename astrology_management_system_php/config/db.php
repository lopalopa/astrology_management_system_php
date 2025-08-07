<?php
// Database configuration
$host     = "localhost";         // Server host (usually localhost)
$username = "root";              // Database username (default for XAMPP is 'root')
$password = "";                  // Database password (default is empty in XAMPP)
$database = "astrology_db";      // Name of your database

// Create connection using MySQLi (object-oriented)
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset to UTF-8
$conn->set_charset("utf8");

// Optional: Echo a success message (only during testing)
// echo "Database connected successfully!";
?>
