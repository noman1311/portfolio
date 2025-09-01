<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\edit_project.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$success = false;
$error = '';
$project = null;

// Get project ID
if (!isset($_GET['id'])) {
    header('Location: manage_projects.php');
    exit;
}

$id = (int)$_GET['id'];

// Fetch project data
try {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if (!$project) {
        header('Location: manage_projects.php');
        exit;
    }
} catch (Exception $e) {
    $error = 'Error fetching project: ' . $e->getMessage();
}

// Handle form submission
if ($_POST && $project) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $tech_stack = trim($_POST['tech_stack']);
    $github_link = trim($_POST['github_link']);
    
    if (empty($title) || empty($description)) {
        $error = 'Title and description are required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, category = ?, tech_stack = ?, github_link = ? WHERE id = ?");
            
            if ($stmt->execute([$title, $description, $category, $tech_stack, $github_link, $id])) {
                $success = true;
                // Refresh project data
                $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
                $stmt->execute([$id]);
                $project = $stmt->fetch();
            } else {
                $error = 'Failed to update project. Please try again.';
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
    <title>Edit Project</title>
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
                <h1>Edit Project</h1>
                <a href="manage_projects.php" class="btn btn-secondary">Back to Projects</a>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message">
                    Project updated successfully! <a href="manage_projects.php">View all projects</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($project): ?>
            <div class="form-container">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title">Project Title *</label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($project['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Project Description *</label>
                        <textarea id="description" name="description" required rows="5"><?php echo htmlspecialchars($project['description']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="">Select Category</option>
                                <option value="web" <?php echo $project['category'] === 'web' ? 'selected' : ''; ?>>Web Development</option>
                                <option value="mobile" <?php echo $project['category'] === 'mobile' ? 'selected' : ''; ?>>Mobile App</option>
                                <option value="api" <?php echo $project['category'] === 'api' ? 'selected' : ''; ?>>API/Backend</option>
                                <option value="desktop" <?php echo $project['category'] === 'desktop' ? 'selected' : ''; ?>>Desktop App</option>
                                <option value="other" <?php echo $project['category'] === 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tech_stack">Tech Stack</label>
                            <input type="text" id="tech_stack" name="tech_stack" value="<?php echo htmlspecialchars($project['tech_stack']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="github_link">GitHub URL</label>
                        <input type="url" id="github_link" name="github_link" value="<?php echo htmlspecialchars($project['github_link']); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Project</button>
                        <a href="manage_projects.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>