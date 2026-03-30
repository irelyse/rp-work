<?php
session_start();
require_once 'backend/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid credentials.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Classic Academy Transport</title>
    <!-- Use Outfit for headings, Inter for body as often used in fintech -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --brand-primary: #1F2937; /* Dark text for headings */
            --brand-accent: #E63197; /* Classic Academy Pink */
            --brand-accent-hover: #d42085;
            --surface-color: #FFFFFF;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --border-color: #E5E7EB;
            --input-bg: #F9FAFB;
            --error-bg: #FEF2F2;
            --error-text: #DC2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F1F5F9;
            height: 100vh;
            display: flex;
            overflow: hidden;
            color: var(--text-primary);
        }

        /* Layout */
        .split-layout {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Left Side: Visual/Branding */
        .visual-panel {
            flex: 1;
            position: relative;
            background: linear-gradient(135deg, #2e1065 0%, #831843 50%, #be185d 100%); /* Deep purple to brand pink */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: white;
            padding: 40px;
        }

        /* Abstract Animated Mesh / Glowing Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 1;
            animation: float-orb 20s infinite ease-in-out alternate;
        }
        .orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(230, 49, 151, 0.4); /* Brand Pink */
            top: 10%;
            left: 10%;
        }
        .orb-2 {
            width: 500px;
            height: 500px;
            background: rgba(255, 128, 197, 0.3); /* Brand Light Pink */
            bottom: -10%;
            right: 10%;
            animation-delay: -5s;
        }
        .orb-3 {
            width: 300px;
            height: 300px;
            background: rgba(162, 28, 175, 0.2); /* Soft purple */
            top: 40%;
            right: 20%;
            animation-delay: -10s;
        }

        @keyframes float-orb {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, 80px) scale(1.1); }
            100% { transform: translate(-30px, -40px) scale(0.9); }
        }

        .visual-content {
            position: relative;
            z-index: 10;
            max-width: 500px;
            text-align: left;
            width: 100%;
        }

        .brand-logo-white {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 60px;
            letter-spacing: -0.5px;
        }
        .brand-logo-white i { color: #60A5FA; }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 4rem;
            line-height: 1.1;
            font-weight: 600;
            margin-bottom: 24px;
            letter-spacing: -2px;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 50px;
            max-width: 400px;
        }

        /* Glassmorphic Live Stats Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 24px;
            display: flex;
            gap: 20px;
            align-items: center;
            transform: translateY(0);
            transition: transform 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .glass-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 128, 197, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF80C5;
        }

        .stat-info h4 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        .stat-info p {
            font-size: 0.85rem;
            color: #94A3B8;
        }
        
        /* Grid pattern overlay */
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 2;
            opacity: 0.5;
        }

        /* Right Side: Login Form */
        .form-panel {
            width: 100%;
            max-width: 560px;
            background-color: var(--surface-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 80px;
            position: relative;
            z-index: 20;
            box-shadow: -20px 0 50px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 1024px) {
            .visual-panel { display: none; }
            .form-panel { max-width: 100%; padding: 40px; }
            .split-layout { justify-content: center; align-items: center; background: #F8FAFC; padding: 20px; }
            .form-panel { border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); max-width: 480px; height: auto; }
        }

        .form-header {
            margin-bottom: 40px;
            animation: fadeUp 0.6s ease-out forwards;
        }
        
        .form-header h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--brand-primary);
            margin-bottom: 12px;
            letter-spacing: -1px;
        }

        .form-header p {
            color: var(--text-secondary);
            font-size: 1.05rem;
        }

        .error-msg {
            background-color: var(--error-bg);
            color: var(--error-text);
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #FECACA;
            animation: fadeUp 0.4s ease-out forwards;
        }

        /* Liquid Modern Inputs */
        .form-group {
            position: relative;
            margin-bottom: 24px;
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .form-group:nth-child(2) { animation-delay: 0.1s; }
        .form-group:nth-child(3) { animation-delay: 0.2s; }

        .input-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: #94A3B8;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 16px 16px 16px 48px;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-control::placeholder { color: #CBD5E1; }

        .form-control:hover {
            border-color: #CBD5E1;
            background-color: #F1F5F9;
        }

        .form-control:focus {
            background-color: #FFFFFF;
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 4px rgba(230, 49, 151, 0.1);
        }

        .form-control:focus + i { color: var(--brand-accent); }

        .btn-submit {
            width: 100%;
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            opacity: 0;
            animation: fadeUp 0.6s ease-out 0.3s forwards;
        }

        .btn-submit:hover {
            background-color: var(--brand-accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(230, 49, 151, 0.2), 0 4px 6px -2px rgba(230, 49, 151, 0.1);
        }

        .btn-submit:active { transform: translateY(0); }

        .meta-links {
            margin-top: 32px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-secondary);
            opacity: 0;
            animation: fadeUp 0.6s ease-out 0.4s forwards;
        }

        .meta-links a {
            color: var(--brand-accent);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .meta-links a:hover { color: var(--brand-accent-hover); text-decoration: underline; }

        /* Security Badge */
        .security-badge {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: #94A3B8;
            opacity: 0;
            animation: fadeUp 0.6s ease-out 0.5s forwards;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>
<body>

    <div class="split-layout">
        
        <!-- Left: Visual/Branding Domain -->
        <div class="visual-panel">
            <div class="grid-overlay"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <div class="visual-content">
                <div class="brand-logo-white">
                    <i data-lucide="bus-front" size="32"></i>
                    Classic Academy
                </div>

                <h1 class="hero-title">Intelligent<br>Transit Control</h1>
                <p class="hero-subtitle">
                    Manage real-time student transportation, process payments securely, and monitor routes—all from one centralized administrative command center.
                </p>

                <!-- Premium Stats Card -->
                <div class="glass-card">
                    <div class="stat-icon">
                        <i data-lucide="shield-check" size="24"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Enterprise Grade Security</h4>
                        <p>End-to-end encrypted transport data.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form -->
        <div class="form-panel">
            <div class="form-header">
                <h2>Sign In</h2>
                <p>Welcome back to the Administrator Portal.</p>
            </div>

            <?php if ($error): ?>
                <div class="error-msg">
                    <i data-lucide="alert-circle" size="20"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="input-label">Admin Username</label>
                    <div class="input-wrapper">
                        <i data-lucide="user"></i>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="input-label">Password</label>
                    <div class="input-wrapper">
                        <i data-lucide="lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Continue firmly
                    <i data-lucide="arrow-right" size="20"></i>
                </button>
            </form>

            <div class="meta-links">
                Having trouble accessing the portal? <br>
                <a href="#">Contact Technical Support</a>
            </div>

            <div class="security-badge">
                <i data-lucide="lock-keyhole" size="14"></i>
                Secured by 256-bit encryption
            </div>
        </div>

    </div>

    <script>
        // Initialize lucide modern icons
        lucide.createIcons();
    </script>
</body>
</html>
