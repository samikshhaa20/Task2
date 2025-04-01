<?php
require_once 'config.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    try {
        // Check if user has permission to update this task
        $stmt = $pdo->prepare("SELECT assigned_to FROM tasks WHERE id = ?");
        $stmt->execute([$task_id]);
        $task = $stmt->fetch();

        if ($task && ($task['assigned_to'] == $_SESSION['user_id'] || isAdmin())) {
            $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
            $stmt->execute([$status, $task_id]);
            
            if (isAdmin()) {
                header("Location: admin_dashboard.php?success=2");
            } else {
                header("Location: dashboard.php?success=1");
            }
            exit();
        } else {
            header("Location: dashboard.php?error=1");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: dashboard.php?error=2");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?> 