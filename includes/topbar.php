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
        <!-- Rounded brand icon (replacing green hamburger in the image) -->
        <div class="brand-btn">
            <i data-lucide="bus" size="22"></i>
        </div>
        
        <!-- Pill navigation links -->
        <div class="top-links">
            <a href="?page=dashboard" class="<?php echo ($safePage === 'dashboard' ? 'active' : ''); ?>">Dashboard</a>
            <a href="?page=students" class="<?php echo ($safePage === 'students' ? 'active' : ''); ?>">Students</a>
            <a href="?page=parents" class="<?php echo ($safePage === 'parents' ? 'active' : ''); ?>">Parents</a>
            <a href="?page=routes" class="<?php echo ($safePage === 'routes' ? 'active' : ''); ?>">Routes</a>
            <a href="?page=payments" class="<?php echo ($safePage === 'payments' ? 'active' : ''); ?>">Payments</a>
        </div>
    </div>
    
    <div class="nav-right">
        <!-- Search Bar -->
        <div class="nav-search">
            <input type="text" placeholder="search...">
            <i data-lucide="search" size="18" style="color: #9CA3AF"></i>
        </div>
        
        <!-- Utility Icons -->
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
            <div class="nav-user-info">
                <span class="name"><?php echo htmlspecialchars($userName); ?></span>
            </div>
            <i data-lucide="chevron-down" size="16" style="color: #64748B; margin-left: 4px;"></i>
            
            <!-- Dropdown Menu -->
            <div id="user-dropdown" class="nav-dropdown">
                <a href="logout.php" class="dropdown-item text-red">
                    <i data-lucide="log-out" size="16"></i>
                    Secure Logout
                </a>
            </div>
        </div>
    </div>
</div>
