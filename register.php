<?php
require_once 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';
$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}
if (strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
}
if (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
}

if (empty($errors)) {
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = 'Email or username already exists.';
    }
    $stmt->close();
}

if (empty($errors)) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare('INSERT INTO users (email, username, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $email, $username, $hash);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['Registration failed. Try again.']]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'errors' => $errors]);
}
$conn->close();
?> 