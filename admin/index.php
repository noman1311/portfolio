<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\index.php
session_start();

// Simple login credentials (you can change these)
$admin_username = "admin";
$admin_password = "password123";

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="login-info">
                <p><strong>Default Login:</strong></p>
                <p>Username: admin</p>
                <p>Password: password123</p>
            </div>
        </div>
    </div>
</body>
</html>