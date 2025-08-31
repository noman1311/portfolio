<?php
// filepath: d:\Xampp\htdocs\portfolio\src\get_projects.php
require_once 'database.php';

try {
    // Fetch all projects from database
    $stmt = $pdo->query('SELECT * FROM projects ORDER BY id DESC');
    $projects = $stmt->fetchAll();
    
    // Check if we have projects
    if (empty($projects)) {
        echo '<p class="no-projects">No projects found. Add some projects through the admin panel.</p>';
        exit;
    }
    
    // Generate HTML for each project
    foreach ($projects as $project) {
        ?>
        <div class="project-card" data-category="<?php echo htmlspecialchars($project['category']); ?>">
            <div class="project-image">
                <?php if (!empty($project['image'])): ?>
                    <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                <?php else: ?>
                    <div class="project-placeholder">
                        <i class="fas fa-code"></i>
                    </div>
                <?php endif; ?>
                <div class="project-overlay">
                    <div class="project-links">
                        <?php if (!empty($project['live_link'])): ?>
                            <a href="<?php echo htmlspecialchars($project['live_link']); ?>" target="_blank" class="project-link" title="Live Demo">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($project['github_link'])): ?>
                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="project-link" title="GitHub">
                                <i class="fab fa-github"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="project-content">
                <div class="project-category">
                    <span class="category-badge"><?php echo htmlspecialchars($project['category']); ?></span>
                </div>
                
                <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                
                <p class="project-description">
                    <?php echo htmlspecialchars(substr($project['description'], 0, 120)) . '...'; ?>
                </p>
                
                <?php if (!empty($project['tech_stack'])): ?>
                    <div class="project-tech">
                        <?php 
                        $techs = explode(',', $project['tech_stack']);
                        foreach ($techs as $tech): 
                        ?>
                            <span class="tech-badge"><?php echo htmlspecialchars(trim($tech)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="project-actions">
                    <?php if (!empty($project['live_link'])): ?>
                        <a href="<?php echo htmlspecialchars($project['live_link']); ?>" target="_blank" class="btn btn-primary">Live Demo</a>
                    <?php endif; ?>
                    <?php if (!empty($project['github_link'])): ?>
                        <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="btn btn-secondary">View Code</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
} catch (Exception $e) {
    echo '<p class="error-message">Error loading projects: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>