<?php
require_once 'db.php';
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$user = trim($data['user'] ?? '');
$password = $data['password'] ?? '';
$errors = [];

if (empty($user) || empty($password)) {
    $errors[] = 'All fields are required.';
}

if (empty($errors)) {
    $stmt = $conn->prepare('SELECT id, username, email, password FROM users WHERE email = ? OR username = ?');
    $stmt->bind_param('ss', $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            echo json_encode(['success' => true, 'username' => $row['username']]);
        } else {
            $errors[] = 'Invalid credentials.';
            echo json_encode(['success' => false, 'errors' => $errors]);
        }
    } else {
        $errors[] = 'User not found.';
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'errors' => $errors]);
}
$conn->close();
?> 