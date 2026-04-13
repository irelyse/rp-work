<?php
require_once 'backend/db.php';
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['support_submit'])) {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($fullname) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO support_messages (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fullname, $email, $subject, $message]);
            $success = true;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Academy Transport | Welcome</title>
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #E63197;
            --primary-hover: #d42085;
            --secondary: #0F172A;
            --text-main: #1F2937;
            --text-dim: #64748B;
            --bg-light: #F8FAFC;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .brand { font-family: 'Outfit', sans-serif; }

        /* Public Header */
        header {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .nav-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            text-decoration: none;
        }

        .brand-icon {
            width: 40px; height: 40px;
            background: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            box-shadow: 0 4px 10px rgba(230, 49, 151, 0.2);
        }

        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a {
            text-decoration: none;
            color: var(--text-dim);
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: var(--primary); }
        
        .btn-portal {
            background: var(--secondary);
            color: white !important;
            padding: 12px 24px;
            border-radius: 100px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15);
        }
        .btn-portal:hover { background: #1E293B; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2); }

        /* Hero Section */
        .hero {
            padding: 180px 0 100px;
            position: relative;
            background: #fff;
            overflow: hidden;
            text-align: center;
        }

        /* Abstract Glow */
        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(230, 49, 151, 0.1) 0%, rgba(255,255,255,0) 70%);
            top: -200px; left: 50%; transform: translateX(-50%);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 24px;
            letter-spacing: -2px;
            color: var(--secondary);
        }

        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), #FF80C5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-dim);
            margin-bottom: 40px;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto; margin-right: auto;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            padding: 16px 36px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 10px 25px rgba(230, 49, 151, 0.3);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(230, 49, 151, 0.4);
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: var(--bg-light);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .feature-card:hover { transform: translateY(-10px); }

        .f-icon {
            width: 60px; height: 60px;
            background: rgba(230, 49, 151, 0.1);
            color: var(--primary);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
        }

        .feature-card h3 { font-size: 1.5rem; margin-bottom: 16px; color: var(--secondary); }
        .feature-card p { color: var(--text-dim); line-height: 1.6; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .contact-box {
            max-width: 800px;
            margin: 0 auto;
            background: var(--bg-light);
            padding: 50px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
        }

        .mobile-nav {
            position: fixed;
            top: 0; right: -100%;
            width: 80%; height: 100vh;
            background: #fff;
            z-index: 1000;
            padding: 100px 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
        }

        .mobile-nav.active { right: 0; }

        .mobile-nav a {
            text-decoration: none;
            color: var(--secondary);
            font-size: 1.25rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }

        .mobile-nav .btn-portal {
            text-align: center;
            margin-top: 12px;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 900px) {
            .hero h1 { font-size: 3rem; }
            .feature-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
            .form-row { grid-template-columns: 1fr; gap: 16px; }
            .contact-box { padding: 30px 20px; border-radius: 16px; }
        }
    </style>
</head>
<body>

    <header>
        <div class="container nav-wrapper">
            <a href="#" class="brand">
                <div class="brand-icon"><i data-lucide="bus-front" size="24"></i></div>
                Classic Academy
            </a>
            <div class="nav-links">
                <a href="index.php#features">Features</a>
                <a href="index.php#about">About</a>
                <a href="index.php#contact">Contact Support</a>
                <a href="login.php" class="btn-portal">Administrator Portal</a>
            </div>
            <button class="mobile-menu-btn" id="mobile-toggle">
                <i data-lucide="menu" size="28"></i>
            </button>
        </div>
    </header>

    <div class="overlay" id="nav-overlay"></div>
    <nav class="mobile-nav" id="mobile-menu">
        <a href="index.php#features" class="mob-link">Features</a>
        <a href="index.php#about" class="mob-link">About Us</a>
        <a href="index.php#contact" class="mob-link">Contact Support</a>
        <a href="login.php" class="btn-portal">Administrator Portal</a>
    </nav>

    <section class="hero">
        <div class="container hero-content">
            <h1>Next-Gen Routing & <span>Secure Transport</span></h1>
            <p>Welcome to the digital command center for Classic Academy. Oversee student transportation, track routes, and manage payments with unprecedented ease.</p>
            <a href="login.php" class="btn-primary">
                Access Admin Portal <i data-lucide="arrow-right" size="20"></i>
            </a>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div style="text-align: center; max-width: 600px; margin: 0 auto;">
                <h2 style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 16px;">Enterprise-Grade Management</h2>
                <p style="color: var(--text-dim); font-size: 1.1rem;">Built to simplify complex transport logistics while keeping student safety as the highest priority.</p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="f-icon"><i data-lucide="map" size="28"></i></div>
                    <h3>Intelligent Routing</h3>
                    <p>Dynamically manage and optimize bus routes to ensure punctual and efficient pickups and drop-offs every day.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon"><i data-lucide="credit-card" size="28"></i></div>
                    <h3>Streamlined Payments</h3>
                    <p>Track parent invoices, easily monitor outstanding balances, and safely process regular transport installments.</p>
                </div>
                <div class="feature-card">
                    <div class="f-icon"><i data-lucide="shield-check" size="28"></i></div>
                    <h3>Top-Tier Security</h3>
                    <p>State-of-the-art data encryption ensures that all student manifests and parent contact details are kept strictly secure.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" style="padding: 100px 0; background: #fff; border-top: 1px solid rgba(0,0,0,0.05);">
        <div class="container" style="display: flex; flex-wrap: wrap; align-items: center; gap: 60px;">
            <div style="flex: 1; min-width: 300px;">
                <div style="display: inline-flex; align-items: center; gap: 10px; padding: 8px 16px; border-radius: 100px; background: rgba(230, 49, 151, 0.1); color: var(--primary); font-weight: 600; font-size: 0.9rem; margin-bottom: 20px;">
                    <i data-lucide="info" size="18"></i> About Us
                </div>
                <h2 style="font-size: 3rem; color: var(--secondary); margin-bottom: 24px; line-height: 1.1; letter-spacing: -1px;">Empowering Education Through Seamless Transport</h2>
                <p style="color: var(--text-dim); font-size: 1.1rem; line-height: 1.8; margin-bottom: 24px;">
                    Classic Academy is dedicated to providing an unparalleled educational experience, and that extends to how our students commute. We believe that a safe, reliable, and well-managed transport system is foundational to student success.
                </p>
                <p style="color: var(--text-dim); font-size: 1.1rem; line-height: 1.8; margin-bottom: 32px;">
                    Our comprehensive transport management system bridges the gap between administrators, drivers, and parents, ensuring real-time coordination, transparent billing, and absolute peace of mind for our community in the Ntarabana sector and beyond.
                </p>
                <div style="display: flex; gap: 32px;">
                    <div>
                        <h4 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 4px; font-weight: 800;">100%</h4>
                        <p style="color: var(--text-dim); font-size: 0.95rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Safety Record</p>
                    </div>
                    <div>
                        <h4 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 4px; font-weight: 800;">24/7</h4>
                        <p style="color: var(--text-dim); font-size: 0.95rem; font-weight: 500; text-transform: uppercase; letter-spacing: 1px;">Admin Support</p>
                    </div>
                </div>
            </div>
            <div style="flex: 1; min-width: 300px; position: relative;">
                <div style="position: absolute; inset: 0; background: radial-gradient(circle, rgba(230,49,151,0.15) 0%, transparent 70%); transform: scale(1.3); z-index: 0;"></div>
                <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" alt="Classic Academy Transport" style="width: 100%; border-radius: 32px; box-shadow: 0 20px 40px rgba(15,23,42,0.1); position: relative; z-index: 1; border: 1px solid rgba(0,0,0,0.05);">
                <div style="position: absolute; bottom: -20px; right: -20px; background: white; padding: 24px; border-radius: 24px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); z-index: 2; display: flex; align-items: center; gap: 16px; border: 1px solid rgba(0,0,0,0.05);">
                    <div style="width: 56px; height: 56px; background: var(--primary); color: white; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(230, 49, 151, 0.3);">
                        <i data-lucide="shield-check" size="28"></i>
                    </div>
                    <div>
                        <p style="font-weight: 700; color: var(--secondary); font-size: 1.2rem;">Trusted By</p>
                        <p style="color: var(--text-dim); font-size: 0.95rem; font-weight: 500;">500+ Parents</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" style="padding: 100px 0; background: var(--bg-light);">
        <div class="container">
            <div style="text-align: center; max-width: 600px; margin: 0 auto; margin-bottom: 60px;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 16px; background: rgba(230, 49, 151, 0.1); color: var(--primary); margin-bottom: 24px;">
                    <i data-lucide="headset" size="28"></i>
                </div>
                <h2 style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 16px;">Contact Support</h2>
                <p style="color: var(--text-dim); font-size: 1.1rem;">Need assistance with routes or payments? Reach out to our dedicated support team today.</p>
                
                <?php if ($success): ?>
                <div style="background: #10B981; color: white; padding: 1rem; border-radius: 12px; margin-top: 20px; font-weight: 500;">
                    Your message has been sent successfully!
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div style="background: #EF4444; color: white; padding: 1rem; border-radius: 12px; margin-top: 20px; font-weight: 500;">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="contact-box">
                <form action="index.php#contact" method="POST" style="display: flex; flex-direction: column; gap: 24px;">
                    <div class="form-row">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 500; color: var(--secondary); font-size: 0.95rem;">Full Name</label>
                            <input type="text" name="fullname" placeholder="John Doe" required style="padding: 16px; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; width: 100%; font-family: inherit; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#E2E8F0'">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label style="font-weight: 500; color: var(--secondary); font-size: 0.95rem;">Email Address</label>
                            <input type="email" name="email" placeholder="john@example.com" required style="padding: 16px; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; width: 100%; font-family: inherit; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#E2E8F0'">
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 500; color: var(--secondary); font-size: 0.95rem;">Subject</label>
                        <select name="subject" style="padding: 16px; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; width: 100%; font-family: inherit; color: var(--text-main); font-size: 1rem; background: #fff; cursor: pointer; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#E2E8F0'">
                            <option>Route Update Request</option>
                            <option>Payment Issue</option>
                            <option>Student Information Update</option>
                            <option>General Inquiry</option>
                        </select>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="font-weight: 500; color: var(--secondary); font-size: 0.95rem;">Message</label>
                        <textarea name="message" rows="5" placeholder="How can we help you?" required style="padding: 16px; border-radius: 12px; border: 1px solid #E2E8F0; outline: none; width: 100%; font-family: inherit; font-size: 1rem; resize: vertical; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#E2E8F0'"></textarea>
                    </div>
                    <button type="submit" name="support_submit" class="btn-primary" style="justify-content: center; border: none; cursor: pointer; font-family: inherit; margin-top: 8px;">
                        Send Message <i data-lucide="send" size="20"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Premium Footer Rebranded for Classic Academy -->
    <footer style="background: #0F172A; color: white; padding: 100px 0 60px; position: relative; overflow: hidden;">
        <!-- Large Background Text (Classic Academy Brand) with Animations -->
        <div class="bg-float-text" style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); font-size: 15rem; font-weight: 900; color: rgba(230, 49, 151, 0.04); white-space: nowrap; pointer-events: none; user-select: none; font-family: 'Outfit', sans-serif; letter-spacing: -5px; animation: floatDrift 20s ease-in-out infinite;">
            CLASSIC
        </div>
        <div class="bg-float-text" style="position: absolute; bottom: 0px; left: 50%; transform: translateX(-50%); font-size: 12rem; font-weight: 900; color: rgba(230, 49, 151, 0.06); white-space: nowrap; pointer-events: none; user-select: none; font-family: 'Outfit', sans-serif; opacity: 0.5; letter-spacing: -3px; animation: floatDrift 25s ease-in-out infinite reverse;">
            ACADEMY
        </div>

        <div class="container" style="position: relative; z-index: 2;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 40px;">
                <div style="max-width: 550px; text-align: left;">
                    <h2 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 24px; font-weight: 700; font-family: 'Outfit', sans-serif;">
                        Let's build something <br>
                        <span style="color: #E63197;">extraordinary.</span>
                    </h2>
                    <div style="display: flex; gap: 24px; margin-top: 30px;">
                        <a href="#about" style="color: #94A3B8; text-decoration: none; font-weight: 500; font-size: 1rem; transition: color 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94A3B8'">About Us</a>
                        <a href="#features" style="color: #94A3B8; text-decoration: none; font-weight: 500; font-size: 1rem; transition: color 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94A3B8'">Features</a>
                        <a href="#contact" style="color: #94A3B8; text-decoration: none; font-weight: 500; font-size: 1rem; transition: color 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94A3B8'">Support</a>
                    </div>
                </div>

                <div style="text-align: right;">
                    <a href="login.php" class="btn-connect" style="display: inline-block; background: #E63197; color: white; padding: 18px 40px; border-radius: 100px; text-decoration: none; font-weight: 600; font-size: 1.1rem; box-shadow: 0 10px 30px rgba(230, 49, 151, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', sans-serif;">
                        Access Admin Portal
                    </a>
                    <div style="margin-top: 30px; color: rgba(255,255,255,0.4); font-size: 0.9rem; font-weight: 400;">
                        &copy; <?php echo date('Y'); ?> Classic Academy Transportation. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        @keyframes floatDrift {
            0%, 100% { transform: translateX(-50%) translateY(0) rotate(0deg); }
            33% { transform: translateX(-48%) translateY(-15px) rotate(1deg); }
            66% { transform: translateX(-52%) translateY(10px) rotate(-1deg); }
        }
        .bg-float-text {
            will-change: transform;
        }
        .btn-connect:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 20px 40px rgba(230, 49, 151, 0.4);
            background: #FF80C5;
        }
        @media (max-width: 768px) {
            footer h2 { font-size: 2.5rem; text-align: center; }
            footer { text-align: center; }
            footer div[style*="text-align: right"] { text-align: center; width: 100%; }
            footer div[style*="text-align: left"] { text-align: center; width: 100%; }
            footer div[style*="justify-content: space-between"] { justify-content: center; }
            footer div[style*="font-size: 15rem"] { font-size: 8rem; }
            footer div[style*="font-size: 12rem"] { font-size: 6rem; }
            footer .container div[style*="display: flex; gap: 24px"] { justify-content: center; }
        }
    </style>

    <script>
        lucide.createIcons();

        // Mobile Menu Toggle Logic
        const toggleBtn = document.getElementById('mobile-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('nav-overlay');
        const links = document.querySelectorAll('.mob-link');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = toggleBtn.querySelector('i');
            if(mobileMenu.classList.contains('active')) {
                toggleBtn.innerHTML = '<i data-lucide="x" size="28"></i>';
            } else {
                toggleBtn.innerHTML = '<i data-lucide="menu" size="28"></i>';
            }
            lucide.createIcons();
        };

        toggleBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        links.forEach(link => link.addEventListener('click', toggleMenu));
    </script>
</body>
</html>
