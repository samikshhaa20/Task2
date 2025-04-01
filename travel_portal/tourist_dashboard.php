<?php
require_once 'config.php';
redirectIfNotLoggedIn();

// Get user's information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Management System - Tourist Dashboard</title>
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

        .profile-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-title {
            font-size: 1.5rem;
            color: #333;
        }

        .edit-btn {
            background: #0083b0;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .info-value {
            color: #333;
            font-size: 1.1rem;
        }

        .edit-form {
            display: none;
        }

        .edit-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .save-btn {
            background: #28a745;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .cancel-btn {
            background: #6c757d;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Tourist Dashboard</h1>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h2 class="profile-title">My Profile</h2>
                <button class="edit-btn" onclick="toggleEdit()">Edit Profile</button>
            </div>

            <div class="profile-info" id="profile-info">
                <div class="info-group">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Username</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Mobile</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['mobile']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo ucfirst(htmlspecialchars($user['gender'])); ?></div>
                </div>
            </div>

            <form action="update_profile.php" method="POST" class="edit-form" id="edit-form">
                <div class="profile-info">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" pattern="[0-9]{10}" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="save-btn">Save Changes</button>
                    <button type="button" class="cancel-btn" onclick="toggleEdit()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const profileInfo = document.getElementById('profile-info');
            const editForm = document.getElementById('edit-form');
            
            if (editForm.style.display === 'none' || editForm.style.display === '') {
                profileInfo.style.display = 'none';
                editForm.style.display = 'block';
            } else {
                profileInfo.style.display = 'grid';
                editForm.style.display = 'none';
            }
        }
    </script>
</body>
</html> 