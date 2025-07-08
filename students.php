<?php
require_once 'db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'fetch':
        $search = trim($_POST['search'] ?? '');
        $sql = "SELECT * FROM students WHERE name LIKE ? OR department LIKE ? OR semester LIKE ? OR email LIKE ? ORDER BY student_id DESC";
        $like = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $like, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        echo json_encode(['success' => true, 'students' => $students]);
        break;
    case 'add':
        $name = trim($_POST['name'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $semester = trim($_POST['semester'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        if ($name && $department && $semester && $email && $image_url) {
            $stmt = $conn->prepare('INSERT INTO students (name, department, semester, email, image_url) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $name, $department, $semester, $email, $image_url);
            $stmt->execute();
            echo json_encode(['success' => true, 'student_id' => $stmt->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => 'All fields required.']);
        }
        break;
    case 'update':
        $student_id = intval($_POST['student_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $semester = trim($_POST['semester'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        if ($student_id && $name && $department && $semester && $email && $image_url) {
            $stmt = $conn->prepare('UPDATE students SET name=?, department=?, semester=?, email=?, image_url=? WHERE student_id=?');
            $stmt->bind_param('sssssi', $name, $department, $semester, $email, $image_url, $student_id);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'All fields required.']);
        }
        break;
    case 'delete':
        $student_id = intval($_POST['student_id'] ?? 0);
        if ($student_id) {
            $stmt = $conn->prepare('DELETE FROM students WHERE student_id=?');
            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid student ID.']);
        }
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action.']);
}
$conn->close();
?> 