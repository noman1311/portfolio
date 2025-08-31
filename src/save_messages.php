<?php
// filepath: d:\Xampp\htdocs\portfolio\src\save_messages.php
require_once 'database.php';

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $subject, $message]);
        
        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    } catch (Exception $e) {
        echo "error";
    }
}
?>