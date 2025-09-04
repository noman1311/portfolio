<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\log_out.php
session_start();

// Clear remember me cookie if exists
if (isset($_COOKIE['admin_remember'])) {
    setcookie('admin_remember', '', time() - 3600, '/');
}

// Clear username cookie if exists
if (isset($_COOKIE['admin_username'])) {
    setcookie('admin_username', '', time() - 3600, '/');
}

// Update last logout time
setcookie('admin_last_logout', date('Y-m-d H:i:s'), time() + (365 * 24 * 60 * 60), '/');

session_destroy();
header('Location: index.php');
exit;
?>