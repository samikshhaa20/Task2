<?php
require_once 'config.php';
redirectIfNotLoggedIn();

// Get user's tasks
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to = ? ORDER BY deadline ASC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Portal - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f5f5f5;
            min-height: 100vh;
        }

        .navbar {
            background: #0083b0;
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: #ff4444;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .task-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .task-card h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        .task-card p {
            color: #666;
            margin-bottom: 1rem;
        }

        .task-card .deadline {
            color: #ff4444;
            font-weight: bold;
        }

        .status-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 1rem;
        }

        .emergency-contact {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ff4444;
            color: white;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Travel Portal Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Your Tasks</h2>
        <div class="tasks-grid">
            <?php foreach ($tasks as $task): ?>
                <div class="task-card">
                    <h3><?php echo htmlspecialchars($task['short_description']); ?></h3>
                    <p><?php echo htmlspecialchars($task['long_description']); ?></p>
                    <p class="deadline">Deadline: <?php echo date('M d, Y H:i', strtotime($task['deadline'])); ?></p>
                    <form action="update_task_status.php" method="POST">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <select name="status" class="status-select" onchange="this.form.submit()">
                            <option value="pending" <?php echo $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="emergency-contact">
        <h3>Emergency Helpline</h3>
        <p>24/7 Tourist Support: 1363</p>
    </div>
</body>
</html> 