<?php
require_once 'config.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $user_id = $_SESSION['user_id'];

    // Validate mobile number
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        die("Invalid mobile number format");
    }

    // Check if email exists for other users
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user_id]);
    if ($stmt->rowCount() > 0) {
        die("Email already exists");
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, mobile = ?, gender = ? WHERE id = ?");
        $stmt->execute([$full_name, $email, $mobile, $gender, $user_id]);

        // Update session data
        $_SESSION['full_name'] = $full_name;

        header("Location: tourist_dashboard.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}
?> 