<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\delete_message.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['message_success'] = 'Message deleted successfully.';
        } else {
            $_SESSION['message_error'] = 'Failed to delete message.';
        }
    } catch (Exception $e) {
        $_SESSION['message_error'] = 'Error: ' . $e->getMessage();
    }
}

header('Location: view_message.php');
exit;
?>