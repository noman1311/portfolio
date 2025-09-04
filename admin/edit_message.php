<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\edit_message.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$success = false;
$error = '';
$message = null;

// Get message ID
if (!isset($_GET['id'])) {
    header('Location: view_message.php');
    exit;
}

$id = (int)$_GET['id'];

// Fetch message data
try {
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    $message = $stmt->fetch();
    
    if (!$message) {
        header('Location: view_message.php');
        exit;
    }
} catch (Exception $e) {
    $error = 'Error fetching message: ' . $e->getMessage();
}

// Handle form submission
if ($_POST && $message) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($message_text)) {
        $error = 'Name, email, and message are required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE messages SET name = ?, email = ?, subject = ?, message = ? WHERE id = ?");
            
            if ($stmt->execute([$name, $email, $subject, $message_text, $id])) {
                $success = true;
                // Refresh message data
                $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
                $stmt->execute([$id]);
                $message = $stmt->fetch();
            } else {
                $error = 'Failed to update message. Please try again.';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Message</title>
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
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="header">
                <h1>Edit Message</h1>
                <a href="view_message.php" class="btn btn-secondary">Back to Messages</a>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message">
                    Message updated successfully! <a href="view_message.php">View all messages</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($message): ?>
            <div class="form-container">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($message['name']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($message['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($message['subject']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required rows="8"><?php echo htmlspecialchars($message['message']); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Message</button>
                        <a href="view_message.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>