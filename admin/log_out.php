<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\log_out.php
session_start();
session_destroy();
header('Location: index.php');
exit;
?>