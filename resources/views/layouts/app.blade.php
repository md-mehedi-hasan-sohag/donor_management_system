<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Global Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<style>
    /* ============================================
       THEME VARIABLES - Light & Dark Mode
    ============================================ */
    :root {
        /* Light Mode Colors */
        --bg-primary: #ffffff;
        --bg-secondary: #f9fafb;
        --bg-tertiary: #f3f4f6;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --text-tertiary: #9ca3af;
        --border-color: #e5e7eb;
        --card-bg: #ffffff;
        --card-shadow: rgba(0, 0, 0, 0.1);
        --navbar-bg: linear-gradient(135deg, #334155 0%, #475569 50%, #64748b 100%);
        --navbar-text: #ffffff;
        --footer-bg: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        --footer-text: #ffffff;
        --input-bg: #ffffff;
        --input-border: #d1d5db;
        --overlay-bg: rgba(0, 0, 0, 0.5);

        /* Color Palette */
        --primary: #4F46E5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;

        /* Gray Scale */
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
    }

    [data-theme="dark"] {
        /* Dark Mode Colors */
        --bg-primary: #1f2937;
        --bg-secondary: #111827;
        --bg-tertiary: #374151;
        --text-primary: #f9fafb;
        --text-secondary: #d1d5db;
        --text-tertiary: #9ca3af;
        --border-color: #374151;
        --card-bg: #374151;
        --card-shadow: rgba(0, 0, 0, 0.3);
        --navbar-bg: linear-gradient(135deg, #111827 0%, #1f2937 50%, #374151 100%);
        --navbar-text: #f9fafb;
        --footer-bg: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        --footer-text: #f9fafb;
        --input-bg: #374151;
        --input-border: #4b5563;
        --overlay-bg: rgba(0, 0, 0, 0.7);

        /* Color Palette - Slightly adjusted for dark mode */
        --primary: #6366f1;
        --success: #10b981;
        --danger: #f87171;
        --warning: #fbbf24;
        --info: #60a5fa;

        /* Gray Scale - Inverted for dark mode */
        --gray-50: #111827;
        --gray-100: #1f2937;
        --gray-200: #374151;
        --gray-300: #4b5563;
        --gray-400: #6b7280;
        --gray-500: #9ca3af;
        --gray-600: #d1d5db;
        --gray-700: #e5e7eb;
        --gray-800: #f3f4f6;
        --gray-900: #f9fafb;
    }

    /* Apply theme colors to body */
    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    #app {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

main {
    flex: 1;
}
    /* Pagination Styles */
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination a, .pagination span {
        display: inline-block;
        padding: 0.5rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        color: var(--gray-700);
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination .active span {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .pagination .disabled span {
        color: var(--gray-400);
        cursor: not-allowed;
    }

    /* Navbar Styles */
    .custom-navbar {
        background: var(--navbar-bg);
        padding: 1.25rem 0;
        box-shadow: 0 4px 20px var(--card-shadow);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .navbar-brand-custom {
        font-size: 1.75rem;
        font-weight: 900;
        color: white !important;
        text-decoration: none;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand-custom:hover {
        transform: scale(1.05);
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        color: white !important;
    }

    .navbar-brand-custom span {
        background: linear-gradient(135deg, #fff 0%, #e2e8f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .nav-buttons {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-left: auto;
    }

    .nav-btn {
        padding: 0.5rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        display: inline-block;
        border-radius: 6px;
    }

    .nav-btn-login {
        background: transparent;
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.8);
    }

    .nav-btn-login:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white !important;
    }

    .nav-btn-register {
        background: white;
        color: #334155 !important;
        border: 2px solid white;
    }

    .nav-btn-register:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
        color: #334155 !important;
    }

    /* User Dropdown Styles */
    .user-dropdown {
        position: relative;
    }

    .dropdown-toggle-custom {
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
        padding: 0.5rem 1.25rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 6px;
        font-size: 0.95rem;
    }

    .dropdown-toggle-custom:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        color: white !important;
    }

    .dropdown-toggle-custom::after {
        display: none;
    }

    .dropdown-menu-custom {
        background: white;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        margin-top: 0.5rem;
        min-width: 200px;
    }

    .dropdown-item-custom {
        padding: 0.75rem 1.5rem;
        color: #334155;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        display: block;
    }

    .dropdown-item-custom:hover {
        background: #f1f5f9;
        color: #334155;
    }

    /* Theme Toggle Button */
    .theme-toggle {
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.25rem;
    }

    .theme-toggle:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: rotate(180deg) scale(1.1);
    }

    /* Footer Styles */
    .footer {
        background: var(--footer-bg);
        color: var(--footer-text);
        padding: 4rem 0 2rem;
        position: relative;
        overflow: hidden;
        margin-top: auto;
        transition: background 0.3s ease;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #10b981 0%, #3b82f6 50%, #8b5cf6 100%);
    }

    .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1.5fr 1.5fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    .footer-section h3 {
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: white;
    }

    .footer-logo {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #fff 0%, #e2e8f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .social-link {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1.25rem;
        color: white;
    }

    .social-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
        color: white;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.75rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        display: inline-block;
    }

    .footer-links a:hover {
        color: white;
        transform: translateX(5px);
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.25rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .contact-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .contact-text {
        flex: 1;
    }

    .contact-text strong {
        display: block;
        color: white;
        margin-bottom: 0.25rem;
        font-weight: 700;
    }

    .security-badge {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: rgba(16, 185, 129, 0.15);
        border: 2px solid rgba(16, 185, 129, 0.3);
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .security-icon {
        width: 50px;
        height: 50px;
        background: rgba(16, 185, 129, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .security-text strong {
        display: block;
        color: white;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .security-text span {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
    }

    .payment-methods {
        margin-top: 1.5rem;
    }

    .payment-methods h4 {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }

    .payment-icons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .payment-icon {
        width: 50px;
        height: 32px;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: #334155;
    }

    .app-download {
        margin-top: 1.5rem;
    }

    .app-badge {
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .app-badge img {
        height: 45px;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .app-badge:hover img {
        transform: scale(1.05);
    }

    .footer-bottom {
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .footer-bottom-text {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .footer-bottom-links {
        display: flex;
        gap: 2rem;
    }

    .footer-bottom-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: white;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
    }

    @media (max-width: 768px) {
        .navbar-brand-custom {
            font-size: 1.5rem;
        }

        .nav-btn {
            padding: 0.5rem 1.25rem;
            font-size: 0.85rem;
        }

        .nav-buttons {
            gap: 0.5rem;
            margin-left: auto;
        }

        .footer-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-bottom-links {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    /* Page Wrapper */
    #app {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }
</style>
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="custom-navbar">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Left: Brand Name -->
                    <a href="{{ url('/') }}" class="navbar-brand-custom">
                        <span>DonorLink</span>
                    </a>

                    <!-- Right: Auth Buttons -->
                    <div class="nav-buttons">
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" class="theme-toggle" title="Toggle theme">
                            <span id="theme-icon">🌙</span>
                        </button>

                        @guest
                            <a href="{{ route('login') }}" class="nav-btn nav-btn-login">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="nav-btn nav-btn-register">
                                Register
                            </a>
                        @else
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle-custom" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                                    </svg>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('dashboard') }}">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.5rem;">
                                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                            </svg>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('volunteer.dashboard') }}">
                                            🤝 Volunteer Dashboard
                                        </a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('saved-campaigns.index') }}">
                                            ❤️ Saved Campaigns
                                        </a>
                                    </li>



                                    <li><hr class="dropdown-divider" style="margin: 0.5rem 0; border-color: var(--border-color);"></li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 0.5rem;">
                                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                            </svg>
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <!-- About Section -->
                    <div class="footer-section">
                        <div class="footer-logo">DonorLink</div>
                        <p class="footer-description">
                            DonorLink is Bangladesh's leading transparent fundraising platform, connecting generous donors 
                            with verified campaigns to create lasting impact in communities.
                        </p>
                        
                        <div class="social-links">
                            <a href="#" class="social-link" title="LinkedIn">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" title="Facebook">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                            <a href="#" class="social-link" title="YouTube">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul class="footer-links">
                            <li><a href="#">Browse Campaigns</a></li>
                            <li><a href="#">Start Campaign</a></li>
                            <li><a href="#">How It Works</a></li>
                            <li><a href="#">Success Stories</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Career</a></li>
                        </ul>
                    </div>

                    <!-- Contact Us -->
                    <div class="footer-section">
                        <h3>Contact Us</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div class="contact-text">
                                <strong>Head Office</strong>
                                1/E/2, Adabor, Mohammadpur, Dhaka-1207
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">📧</div>
                            <div class="contact-text">
                                <strong>Email</strong>
                                support@donorlink.com
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div class="contact-text">
                                <strong>Phone</strong>
                                01958-622155
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Security -->
                    <div class="footer-section">
                        <div class="security-badge">
                            <div class="security-icon">🔒</div>
                            <div class="security-text">
                                <strong>GUARANTEED</strong>
                                <span>SECURE PAYMENT</span>
                            </div>
                        </div>

                        <div class="payment-methods">
                            <h4>We Accept</h4>
                            <div class="payment-icons">
                                <div class="payment-icon">bKash</div>
                                <div class="payment-icon">Nagad</div>
                                <div class="payment-icon">Rocket</div>
                                <div class="payment-icon">VISA</div>
                                <div class="payment-icon">Master</div>
                            </div>
                        </div>

                        <div class="app-download">
                            <h4 style="font-size: 0.9rem; font-weight: 700; margin-bottom: 1rem; color: white;">Download App</h4>
                            <a href="#" class="app-badge">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Get it on Google Play">
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="footer-bottom-text">
                        © 2025 All Rights Reserved to DonorLink
                    </div>
                    <div class="footer-bottom-links">
                        <a href="#">Terms & Conditions</a>
                        <a href="#">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Theme Switching Script -->
    <script>
        // Theme switching functionality
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const htmlElement = document.documentElement;

            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';

            // Apply the current theme on page load
            htmlElement.setAttribute('data-theme', currentTheme);
            updateThemeIcon(currentTheme);

            // Toggle theme on button click
            themeToggle.addEventListener('click', function() {
                const currentTheme = htmlElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';

                htmlElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            // Update theme icon based on current theme
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.textContent = '☀️'; // Sun icon for dark mode (click to go light)
                } else {
                    themeIcon.textContent = '🌙'; // Moon icon for light mode (click to go dark)
                }
            }
        })();
    </script>
</body>
</html>