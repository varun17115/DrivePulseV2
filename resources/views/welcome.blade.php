<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DrivePulse — Next-Gen Driving Academy & Smart Learning Platform</title>

    <!-- Theme Initialization Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Pro/Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-rgb: 79, 70, 229;
            --secondary: #0ea5e9;
            --accent: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;

            --bg-canvas: #ffffff;
            --bg-surface: #f8fafc;
            --bg-card: #ffffff;
            --border-color: rgba(226, 232, 240, 0.8);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --navbar-bg: rgba(255, 255, 255, 0.85);
            --hero-glow: radial-gradient(circle at 50% 30%, rgba(79, 70, 229, 0.12) 0%, rgba(14, 165, 233, 0.05) 50%, transparent 80%);
            --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 0 1px 1px rgba(0,0,0,0.02);
            --card-hover-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.15), 0 0 1px 1px rgba(79, 70, 229, 0.2);
        }

        [data-bs-theme="dark"] {
            --bg-canvas: #090d16;
            --bg-surface: #0f172a;
            --bg-card: #131c31;
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --navbar-bg: rgba(9, 13, 22, 0.85);
            --hero-glow: radial-gradient(circle at 50% 30%, rgba(99, 102, 241, 0.22) 0%, rgba(14, 165, 233, 0.12) 50%, transparent 80%);
            --card-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.5), 0 0 1px 1px rgba(255, 255, 255, 0.05);
            --card-hover-shadow: 0 20px 45px -5px rgba(99, 102, 241, 0.35), 0 0 1px 1px rgba(99, 102, 241, 0.4);
        }

        * {
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-main);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Gradient Texts */
        .text-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-amber {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Glassmorphism Navbar */
        .glass-nav {
            background: var(--navbar-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-main) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(79, 70, 229, 0.08);
        }

        /* Masterpiece Hero Section */
        .hero-wrap {
            position: relative;
            background: var(--hero-glow);
            padding: 140px 0 100px;
            overflow: hidden;
        }

        .hero-badge {
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(79, 70, 229, 0.3);
            color: var(--primary);
            padding: 6px 18px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.15);
        }

        .hero-title {
            font-size: 3.8rem;
            font-weight: 900;
            line-height: 1.12;
            letter-spacing: -0.03em;
        }

        @media (max-width: 991.98px) {
            .hero-title { font-size: 2.8rem; }
            .hero-wrap { padding: 110px 0 60px; }
        }

        @media (max-width: 575.98px) {
            .hero-title { font-size: 2.2rem; }
        }

        /* Buttons */
        .btn-brand-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            font-weight: 600;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-brand-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(79, 70, 229, 0.6);
            color: #fff !important;
        }

        .btn-brand-outline {
            background: var(--bg-card);
            color: var(--text-main) !important;
            font-weight: 600;
            border: 1px solid var(--border-color);
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-brand-outline:hover {
            border-color: var(--primary);
            color: var(--primary) !important;
            transform: translateY(-2px);
        }

        /* Cards & Surfaces */
        .premium-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .premium-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-hover-shadow);
            border-color: rgba(79, 70, 229, 0.3);
        }

        .icon-box-lg {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }

        .premium-card:hover .icon-box-lg {
            transform: scale(1.1) rotate(4deg);
        }

        /* Floating Stats Mockup in Hero */
        .hero-mockup-wrapper {
            position: relative;
        }

        .floating-stat-pill {
            position: absolute;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 12px 18px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(10px);
            animation: floatSlow 4s ease-in-out infinite alternate;
            z-index: 5;
        }

        .floating-pill-1 { top: -20px; right: -15px; }
        .floating-pill-2 { bottom: -20px; left: -15px; animation-delay: 2s; }

        @keyframes floatSlow {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-10px); }
        }

        /* Section Headings */
        .section-tag {
            color: var(--primary);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            display: inline-block;
            margin-bottom: 0.75rem;
        }

        .section-heading {
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        @media (max-width: 767.98px) {
            .section-heading { font-size: 2rem; }
        }

        /* Feature Checklist */
        .feature-check-list {
            list-style: none;
            padding-left: 0;
        }

        .feature-check-list li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .feature-check-list li i {
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--success);
            font-size: 1rem;
        }

        /* Testimonials */
        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        /* Pricing Card Highlight */
        .pricing-highlight {
            border: 2px solid var(--primary) !important;
            position: relative;
        }

        .pricing-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: #fff;
            padding: 4px 16px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Grid Background Pattern */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(120, 119, 198, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(120, 119, 198, 0.05) 1px, transparent 1px);
        }

        /* Footer */
        .footer-wrap {
            background-color: var(--bg-surface);
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        .footer-link {
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
            margin-bottom: 0.5rem;
            font-size: 0.92rem;
        }

        .footer-link:hover {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* Live Pulse Dot */
        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
</head>
<body class="bg-grid-pattern">

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg glass-nav fixed-top py-3">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary text-white shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                    <i class="fa-solid fa-car-side fs-5"></i>
                </div>
                <span class="brand-font fw-bold fs-4 text-dark dark-text-light">Drive<span class="text-gradient-primary">Pulse</span></span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill text-3xs px-2 py-0.5 fw-bold">v2.0</span>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="fa-solid fa-bars-staggered fs-4 text-dark dark-text-light"></i>
            </button>

            <!-- Menu Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="#features"><i class="fa-solid fa-bolt-lightning text-primary me-1"></i> Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#courses"><i class="fa-solid fa-graduation-cap text-primary me-1"></i> Driving Programs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#mocktest"><i class="fa-solid fa-laptop-code text-primary me-1"></i> Theory Simulator</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fleet"><i class="fa-solid fa-car text-primary me-1"></i> Fleet & Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#instructors"><i class="fa-solid fa-user-tie text-primary me-1"></i> Instructors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq"><i class="fa-solid fa-circle-question text-primary me-1"></i> FAQ</a>
                    </li>
                </ul>

                <!-- Right Actions & Theme Toggle -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Dark/Light Theme Button -->
                    <button class="btn btn-sm btn-icon rounded-3 btn-brand-outline p-2 d-flex align-items-center justify-content-center" id="themeToggleBtn" type="button" title="Toggle Theme" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-moon text-primary theme-icon-moon"></i>
                        <i class="fa-solid fa-sun text-warning theme-icon-sun d-none"></i>
                    </button>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-brand-primary d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-gauge-high"></i>
                                <span>Go to Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-brand-outline">
                                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Sign In
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-brand-primary">
                                    <i class="fa-solid fa-user-plus me-1"></i> Enroll Now
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- ================= HERO SECTION ================= -->
    <section class="hero-wrap">
        <div class="container">
            <div class="row align-items-center g-5">
                
                <!-- Left: Headline & Actions -->
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800">
                    <div class="hero-badge mb-3">
                        <span class="pulse-dot"></span>
                        <span>Now Enrolling: Advanced Road Safety & Smart Driving 2026</span>
                    </div>

                    <h1 class="hero-title mb-4">
                        Master the Road with <span class="text-gradient-primary">Confidence & Precision.</span>
                    </h1>

                    <p class="lead text-muted mb-4" style="font-size: 1.15rem; line-height: 1.7;">
                        DrivePulse transforms standard driving education into a high-tech academy experience. Book certified trainers, practice on dual-control modern vehicles, and pass your theory exam on the first try with our real-time simulator.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-brand-primary btn-lg d-inline-flex align-items-center gap-2 px-4 py-3">
                                <span>Start Your Journey</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                        <a href="#courses" class="btn btn-brand-outline btn-lg d-inline-flex align-items-center gap-2 px-4 py-3">
                            <i class="fa-solid fa-compass"></i>
                            <span>Explore Courses</span>
                        </a>
                    </div>

                    <!-- Trust Stats Bar -->
                    <div class="row g-4 pt-3 border-top" style="border-color: var(--border-color) !important;">
                        <div class="col-4">
                            <h3 class="fw-bold mb-0 text-dark dark-text-light">99.2%</h3>
                            <span class="text-muted text-xs">First-Attempt Pass Rate</span>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold mb-0 text-dark dark-text-light">15,000+</h3>
                            <span class="text-muted text-xs">Certified Drivers</span>
                        </div>
                        <div class="col-4">
                            <h3 class="fw-bold mb-0 text-dark dark-text-light">5.0 <i class="fa-solid fa-star text-warning fs-6"></i></h3>
                            <span class="text-muted text-xs">From 3,200+ Reviews</span>
                        </div>
                    </div>
                </div>

                <!-- Right: High-Impact Visual Mockup Card -->
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="hero-mockup-wrapper">
                        
                        <!-- Floating Stat Pill 1 (Top-Right) -->
                        <div class="floating-stat-pill floating-pill-1">
                            <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-shield-halved fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold text-dark dark-text-light fs-8">100% Dual-Control Safety</span>
                                <span class="d-block text-muted text-3xs">Verified ADAS Certified Fleet</span>
                            </div>
                        </div>

                        <!-- Main Showcase Card -->
                        <div class="premium-card p-4 p-md-5">
                            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-4 p-3 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);">
                                        <i class="fa-solid fa-gauge-high fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark dark-text-light">Student Live Portal</h5>
                                        <span class="text-muted text-xs">Real-Time Progress Tracking</span>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold">Active Session</span>
                            </div>

                            <!-- Interactive Progress Snapshot -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold fs-8 text-dark dark-text-light">Overall Practical Mastery</span>
                                    <span class="fw-bold text-primary">88% (Ready for Test)</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 10px; background: rgba(79,70,229,0.1);">
                                    <div class="progress-bar rounded-pill" style="width: 88%; background: linear-gradient(90deg, #4f46e5 0%, #06b6d4 100%);"></div>
                                </div>
                            </div>

                            <!-- Competencies Pills -->
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border" style="border-color: var(--border-color) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fs-8 fw-semibold text-dark dark-text-light"><i class="fa-solid fa-square-parking text-primary me-1"></i> Parallel Park</span>
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                        </div>
                                        <span class="text-muted text-3xs">Mastered (Score: 5/5)</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border" style="border-color: var(--border-color) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fs-8 fw-semibold text-dark dark-text-light"><i class="fa-solid fa-road text-info me-1"></i> Highway Merge</span>
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                        </div>
                                        <span class="text-muted text-3xs">Mastered (Score: 5/5)</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border" style="border-color: var(--border-color) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fs-8 fw-semibold text-dark dark-text-light"><i class="fa-solid fa-arrows-spin text-warning me-1"></i> Roundabouts</span>
                                            <i class="fa-solid fa-circle-check text-success"></i>
                                        </div>
                                        <span class="text-muted text-3xs">Proficient (Score: 4/5)</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border" style="border-color: var(--border-color) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fs-8 fw-semibold text-dark dark-text-light"><i class="fa-solid fa-laptop-code text-purple me-1"></i> Theory Score</span>
                                            <i class="fa-solid fa-trophy text-warning"></i>
                                        </div>
                                        <span class="text-muted text-3xs">Mock Exam: 96%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Next Lesson Banner in Mockup -->
                            <div class="p-3 rounded-4 d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-calendar-check fs-4 text-info"></i>
                                    <div>
                                        <span class="d-block fw-bold fs-8">Next Session: Tomorrow at 10:00 AM</span>
                                        <span class="d-block text-white-50 text-3xs">Trainer Marcus Sterling • Honda Civic (Dual-Control)</span>
                                    </div>
                                </div>
                                <span class="badge bg-white text-dark rounded-pill px-2.5 py-1 text-3xs fw-bold">Confirmed</span>
                            </div>

                        </div>

                        <!-- Floating Stat Pill 2 (Bottom-Left) -->
                        <div class="floating-stat-pill floating-pill-2">
                            <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="fa-solid fa-qrcode fs-5"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold text-dark dark-text-light fs-8">QR-Verifiable Graduation</span>
                                <span class="d-block text-muted text-3xs">Tamper-proof Digital Certificate</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= KEY FEATURES SECTION ================= -->
    <section id="features" class="py-5" style="padding: 100px 0 !important;">
        <div class="container">
            
            <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">Why DrivePulse Academy</span>
                <h2 class="section-heading text-dark dark-text-light">Engineered for Safer, Faster & Smarter Learning</h2>
                <p class="text-muted lead" style="font-size: 1.05rem;">
                    Traditional driving schools rely on paper logbooks and guesswork. DrivePulse gives you an intelligent digital ecosystem from your very first booking to your official license.
                </p>
            </div>

            <div class="row g-4">
                
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-primary-subtle text-primary">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">Self-Service Slot Booking</h4>
                        <p class="text-muted mb-0">
                            Book, reschedule, or cancel practical and theory lessons 24/7. Select your preferred instructor, vehicle transmission (Manual/Automatic), and exact time slot.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-info-subtle text-info">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">Real-Time Mock Exam Center</h4>
                        <p class="text-muted mb-0">
                            Practice with our 2026 theory question bank. Step-by-step 1-question-at-a-time simulator with instant visual indicators, countdown timers, and pass/fail analytics.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-success-subtle text-success">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">Competency & Skill Matrix</h4>
                        <p class="text-muted mb-0">
                            Instructors evaluate you after every lesson across 15+ competencies: parallel parking, hill starts, emergency stops, mirror checks, and traffic rules.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-warning-subtle text-warning">
                            <i class="fa-solid fa-car-tunnel"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">Premium Dual-Control Fleet</h4>
                        <p class="text-muted mb-0">
                            Practice with confidence in certified dual-brake, dual-clutch vehicles maintained under rigorous multi-point mechanical inspections and weekly safety audits.
                        </p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-danger-subtle text-danger">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">Transparent Installment Plans</h4>
                        <p class="text-muted mb-0">
                            Zero hidden charges. Pay per lesson or choose flexible installment plans with instant digital payment receipts, automated due alerts, and invoice downloads.
                        </p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box-lg bg-purple-subtle text-purple" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h4 class="fw-bold text-dark dark-text-light mb-2">QR-Code Digital Certificates</h4>
                        <p class="text-muted mb-0">
                            Upon course completion, receive a globally verifiable digital driver certification featuring secure QR validation for licensing authorities and employers.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= DRIVING PROGRAMS / COURSES ================= -->
    <section id="courses" class="py-5 bg-surface" style="padding: 100px 0 !important; background-color: var(--bg-surface);">
        <div class="container">
            
            <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">Courses & Curriculum</span>
                <h2 class="section-heading text-dark dark-text-light">Curated Driving Programs For Every Goal</h2>
                <p class="text-muted lead" style="font-size: 1.05rem;">
                    Whether you are getting behind the wheel for the first time or polishing your highway reflexes, we have the perfect tailored path for you.
                </p>
            </div>

            <div class="row g-4 align-items-stretch">
                
                <!-- Plan 1: Beginner Comprehensive -->
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card p-4 p-xl-5 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-bold">Beginner Foundation</span>
                                <span class="text-muted fs-8">20 Hours</span>
                            </div>
                            <h3 class="fw-bold text-dark dark-text-light mb-2">Complete Learner Track</h3>
                            <p class="text-muted fs-8 mb-4">For complete novices wanting to pass on their first attempt with comprehensive zero-stress road education.</p>
                            
                            <div class="d-flex align-items-baseline gap-1 mb-4">
                                <span class="display-6 fw-bold text-dark dark-text-light">₹4,999</span>
                                <span class="text-muted fs-8">/ complete course</span>
                            </div>

                            <ul class="feature-check-list mb-4">
                                <li><i class="fa-solid fa-check"></i> 15 On-Road Practical Driving Lessons</li>
                                <li><i class="fa-solid fa-check"></i> 5 Theory & Hazard Awareness Classes</li>
                                <li><i class="fa-solid fa-check"></i> Unlimited Online Mock Test Access</li>
                                <li><i class="fa-solid fa-check"></i> Dual-Control Vehicle Selection</li>
                                <li><i class="fa-solid fa-check"></i> Official Test Car Rental on Exam Day</li>
                            </ul>
                        </div>

                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="btn btn-brand-outline w-100 py-2.5">
                            Enroll in Complete Track
                        </a>
                    </div>
                </div>

                <!-- Plan 2: Express Fast-Track (FEATURED) -->
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card p-4 p-xl-5 h-100 d-flex flex-column justify-content-between pricing-highlight shadow-lg">
                        <span class="pricing-badge">Most Popular</span>
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fw-bold">Fast-Track Intensive</span>
                                <span class="text-muted fs-8">30 Hours</span>
                            </div>
                            <h3 class="fw-bold text-dark dark-text-light mb-2">All-Inclusive Pro Pass</h3>
                            <p class="text-muted fs-8 mb-4">The ultimate master program with prioritized slot booking, intensive highway coaching, and guaranteed test readiness.</p>
                            
                            <div class="d-flex align-items-baseline gap-1 mb-4">
                                <span class="display-6 fw-bold text-primary">₹7,499</span>
                                <span class="text-muted fs-8">/ complete course</span>
                            </div>

                            <ul class="feature-check-list mb-4">
                                <li><i class="fa-solid fa-check"></i> 25 On-Road Intensive Practical Lessons</li>
                                <li><i class="fa-solid fa-check"></i> Highway, Night & Adverse Weather Driving</li>
                                <li><i class="fa-solid fa-check"></i> 1-on-1 Senior Instructor Assignment</li>
                                <li><i class="fa-solid fa-check"></i> Real-time Evaluation & Mock Tests</li>
                                <li><i class="fa-solid fa-check"></i> Guaranteed Test Booking Slot Assistance</li>
                                <li><i class="fa-solid fa-check"></i> QR-Verified Academy Certificate</li>
                            </ul>
                        </div>

                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="btn btn-brand-primary w-100 py-3">
                            Enroll in Pro Track
                        </a>
                    </div>
                </div>

                <!-- Plan 3: Refresher & Defensive -->
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card p-4 p-xl-5 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1.5 fw-bold">Refresher & Confidence</span>
                                <span class="text-muted fs-8">10 Hours</span>
                            </div>
                            <h3 class="fw-bold text-dark dark-text-light mb-2">Defensive Refresher</h3>
                            <p class="text-muted fs-8 mb-4">For licensed drivers wanting to overcome driving anxiety, master manual gears, or brush up on defensive maneuvers.</p>
                            
                            <div class="d-flex align-items-baseline gap-1 mb-4">
                                <span class="display-6 fw-bold text-dark dark-text-light">₹2,999</span>
                                <span class="text-muted fs-8">/ complete course</span>
                            </div>

                            <ul class="feature-check-list mb-4">
                                <li><i class="fa-solid fa-check"></i> 8 Targeted Road Sessions (Parking & Highway)</li>
                                <li><i class="fa-solid fa-check"></i> Manual Stick-Shift or EV Transition</li>
                                <li><i class="fa-solid fa-check"></i> Emergency Avoidance Techniques</li>
                                <li><i class="fa-solid fa-check"></i> Flexible Weekend Time Slots</li>
                                <li><i class="fa-solid fa-check"></i> Defensive Driving Certificate</li>
                            </ul>
                        </div>

                        <a href="{{ Route::has('register') ? route('register') : '#' }}" class="btn btn-brand-outline w-100 py-2.5">
                            Enroll in Refresher Track
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= THEORY EXAM & MOCK TEST SPOTLIGHT ================= -->
    <section id="mocktest" class="py-5" style="padding: 100px 0 !important;">
        <div class="container">
            <div class="row align-items-center g-5">
                
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="section-tag">Interactive Exam Simulator</span>
                    <h2 class="section-heading text-dark dark-text-light">Pass Your Theory Exam With Zero Anxiety</h2>
                    <p class="text-muted lead mb-4" style="font-size: 1.05rem; line-height: 1.7;">
                        Our proprietary mock test center mirrors the official road authority examination. Tackle randomized questions 1-at-a-time, review instant visual feedback, and identify knowledge gaps before taking your real test.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex gap-3">
                            <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark dark-text-light">Single Question Focus Mode</h6>
                                <p class="text-muted text-xs mb-0">Prevents cognitive overload with clear answer selection badges and real-time question navigators.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="rounded-circle p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-stopwatch"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark dark-text-light">Countdown Timer & Auto-Submit</h6>
                                <p class="text-muted text-xs mb-0">Build time management skills with realistic countdown alerts identical to the official DMV test.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-square-poll-vertical"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark dark-text-light">Detailed Explanations & Analytics</h6>
                                <p class="text-muted text-xs mb-0">Every question provides official road code rule references and illustrated diagrams.</p>
                            </div>
                        </div>
                    </div>

                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-brand-primary d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-play"></i>
                            <span>Try Mock Test Simulator</span>
                        </a>
                    @endif
                </div>

                <!-- Simulator Mockup Card -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="premium-card p-4 p-md-5 border-2 shadow-lg" style="background: linear-gradient(180deg, var(--bg-card) 0%, var(--bg-surface) 100%);">
                        
                        <!-- Question Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                            <div>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 text-3xs fw-bold">QUESTION 04 / 20</span>
                                <h6 class="fw-bold text-dark dark-text-light mb-0 mt-2">Right-of-Way at Uncontrolled Junctions</h6>
                            </div>
                            <div class="d-flex align-items-center gap-2 bg-danger-subtle text-danger px-3 py-1.5 rounded-pill border border-danger-subtle fw-bold fs-8">
                                <i class="fa-regular fa-clock"></i>
                                <span>14:28</span>
                            </div>
                        </div>

                        <!-- Question Body -->
                        <p class="fw-semibold text-dark dark-text-light mb-4" style="font-size: 1.05rem;">
                            When two vehicles arrive simultaneously at a four-way junction without signs or traffic lights, who has the right of way?
                        </p>

                        <!-- Answer Options -->
                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-light text-dark rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">A</span>
                                    <span class="fs-8 text-muted">The vehicle driving at the highest speed</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-3 border border-2 border-primary d-flex align-items-center justify-content-between shadow-sm" style="background: rgba(79, 70, 229, 0.08);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-primary text-white rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">B</span>
                                    <span class="fs-8 fw-bold text-primary">The vehicle approaching from the right side</span>
                                </div>
                                <i class="fa-solid fa-circle-check text-primary fs-5"></i>
                            </div>

                            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-light text-dark rounded-circle p-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">C</span>
                                    <span class="fs-8 text-muted">The larger commercial vehicle</span>
                                </div>
                            </div>
                        </div>

                        <!-- Question Navigator Bar -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                            <button class="btn btn-sm btn-light border px-3 rounded-pill" disabled><i class="fa-solid fa-chevron-left me-1"></i> Prev</button>
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fs-8">Selected: Option B</span>
                            <button class="btn btn-sm btn-primary px-3 rounded-pill">Next <i class="fa-solid fa-chevron-right ms-1"></i></button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FLEET & VEHICLES ================= -->
    <section id="fleet" class="py-5 bg-surface" style="padding: 100px 0 !important; background-color: var(--bg-surface);">
        <div class="container">
            
            <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">Modern Fleet</span>
                <h2 class="section-heading text-dark dark-text-light">Trained on Latest Generation Vehicles</h2>
                <p class="text-muted lead" style="font-size: 1.05rem;">
                    Every car in our academy fleet is under 3 years old, equipped with certified dual controls, reversing cameras, and blind-spot monitors.
                </p>
            </div>

            <div class="row g-4">
                
                <!-- Vehicle 1 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card h-100">
                        <div class="p-4 bg-light border-bottom text-center position-relative" style="border-color: var(--border-color) !important;">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3 rounded-pill px-3 py-1 text-3xs">Manual 6-Speed</span>
                            <i class="fa-solid fa-car-side text-primary my-4 d-block" style="font-size: 5rem;"></i>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold text-dark dark-text-light mb-0">Honda Civic 1.5T</h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Dual-Control</span>
                            </div>
                            <p class="text-muted fs-8 mb-3">Ideal for mastering clutch bite points, hill-starts, and precision parallel parking.</p>
                            <div class="d-flex justify-content-between text-xs text-muted pt-2 border-top" style="border-color: var(--border-color) !important;">
                                <span><i class="fa-solid fa-shield-halved text-success me-1"></i> 5-Star Safety</span>
                                <span><i class="fa-solid fa-gas-pump text-primary me-1"></i> Fuel Efficient</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle 2 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card h-100">
                        <div class="p-4 bg-light border-bottom text-center position-relative" style="border-color: var(--border-color) !important;">
                            <span class="badge bg-info position-absolute top-0 start-0 m-3 rounded-pill px-3 py-1 text-3xs">Automatic CVT</span>
                            <i class="fa-solid fa-car text-info my-4 d-block" style="font-size: 5rem;"></i>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold text-dark dark-text-light mb-0">Toyota Corolla Hybrid</h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Dual-Control</span>
                            </div>
                            <p class="text-muted fs-8 mb-3">Smooth acceleration and ultra-responsive braking for effortless urban navigation.</p>
                            <div class="d-flex justify-content-between text-xs text-muted pt-2 border-top" style="border-color: var(--border-color) !important;">
                                <span><i class="fa-solid fa-shield-halved text-success me-1"></i> ADAS Sensors</span>
                                <span><i class="fa-solid fa-leaf text-success me-1"></i> Eco Hybrid</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle 3 -->
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card h-100">
                        <div class="p-4 bg-light border-bottom text-center position-relative" style="border-color: var(--border-color) !important;">
                            <span class="badge bg-purple position-absolute top-0 start-0 m-3 rounded-pill px-3 py-1 text-3xs text-white" style="background-color: #8b5cf6;">Compact SUV</span>
                            <i class="fa-solid fa-truck-monster text-warning my-4 d-block" style="font-size: 5rem;"></i>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold text-dark dark-text-light mb-0">Hyundai Kona Urban</h5>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Dual-Control</span>
                            </div>
                            <p class="text-muted fs-8 mb-3">Higher seating vantage point providing optimal road visibility for beginner drivers.</p>
                            <div class="d-flex justify-content-between text-xs text-muted pt-2 border-top" style="border-color: var(--border-color) !important;">
                                <span><i class="fa-solid fa-camera text-primary me-1"></i> 360° Reversing Cam</span>
                                <span><i class="fa-solid fa-star text-warning me-1"></i> Top Rated</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= INSTRUCTORS ================= -->
    <section id="instructors" class="py-5" style="padding: 100px 0 !important;">
        <div class="container">
            
            <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">Expert Instructors</span>
                <h2 class="section-heading text-dark dark-text-light">Learn From Patient, Certified Professionals</h2>
                <p class="text-muted lead" style="font-size: 1.05rem;">
                    All DrivePulse trainers hold high-tier state certifications, advanced defensive driving credentials, and background security clearances.
                </p>
            </div>

            <div class="row g-4">
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card p-4 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80" alt="Trainer" class="testimonial-avatar mx-auto mb-3" style="width: 80px; height: 80px;">
                        <h5 class="fw-bold text-dark dark-text-light mb-1">Marcus Sterling</h5>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 text-3xs mb-3">8 Years Exp • Manual Specialist</span>
                        <p class="text-muted fs-8 mb-3">Specializes in nervous learners, defensive highway driving, and roundabouts mastery.</p>
                        <div class="d-flex justify-content-center gap-1 text-warning fs-8">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <span class="text-muted fs-8 ms-1">(4.9/5)</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card p-4 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80" alt="Trainer" class="testimonial-avatar mx-auto mb-3" style="width: 80px; height: 80px;">
                        <h5 class="fw-bold text-dark dark-text-light mb-1">Elena Rostova</h5>
                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1 text-3xs mb-3">6 Years Exp • Automatic & Highway</span>
                        <p class="text-muted fs-8 mb-3">Passionate about building student confidence, hazard anticipation, and night navigation.</p>
                        <div class="d-flex justify-content-center gap-1 text-warning fs-8">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <span class="text-muted fs-8 ms-1">(5.0/5)</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card p-4 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" alt="Trainer" class="testimonial-avatar mx-auto mb-3" style="width: 80px; height: 80px;">
                        <h5 class="fw-bold text-dark dark-text-light mb-1">David Kim</h5>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 text-3xs mb-3">11 Years Exp • Senior Examiner</span>
                        <p class="text-muted fs-8 mb-3">Former official license examiner offering insider prep for mock exams and precision maneuvers.</p>
                        <div class="d-flex justify-content-center gap-1 text-warning fs-8">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            <span class="text-muted fs-8 ms-1">(4.95/5)</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FAQ SECTION ================= -->
    <section id="faq" class="py-5 bg-surface" style="padding: 100px 0 !important; background-color: var(--bg-surface);">
        <div class="container">
            
            <div class="text-center max-w-700 mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">Frequently Asked Questions</span>
                <h2 class="section-heading text-dark dark-text-light">Got Questions? We’ve Got Answers</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        
                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button collapsed fw-bold text-dark dark-text-light" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                    How do I book my driving lessons after enrollment?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted fs-8">
                                    Once enrolled, log in to your <strong>Student Portal</strong> and open <strong>Book Lessons</strong>. You can choose any available instructor, pick your vehicle, select your date/time, and receive instant booking confirmation notifications.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed fw-bold text-dark dark-text-light" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                    Can I pay course fees in installments?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted fs-8">
                                    Yes! DrivePulse offers flexible installment management. You can record payments, track outstanding dues, and generate official stamped PDF receipts right inside your student dashboard.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                            <h2 class="accordion-header" id="faqHeading3">
                                <button class="accordion-button collapsed fw-bold text-dark dark-text-light" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                    What makes the Theory Mock Test simulator special?
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted fs-8">
                                    Our simulator displays one question at a time with instant visual selection badges, realistic countdown timers, and full breakdown explanations, drastically reducing official exam day stress.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border" style="border-color: var(--border-color) !important; background: var(--bg-card);">
                            <h2 class="accordion-header" id="faqHeading4">
                                <button class="accordion-button collapsed fw-bold text-dark dark-text-light" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4">
                                    How are certificates verified?
                                </button>
                            </h2>
                            <div id="faqCollapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted fs-8">
                                    Each certificate generated upon course completion features a unique SHA-256 tamper-proof hash and QR code that can be scanned by any smartphone camera to view verified student details.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ================= CTA CONVERSION BANNER ================= -->
    <section class="py-5" style="padding: 100px 0 !important;">
        <div class="container" data-aos="zoom-in">
            <div class="rounded-5 p-5 text-center text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.4);">
                
                <div class="position-relative z-2 max-w-700 mx-auto">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-1.5 fw-bold text-3xs mb-3">GET LICENSED IN 2026</span>
                    <h2 class="display-5 fw-bold mb-3">Ready to Become a Safe, Confident Driver?</h2>
                    <p class="lead text-white-50 mb-4" style="font-size: 1.15rem;">
                        Join over 15,000 satisfied graduates. Choose your instructor, pick your vehicle, and start learning with DrivePulse today.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold text-primary shadow">
                                Create Student Account
                            </a>
                        @endif
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-semibold">
                                Sign In to Portal
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="footer-wrap pt-5 pb-4">
        <div class="container">
            <div class="row g-4 mb-5">
                
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center gap-2 mb-3" href="{{ url('/') }}">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary text-white shadow-sm" style="width: 38px; height: 38px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%) !important;">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <span class="brand-font fw-bold fs-4 text-dark dark-text-light">Drive<span class="text-gradient-primary">Pulse</span></span>
                    </a>
                    <p class="text-muted fs-8 mb-4">
                        DrivePulse is a next-generation driving academy management system empowering students, certified trainers, and academy administrators with modern road safety technology.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-icon rounded-circle btn-light text-primary"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-sm btn-icon rounded-circle btn-light text-info"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="btn btn-sm btn-icon rounded-circle btn-light text-danger"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-icon rounded-circle btn-light text-primary"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark dark-text-light mb-3">Academy</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="footer-link">Why Choose Us</a></li>
                        <li><a href="#courses" class="footer-link">Driving Programs</a></li>
                        <li><a href="#fleet" class="footer-link">Dual-Control Fleet</a></li>
                        <li><a href="#instructors" class="footer-link">Certified Trainers</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark dark-text-light mb-3">Student Portal</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="footer-link">Book Lessons</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Theory Mock Tests</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Progress Tracking</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Certificate Verify</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h6 class="fw-bold text-dark dark-text-light mb-3">Academy Headquarters</h6>
                    <ul class="list-unstyled text-muted fs-8">
                        <li class="mb-2"><i class="fa-solid fa-location-dot text-primary me-2"></i> 742 Evergreen Motorway, Suite 400, Metro City</li>
                        <li class="mb-2"><i class="fa-solid fa-phone text-primary me-2"></i> +1 (800) 555-PULSE (78573)</li>
                        <li class="mb-2"><i class="fa-solid fa-envelope text-primary me-2"></i> admissions@drivepulse.com</li>
                        <li class="mb-2"><i class="fa-solid fa-clock text-primary me-2"></i> Mon – Sat: 07:00 AM – 08:00 PM</li>
                    </ul>
                </div>

            </div>

            <div class="border-top pt-4 text-center text-muted fs-8" style="border-color: var(--border-color) !important;">
                <p class="mb-0">&copy; {{ date('Y') }} DrivePulse Academy v2.0. All Rights Reserved. Built with precision for the modern road.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 60
        });

        // Theme Toggle Script
        function updateLandingTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);

            if (theme === 'dark') {
                $('.theme-icon-moon').addClass('d-none');
                $('.theme-icon-sun').removeClass('d-none');
            } else {
                $('.theme-icon-moon').removeClass('d-none');
                $('.theme-icon-sun').addClass('d-none');
            }
        }

        const currentSaved = document.documentElement.getAttribute('data-bs-theme') || 'light';
        updateLandingTheme(currentSaved);

        $('#themeToggleBtn').on('click', function() {
            const next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            updateLandingTheme(next);
        });
    </script>
</body>
</html>
