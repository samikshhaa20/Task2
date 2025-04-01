<?php
require_once 'config.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

// Get all users
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user'");
$users = $stmt->fetchAll();

// Get all tasks
$stmt = $pdo->query("SELECT t.*, u.full_name as assigned_to_name 
                     FROM tasks t 
                     JOIN users u ON t.assigned_to = u.id 
                     ORDER BY t.deadline ASC");
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Portal - Admin Dashboard</title>
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

        .section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        .add-task-form {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: grid;
            gap: 0.5rem;
        }

        label {
            color: #555;
        }

        input, select, textarea {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background: #0083b0;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #006d94;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            color: #333;
        }

        .status-pending {
            color: #ff4444;
        }

        .status-completed {
            color: #28a745;
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
        <h1>Admin Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="section">
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST" class="add-task-form">
                <div class="form-group">
                    <label for="short_description">Short Description</label>
                    <input type="text" id="short_description" name="short_description" required>
                </div>
                <div class="form-group">
                    <label for="long_description">Long Description</label>
                    <textarea id="long_description" name="long_description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="datetime-local" id="deadline" name="deadline" required>
                </div>
                <div class="form-group">
                    <label for="assigned_to">Assign To</label>
                    <select id="assigned_to" name="assigned_to" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Add Task</button>
            </form>
        </div>

        <div class="section">
            <h2>All Tasks</h2>
            <table>
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Assigned To</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['short_description']); ?></td>
                            <td><?php echo htmlspecialchars($task['assigned_to_name']); ?></td>
                            <td><?php echo date('M d, Y H:i', strtotime($task['deadline'])); ?></td>
                            <td class="status-<?php echo $task['status']; ?>">
                                <?php echo ucfirst($task['status']); ?>
                            </td>
                            <td>
                                <form action="delete_task.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" style="background: #ff4444; padding: 0.5rem 1rem;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="emergency-contact">
        <h3>Emergency Helpline</h3>
        <p>24/7 Tourist Support: 1363</p>
    </div>
</body>
</html> 