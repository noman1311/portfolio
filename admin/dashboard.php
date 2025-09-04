<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\dashboard.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

// Get statistics
try {
    // Count projects
    $project_count = $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
    
    // Count messages
    $message_count = $pdo->query('SELECT COUNT(*) FROM messages')->fetchColumn();
    
    // Count unread messages (you can add a 'read' column later)
    $unread_messages = $message_count; // For now, assume all are unread
    
    // Get recent projects
    $recent_projects = $pdo->query('SELECT title, category, created_at FROM projects ORDER BY created_at DESC LIMIT 5')->fetchAll();
    
    // Get recent messages
    $recent_messages = $pdo->query('SELECT name, email, subject, created_at FROM messages ORDER BY created_at DESC LIMIT 5')->fetchAll();
    
    // Calculate days since last login
    $login_days = isset($_SESSION['login_time']) ? floor((time() - $_SESSION['login_time']) / 86400) : 0;
    
} catch (Exception $e) {
    $project_count = 0;
    $message_count = 0;
    $unread_messages = 0;
    $recent_projects = [];
    $recent_messages = [];
    $login_days = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="manage_projects.php">📁 Manage Projects</a></li>
                <li><a href="view_message.php">💬 View Messages</a></li>
                <li><a href="change_password.php">🔑 Change Password</a></li>
                <li><a href="settings.php">⚙️ Settings</a></li>
                <li><a href="log_out.php">🚪 Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <!-- Welcome Header -->
            <div class="dashboard-header">
                <div class="welcome-section">
                    <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>! 👋</h1>
                    <p class="welcome-subtitle">Here's what's happening with your portfolio today</p>
                </div>
                <div class="header-actions">
                    <div class="current-time">
                        <span id="currentTime"></span>
                    </div>
                    <div class="quick-actions">
                        <a href="add_project.php" class="quick-btn">➕ Add Project</a>
                        <a href="view_message.php" class="quick-btn">📬 Messages</a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">📁</div>
                    <div class="stat-content">
                        <h3><?php echo $project_count; ?></h3>
                        <p>Total Projects</p>
                        <div class="stat-trend">
                            <span class="trend-up">📈 Active Portfolio</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">💬</div>
                    <div class="stat-content">
                        <h3><?php echo $message_count; ?></h3>
                        <p>Total Messages</p>
                        <div class="stat-trend">
                            <span class="trend-neutral">📊 Communications</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">📮</div>
                    <div class="stat-content">
                        <h3><?php echo $unread_messages; ?></h3>
                        <p>Unread Messages</p>
                        <div class="stat-trend">
                            <?php if ($unread_messages > 0): ?>
                                <span class="trend-up">🔔 Needs Attention</span>
                            <?php else: ?>
                                <span class="trend-neutral">✅ All Caught Up</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">⏰</div>
                    <div class="stat-content">
                        <h3><?php echo $login_days; ?></h3>
                        <p>Days Active</p>
                        <div class="stat-trend">
                            <span class="trend-neutral">🚀 Keep Going!</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="dashboard-grid">
                <!-- Recent Projects -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📁 Recent Projects</h2>
                        <a href="manage_projects.php" class="view-all-link">View All →</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($recent_projects)): ?>
                            <div class="empty-state-small">
                                <div class="empty-icon">📂</div>
                                <p>No projects yet</p>
                                <a href="add_project.php" class="btn btn-primary btn-small">Add Your First Project</a>
                            </div>
                        <?php else: ?>
                            <div class="project-list">
                                <?php foreach ($recent_projects as $project): ?>
                                <div class="project-item">
                                    <div class="project-info">
                                        <h4><?php echo htmlspecialchars($project['title']); ?></h4>
                                        <span class="project-category"><?php echo htmlspecialchars($project['category']); ?></span>
                                    </div>
                                    <div class="project-date">
                                        <?php echo date('M j', strtotime($project['created_at'])); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>💬 Recent Messages</h2>
                        <a href="view_message.php" class="view-all-link">View All →</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($recent_messages)): ?>
                            <div class="empty-state-small">
                                <div class="empty-icon">📭</div>
                                <p>No messages yet</p>
                                <p class="empty-subtitle">Messages from your portfolio will appear here</p>
                            </div>
                        <?php else: ?>
                            <div class="message-list">
                                <?php foreach ($recent_messages as $message): ?>
                                <div class="message-item">
                                    <div class="message-avatar">
                                        <?php echo strtoupper(substr($message['name'], 0, 1)); ?>
                                    </div>
                                    <div class="message-info">
                                        <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                                        <p><?php echo htmlspecialchars(substr($message['subject'], 0, 40)) . '...'; ?></p>
                                        <span class="message-email"><?php echo htmlspecialchars($message['email']); ?></span>
                                    </div>
                                    <div class="message-date">
                                        <?php echo date('M j', strtotime($message['created_at'])); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>⚡ Quick Actions</h2>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions-grid">
                            <a href="add_project.php" class="action-item">
                                <div class="action-icon">➕</div>
                                <div class="action-text">
                                    <h4>Add Project</h4>
                                    <p>Showcase new work</p>
                                </div>
                            </a>
                            <a href="view_message.php" class="action-item">
                                <div class="action-icon">📬</div>
                                <div class="action-text">
                                    <h4>Check Messages</h4>
                                    <p>Respond to inquiries</p>
                                </div>
                            </a>
                            <a href="settings.php" class="action-item">
                                <div class="action-icon">⚙️</div>
                                <div class="action-text">
                                    <h4>Settings</h4>
                                    <p>Customize preferences</p>
                                </div>
                            </a>
                            <a href="../public/index.html" target="_blank" class="action-item">
                                <div class="action-icon">🌐</div>
                                <div class="action-text">
                                    <h4>View Portfolio</h4>
                                    <p>See live website</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>ℹ️ System Info</h2>
                    </div>
                    <div class="card-content">
                        <div class="system-info">
                            <div class="info-item">
                                <span class="info-label">Last Login:</span>
                                <span class="info-value">
                                    <?php echo isset($_COOKIE['admin_last_login']) ? $_COOKIE['admin_last_login'] : 'First time'; ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Session:</span>
                                <span class="info-value">Active since <?php echo date('H:i', $_SESSION['login_time'] ?? time()); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Database:</span>
                                <span class="info-value status-online">🟢 Connected</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Theme:</span>
                                <span class="info-value"><?php echo ucfirst($_COOKIE['admin_theme'] ?? 'Default'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Real-time clock
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('currentTime').innerHTML = `
                <div class="time">${timeString}</div>
                <div class="date">${dateString}</div>
            `;
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .dashboard-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>