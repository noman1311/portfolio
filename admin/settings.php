<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\settings.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$success = false;
$error = '';

// Get current preferences from cookies
$current_theme = $_COOKIE['admin_theme'] ?? 'default';
$current_items_per_page = $_COOKIE['admin_items_per_page'] ?? '10';
$current_auto_logout = $_COOKIE['admin_auto_logout'] ?? '60';

if ($_POST) {
    $theme = $_POST['theme'] ?? 'default';
    $items_per_page = $_POST['items_per_page'] ?? '10';
    $auto_logout = $_POST['auto_logout'] ?? '60';
    $clear_all_cookies = isset($_POST['clear_all_cookies']);
    
    if ($clear_all_cookies) {
        // Clear all admin cookies except session
        $cookies_to_clear = ['admin_theme', 'admin_items_per_page', 'admin_auto_logout', 'admin_last_login', 'admin_username'];
        foreach ($cookies_to_clear as $cookie) {
            if (isset($_COOKIE[$cookie])) {
                setcookie($cookie, '', time() - 3600, '/');
            }
        }
        $success = true;
        $error = 'All cookies cleared successfully!';
        $current_theme = 'default';
        $current_items_per_page = '10';
        $current_auto_logout = '60';
    } else {
        // Save preferences to cookies (1 year expiry)
        setcookie('admin_theme', $theme, time() + (365 * 24 * 60 * 60), '/');
        setcookie('admin_items_per_page', $items_per_page, time() + (365 * 24 * 60 * 60), '/');
        setcookie('admin_auto_logout', $auto_logout, time() + (365 * 24 * 60 * 60), '/');
        setcookie('admin_username', $_SESSION['admin_username'], time() + (365 * 24 * 60 * 60), '/');
        
        $success = true;
        $current_theme = $theme;
        $current_items_per_page = $items_per_page;
        $current_auto_logout = $auto_logout;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Settings</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_projects.php">Manage Projects</a></li>
                <li><a href="view_message.php">View Messages</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="settings.php" class="active">Settings</a></li>
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="header">
                <h1>Admin Settings</h1>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message">
                    Settings saved successfully!
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="settings-container">
                <div class="settings-section">
                    <h2>Preferences</h2>
                    <form method="POST" class="admin-form">
                        <div class="form-group">
                            <label for="theme">Admin Theme</label>
                            <select id="theme" name="theme">
                                <option value="default" <?php echo $current_theme === 'default' ? 'selected' : ''; ?>>Default</option>
                                <option value="dark" <?php echo $current_theme === 'dark' ? 'selected' : ''; ?>>Dark Mode</option>
                                <option value="light" <?php echo $current_theme === 'light' ? 'selected' : ''; ?>>Light Mode</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="items_per_page">Items Per Page</label>
                            <select id="items_per_page" name="items_per_page">
                                <option value="5" <?php echo $current_items_per_page === '5' ? 'selected' : ''; ?>>5</option>
                                <option value="10" <?php echo $current_items_per_page === '10' ? 'selected' : ''; ?>>10</option>
                                <option value="20" <?php echo $current_items_per_page === '20' ? 'selected' : ''; ?>>20</option>
                                <option value="50" <?php echo $current_items_per_page === '50' ? 'selected' : ''; ?>>50</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="auto_logout">Auto Logout (minutes)</label>
                            <select id="auto_logout" name="auto_logout">
                                <option value="30" <?php echo $current_auto_logout === '30' ? 'selected' : ''; ?>>30 minutes</option>
                                <option value="60" <?php echo $current_auto_logout === '60' ? 'selected' : ''; ?>>1 hour</option>
                                <option value="120" <?php echo $current_auto_logout === '120' ? 'selected' : ''; ?>>2 hours</option>
                                <option value="240" <?php echo $current_auto_logout === '240' ? 'selected' : ''; ?>>4 hours</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
                
                <div class="settings-section">
                    <h2>Cookie Information</h2>
                    <div class="cookie-info">
                        <div class="cookie-item">
                            <strong>Login Status:</strong> 
                            <?php echo isset($_COOKIE['admin_remember']) ? 'Remembered' : 'Not Remembered'; ?>
                        </div>
                        <div class="cookie-item">
                            <strong>Last Login:</strong> 
                            <?php echo $_COOKIE['admin_last_login'] ?? 'Not Available'; ?>
                        </div>
                        <div class="cookie-item">
                            <strong>Theme:</strong> 
                            <?php echo ucfirst($current_theme); ?>
                        </div>
                        <div class="cookie-item">
                            <strong>Items Per Page:</strong> 
                            <?php echo $current_items_per_page; ?>
                        </div>
                    </div>
                    
                    <form method="POST" class="admin-form">
                        <input type="hidden" name="clear_all_cookies" value="1">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to clear all cookies? This will reset all your preferences.')">
                            Clear All Cookies
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>