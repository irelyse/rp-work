<?php
$safePage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$userName = $_SESSION['user_name'] ?? 'Administrator';

// Get initials for profile picture fallback
$words = explode(" ", $userName);
$initials = "";
foreach ($words as $w) {
    if (!empty($w)) $initials .= $w[0];
}
$initials = strtoupper(substr($initials, 0, 2));
?>

<!-- Brand new Top Navigation matching reference image, modified for brand colors -->
<div class="top-nav">
    <div class="nav-left">
        <!-- Mobile Toggle Button -->
        <button class="mobile-nav-toggle" id="open-mobile-menu">
            <i data-lucide="menu" size="24"></i>
        </button>

        <!-- Rounded brand icon (replacing green hamburger in the image) -->
        <a href="index.php" class="brand-btn" title="View Public Landing Page" style="text-decoration: none; color: white;">
            <i data-lucide="external-link" size="22"></i>
        </a>
        
        <!-- Pill navigation links (Desktop) -->
        <div class="top-links">
            <a href="?page=dashboard" class="<?php echo ($safePage === 'dashboard' ? 'active' : ''); ?>">Dashboard</a>
            <a href="?page=students" class="<?php echo ($safePage === 'students' ? 'active' : ''); ?>">Students</a>
            <a href="?page=parents" class="<?php echo ($safePage === 'parents' ? 'active' : ''); ?>">Parents</a>
            <a href="?page=routes" class="<?php echo ($safePage === 'routes' ? 'active' : ''); ?>">Routes</a>
            <a href="?page=enrollments" class="<?php echo ($safePage === 'enrollments' ? 'active' : ''); ?>">Enrollments</a>
            <a href="?page=payments" class="<?php echo ($safePage === 'payments' ? 'active' : ''); ?>">Payments</a>
            <a href="?page=reports" class="<?php echo ($safePage === 'reports' ? 'active' : ''); ?>">Reports Center</a>
            <a href="?page=admin_support" class="<?php echo ($safePage === 'admin_support' ? 'active' : ''); ?>">Support Messages</a>
        </div>
    </div>
    
    <div class="nav-right">

        <!-- Utility Icons -->
        <a href="index.php#contact" style="color: inherit;" title="Go to Public Support Section"><i class="nav-icon" data-lucide="help-circle" size="20"></i></a>
        <a href="?page=settings" style="color: inherit;"><i class="nav-icon" data-lucide="settings" size="20"></i></a>
        <div style="position: relative;">
            <i class="nav-icon" data-lucide="bell" size="20"></i>
            <span class="nav-badge"></span>
        </div>
        
        <!-- User Profile Area -->
        <div class="nav-user" onclick="document.getElementById('user-dropdown').classList.toggle('show')">
            <div class="nav-user-img">
                <?php echo $initials; ?>
            </div> 
            <!-- Dropdown Menu -->
            <div id="user-dropdown" class="nav-dropdown">
                <a href="logout.php" class="dropdown-item text-red">
                    <i data-lucide="log-out" size="16"></i>
                     Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="mobile-sidebar-overlay" id="mobile-overlay"></div>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar" id="mobile-sidebar">
    <div class="mobile-sidebar-header">
        <div class="brand-btn" style="width: 40px; height: 40px;">
            <i data-lucide="bus" size="20"></i>
        </div>
        <button id="close-mobile-menu" style="background:none; border:none; cursor:pointer; color:var(--text-dim);">
            <i data-lucide="x" size="24"></i>
        </button>
    </div>
    
    <div class="mobile-sidebar-links">
        <a href="?page=dashboard" class="<?php echo ($safePage === 'dashboard' ? 'active' : ''); ?>"><i data-lucide="layout-dashboard" size="18"></i> Dashboard</a>
        <a href="?page=students" class="<?php echo ($safePage === 'students' ? 'active' : ''); ?>"><i data-lucide="users" size="18"></i> Students</a>
        <a href="?page=parents" class="<?php echo ($safePage === 'parents' ? 'active' : ''); ?>"><i data-lucide="heart" size="18"></i> Parents</a>
        <a href="?page=routes" class="<?php echo ($safePage === 'routes' ? 'active' : ''); ?>"><i data-lucide="map" size="18"></i> Routes</a>
        <a href="?page=enrollments" class="<?php echo ($safePage === 'enrollments' ? 'active' : ''); ?>"><i data-lucide="clipboard-list" size="18"></i> Enrollments</a>
        <a href="?page=payments" class="<?php echo ($safePage === 'payments' ? 'active' : ''); ?>"><i data-lucide="credit-card" size="18"></i> Payments</a>
        <a href="?page=reports" class="<?php echo ($safePage === 'reports' ? 'active' : ''); ?>"><i data-lucide="file-bar-chart" size="18"></i> Reports</a>
        <a href="?page=admin_support" class="<?php echo ($safePage === 'admin_support' ? 'active' : ''); ?>"><i data-lucide="message-square" size="18"></i> Messages</a>
        <div style="height: 1px; background: #eee; margin: 10px 0;"></div>
        <a href="logout.php" style="color: #ef4444;"><i data-lucide="log-out" size="18"></i> Logout</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('open-mobile-menu');
    const closeBtn = document.getElementById('close-mobile-menu');
    const overlay = document.getElementById('mobile-overlay');
    const sidebar = document.getElementById('mobile-sidebar');

    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    if(openBtn) openBtn.addEventListener('click', toggleMenu);
    if(closeBtn) closeBtn.addEventListener('click', toggleMenu);
    if(overlay) overlay.addEventListener('click', toggleMenu);
});
</script>
