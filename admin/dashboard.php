<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\dashboard.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// Calculate days since last login
$login_days = isset($_SESSION['login_time']) ? floor((time() - $_SESSION['login_time']) / 86400) : 0;
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
                    <p class="welcome-subtitle">Manage your portfolio and stay connected with your audience</p>
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

            <!-- Main Content Grid -->
            <div class="dashboard-grid">
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
                            <a href="manage_projects.php" class="action-item">
                                <div class="action-icon">📁</div>
                                <div class="action-text">
                                    <h4>Manage Projects</h4>
                                    <p>Edit existing projects</p>
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
                            <a href="change_password.php" class="action-item">
                                <div class="action-icon">🔑</div>
                                <div class="action-text">
                                    <h4>Change Password</h4>
                                    <p>Update security</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Management -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📁 Portfolio Management</h2>
                    </div>
                    <div class="card-content">
                        <div class="management-section">
                            <div class="management-item">
                                <div class="management-icon">📝</div>
                                <div class="management-content">
                                    <h4>Content Creation</h4>
                                    <p>Add new projects to showcase your skills and experience</p>
                                    <a href="add_project.php" class="btn btn-primary btn-small">Add Project</a>
                                </div>
                            </div>
                            <div class="management-item">
                                <div class="management-icon">✏️</div>
                                <div class="management-content">
                                    <h4>Content Editing</h4>
                                    <p>Update and modify your existing portfolio projects</p>
                                    <a href="manage_projects.php" class="btn btn-secondary btn-small">Manage Projects</a>
                                </div>
                            </div>
                            <div class="management-item">
                                <div class="management-icon">💬</div>
                                <div class="management-content">
                                    <h4>Communication</h4>
                                    <p>Stay connected with visitors and potential clients</p>
                                    <a href="view_message.php" class="btn btn-success btn-small">View Messages</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>ℹ️ System Information</h2>
                    </div>
                    <div class="card-content">
                        <div class="system-info">
                            <div class="info-item">
                                <span class="info-label">Welcome Back:</span>
                                <span class="info-value">Great to see you again! 👋</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Session Status:</span>
                                <span class="info-value status-online">🟢 Active & Secure</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Current Time:</span>
                                <span class="info-value" id="currentTimeInfo"></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Admin Level:</span>
                                <span class="info-value">🔒 Full Access</span>
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
            
            document.getElementById('currentTimeInfo').textContent = timeString;
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>