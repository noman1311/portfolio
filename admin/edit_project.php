<?php
// filepath: d:\Xampp\htdocs\portfolio\admin\edit_project.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

require_once '../src/database.php';

$project_id = $_GET['id'] ?? null;
if (!$project_id) {
    header('Location: manage_projects.php');
    exit;
}

// Get project data
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    header('Location: manage_projects.php');
    exit;
}

// Handle form submission
if ($_POST) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $tech_stack = $_POST['tech_stack'];
    $live_link = $_POST['live_link'];
    $github_link = $_POST['github_link'];
    
    $stmt = $pdo->prepare("UPDATE projects SET title=?, description=?, category=?, tech_stack=?, live_link=?, github_link=? WHERE id=?");
    
    if ($stmt->execute([$title, $description, $category, $tech_stack, $live_link, $github_link, $project_id])) {
        header('Location: manage_projects.php');
        exit;
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
            <h1>Edit Project</h1>
            
            <form method="POST" class="admin-form">
                <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" placeholder="Project Title" required>
                <textarea name="description" placeholder="Project Description" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                <input type="text" name="category" value="<?php echo htmlspecialchars($project['category']); ?>" placeholder="Category (e.g., web, mobile)">
                <input type="text" name="tech_stack" value="<?php echo htmlspecialchars($project['tech_stack']); ?>" placeholder="Tech Stack (e.g., HTML, CSS, JavaScript)">
                <input type="url" name="live_link" value="<?php echo htmlspecialchars($project['live_link']); ?>" placeholder="Live Demo URL">
                <input type="url" name="github_link" value="<?php echo htmlspecialchars($project['github_link']); ?>" placeholder="GitHub URL">
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="manage_projects.php" class="btn btn-secondary">Cancel</a>
            </form>
        </main>
    </div>
</body>
</html>