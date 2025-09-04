<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\index.php
session_start();

// Check for remember me cookie
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['admin_remember'])) {
    $cookie_data = json_decode($_COOKIE['admin_remember'], true);
    if ($cookie_data && $cookie_data['username'] === 'admin') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $cookie_data['username'];
        $_SESSION['login_time'] = time();
        header('Location: dashboard.php');
        exit;
    }
}

// Check if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);
    
    // Simple authentication
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        
        // Set remember me cookie if checked
        if ($remember) {
            $cookie_data = json_encode([
                'username' => $username,
                'login_time' => time()
            ]);
            // Set cookie for 30 days
            setcookie('admin_remember', $cookie_data, time() + (30 * 24 * 60 * 60), '/');
        }
        
        // Set last login preference cookie (but don't display it)
        setcookie('admin_last_login', date('Y-m-d H:i:s'), time() + (365 * 24 * 60 * 60), '/');
        
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
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
        <form method="POST" class="login-form">
            <h2>Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($_COOKIE['admin_username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember_me" <?php echo isset($_COOKIE['admin_remember']) ? 'checked' : ''; ?>>
                    <span class="checkbox-text">Remember me for 30 days</span>
                </label>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>