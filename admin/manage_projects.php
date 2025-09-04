<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\manage_projects.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$projects = $pdo->query('SELECT * FROM projects ORDER BY id DESC')->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Projects</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <nav class="admin-nav">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_projects.php" class="active">Manage Projects</a></li>
                <li><a href="view_message.php">View Messages</a></li>
                <li><a href="change_password.php">Change Password</a></li>
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="header">
                <h1>Manage Projects</h1>
                <a href="add_project.php" class="btn btn-primary">Add New Project</a>
            </div>
            
            <?php if (empty($projects)): ?>
                <div class="empty-state">
                    <h3>No projects yet</h3>
                    <p>Start by adding your first project to showcase your work.</p>
                    <a href="add_project.php" class="btn btn-primary">Add Your First Project</a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Tech Stack</th>
                                <th>GitHub</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?php echo $project['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                    <br>
                                    <small><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?></small>
                                </td>
                                <td>
                                    <span class="category-badge"><?php echo htmlspecialchars($project['category']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($project['tech_stack']); ?></td>
                                <td>
                                    <?php if ($project['github_link']): ?>
                                        <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="link-btn">GitHub</a>
                                    <?php else: ?>
                                        <span style="color: #999;">No link</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="btn btn-small btn-edit">Edit</a>
                                    <a href="delete_project.php?id=<?php echo $project['id']; ?>" 
                                       class="btn btn-small btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>