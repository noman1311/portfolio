<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\delete_project.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        
        // Redirect with success message
        header('Location: manage_projects.php?deleted=1');
    } catch (Exception $e) {
        // Redirect with error message
        header('Location: manage_projects.php?error=1');
    }
} else {
    header('Location: manage_projects.php');
}
exit;
?>