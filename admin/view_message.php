<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\view_message.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();

// Handle session messages
$success_message = $_SESSION['message_success'] ?? '';
$error_message = $_SESSION['message_error'] ?? '';
unset($_SESSION['message_success'], $_SESSION['message_error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Messages</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_projects.php">Manage Projects</a></li>
                <li><a href="view_message.php" class="active">View Messages</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="header">
                <h1>Contact Messages</h1>
            </div>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($messages)): ?>
                <div class="empty-state">
                    <h3>No messages yet</h3>
                    <p>When visitors contact you through the portfolio, their messages will appear here.</p>
                </div>
            <?php else: ?>
                <div class="messages-container">
                    <?php foreach ($messages as $message): ?>
                    <div class="message-card">
                        <div class="message-header">
                            <div class="message-info">
                                <h3><?php echo htmlspecialchars($message['name']); ?></h3>
                                <span class="message-date"><?php echo date('M j, Y g:i A', strtotime($message['created_at'])); ?></span>
                            </div>
                            <div class="message-actions">
                                <a href="edit_message.php?id=<?php echo $message['id']; ?>" class="btn btn-small btn-edit" title="Edit Message">
                                    ✏️ Edit
                                </a>
                                <a href="delete_message.php?id=<?php echo $message['id']; ?>" 
                                   class="btn btn-small btn-danger" 
                                   title="Delete Message"
                                   onclick="return confirm('Are you sure you want to delete this message?')">
                                    🗑️ Delete
                                </a>
                            </div>
                        </div>
                        <div class="message-details">
                            <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></p>
                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($message['subject']); ?></p>
                        </div>
                        <div class="message-content">
                            <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>