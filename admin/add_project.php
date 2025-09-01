<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\add_project.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$success = false;
$error = '';

if ($_POST) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $tech_stack = trim($_POST['tech_stack']);
    $github_link = trim($_POST['github_link']);
    
    if (empty($title) || empty($description)) {
        $error = 'Title and description are required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, tech_stack, github_link) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$title, $description, $category, $tech_stack, $github_link])) {
                $success = true;
            } else {
                $error = 'Failed to add project. Please try again.';
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
    <title>Add Project</title>
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
                <li><a href="log_out.php">Logout</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="header">
                <h1>Add New Project</h1>
                <a href="manage_projects.php" class="btn btn-secondary">Back to Projects</a>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message">
                    Project added successfully! <a href="manage_projects.php">View all projects</a> or <a href="add_project.php">add another</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title">Project Title *</label>
                        <input type="text" id="title" name="title" placeholder="e.g., E-commerce Website" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Project Description *</label>
                        <textarea id="description" name="description" placeholder="Describe your project, its features, and technologies used..." required rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="web" <?php echo ($_POST['category'] ?? '') === 'web' ? 'selected' : ''; ?>>Web Development</option>
                                <option value="mobile" <?php echo ($_POST['category'] ?? '') === 'mobile' ? 'selected' : ''; ?>>Mobile App</option>
                                <option value="api" <?php echo ($_POST['category'] ?? '') === 'api' ? 'selected' : ''; ?>>API/Backend</option>
                                <option value="desktop" <?php echo ($_POST['category'] ?? '') === 'desktop' ? 'selected' : ''; ?>>Desktop App</option>
                                <option value="other" <?php echo ($_POST['category'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tech_stack">Tech Stack</label>
                            <input type="text" id="tech_stack" name="tech_stack" placeholder="e.g., React, Node.js, MongoDB" value="<?php echo htmlspecialchars($_POST['tech_stack'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="github_link">GitHub URL</label>
                        <input type="url" id="github_link" name="github_link" placeholder="https://github.com/username/repo" value="<?php echo htmlspecialchars($_POST['github_link'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Project</button>
                        <a href="manage_projects.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>