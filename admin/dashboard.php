<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\dashboard.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

// Get counts
$projects_count = $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$messages_count = $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
$recent_messages_count = $pdo->query('SELECT COUNT(*) FROM messages WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)')->fetchColumn();

// Get recent projects
$recent_projects = $pdo->query('SELECT * FROM projects ORDER BY id DESC LIMIT 5')->fetchAll();

// Get recent messages
$recent_messages = $pdo->query('SELECT * FROM messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="manage_projects.php">Manage Projects</a></li>
                <li><a href="view_message.php">View Messages</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
                <p>Welcome to your portfolio admin panel</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📂</div>
                    <div class="stat-info">
                        <h3>Total Projects</h3>
                        <p class="stat-number"><?php echo $projects_count; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">💬</div>
                    <div class="stat-info">
                        <h3>Total Messages</h3>
                        <p class="stat-number"><?php echo $messages_count; ?></p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🆕</div>
                    <div class="stat-info">
                        <h3>Recent Messages</h3>
                        <p class="stat-number"><?php echo $recent_messages_count; ?></p>
                        <small>Last 7 days</small>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-section">
                    <h2>Recent Projects</h2>
                    <div class="recent-items">
                        <?php if (empty($recent_projects)): ?>
                            <p>No projects yet. <a href="add_project.php">Add your first project</a></p>
                        <?php else: ?>
                            <?php foreach ($recent_projects as $project): ?>
                                <div class="recent-item">
                                    <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                                    <p><?php echo htmlspecialchars(substr($project['description'], 0, 100)) . '...'; ?></p>
                                    <span class="category-tag"><?php echo htmlspecialchars($project['category']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>Recent Messages</h2>
                    <div class="recent-items">
                        <?php if (empty($recent_messages)): ?>
                            <p>No messages yet.</p>
                        <?php else: ?>
                            <?php foreach ($recent_messages as $message): ?>
                                <div class="recent-item">
                                    <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                                    <p><strong><?php echo htmlspecialchars($message['subject']); ?></strong></p>
                                    <p><?php echo htmlspecialchars(substr($message['message'], 0, 100)) . '...'; ?></p>
                                    <small><?php echo $message['created_at']; ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="add_project.php" class="btn btn-primary">Add New Project</a>
                    <a href="manage_projects.php" class="btn btn-secondary">Manage Projects</a>
                    <a href="view_message.php" class="btn btn-info">View Messages</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>