<?php
require_once 'config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $short_description = $_POST['short_description'];
    $long_description = $_POST['long_description'];
    $deadline = $_POST['deadline'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (short_description, long_description, deadline, assigned_to, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$short_description, $long_description, $deadline, $assigned_to, $created_by]);
        
        header("Location: admin_dashboard.php?success=1");
        exit();
    } catch(PDOException $e) {
        header("Location: admin_dashboard.php?error=1");
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?> 
 