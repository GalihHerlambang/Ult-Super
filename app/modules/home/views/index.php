<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($setting['deskripsi'] ?? 'Layanan Pengaduan Publik Digital Terpadu Enterprise') ?>">
    <title><?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Aplikasi Pengaduan') ?> — Portal Layanan & Pengaduan Digital</title>
    <?php if (!empty($setting['favicon'])): ?>
    <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars(url($setting['favicon'])) ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= htmlspecialchars(url($setting['favicon'])) ?>">
    <?php endif; ?>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-heading: 'Outfit', sans-serif;
            
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-light: #38bdf8;
            --accent-violet: #6366f1;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            
            --bg-body: #f8fafc;
            --bg-dark: #071328;
            --bg-dark-card: rgba(15, 23, 42, 0.75);
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-dark: rgba(255, 255, 255, 0.1);
            
            --radius-2xl: 1.75rem;
            --radius-xl: 1.25rem;
            --radius-lg: 1rem;
            --radius-md: 0.75rem;
            --radius-full: 9999px;
            
            --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.03);
            --shadow-md: 0 12px 28px -6px rgba(15, 23, 42, 0.07);
            --shadow-lg: 0 24px 48px -12px rgba(15, 23, 42, 0.12);
            --shadow-glow: 0 0 50px rgba(2, 132, 199, 0.25);
            --shadow-modal: 0 32px 72px -16px rgba(7, 19, 40, 0.45);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-main);
            background: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }

        /* =========== Ambient Background Glows =========== */
        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            opacity: 0.45;
            z-index: 0;
        }
        .glow-orb-1 {
            width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.4) 0%, rgba(14, 165, 233, 0) 70%);
            top: -120px; left: 8%;
            animation: floatOrb 14s ease-in-out infinite alternate;
        }
        .glow-orb-2 {
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.35) 0%, rgba(16, 185, 129, 0) 70%);
            top: 200px; right: 4%;
            animation: floatOrb 16s ease-in-out infinite alternate-reverse;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 50px) scale(1.1); }
        }

        /* =========== Enterprise Header & Navbar =========== */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            background: rgba(7, 19, 40, 0.82);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .brand-logo {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #0ea5e9, #10b981);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.3rem;
            box-shadow: 0 4px 20px rgba(14, 165, 233, 0.45);
        }
        .brand-text h2 {
            color: #ffffff;
            font-family: var(--font-heading);
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }
        .brand-text span {
            color: #38bdf8;
            font-size: 0.725rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.25rem;
            list-style: none;
        }
        .nav-links a {
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .nav-links a:hover {
            color: #ffffff;
            transform: translateY(-1px);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .btn-nav-track {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0 !important;
            padding: 0.65rem 1.35rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        .btn-nav-track:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #fff !important;
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(255, 255, 255, 0.1);
        }

        .btn-nav-action {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #10b981 100%);
            color: #ffffff !important;
            padding: 0.65rem 1.6rem;
            border-radius: var(--radius-full);
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            box-shadow: 0 4px 20px rgba(2, 132, 199, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-nav-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(2, 132, 199, 0.65);
        }

        /* =========== Enterprise Hero Section =========== */
        .hero {
            background: radial-gradient(ellipse at top center, #0c2646 0%, #071328 85%);
            color: #ffffff;
            padding: 10.5rem 1.5rem 7.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .hero-left {
            text-align: left;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            padding: 0.55rem 1.35rem;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            color: #38bdf8;
            font-weight: 700;
            margin-bottom: 1.75rem;
            backdrop-filter: blur(14px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .hero h1 {
            font-family: var(--font-heading);
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.12;
            margin-bottom: 1.35rem;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #ffffff 30%, #7dd3fc 70%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.125rem;
            color: #94a3b8;
            margin-bottom: 2.25rem;
            line-height: 1.75;
            max-width: 680px;
        }

        /* Search Box inside Hero */
        .hero-search-box {
            position: relative;
            margin-bottom: 2.25rem;
            max-width: 650px;
        }
        .hero-search-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: var(--radius-full);
            padding: 1.15rem 1.5rem 1.15rem 3.75rem;
            color: #fff;
            font-size: 1rem;
            font-family: var(--font-main);
            backdrop-filter: blur(20px);
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.35);
        }
        .hero-search-input::placeholder { color: #94a3b8; }
        .hero-search-input:focus {
            background: rgba(255, 255, 255, 0.13);
            border-color: var(--primary-light);
            box-shadow: 0 0 35px rgba(56, 189, 248, 0.45);
        }
        .hero-search-icon {
            position: absolute;
            left: 1.4rem;
            top: 50%;
            transform: translateY(-50%);
            color: #38bdf8;
            font-size: 1.35rem;
            pointer-events: none;
        }

        .hero-btn-group {
            display: flex;
            align-items: center;
            gap: 1.15rem;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #fff;
            padding: 0.95rem 2.25rem;
            border-radius: var(--radius-full);
            font-weight: 700;
            font-size: 0.975rem;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            box-shadow: 0 8px 30px rgba(2, 132, 199, 0.5);
            transition: all 0.3s ease;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(2, 132, 199, 0.7);
            color: #fff;
        }
        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #e2e8f0;
            padding: 0.95rem 2rem;
            border-radius: var(--radius-full);
            font-weight: 600;
            font-size: 0.975rem;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            backdrop-filter: blur(12px);
            transition: all 0.3s ease;
        }
        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Hero Right Preview Glass Card */
        .hero-right {
            position: relative;
        }
        .hero-preview-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: var(--radius-2xl);
            padding: 2.25rem;
            backdrop-filter: blur(24px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            text-align: left;
            position: relative;
        }
        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            padding-bottom: 1.15rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .status-pulse {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #34d399;
            background: rgba(52, 211, 153, 0.12);
            padding: 0.35rem 0.95rem;
            border-radius: var(--radius-full);
            border: 1px solid rgba(52, 211, 153, 0.3);
        }
        .pulse-dot {
            width: 8px; height: 8px;
            background: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 12px #34d399;
            animation: pulseGlow 1.8s infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0.5; }
        }

        .quick-track-box {
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-xl);
            padding: 1.35rem;
            margin-bottom: 1.75rem;
        }
        .quick-track-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #cbd5e1;
            margin-bottom: 0.7rem;
            display: flex; align-items: center; gap: 0.55rem;
        }
        .track-input-group {
            display: flex;
            gap: 0.6rem;
        }
        .track-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: var(--radius-md);
            padding: 0.7rem 1rem;
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.25s ease;
        }
        .track-input::placeholder { color: #64748b; }
        .track-input:focus {
            background: rgba(255, 255, 255, 0.16);
            border-color: #38bdf8;
        }
        .btn-track-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.7rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4);
        }
        .btn-track-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .hero-mini-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            text-align: center;
        }
        .mini-stat-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-lg);
            padding: 1rem 0.65rem;
            transition: all 0.3s ease;
        }
        .mini-stat-box:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .mini-stat-num {
            font-family: var(--font-heading);
            font-size: 1.65rem;
            font-weight: 800;
            color: #fff;
        }
        .mini-stat-lbl {
            font-size: 0.725rem;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .hero-divider {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 70px;
            background: linear-gradient(to top, var(--bg-body), transparent);
            pointer-events: none;
        }

        /* =========== Layout Containers & Section Headers =========== */
        .container {
            max-width: 1260px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 4rem;
        }
        .section-header .tag-badge {
            display: inline-block;
            background: rgba(2, 132, 199, 0.1);
            color: var(--primary);
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0.4rem 1.15rem;
            border-radius: var(--radius-full);
            margin-bottom: 0.85rem;
            border: 1px solid rgba(2, 132, 199, 0.2);
        }
        .section-header h2 {
            font-family: var(--font-heading);
            font-size: 2.35rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .section-header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-top: 0.65rem;
            line-height: 1.65;
        }

        /* =========== Enterprise Services Section =========== */
        .layanan-section {
            padding: 5rem 0 7rem;
            position: relative;
        }

        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 0.85rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        .tab-btn {
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-full);
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.55rem;
            box-shadow: var(--shadow-sm);
        }
        .tab-btn:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            transform: translateY(-1px);
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
            gap: 2.25rem;
        }

        .card-layanan {
            background: var(--bg-card);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border);
            padding: 2.25rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .card-layanan::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0284c7, #10b981, #6366f1);
            opacity: 0;
            transition: opacity 0.35s ease;
        }
        .card-layanan:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(2, 132, 199, 0.35);
        }
        .card-layanan:hover::before {
            opacity: 1;
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.35rem;
        }

        .card-icon {
            width: 64px; height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(2,132,199,0.12), rgba(16,185,129,0.12));
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            box-shadow: inset 0 0 0 1px rgba(2, 132, 199, 0.18);
        }

        .template-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.85rem;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 700;
        }
        .template-badge.wajib {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .template-badge.opsional {
            background: rgba(2, 132, 199, 0.12);
            color: #0284c7;
            border: 1px solid rgba(2, 132, 199, 0.25);
        }

        .card-layanan h3 {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.65rem;
            line-height: 1.3;
        }
        .card-layanan p {
            color: var(--text-muted);
            font-size: 0.935rem;
            margin-bottom: 1.5rem;
            line-height: 1.65;
            flex: 1;
        }

        .card-action-btns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
            margin-top: auto;
        }

        .btn-card-primary {
            background: var(--bg-dark);
            color: #ffffff;
            padding: 0.8rem;
            border-radius: var(--radius-lg);
            font-weight: 700;
            font-size: 0.875rem;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            transition: all 0.3s ease;
        }
        .btn-card-primary:hover {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
            transform: translateY(-2px);
        }

        .btn-card-info {
            background: #f1f5f9;
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 0.8rem;
            border-radius: var(--radius-lg);
            font-weight: 700;
            font-size: 0.875rem;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-card-info:hover {
            background: #e2e8f0;
            color: var(--primary);
            border-color: #cbd5e1;
        }

        /* Empty Search State */
        .empty-search {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4.5rem 1rem;
            color: var(--text-muted);
        }
        .empty-search i {
            font-size: 3.8rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            display: block;
        }

        /* =========== Interactive Process Section =========== */
        .process-section {
            background: #ffffff;
            padding: 6.5rem 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .process-steps-tabs {
            display: flex;
            justify-content: center;
            gap: 1.15rem;
            margin-bottom: 2.75rem;
            flex-wrap: wrap;
        }
        .step-tab {
            background: var(--bg-body);
            border: 1px solid var(--border);
            padding: 1.15rem 1.6rem;
            border-radius: var(--radius-xl);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: left;
            min-width: 230px;
            flex: 1;
        }
        .step-tab.active {
            background: #ffffff;
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .step-tab .step-num-badge {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            color: var(--text-main);
            font-weight: 800;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
            transition: all 0.3s ease;
        }
        .step-tab.active .step-num-badge {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }
        .step-tab h5 {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .step-tab p {
            font-size: 0.825rem;
            color: var(--text-muted);
            margin: 0;
        }

        .step-detail-box {
            background: var(--bg-body);
            border: 1px solid var(--border);
            border-radius: var(--radius-2xl);
            padding: 2.5rem;
            max-width: 880px;
            margin: 0 auto;
            box-shadow: var(--shadow-sm);
        }

        /* =========== Features Highlight =========== */
        .features-section {
            padding: 6.5rem 0;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.25rem;
        }
        .feature-box {
            background: #ffffff;
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            padding: 2.25rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        .feature-box:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: rgba(2, 132, 199, 0.35);
        }
        .feature-icon {
            width: 54px; height: 54px;
            border-radius: 16px;
            background: rgba(2, 132, 199, 0.1);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.65rem;
            margin-bottom: 1.35rem;
        }
        .feature-box h4 {
            font-family: var(--font-heading);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.55rem;
        }
        .feature-box p {
            font-size: 0.925rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* =========== FAQ Accordion Section =========== */
        .faq-section {
            background: #ffffff;
            padding: 6.5rem 0;
            border-top: 1px solid var(--border);
        }
        .faq-container {
            max-width: 850px;
            margin: 0 auto;
        }
        .faq-item {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 1.15rem;
            overflow: hidden;
            background: var(--bg-body);
            transition: all 0.25s ease;
        }
        .faq-question {
            padding: 1.35rem 1.65rem;
            font-weight: 700;
            font-size: 1.025rem;
            color: var(--text-main);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            user-select: none;
        }
        .faq-question i {
            color: var(--primary);
            transition: transform 0.3s ease;
        }
        .faq-answer {
            padding: 0 1.65rem 1.35rem;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.65;
            display: none;
        }
        .faq-item.active {
            border-color: var(--primary-light);
            background: #ffffff;
            box-shadow: var(--shadow-sm);
        }
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        /* =========== Footer =========== */
        .footer {
            background: var(--bg-dark);
            color: #94a3b8;
            padding: 5.5rem 0 2.75rem;
            font-size: 0.925rem;
            position: relative;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }
        .footer-brand h3 {
            color: #ffffff;
            font-family: var(--font-heading);
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0.85rem;
        }
        .footer-brand p {
            font-size: 0.925rem;
            line-height: 1.75;
            max-width: 420px;
        }
        .footer-title {
            color: #ffffff;
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 1.35rem;
        }
        .footer-contact { list-style: none; }
        .footer-contact li {
            margin-bottom: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.925rem;
        }
        .footer-contact li i { color: var(--primary-light); margin-top: 0.2rem; }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 2.25rem;
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
        }

        /* =========== Enterprise Modals & Popups =========== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(7, 19, 40, 0.82);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background: #ffffff;
            border-radius: var(--radius-2xl);
            width: 100%;
            max-width: 650px;
            max-height: 88vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-modal);
            border: 1px solid rgba(226, 232, 240, 0.9);
            transform: translateY(30px) scale(0.95);
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .modal-content::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0284c7, #10b981, #f97316);
            z-index: 10;
        }
        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
        }
        .modal-body {
            padding: 1.85rem;
            overflow-y: auto;
            flex: 1;
        }

        /* Colorful Header Modal */
        .modal-colorful-header {
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0d9488 100%);
            padding: 1.85rem 2.25rem;
            color: #ffffff;
            overflow: hidden;
        }
        .modal-colorful-header .header-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.22), transparent 70%);
            pointer-events: none;
        }
        .header-icon-badge {
            width: 54px; height: 54px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.65rem;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .modal-close-btn-glow {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #ffffff;
            font-size: 1.15rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        .modal-close-btn-glow:hover {
            background: rgba(239, 68, 68, 0.85);
            border-color: rgba(255, 255, 255, 0.5);
            transform: rotate(90deg) scale(1.1);
        }
        .badge-light-glass {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            backdrop-filter: blur(8px);
            font-weight: 700;
            font-size: 0.725rem;
            padding: 0.25rem 0.8rem;
            border-radius: var(--radius-full);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-block;
        }

        /* Track Status Modal Specific Styles */
        .track-search-input-group {
            position: relative;
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-full);
            padding: 0.4rem 0.4rem 0.4rem 1.35rem;
            transition: all 0.3s ease;
        }
        .track-search-input-group:focus-within {
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15);
        }
        .track-search-input-group input {
            border: none;
            background: transparent;
            outline: none;
            box-shadow: none;
            font-weight: 700;
            color: var(--text-main);
            font-size: 0.95rem;
            width: 100%;
        }
        .track-search-input-group input::placeholder {
            color: #94a3b8;
            font-weight: 500;
        }
        .track-search-btn {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border: none;
            padding: 0.7rem 1.6rem;
            border-radius: var(--radius-full);
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.4);
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .track-search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(2, 132, 199, 0.55);
        }

        .sample-code-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(2, 132, 199, 0.08);
            border: 1px dashed rgba(2, 132, 199, 0.3);
            color: #0284c7;
            font-size: 0.775rem;
            font-weight: 700;
            padding: 0.3rem 0.85rem;
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .sample-code-chip:hover {
            background: rgba(2, 132, 199, 0.16);
            border-color: #0284c7;
            transform: translateY(-1px);
        }

        /* Enhanced Track Body Layout & Grid Cards */
        .track-search-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 1.35rem;
            padding: 1.35rem;
            margin-bottom: 1.35rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .track-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
            margin-bottom: 1.35rem;
        }
        @media (max-width: 576px) {
            .track-info-grid { grid-template-columns: 1fr; }
        }
        .track-info-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.95rem 1.15rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all 0.25s ease;
        }
        .track-info-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .track-info-icon-box {
            width: 42px; height: 42px;
            border-radius: 14px;
            background: rgba(2, 132, 199, 0.08);
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .track-info-text {
            flex: 1;
            min-width: 0;
        }
        .track-info-lbl {
            font-size: 0.725rem;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.15rem;
        }
        .track-info-val {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-copy-chip {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.75rem;
            border-radius: var(--radius-full);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .btn-copy-chip:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
        }

        /* Tracker Step Timeline Visual */
        .track-timeline {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 1.35rem 0 0.65rem;
            padding: 0 0.35rem;
        }
        .track-timeline-line {
            position: absolute;
            top: 20px;
            left: 28px;
            right: 28px;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
        }
        .track-timeline-progress {
            position: absolute;
            top: 20px;
            left: 28px;
            height: 3px;
            background: linear-gradient(90deg, #0284c7, #10b981);
            z-index: 2;
            transition: width 0.5s ease;
        }
        .track-step-node {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
        }
        .track-step-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid #cbd5e1;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 800;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .track-step-node.completed .track-step-icon {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
        }
        .track-step-node.active .track-step-icon {
            background: #0284c7;
            border-color: #38bdf8;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.25), 0 6px 20px rgba(2, 132, 199, 0.45);
            animation: pulseGlow 2s infinite;
        }
        .track-step-node.rejected .track-step-icon {
            background: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
        }
        .track-step-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            margin-top: 0.5rem;
            line-height: 1.3;
            max-width: 90px;
        }
        .track-step-node.completed .track-step-label,
        .track-step-node.active .track-step-label {
            color: var(--text-main);
        }

        .modal-tab-bar {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            padding: 0.6rem 1.85rem 0;
            gap: 0.6rem;
        }
        .modal-tab-item {
            padding: 0.8rem 1.35rem;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-muted);
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex; align-items: center; gap: 0.55rem;
        }
        .modal-tab-item:hover { color: var(--primary); }
        .modal-tab-item.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: #ffffff;
            border-top-left-radius: var(--radius-md);
            border-top-right-radius: var(--radius-md);
        }

        .modal-timeline-item {
            display: flex;
            gap: 1.35rem;
            position: relative;
            padding-bottom: 1.6rem;
        }
        .modal-timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 21px;
            top: 42px;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }
        .modal-timeline-circle {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
            flex-shrink: 0;
            z-index: 2;
        }
        .modal-timeline-body {
            flex: 1;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.15rem 1.35rem;
            box-shadow: var(--shadow-sm);
        }

        .badge-role-helpdesk { background: rgba(2, 132, 199, 0.14); color: #0284c7; border: 1px solid rgba(2, 132, 199, 0.3); }
        .badge-role-atasan { background: rgba(147, 51, 234, 0.14); color: #9333ea; border: 1px solid rgba(147, 51, 234, 0.3); }
        .badge-role-admin { background: rgba(249, 115, 22, 0.14); color: #f97316; border: 1px solid rgba(249, 115, 22, 0.3); }

        .btn-modal-cancel {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #64748b;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-modal-cancel:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .btn-modal-submit {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 40%, #10b981 100%);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.8rem 1.85rem;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            border: none;
            box-shadow: 0 6px 22px rgba(2, 132, 199, 0.45);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            cursor: pointer;
        }
        .btn-modal-submit:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 30px rgba(2, 132, 199, 0.65);
            color: #ffffff !important;
        }

        .spin-slow { animation: spinSlow 14s linear infinite; }
        @keyframes spinSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* =========== Responsive Styles =========== */
        @media (max-width: 992px) {
            .navbar { padding: 1rem 1.5rem; }
            .hero-grid { grid-template-columns: 1fr; gap: 3rem; text-align: center; }
            .hero-left { text-align: center; }
            .hero h1 { font-size: 2.65rem; }
            .hero p { margin: 0 auto 2.25rem; }
            .hero-search-box { margin: 0 auto 2.25rem; }
            .hero-btn-group { justify-content: center; }
            .footer-grid { grid-template-columns: 1fr; gap: 3rem; }
            .nav-links { display: none; }
        }

        @media (max-width: 576px) {
            .navbar { padding: 0.85rem 1.25rem; }
            .hero { padding: 8.5rem 1rem 5.5rem; }
            .hero h1 { font-size: 2.1rem; }
            .cards-grid { grid-template-columns: 1fr; }
            .card-action-btns { grid-template-columns: 1fr; }
            .container { padding: 0 1.25rem; }
        }
    </style>
</head>
<body>

    <!-- Ambient Glow Orbs -->
    <div class="glow-orb glow-orb-1"></div>
    <div class="glow-orb glow-orb-2"></div>

    <!-- Header / Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <?php if (!empty($setting['logo'])): ?>
                <img src="<?= htmlspecialchars(url($setting['logo'])) ?>" alt="Logo" style="width:44px;height:44px;border-radius:14px;object-fit:cover;">
            <?php else: ?>
                <div class="brand-logo"><?= strtoupper(substr($setting['singkatan'] ?? 'S', 0, 1)) ?></div>
            <?php endif; ?>
            <div class="brand-text">
                <h2><?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Pengaduan') ?></h2>
                <span><?= htmlspecialchars($setting['singkatan'] ?? 'SUPERADU') ?></span>
            </div>
        </div>

        <ul class="nav-links">
            <li><a href="#layanan"><i class="bi bi-grid-3x3-gap"></i> Layanan</a></li>
            <li><a href="#alur"><i class="bi bi-diagram-3"></i> Alur Proses</a></li>
            <li><a href="#keunggulan"><i class="bi bi-stars"></i> Keunggulan</a></li>
            <li><a href="#faq"><i class="bi bi-question-circle"></i> FAQ</a></li>
        </ul>

        <div class="nav-actions">
            <button class="btn-nav-track" onclick="openTrackModal()">
                <i class="bi bi-search"></i> Cek Status
            </button>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <?php
                $userRole = $_SESSION['role'] ?? 'pengadu';
                $dashboardUrl = url("{$userRole}/dashboard");
                ?>
                <a href="<?= $dashboardUrl ?>" class="btn-nav-action">
                    <i class="bi bi-speedometer2"></i> Dashboard Saya
                </a>
            <?php else: ?>
                <a href="<?= url('login') ?>" class="btn-nav-action">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Portal
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <!-- Hero Left Column -->
                <div class="hero-left">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check text-warning"></i> Portal Resmi Pelayanan & Pengaduan Digital
                    </div>
                    <h1>Layanan Pengaduan Cepat, Transparan & Akuntabel</h1>
                    <p><?= htmlspecialchars($setting['deskripsi'] ?? 'Sampaikan aspirasi dan kendala Anda dengan aman. Laporan diproses secara terstruktur melalui alur verifikasi resmi dan transparan.') ?></p>

                    <!-- Search Bar inside Hero -->
                    <div class="hero-search-box">
                        <i class="bi bi-search hero-search-icon"></i>
                        <input type="text" id="layananSearchInput" class="hero-search-input" placeholder="Cari jenis layanan pengaduan (misal: Layanan Umum, SDM, Infrastruktur)...">
                    </div>

                    <div class="hero-btn-group">
                        <a href="#layanan" class="btn-hero-primary">
                            <i class="bi bi-grid-fill"></i> Jelajahi Layanan
                        </a>
                        <button onclick="openTrackModal()" class="btn-hero-secondary">
                            <i class="bi bi-search"></i> Cek Status Aduan
                        </button>
                    </div>
                </div>

                <!-- Hero Right Column: Interactive Glass Card Mockup -->
                <div class="hero-right">
                    <div class="hero-preview-card">
                        <div class="preview-header">
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:#fff;"><i class="bi bi-shield-lock me-1" style="color:#38bdf8;"></i> Live Tracker Portal</div>
                                <div style="font-size:0.75rem;color:#94a3b8;">Integrasi Sistem Realtime</div>
                            </div>
                            <div class="status-pulse">
                                <span class="pulse-dot"></span> Sistem Aktif
                            </div>
                        </div>

                        <!-- Quick Track Box inside Card -->
                        <div class="quick-track-box">
                            <div class="quick-track-title">
                                <i class="bi bi-search" style="color:#38bdf8;"></i> Lacak Status Pengaduan Kamu
                            </div>
                            <div class="track-input-group">
                                <input type="text" id="quickTrackInput" class="track-input" placeholder="Contoh: ADU-2026-0001">
                                <button class="btn-track-submit" onclick="submitQuickTrack()">
                                    Cari
                                </button>
                            </div>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="hero-mini-stats">
                            <div class="mini-stat-box">
                                <div class="mini-stat-num"><?= $stats['total_layanan'] ?></div>
                                <div class="mini-stat-lbl">Layanan</div>
                            </div>
                            <div class="mini-stat-box">
                                <div class="mini-stat-num"><?= $stats['total_aduan'] ?></div>
                                <div class="mini-stat-lbl">Aduan</div>
                            </div>
                            <div class="mini-stat-box">
                                <div class="mini-stat-num" style="color:#34d399;"><?= $stats['selesai'] ?></div>
                                <div class="mini-stat-lbl">Selesai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-divider"></div>
    </section>

    <!-- Services Cards Section -->
    <section class="layanan-section" id="layanan">
        <div class="container">
            <div class="section-header">
                <span class="tag-badge">Katalog Layanan</span>
                <h2>Pilih Kategori Pengaduan Anda</h2>
                <p>Klik layanan yang sesuai untuk mengunduh template syarat (jika ada) dan mengisi formulir pengajuan.</p>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="tab-btn active" data-filter="all">
                    <i class="bi bi-grid-fill"></i> Semua Layanan (<?= count($layanans) ?>)
                </button>
                <button class="tab-btn" data-filter="wajib">
                    <i class="bi bi-file-earmark-check-fill" style="color:#f59e0b;"></i> Draft Dokumen Wajib
                </button>
                <button class="tab-btn" data-filter="opsional">
                    <i class="bi bi-file-earmark-text-fill" style="color:#0284c7;"></i> Draft Dokumen Opsional
                </button>
            </div>

            <?php if (empty($layanans)): ?>
                <div class="empty-search">
                    <i class="bi bi-inbox"></i>
                    <h3>Belum Ada Layanan Aktif</h3>
                    <p>Layanan pengaduan akan segera ditampilkan setelah dikonfigurasi Administrator.</p>
                </div>
            <?php else: ?>
                <div class="cards-grid" id="layananGridContainer">
                    <?php
                    $icons = ['bi-briefcase-fill', 'bi-tools', 'bi-people-fill', 'bi-building-gear', 'bi-file-earmark-text-fill', 'bi-shield-exclamation'];
                    $palettes = [
                        ['bg' => 'linear-gradient(135deg, rgba(16,185,129,0.14), rgba(52,211,153,0.14))', 'color' => '#10b981', 'border' => 'rgba(16,185,129,0.25)'],
                        ['bg' => 'linear-gradient(135deg, rgba(249,115,22,0.14), rgba(251,146,60,0.14))', 'color' => '#f97316', 'border' => 'rgba(249,115,22,0.25)'],
                        ['bg' => 'linear-gradient(135deg, rgba(2,132,199,0.14), rgba(56,189,248,0.14))', 'color' => '#0284c7', 'border' => 'rgba(2,132,199,0.25)'],
                        ['bg' => 'linear-gradient(135deg, rgba(20,184,166,0.14), rgba(45,212,191,0.14))', 'color' => '#14b8a6', 'border' => 'rgba(20,184,166,0.25)'],
                        ['bg' => 'linear-gradient(135deg, rgba(37,99,235,0.14), rgba(96,165,250,0.14))', 'color' => '#2563eb', 'border' => 'rgba(37,99,235,0.25)'],
                        ['bg' => 'linear-gradient(135deg, rgba(245,158,11,0.14), rgba(252,211,77,0.14))', 'color' => '#d97706', 'border' => 'rgba(245,158,11,0.25)'],
                    ];
                    foreach ($layanans as $idx => $layanan):
                        $iconClass = $icons[$idx % count($icons)];
                        $palette   = $palettes[$idx % count($palettes)];
                        $targetUrl = !empty($_SESSION['user_id']) ? url('pengadu/aduan/buat') : url('login');
                        $hasTemplate = !empty($layanan['file_template']);
                        $isWajib = !empty($layanan['is_template_wajib']);
                        $filterCategory = $hasTemplate ? ($isWajib ? 'wajib' : 'opsional') : 'tanpa';
                    ?>
                    <div class="card-layanan" data-category="<?= $filterCategory ?>" data-title="<?= strtolower(htmlspecialchars($layanan['nama'])) ?>">
                        <div class="card-top">
                            <div class="card-icon" style="background:<?= $palette['bg'] ?>;color:<?= $palette['color'] ?>;box-shadow:inset 0 0 0 1px <?= $palette['border'] ?>;">
                                <i class="bi <?= $iconClass ?>"></i>
                            </div>
                            <?php if ($hasTemplate): ?>
                                <?php if ($isWajib): ?>
                                    <span class="template-badge wajib" title="Wajib Upload Berkas Template">
                                        <i class="bi bi-exclamation-circle-fill"></i> Syarat Berkas Wajib
                                    </span>
                                <?php else: ?>
                                    <span class="template-badge opsional" title="Berkas Template Opsional">
                                        <i class="bi bi-info-circle-fill"></i> Template Opsional
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <h3><?= htmlspecialchars($layanan['nama']) ?></h3>
                        <p><?= htmlspecialchars($layanan['deskripsi'] ?? 'Formulir pengaduan resmi untuk kategori ini.') ?></p>

                        <div class="card-action-btns">
                            <a href="<?= $targetUrl ?>" class="btn-card-primary">
                                <i class="bi bi-plus-circle-fill"></i> Ajukan
                            </a>
                            <button type="button" class="btn-card-info" onclick="openDetailModal(<?= htmlspecialchars(json_encode([
                                'nama' => $layanan['nama'],
                                'deskripsi' => $layanan['deskripsi'] ?? 'Tidak ada deskripsi khusus.',
                                'has_template' => $hasTemplate,
                                'nama_file_template' => $layanan['nama_file_template'] ?? null,
                                'file_template' => $hasTemplate ? url($layanan['file_template']) : null,
                                'is_wajib' => $isWajib,
                                'tahapan' => $layanan['tahapan_list'] ?? [],
                                'tahapan_detail' => $layanan['tahapan_detail'] ?? [],
                                'target_url' => $targetUrl
                            ])) ?>)">
                                <i class="bi bi-info-circle"></i> Detail & SLA
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="empty-search" id="noSearchResults" style="display:none;">
                    <i class="bi bi-search"></i>
                    <h3>Layanan Tidak Ditemukan</h3>
                    <p>Tidak ada layanan yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Interactive Process Section -->
    <section class="process-section" id="alur">
        <div class="container">
            <div class="section-header">
                <span class="tag-badge">Transparan & Terukur</span>
                <h2>4 Langkah Mudah Mengajukan Laporan</h2>
                <p>Klik tiap tahapan di bawah ini untuk melihat detail alur pengajuan laporan.</p>
            </div>

            <!-- Interactive Step Tabs -->
            <div class="process-steps-tabs">
                <div class="step-tab active" onclick="switchStep(1, this)">
                    <div class="step-num-badge">1</div>
                    <h5>1. Pilih Layanan</h5>
                    <p>Pilih kategori & unduh draft dokumen.</p>
                </div>
                <div class="step-tab" onclick="switchStep(2, this)">
                    <div class="step-num-badge">2</div>
                    <h5>2. Isi & Upload</h5>
                    <p>Lengkapi form & unggah berkas syarat.</p>
                </div>
                <div class="step-tab" onclick="switchStep(3, this)">
                    <div class="step-num-badge">3</div>
                    <h5>3. Verifikasi Approval</h5>
                    <p>Petugas & Atasan memproses laporan.</p>
                </div>
                <div class="step-tab" onclick="switchStep(4, this)">
                    <div class="step-num-badge">4</div>
                    <h5>4. Selesai & Tracking</h5>
                    <p>Pantau progres hingga laporan selesai.</p>
                </div>
            </div>

            <!-- Step Detail Container -->
            <div class="step-detail-box" id="stepDetailDisplay">
                <div style="display:flex; align-items:center; gap:1.25rem; margin-bottom:1rem;">
                    <div style="width:52px;height:52px;border-radius:16px;background:rgba(2,132,199,0.12);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0;">
                        <i class="bi bi-grid-3x3-gap-fill" id="stepIcon"></i>
                    </div>
                    <div>
                        <h3 style="font-family:var(--font-heading);font-size:1.3rem;font-weight:800;margin:0;" id="stepTitle">Langkah 1: Pilih Layanan Pengaduan</h3>
                        <div style="font-size:0.85rem;color:var(--text-muted);" id="stepSubtitle">Temukan kategori layanan yang tepat untuk permohonan atau aduan Anda.</div>
                    </div>
                </div>
                <p style="color:var(--text-muted);font-size:0.975rem;line-height:1.75;margin:0;" id="stepDescription">
                    Jelajahi berbagai katalog layanan yang tersedia pada portal. Jika layanan memiliki dokumen persyaratannya, Anda dapat mengklik tombol <strong>"Draft Dokumen"</strong> untuk mengunduh template resmi sebelum mengisi formulir.
                </p>
            </div>
        </div>
    </section>

    <!-- Key Features Highlight Section -->
    <section class="features-section" id="keunggulan">
        <div class="container">
            <div class="section-header">
                <span class="tag-badge">Fitur Unggulan</span>
                <h2>Keunggulan Portal Pengaduan</h2>
                <p>Dirancang untuk memberikan kemudahan dan kepastian bagi pengguna.</p>
            </div>

            <div class="features-grid">
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
                    <h4>Realtime Tracking</h4>
                    <p>Setiap tahapan tindak lanjut dapat dipantau langsung dalam timeline visual yang transparan.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-folder-check"></i></div>
                    <h4>Dokumen & Template Syarat</h4>
                    <p>Dukungan pengunggahan berkas template syarat yang otomatis divalidasi oleh sistem.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    <h4>Workflow Dinamis</h4>
                    <p>Alur verifikasi multi-level yang dapat menyesuaikan jenis layanan secara efisien.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <h4>Aman & Rahasia</h4>
                    <p>Kerahasiaan data pelapor terlindungi dengan standar keamanan hak akses berbasis role.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Accordion Section -->
    <section class="faq-section" id="faq">
        <div class="container">
            <div class="section-header">
                <span class="tag-badge">FAQ</span>
                <h2>Pertanyaan Sering Diajukan</h2>
                <p>Jawaban atas pertanyaan umum seputar pengajuan dan alur pengaduan.</p>
            </div>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bagaimana cara membuat aduan baru?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Anda dapat memilih kategori layanan di halaman ini, mengeklik tombol "Ajukan Pengaduan", lalu masuk/login ke portal untuk melengkapi formulir dan berkas yang diperlukan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Apakah saya harus mengunggah file berkas template?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Tergantung jenis layanan. Jika pada layanan terdapat indikator "Syarat Berkas Wajib", maka Anda wajib mengunduh template yang disediakan, mengisinya, dan mengunggahnya kembali saat pengajuan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Bagaimana saya mengetahui perkembangan aduan saya?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Setelah aduan diajukan, Anda dapat memantau posisi tahapan, riwayat persetujuan, dan catatan dari petugas secara realtime melalui menu "Aduan Saya" di dashboard pengguna atau menggunakan tombol "Cek Status" di halaman depan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Berapa lama proses verifikasi pengaduan dilakukan?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Waktu penanganan disesuaikan dengan standar SLA pada masing-masing tahapan layanan. Anda dapat melihat progres visual pada detail aduan Anda.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <?php if (!empty($setting['logo_footer'])): ?>
                        <img src="<?= htmlspecialchars(url($setting['logo_footer'])) ?>" alt="Logo Footer" style="max-height:50px; border-radius:10px; object-fit:contain; margin-bottom:1rem;">
                    <?php elseif (!empty($setting['logo'])): ?>
                        <img src="<?= htmlspecialchars(url($setting['logo'])) ?>" alt="Logo Footer" style="max-height:50px; border-radius:10px; object-fit:contain; margin-bottom:1rem;">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($setting['nama_aplikasi'] ?? 'Super Aplikasi Pengaduan') ?></h3>
                    <p><?= htmlspecialchars($setting['deskripsi'] ?? 'Sistem informasi pelayanan dan pengaduan publik terpadu.') ?></p>
                </div>

                <div>
                    <h4 class="footer-title">Kontak & Alamat</h4>
                    <ul class="footer-contact">
                        <?php if (!empty($setting['alamat'])): ?>
                            <li><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($setting['alamat']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($setting['telepon'])): ?>
                            <li><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($setting['telepon']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($setting['email'])): ?>
                            <li><i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($setting['email']) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Akses Portal</h4>
                    <ul class="footer-contact">
                        <li>
                            <a href="<?= url('login') ?>" style="color:var(--primary-light);font-weight:700;">
                                <i class="bi bi-box-arrow-in-right"></i> Login Petugas & User
                            </a>
                        </li>
                        <?php if (!empty($setting['website'])): ?>
                            <li style="margin-top:0.6rem;">
                                <a href="<?= htmlspecialchars($setting['website']) ?>" target="_blank">
                                    <i class="bi bi-globe"></i> Website Resmi Portal
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <?= htmlspecialchars($setting['footer_text'] ?? '© 2026 Super Aplikasi Pengaduan. All Rights Reserved.') ?>
            </div>
        </div>
    </footer>

    <!-- Interactive Track Status Modal -->
    <div class="modal-overlay" id="trackModal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-colorful-header">
                <div class="header-overlay"></div>
                <div style="position:relative;z-index:2;display:flex;align-items:flex-start;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:1.15rem;">
                        <div class="header-icon-badge">
                            <i class="bi bi-radar spin-slow"></i>
                        </div>
                        <div>
                            <span class="badge-light-glass" style="margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:0.05em;font-size:0.7rem;"><i class="bi bi-broadcast me-1"></i> Live Status Engine</span>
                            <h3 style="color:#ffffff;font-family:var(--font-heading);font-size:1.4rem;font-weight:800;margin:0;">Lacak Status Pengaduan</h3>
                        </div>
                    </div>
                    <button type="button" class="modal-close-btn-glow" onclick="closeTrackModal()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>

            <div class="modal-body">
                <!-- Search Box Wrapper Card -->
                <div class="track-search-box">
                    <label style="font-weight:700;font-size:0.875rem;color:var(--text-main);margin-bottom:0.6rem;display:block;">
                        <i class="bi bi-search" style="color:var(--primary);"></i> Masukkan Kode Pengaduan Anda
                    </label>
                    <div class="track-search-input-group">
                        <i class="bi bi-upc-scan" style="font-size:1.25rem;color:var(--primary);margin-right:0.6rem;"></i>
                        <input type="text" id="modalKodeInput" placeholder="Contoh: ADU-2026-0001" 
                               onkeypress="if(event.key==='Enter') executeModalSearch()">
                        <button type="button" class="track-search-btn" onclick="executeModalSearch()">
                            <i class="bi bi-search"></i> Cari Data
                        </button>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.6rem;margin-top:0.85rem;">
                        <span class="sample-code-chip" onclick="fillTrackCode('ADU-2026-0001')">
                            <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Klik Sampel: <strong>ADU-2026-0001</strong>
                        </span>
                        <span class="sample-code-chip" onclick="fillTrackCode('ADU-2026-0002')">
                            <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Klik Sampel: <strong>ADU-2026-0002</strong>
                        </span>
                    </div>
                </div>

                <div id="modalSearchResult" style="margin-top:1rem;">
                    <!-- Default Guidance Card -->
                    <div style="padding:1.75rem;text-align:center;border-radius:1.35rem;background:linear-gradient(135deg, rgba(2,132,199,0.03), rgba(16,185,129,0.03));border:1px dashed rgba(2,132,199,0.25);">
                        <div style="width:64px;height:64px;border-radius:50%;background:rgba(2,132,199,0.12);color:#0284c7;display:inline-flex;align-items:center;justify-content:center;font-size:2.2rem;box-shadow:0 6px 18px rgba(2,132,199,0.18);margin-bottom:1rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 style="font-weight:800;font-size:1.15rem;margin-bottom:0.35rem;color:var(--text-main);">Sistem Lacak Pengaduan Transparan</h6>
                        <p style="font-size:0.85rem;color:var(--text-muted);max-width:460px;margin:0 auto 1.25rem;line-height:1.65;">
                            Masukkan Kode Pengaduan untuk memantau status terkini, melihat alur verifikasi multi-level, serta mengunduh dokumen SK hasil secara realtime.
                        </p>
                        <div style="display:flex;align-items:center;justify-content:center;gap:1.15rem;flex-wrap:wrap;font-size:0.825rem;color:var(--text-muted);font-weight:700;">
                            <span><i class="bi bi-check-circle-fill text-success" style="margin-right:0.35rem;"></i> Verifikasi Helpdesk</span>
                            <span><i class="bi bi-check-circle-fill text-success" style="margin-right:0.35rem;"></i> Approval Atasan</span>
                            <span><i class="bi bi-check-circle-fill text-success" style="margin-right:0.35rem;"></i> Terbit SK</span>
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding:1rem 1.85rem;display:flex;justify-content:space-between;align-items:center;border-top:1px solid var(--border);background:#f8fafc;">
                <span style="font-size:0.825rem;color:var(--text-muted);"><i class="bi bi-shield-lock-fill text-success" style="margin-right:0.35rem;"></i> Data Terdekripsi & Aman</span>
                <button type="button" class="btn-modal-cancel" onclick="closeTrackModal()">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Interactive Service Detail Modal -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content" style="max-width: 680px;">
            <div class="modal-colorful-header" id="detailModalHeader">
                <div class="header-overlay"></div>
                <div style="position:relative;z-index:2;display:flex;align-items:flex-start;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:1.15rem;">
                        <div class="header-icon-badge" id="modalHeaderIcon">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <div>
                            <span class="badge-light-glass" id="modalCategoryTag" style="margin-bottom:0.35rem;">Detail Informasi Layanan</span>
                            <h3 id="detailModalTitle" style="color:#ffffff;font-family:var(--font-heading);font-size:1.4rem;font-weight:800;margin:0;">Detail Layanan</h3>
                        </div>
                    </div>
                    <button type="button" class="modal-close-btn-glow" onclick="closeDetailModal()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>

            <!-- Tab Navigation Inside Modal -->
            <div class="modal-tab-bar" id="modalTabBar">
                <div class="modal-tab-item active" onclick="switchModalTab('tab-info', this)">
                    <i class="bi bi-info-circle-fill"></i> Informasi
                </div>
                <div class="modal-tab-item" onclick="switchModalTab('tab-alur', this)">
                    <i class="bi bi-diagram-3-fill"></i> Alur Tahapan
                </div>
                <div class="modal-tab-item" id="modalTabDokumenBtn" onclick="switchModalTab('tab-dokumen', this)" style="display:none;">
                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Berkas Syarat
                </div>
            </div>
            
            <div class="modal-body" id="detailModalBody" style="background:#ffffff; max-height: 60vh; overflow-y: auto;">
                <!-- Populated dynamically by JS -->
            </div>

            <div style="padding:1.15rem 1.85rem;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);background:#f8fafc;" id="detailModalFooter">
                <!-- Dynamic Footer CTA -->
            </div>
        </div>
    </div>

    <!-- Interactive JavaScript -->
    <script>
        // Realtime Search & Category Filter for Layanan Cards
        const searchInput = document.getElementById('layananSearchInput');
        const tabBtns = document.querySelectorAll('.tab-btn');
        const cardItems = document.querySelectorAll('.card-layanan');
        const noResults = document.getElementById('noSearchResults');

        let currentFilter = 'all';

        function filterCards() {
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            let visibleCount = 0;

            cardItems.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const category = card.getAttribute('data-category') || '';

                const matchesSearch = title.includes(query);
                const matchesCategory = (currentFilter === 'all') || (category === currentFilter);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                filterCards();
            });
        });

        // FAQ Accordion Toggle
        document.querySelectorAll('.faq-question').forEach(q => {
            q.addEventListener('click', function() {
                const item = this.parentElement;
                const answer = item.querySelector('.faq-answer');

                if (item.classList.contains('active')) {
                    item.classList.remove('active');
                    answer.style.display = 'none';
                } else {
                    document.querySelectorAll('.faq-item').forEach(i => {
                        i.classList.remove('active');
                        const a = i.querySelector('.faq-answer');
                        if (a) a.style.display = 'none';
                    });
                    item.classList.add('active');
                    answer.style.display = 'block';
                }
            });
        });

        // Interactive Process Step Switcher
        const stepData = {
            1: {
                icon: 'bi-grid-3x3-gap-fill',
                title: 'Langkah 1: Pilih Layanan Pengaduan',
                subtitle: 'Temukan kategori layanan yang tepat untuk permohonan atau aduan Anda.',
                desc: 'Jelajahi berbagai katalog layanan yang tersedia pada portal. Jika layanan memiliki dokumen persyaratannya, Anda dapat mengklik tombol <strong>"Draft Dokumen"</strong> untuk mengunduh template resmi sebelum melengkapi formulir.'
            },
            2: {
                icon: 'bi-file-earmark-arrow-up-fill',
                title: 'Langkah 2: Isi Form & Upload Berkas Syarat',
                subtitle: 'Lengkapi rincian laporan dan lampirkan berkas yang disyaratkan.',
                desc: 'Masuk ke portal pengaduan, isi formulir dinamis sesuai kronologi kejadian, dan unggah berkas template syarat yang telah diisi. Sistem secara otomatis memverifikasi kelengkapan berkas wajib Anda.'
            },
            3: {
                icon: 'bi-diagram-3-fill',
                title: 'Langkah 3: Verifikasi & Multi-level Approval',
                subtitle: 'Tim Helpdesk & Atasan memproses laporan Anda secara bertahap.',
                desc: 'Laporan Anda akan ditinjau oleh petugas Helpdesk dan diteruskan kepada Atasan sesuai alur workflow layanan. Setiap rekomendasi dan persetujuan akan tercatat secara transparan.'
            },
            4: {
                icon: 'bi-check-circle-fill',
                title: 'Langkah 4: Selesai & Realtime Tracking',
                subtitle: 'Pantau perkembangan laporan kapan saja dengan Kode Aduan.',
                desc: 'Dapatkan status terkini laporan hingga dinyatakan Selesai. Anda dapat memantau pergerakan aduan Anda secara realtime di dashboard atau melalui tombol <strong>Cek Status</strong> di halaman depan.'
            }
        };

        function switchStep(stepNum, el) {
            document.querySelectorAll('.step-tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');

            const info = stepData[stepNum];
            if (info) {
                document.getElementById('stepIcon').className = 'bi ' + info.icon;
                document.getElementById('stepTitle').textContent = info.title;
                document.getElementById('stepSubtitle').textContent = info.subtitle;
                document.getElementById('stepDescription').innerHTML = info.desc;
            }
        }

        // Track Modal Functions & Interactive Logic
        function openTrackModal() {
            document.getElementById('trackModal').classList.add('active');
            const input = document.getElementById('modalKodeInput');
            if (input) setTimeout(() => input.focus(), 150);
        }
        function closeTrackModal() {
            document.getElementById('trackModal').classList.remove('active');
        }

        function fillTrackCode(kode) {
            const input = document.getElementById('modalKodeInput');
            if (input) {
                input.value = kode;
                executeModalSearch();
            }
        }

        function copyToClipboard(text, el) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = el.innerHTML;
                el.innerHTML = '<i class="bi bi-check2 text-success me-1"></i> Tersalin!';
                setTimeout(() => el.innerHTML = orig, 2000);
            });
        }

        function submitQuickTrack() {
            const input = document.getElementById('quickTrackInput');
            if (input && input.value.trim()) {
                document.getElementById('modalKodeInput').value = input.value.trim();
                openTrackModal();
                executeModalSearch();
            }
        }

        function executeModalSearch() {
            const kode = document.getElementById('modalKodeInput').value.trim();
            const resContainer = document.getElementById('modalSearchResult');

            if (!kode) {
                resContainer.innerHTML = `
                    <div style="padding:1.25rem;text-align:center;border-radius:1.15rem;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:1.8rem;display:block;margin-bottom:0.35rem;"></i>
                        <div style="font-weight:800;font-size:0.9rem;color:#d97706;">Kode Aduan Tidak Boleh Kosong</div>
                        <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.25rem;">Silakan ketikkan nomor kode aduan Anda pada kolom pencarian di atas.</div>
                    </div>
                `;
                return;
            }

            resContainer.innerHTML = `
                <div style="padding:1.75rem;text-align:center;border-radius:1.25rem;background:#f8fafc;border:1px solid var(--border);">
                    <i class="bi bi-arrow-repeat spin text-primary" style="font-size:2.4rem;display:block;margin-bottom:0.65rem;"></i>
                    <div style="font-weight:800;font-size:0.95rem;color:var(--text-main);">Menghubungkan ke Database Realtime...</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.25rem;">Memeriksa status & alur tahapan kode <strong>${kode}</strong></div>
                </div>
            `;

            fetch(`<?= url("home/cekAduan") ?>?kode=${encodeURIComponent(kode)}`)
                .then(r => r.json())
                .then(res => {
                    if (res.success && res.data) {
                        const d = res.data;
                        let statusBadge = '';
                        let statusCardBg = '';
                        let statusBorder = '';
                        let iconHeader = '';
                        let statusDesc = '';

                        if (d.status === 'selesai') {
                            statusBadge = '<span class="badge" style="background:rgba(16,185,129,0.15); color:#10b981; border:1px solid rgba(16,185,129,0.3); font-weight:800; padding:0.45rem 1rem; font-size:0.8rem; border-radius:999px;"><i class="bi bi-check-circle-fill me-1"></i> SELESAI</span>';
                            statusCardBg = 'linear-gradient(135deg, rgba(16,185,129,0.08), rgba(2,132,199,0.04))';
                            statusBorder = 'rgba(16,185,129,0.35)';
                            iconHeader = '<div style="width:50px;height:50px;border-radius:16px;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.65rem;box-shadow:0 6px 18px rgba(16,185,129,0.35);"><i class="bi bi-patch-check-fill"></i></div>';
                            statusDesc = 'Selamat! Permohonan/pengaduan Anda telah selesai diproses oleh tim pengelola layanan.';
                        } else if (d.status === 'ditolak') {
                            statusBadge = '<span class="badge" style="background:rgba(239,68,68,0.15); color:#ef4444; border:1px solid rgba(239,68,68,0.3); font-weight:800; padding:0.45rem 1rem; font-size:0.8rem; border-radius:999px;"><i class="bi bi-x-circle-fill me-1"></i> DITOLAK</span>';
                            statusCardBg = 'linear-gradient(135deg, rgba(239,68,68,0.08), rgba(245,158,11,0.04))';
                            statusBorder = 'rgba(239,68,68,0.35)';
                            iconHeader = '<div style="width:50px;height:50px;border-radius:16px;background:#ef4444;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.65rem;box-shadow:0 6px 18px rgba(239,68,68,0.35);"><i class="bi bi-x-octagon-fill"></i></div>';
                            statusDesc = 'Permohonan Anda tidak dapat diproses lebih lanjut. Buka detail aduan untuk melihat rincian catatan penolakan.';
                        } else {
                            statusBadge = '<span class="badge" style="background:rgba(245,158,11,0.15); color:#d97706; border:1px solid rgba(245,158,11,0.3); font-weight:800; padding:0.45rem 1rem; font-size:0.8rem; border-radius:999px;"><i class="bi bi-hourglass-split me-1"></i> SEDANG DIPROSES</span>';
                            statusCardBg = 'linear-gradient(135deg, rgba(245,158,11,0.08), rgba(2,132,199,0.04))';
                            statusBorder = 'rgba(245,158,11,0.35)';
                            iconHeader = '<div style="width:50px;height:50px;border-radius:16px;background:linear-gradient(135deg, #f59e0b, #d97706);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.65rem;box-shadow:0 6px 18px rgba(245,158,11,0.35);"><i class="bi bi-gear-wide-connected spin-slow"></i></div>';
                            statusDesc = `Laporan sedang diproses pada tahap: <strong>${d.tahapan_saat_ini}</strong>.`;
                        }

                        // Build visual interactive step progress timeline
                        let timelineHtml = '';
                        if (d.steps && d.steps.length) {
                            timelineHtml += `<div style="margin-bottom:1.25rem;padding-top:0.5rem;padding-bottom:0.25rem;border-bottom:1px solid var(--border);"><div style="font-weight:800;font-size:0.725rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.75rem;"><i class="bi bi-diagram-3-fill me-1" style="color:var(--primary);"></i> Alur Progres Verifikasi</div><div class="track-timeline">`;
                            
                            const totalSteps = d.steps.length;
                            const completedCount = d.steps.filter(s => s.status === 'completed').length;
                            const progressPercent = totalSteps > 1 ? Math.min(100, Math.round((completedCount / (totalSteps - 1)) * 100)) : (d.status === 'selesai' ? 100 : 50);

                            timelineHtml += `<div class="track-timeline-line"></div>`;
                            timelineHtml += `<div class="track-timeline-progress" style="width: ${progressPercent}%;"></div>`;

                            d.steps.forEach((st, idx) => {
                                let nodeClass = st.status;
                                let iconInner = idx + 1;
                                if (st.status === 'completed') iconInner = '<i class="bi bi-check-lg"></i>';
                                else if (st.status === 'active') iconInner = '<i class="bi bi-clock-fill"></i>';
                                else if (st.status === 'rejected') iconInner = '<i class="bi bi-x-lg"></i>';

                                timelineHtml += `
                                    <div class="track-step-node ${nodeClass}" title="${st.nama} (${st.role})">
                                        <div class="track-step-icon">${iconInner}</div>
                                        <div class="track-step-label">${st.nama}</div>
                                    </div>
                                `;
                            });
                            timelineHtml += `</div></div>`;
                        }

                        let hasilCardHtml = '';
                        if (d.file_hasil) {
                            hasilCardHtml = `
                                <div style="padding:1rem;margin-bottom:1rem;background:linear-gradient(135deg, rgba(16,185,129,0.1), rgba(2,132,199,0.1));border:1px solid rgba(16,185,129,0.3);border-radius:1rem;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
                                        <div style="display:flex;align-items:center;gap:0.75rem;">
                                            <i class="bi bi-file-earmark-check-fill text-success" style="font-size:1.6rem;"></i>
                                            <div>
                                                <div style="font-weight:800;font-size:0.9rem;color:var(--text-main);">Dokumen Hasil / SK Terbit</div>
                                                <div style="font-size:0.775rem;color:var(--text-muted);">Berkas resmi hasil pemrosesan aduan Anda</div>
                                            </div>
                                        </div>
                                        <a href="${d.file_hasil}" target="_blank" style="background:#10b981;color:#fff;font-weight:800;padding:0.45rem 1.15rem;border-radius:999px;font-size:0.85rem;display:inline-flex;align-items:center;gap:0.4rem;box-shadow:0 4px 14px rgba(16,185,129,0.35);">
                                            <i class="bi bi-download"></i> Unduh SK
                                        </a>
                                    </div>
                                </div>
                            `;
                        }

                        const detailUrl = `<?= !empty($_SESSION['user_id']) ? url('pengadu/aduan/detail/') : url('login') ?>${d.id || ''}`;

                        resContainer.innerHTML = `
                            <div style="background:${statusCardBg};border:1px solid ${statusBorder};border-radius:1.35rem;padding:1.5rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;padding-bottom:0.85rem;border-bottom:1px solid var(--border);">
                                    <div style="display:flex;align-items:center;gap:0.85rem;">
                                        ${iconHeader}
                                        <div>
                                            <div style="font-size:0.725rem;color:var(--text-muted);text-transform:uppercase;font-weight:800;letter-spacing:0.05em;margin-bottom:0.15rem;">Kode Pengaduan</div>
                                            <div style="display:flex;align-items:center;gap:0.55rem;">
                                                <span style="font-weight:800;color:var(--primary);font-size:1.35rem;font-family:var(--font-heading);">${d.kode_aduan}</span>
                                                <button type="button" class="btn-copy-chip" onclick="copyToClipboard('${d.kode_aduan}', this)">
                                                    <i class="bi bi-clipboard"></i> Salin
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div>${statusBadge}</div>
                                </div>

                                <div style="font-size:0.875rem;color:var(--text-muted);margin-bottom:1.15rem;line-height:1.6;">${statusDesc}</div>

                                ${timelineHtml}

                                <div class="track-info-grid">
                                    <div class="track-info-card">
                                        <div class="track-info-icon-box" style="background:rgba(2,132,199,0.08); color:#0284c7;">
                                            <i class="bi bi-grid-fill"></i>
                                        </div>
                                        <div class="track-info-text">
                                            <div class="track-info-lbl">Kategori Layanan</div>
                                            <div class="track-info-val" title="${d.layanan_nama}">${d.layanan_nama}</div>
                                        </div>
                                    </div>

                                    <div class="track-info-card">
                                        <div class="track-info-icon-box" style="background:rgba(16,185,129,0.08); color:#10b981;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="track-info-text">
                                            <div class="track-info-lbl">Pemohon / Pengadu</div>
                                            <div class="track-info-val" title="${d.pemohon_nama}">${d.pemohon_nama}</div>
                                        </div>
                                    </div>

                                    <div class="track-info-card">
                                        <div class="track-info-icon-box" style="background:rgba(245,158,11,0.08); color:#d97706;">
                                            <i class="bi bi-diagram-3-fill"></i>
                                        </div>
                                        <div class="track-info-text">
                                            <div class="track-info-lbl">Posisi Tahapan</div>
                                            <div class="track-info-val" title="${d.tahapan_saat_ini}">${d.tahapan_saat_ini}</div>
                                        </div>
                                    </div>

                                    <div class="track-info-card">
                                        <div class="track-info-icon-box" style="background:rgba(100,116,139,0.08); color:#64748b;">
                                            <i class="bi bi-calendar-event-fill"></i>
                                        </div>
                                        <div class="track-info-text">
                                            <div class="track-info-lbl">Waktu Pengajuan</div>
                                            <div class="track-info-val">${d.created_at}</div>
                                        </div>
                                    </div>
                                </div>

                                ${hasilCardHtml}

                                <div style="display:flex;gap:0.6rem;margin-top:0.6rem;">
                                    <a href="${detailUrl}" style="flex:1;text-align:center;font-weight:800;padding:0.8rem;border-radius:999px;background:linear-gradient(135deg, #0284c7, #0369a1);color:#fff;border:none;box-shadow:0 6px 20px rgba(2,132,199,0.4);display:inline-flex;align-items:center;justify-content:center;gap:0.55rem;">
                                        <i class="bi bi-box-arrow-up-right"></i> Buka Detail Lengkap Aduan
                                    </a>
                                </div>
                            </div>
                        `;
                    } else {
                        resContainer.innerHTML = `
                            <div style="padding:1.75rem;text-align:center;border-radius:1.35rem;background:rgba(239,68,68,0.05);border:1px dashed rgba(239,68,68,0.3);">
                                <div style="width:58px;height:58px;border-radius:50%;background:rgba(239,68,68,0.1);color:#ef4444;display:inline-flex;align-items:center;justify-content:center;font-size:1.75rem;margin-bottom:0.75rem;">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h6 style="font-weight:800;color:#ef4444;margin-bottom:0.35rem;font-size:1.05rem;">Aduan Tidak Ditemukan</h6>
                                <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:0.85rem;">${res.message || 'Kode aduan yang Anda masukkan tidak terdaftar di sistem.'}</p>
                                <div style="padding:0.6rem 1rem;border-radius:0.75rem;background:#fff;border:1px solid #e2e8f0;display:inline-block;font-size:0.8rem;color:var(--text-muted);">
                                    <i class="bi bi-info-circle me-1" style="color:var(--primary);"></i> Pastikan format kode benar, contoh: <code>ADU-2026-0001</code>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    resContainer.innerHTML = `
                        <div style="padding:1.25rem;text-align:center;border-radius:1.15rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);">
                            <i class="bi bi-exclamation-circle text-danger me-1"></i> Gagal memeriksa data aduan. Silakan coba beberapa saat lagi.
                        </div>
                    `;
                });
        }

        // Colorful Service Detail Modal Controls
        function switchModalTab(tabId, el) {
            document.querySelectorAll('.modal-tab-item').forEach(t => t.classList.remove('active'));
            if (el) el.classList.add('active');

            document.querySelectorAll('.modal-tab-content').forEach(c => c.style.display = 'none');
            const target = document.getElementById(tabId);
            if (target) target.style.display = 'block';
        }

        function openDetailModal(data) {
            document.getElementById('detailModalTitle').textContent = data.nama;
            
            const icons = ['bi-briefcase-fill', 'bi-tools', 'bi-people-fill', 'bi-building-gear', 'bi-file-earmark-text-fill', 'bi-shield-check'];
            const randomIcon = icons[Math.abs(hashCode(data.nama)) % icons.length];
            document.getElementById('modalHeaderIcon').innerHTML = `<i class="bi ${randomIcon}"></i>`;

            const tabDokumenBtn = document.getElementById('modalTabDokumenBtn');
            if (data.has_template) {
                tabDokumenBtn.style.display = 'flex';
            } else {
                tabDokumenBtn.style.display = 'none';
            }

            // Tab 1: Info Content
            let infoHtml = `
                <div class="modal-tab-content" id="tab-info" style="display:block;">
                    <div style="padding:1.25rem;margin-bottom:1.35rem;background:linear-gradient(135deg, rgba(2,132,199,0.06), rgba(16,185,129,0.06));border:1px solid rgba(2,132,199,0.18);border-radius:1.15rem;">
                        <h6 style="font-weight:800;color:var(--primary);font-size:0.85rem;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.45rem;">Deskripsi Layanan</h6>
                        <p style="color:var(--text-main);font-size:0.975rem;line-height:1.65;margin:0;">${data.deskripsi}</p>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1rem;" class="mb-2">
                        <div style="padding:1rem;text-align:center;border:1px solid var(--border);background:#f8fafc;border-radius:1rem;">
                            <div style="color:var(--primary);font-size:1.5rem;margin-bottom:0.25rem;"><i class="bi bi-clock-history"></i></div>
                            <div style="font-weight:800;font-size:0.95rem;">${calcTotalSLA(data.tahapan_detail)} Hari</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Estimasi SLA</div>
                        </div>
                        <div style="padding:1rem;text-align:center;border:1px solid var(--border);background:#f8fafc;border-radius:1rem;">
                            <div style="color:#10b981;font-size:1.5rem;margin-bottom:0.25rem;"><i class="bi bi-shield-check"></i></div>
                            <div style="font-weight:800;font-size:0.95rem;">Resmi & Safed</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Proteksi Hak Akses</div>
                        </div>
                        <div style="padding:1rem;text-align:center;border:1px solid var(--border);background:#f8fafc;border-radius:1rem;">
                            <div style="color:#f59e0b;font-size:1.5rem;margin-bottom:0.25rem;"><i class="bi bi-lightning-charge-fill"></i></div>
                            <div style="font-weight:800;font-size:0.95rem;">${(data.tahapan_detail || []).length} Tahapan</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">Alur Workflow</div>
                        </div>
                    </div>
                </div>
            `;

            // Tab 2: Alur Content
            let alurHtml = `<div class="modal-tab-content" id="tab-alur" style="display:none;">`;
            if (data.tahapan_detail && data.tahapan_detail.length) {
                alurHtml += `<div style="padding-top:0.5rem;">`;
                data.tahapan_detail.forEach((t, i) => {
                    const roleClass = t.role === 'atasan' ? 'badge-role-atasan' : (t.role === 'admin' ? 'badge-role-admin' : 'badge-role-helpdesk');
                    const isTask = t.jenis === 'task_execution';
                    const internalBadge = t.is_internal ? '<span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.7rem;margin-left:0.35rem;"><i class="bi bi-eye-slash"></i> Internal</span>' : '';
                    const jenisBadge = isTask 
                        ? '<span class="badge" style="background:rgba(245,158,11,0.14);color:#d97706;font-size:0.7rem;"><i class="bi bi-tools"></i> Upload SK Hasil</span>' 
                        : '<span class="badge" style="background:rgba(2,132,199,0.12);color:#0284c7;font-size:0.7rem;"><i class="bi bi-check-circle"></i> Approval</span>';

                    alurHtml += `
                        <div class="modal-timeline-item">
                            <div class="modal-timeline-circle">${i + 1}</div>
                            <div class="modal-timeline-body">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                                    <h6 style="font-weight:800;margin:0;font-size:0.975rem;color:var(--text-main);">${t.nama}</h6>
                                    <span class="badge ${roleClass}" style="font-size:0.7rem;text-transform:uppercase;padding:0.3rem 0.65rem;border-radius:999px;">${t.role}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.45rem;">
                                    ${jenisBadge}
                                    ${internalBadge}
                                    <span style="font-size:0.775rem;color:var(--text-muted);margin-left:auto;"><i class="bi bi-clock me-1"></i>SLA ${t.sla} hari</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                alurHtml += `</div>`;
            } else {
                alurHtml += `<div style="padding:2rem;text-align:center;color:var(--text-muted);">Belum ada rincian alur tahapan.</div>`;
            }
            alurHtml += `</div>`;

            // Tab 3: Dokumen Content
            let dokumenHtml = `<div class="modal-tab-content" id="tab-dokumen" style="display:none;">`;
            if (data.has_template) {
                const isWajib = data.is_wajib;
                const reqBadge = isWajib
                    ? '<span class="badge" style="background:rgba(245,158,11,0.15); color:#d97706; border:1px solid rgba(245,158,11,0.3); font-weight:800; font-size:0.775rem; padding:0.35rem 0.85rem; border-radius:999px;"><i class="bi bi-exclamation-circle-fill me-1"></i> Syarat Berkas Wajib</span>'
                    : '<span class="badge" style="background:rgba(2,132,199,0.12); color:#0284c7; border:1px solid rgba(2,132,199,0.25); font-weight:800; font-size:0.775rem; padding:0.35rem 0.85rem; border-radius:999px;"><i class="bi bi-info-circle-fill me-1"></i> Template Opsional</span>';
                
                dokumenHtml += `
                    <div style="background:linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(2,132,199,0.08) 100%);border-radius:1.35rem;border:1px solid rgba(245,158,11,0.3);padding:1.5rem;margin-bottom:0.5rem;">
                        <div style="display:flex;align-items:flex-start;gap:1rem;margin-bottom:1rem;">
                            <div style="width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg, #f59e0b, #d97706);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;box-shadow:0 8px 20px rgba(245,158,11,0.35);flex-shrink:0;">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="margin-bottom:0.35rem;">${reqBadge}</div>
                                <h5 style="font-weight:800;color:var(--text-main);font-size:1.15rem;margin-bottom:0.25rem;">${data.nama_file_template || 'Draft Dokumen Template Resmi'}</h5>
                                <p style="font-size:0.825rem;color:var(--text-muted);margin:0;">Format berkas resmi yang telah disediakan oleh administrator.</p>
                            </div>
                        </div>

                        <!-- Step Guide Box -->
                        <div style="background:rgba(255,255,255,0.85);border-radius:1rem;border:1px solid rgba(226,232,240,0.9);padding:1.15rem;margin-bottom:1.15rem;backdrop-filter:blur(5px);">
                            <div style="font-weight:800;font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.6rem;"><i class="bi bi-list-check me-1" style="color:var(--primary);"></i> Langkah Pengisian Dokumen</div>
                            <div style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.875rem;color:var(--text-main);">
                                <div style="display:flex;align-items:center;gap:0.55rem;"><span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">1</span> Unduh berkas draft template di bawah ini</div>
                                <div style="display:flex;align-items:center;gap:0.55rem;"><span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">2</span> Isi data permohonan & lengkapi data syarat</div>
                                <div style="display:flex;align-items:center;gap:0.55rem;"><span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">3</span> Unggah berkas yang telah diisi saat membuat laporan</div>
                            </div>
                        </div>

                        <!-- Download Button -->
                        <a href="${data.file_template}" target="_blank" style="width:100%;font-weight:800;color:#fff;padding:0.85rem 1.65rem;border-radius:999px;background:linear-gradient(135deg, #f59e0b, #d97706);border:none;box-shadow:0 6px 22px rgba(245,158,11,0.45);display:inline-flex;align-items:center;justify-content:center;gap:0.65rem;transition:all 0.3s ease;">
                            <i class="bi bi-download" style="font-size:1.15rem;"></i> Unduh Draft Dokumen Syarat <i class="bi bi-box-arrow-up-right text-xs"></i>
                        </a>
                    </div>
                `;
            }
            dokumenHtml += `</div>`;

            document.getElementById('detailModalBody').innerHTML = infoHtml + alurHtml + dokumenHtml;

            const targetUrl = data.target_url || `<?= !empty($_SESSION['user_id']) ? url('pengadu/aduan/buat') : url('login') ?>`;
            document.getElementById('detailModalFooter').innerHTML = `
                <button type="button" class="btn-modal-cancel" onclick="closeDetailModal()">
                    <i class="bi bi-x-circle me-1"></i> Tutup
                </button>
                <a href="${targetUrl}" class="btn-modal-submit">
                    <i class="bi bi-send-fill me-1"></i> Ajukan Layanan Ini <i class="bi bi-arrow-right ms-1"></i>
                </a>
            `;

            const firstTab = document.querySelectorAll('.modal-tab-item')[0];
            switchModalTab('tab-info', firstTab);

            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
        }

        function calcTotalSLA(tahapanDetail) {
            if (!tahapanDetail || !tahapanDetail.length) return 3;
            return tahapanDetail.reduce((acc, t) => acc + (parseInt(t.sla) || 0), 0);
        }

        function hashCode(str) {
            let hash = 0;
            if (!str) return hash;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            return hash;
        }
    </script>
</body>
</html>
