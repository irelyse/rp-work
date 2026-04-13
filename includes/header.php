<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
$pageNames = [
    'dashboard' => 'Dashboard',
    'students' => 'Students Management',
    'parents' => 'Parents Management',
    'routes' => 'Routes',
    'payments' => 'Payments',
    'enrollments' => 'Enrollments',
    'settings' => 'Settings',
    'reports' => 'Reports Center',
    'support' => 'Contact Support',
    'admin_support' => 'Support Messages'
];
$currentPageName = isset($pageNames[$page]) ? $pageNames[$page] : 'Classic Academy';
?>
    <title><?php echo $currentPageName; ?> | Classic Academy</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* Global Preloader Styles */
        #global-preloader {
            position: fixed;
            inset: 0;
            background-color: #F8FAFC;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }
        #global-preloader .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(230, 49, 151, 0.15);
            border-top-color: #E63197;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 16px;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .preloader-hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body>
    <!-- Global Preloader -->
    <div id="global-preloader">
        <div class="spinner"></div>
        <div style="font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 1.1rem; color: #1F2937; letter-spacing: -0.5px;">Classic Academy</div>
    </div>
    
    <div class="layout">
