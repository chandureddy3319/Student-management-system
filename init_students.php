<?php
// Run this script ONCE to populate the students table from students.js if empty
require_once 'db.php';

// Check if students table is empty
$res = $conn->query('SELECT COUNT(*) as cnt FROM students');
$row = $res->fetch_assoc();
if ($row['cnt'] > 0) {
    echo 'Students table already populated.';
    exit;
}

// Load students.js data
$js = file_get_contents('../js/students.js');
$matches = [];
preg_match('/\[(.*?)\]/s', $js, $matches);
if (!$matches) {
    echo 'Could not parse students.js.';
    exit;
}
$json = '[' . $matches[1] . ']';
$students = json_decode($json, true);
if (!$students) {
    echo 'Invalid JSON in students.js.';
    exit;
}

$stmt = $conn->prepare('INSERT INTO students (name, department, semester, email, image_url) VALUES (?, ?, ?, ?, ?)');
foreach ($students as $s) {
    $stmt->bind_param('sssss', $s['name'], $s['department'], $s['semester'], $s['email'], $s['image_url']);
    $stmt->execute();
}
$stmt->close();
$conn->close();
echo 'Inserted ' . count($students) . ' students.';
?> 