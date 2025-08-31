<?php
// filepath: d:\Xampp\htdocs\portfolio\src\database.php

$host = 'localhost';
$db   = 'portfolio_db';
$user = 'root';
$pass = '';  // XAMPP default password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Log the error or handle it appropriately
    error_log('Database connection failed: ' . $e->getMessage());
    
    // For development, you can show the error
    // In production, show a generic error message
    die('Database connection failed. Please check your configuration.');
}
?>