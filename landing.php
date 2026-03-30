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

        @media (max-width: 900px) {
            .hero h1 { font-size: 3rem; }
            .feature-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
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
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact Support</a>
                <a href="login.php" class="btn-portal">Administrator Portal</a>
            </div>
        </div>
    </header>

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

    <footer style="background: var(--secondary); color: white; padding: 60px 0; text-align: center;">
        <div class="container">
            <div style="opacity: 0.6; font-size: 0.9rem;">
                &copy; 2026 Classic Academy Transportation. Designed for secure and sustainable schooling.
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
