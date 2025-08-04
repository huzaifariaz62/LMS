<?php
// This file creates a new, secure password hash for 'password123'
// It is guaranteed to be compatible with your server environment.

$passwordToHash = 'password123';
$newHash = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "<h1>Your New Admin Password Hash</h1>";
echo "<p>The password to be hashed is: <strong>" . htmlspecialchars($passwordToHash) . "</strong></p>";
echo "<p>Copy the ENTIRE hash string below (it starts with '$2y$10...'):</p>";
echo "<hr>";
echo "<strong style='font-size: 1.2em; color: blue;'>" . htmlspecialchars($newHash) . "</strong>";
echo "<hr>";
?>