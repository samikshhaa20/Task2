<?php
require_once 'config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    // Validate role
    if (!in_array($new_role, ['user', 'admin'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $user_id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 