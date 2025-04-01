<?php
require_once 'config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);
        
        header("Location: admin_dashboard.php?success=3");
        exit();
    } catch(PDOException $e) {
        header("Location: admin_dashboard.php?error=2");
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?> 