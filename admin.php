<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: landing.php');
    exit;
}

// Set up routing logic
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Sanitization to prevent directory traversal
$allowedPages = ['dashboard', 'students', 'parents', 'routes', 'payments', 'enrollments', 'settings', 'reports', 'support', 'admin_support'];
if (!in_array($page, $allowedPages)) {
    $page = 'dashboard';
}

// Map page names to file paths
$pageFile = "pages/{$page}.php";
if (!file_exists($pageFile)) {
    // If file doesn't exist yet, we'll create a placeholder
    $placeholderContent = "<div class='table-container'><div class='table-title'>" . ucfirst($page) . "</div><p>This module is currently under development...</p></div>";
}

// Start building the layout
include 'includes/header.php';
include 'includes/navbar.php';

?>
<main class="main-content <?php echo ($page === 'dashboard' ? 'dashboard-page-active' : ''); ?>">
    <?php include 'includes/topbar.php'; ?>
<?php
if (isset($placeholderContent)) {
    echo $placeholderContent;
} else {
    include $pageFile;
}
?>
</main>
<?php
include 'includes/footer.php';
?>
