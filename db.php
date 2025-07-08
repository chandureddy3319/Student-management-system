<?php
// db.php - Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'student_management';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
// Use $conn in other PHP scripts
?> 