<?php
// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Your MySQL username (default is root)
define('DB_PASSWORD', '');     // Your MySQL password (default is empty)
define('DB_NAME', 'medico');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Set charset to utf8mb4 for full character support
$conn->set_charset("utf8mb4");
?>
