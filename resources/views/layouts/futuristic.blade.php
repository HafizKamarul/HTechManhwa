<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'The future of manhwa reading is here. Experience stories like never before with HTech Manhwa.')">
    <meta name="keywords" content="manhwa, manga, webtoon, comics, online reading, htech">
    <meta name="author" content="HTech Manhwa">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('title', 'HTech Manhwa')">
    <meta property="og:description" content="@yield('description', 'The future of manhwa reading is here. Experience stories like never before with HTech Manhwa.')">
    <meta property="og:image" content="{{ asset('logo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="@yield('title', 'HTech Manhwa')">
    <meta property="twitter:description" content="@yield('description', 'The future of manhwa reading is here. Experience stories like never before with HTech Manhwa.')">
    <meta property="twitter:image" content="{{ asset('logo.png') }}">
    
    <title>@yield('title', 'HTech Manhwa')</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#a855f7">
    
    <!-- Preload important assets -->
    <link rel="preload" href="{{ asset('full_logo.png') }}" as="image">
    <link rel="preload" href="{{ asset('images/backgrounds/background.png') }}" as="image">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.globe.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
        
        /* Background Image */
        body {
            background-image: url('{{ asset("images/backgrounds/background.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            /* Optimize background loading */
            background-color: #000000; /* Fallback color while loading */
        }
        
        /* Background loading optimization */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0f0f0f 100%);
            z-index: -2;
            opacity: 1;
            transition: opacity 0.5s ease;
        }
        
        /* Hide fallback gradient when background image loads */
        body.bg-loaded::after {
            opacity: 0;
        }
        
        /* Overlay for better text readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }
        
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .purple-glow-text { text-shadow: 0 0 10px #a855f7, 0 0 20px #a855f7; }
        
        /* Enhanced Neon Logo Effect */
        .logo-glow {
            filter: 
                drop-shadow(0 0 5px rgba(138, 43, 226, 0.8))
                drop-shadow(0 0 10px rgba(147, 51, 234, 0.6))
                drop-shadow(0 0 15px rgba(168, 85, 247, 0.4))
                drop-shadow(0 0 20px rgba(192, 132, 252, 0.3));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .logo-glow:hover {
            filter: 
                drop-shadow(0 0 8px rgba(138, 43, 226, 1))
                drop-shadow(0 0 16px rgba(147, 51, 234, 0.8))
                drop-shadow(0 0 24px rgba(168, 85, 247, 0.6))
                drop-shadow(0 0 32px rgba(192, 132, 252, 0.4))
                drop-shadow(0 0 40px rgba(221, 214, 254, 0.2));
            transform: scale(1.05) translateY(-2px);
            animation: neon-intense 1.5s ease-in-out infinite;
        }
        
        /* Logo Container Background Glow */
        .logo-container::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            height: 200%;
            background: radial-gradient(
                ellipse at center,
                rgba(138, 43, 226, 0.15) 0%,
                rgba(147, 51, 234, 0.1) 30%,
                rgba(168, 85, 247, 0.05) 50%,
                transparent 70%
            );
            border-radius: 50%;
            opacity: 0.6;
            z-index: 1;
            pointer-events: none;
        }
        
        .logo-container:hover::before {
            animation: background-intense 2s ease-in-out infinite;
            opacity: 1;
        }
        
        /* Background Glow Animations */
        @keyframes background-intense {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1.1);
                opacity: 0.8;
            }
            25% { 
                transform: translate(-50%, -50%) scale(1.3);
                opacity: 1;
            }
            75% { 
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 0.9;
            }
        }
        
        /* Intense Neon Animation on Hover */
        @keyframes neon-intense {
            0%, 100% { 
                filter: 
                    drop-shadow(0 0 8px rgba(138, 43, 226, 1))
                    drop-shadow(0 0 16px rgba(147, 51, 234, 0.8))
                    drop-shadow(0 0 24px rgba(168, 85, 247, 0.6))
                    drop-shadow(0 0 32px rgba(192, 132, 252, 0.4))
                    drop-shadow(0 0 40px rgba(221, 214, 254, 0.2));
            }
            25% { 
                filter: 
                    drop-shadow(0 0 12px rgba(138, 43, 226, 1))
                    drop-shadow(0 0 24px rgba(147, 51, 234, 0.9))
                    drop-shadow(0 0 36px rgba(168, 85, 247, 0.7))
                    drop-shadow(0 0 48px rgba(192, 132, 252, 0.5))
                    drop-shadow(0 0 60px rgba(221, 214, 254, 0.3));
            }
            75% { 
                filter: 
                    drop-shadow(0 0 6px rgba(138, 43, 226, 1))
                    drop-shadow(0 0 12px rgba(147, 51, 234, 0.7))
                    drop-shadow(0 0 18px rgba(168, 85, 247, 0.5))
                    drop-shadow(0 0 24px rgba(192, 132, 252, 0.3))
                    drop-shadow(0 0 30px rgba(221, 214, 254, 0.1));
            }
        }
        .holographic-card {
            background: rgba(20, 10, 30, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.4);
            box-shadow: 0 0 25px rgba(168, 85, 247, 0.3);
        }
        .holographic-card:hover {
            box-shadow: 0 0 35px rgba(168, 85, 247, 0.5);
            transform: translateY(-5px);
        }
        .chapter-list li:hover {
            background: rgba(168, 85, 247, 0.1);
            border-left: 3px solid #a855f7;
        }
        .reader-controls button:hover {
            background: rgba(168, 85, 247, 0.2);
        }
        #vanta-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .manhwa-cover {
            height: 300px;
            object-fit: cover;
            width: 100%;
        }
        
        /* Responsive manhwa cover styles */
        .manhwa-card {
            aspect-ratio: 3/4;
            min-height: 200px;
        }
        
        .manhwa-card .manhwa-cover {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        /* Text clamping utilities */
        .line-clamp-1 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
        }
        
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        
        /* Enhanced Read Now button styles */
        .read-now-btn {
            background: linear-gradient(135deg, #a855f7, #8b5cf6);
            border: none;
            transition: all 0.3s ease;
            min-height: 36px;
            touch-action: manipulation;
            position: relative;
            overflow: hidden;
        }
        
        .read-now-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .read-now-btn:hover:before {
            left: 100%;
        }
        
        .read-now-btn:hover {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.4);
        }
        
        .read-now-btn:active {
            transform: translateY(0);
        }
        
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .read-now-btn {
                min-height: 40px;
                font-weight: 600;
            }
        }
        .chapter-page {
            max-width: 88%; /* Increased from 80% by 10% for desktop */
            width: 88%;
            height: auto;
            display: block;
            margin: 0 auto;
            padding: 0;
            border: none;
            box-shadow: none;
            vertical-align: top;
            line-height: 0;
        }
        .chapter-reader {
            line-height: 0;
            margin: 0;
            padding: 0;
            font-size: 0;
            max-width: 88%; /* Increased from 80% by 10% for desktop */
            margin: 0 auto;
        }
        .chapter-page-container {
            margin: 0;
            padding: 0;
            line-height: 0;
            font-size: 0;
            display: block;
            border: none;
            outline: none;
        }
        .chapter-page-container:before,
        .chapter-page-container:after {
            content: none;
        }
        .chapter-page-container {
            margin: 0;
            padding: 0;
            line-height: 0;
            font-size: 0;
            display: block;
            border: none;
            outline: none;
            position: relative;
        }
        .chapter-page-container:before,
        .chapter-page-container:after {
            content: none;
        }
        
        /* High DPI display optimization */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx) {
            .chapter-page {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }
        /* Prevent image scaling on zoom */
        @media (min-width: 1200px) {
            .chapter-reader {
                max-width: 800px;
                margin: 0 auto;
            }
        }
        .chapter-navigation {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        /* Auto-hide navigation */
        nav {
            transition: transform 0.3s ease-in-out;
        }
        
        nav.nav-hidden {
            transform: translateY(-100%);
        }
        
        /* Scroll to top button */
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: rgba(139, 92, 246, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 50%;
            color: #a855f7;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }
        
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .scroll-to-top:hover {
            background: rgba(139, 92, 246, 0.3);
            border-color: rgba(139, 92, 246, 0.5);
            color: #c084fc;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .manhwa-card {
                aspect-ratio: 2/3;
                min-height: 250px;
            }
            
            .manhwa-cover {
                height: 250px;
            }
            
            .chapter-navigation {
                bottom: 10px;
                right: 10px;
            }
            
            /* Mobile scroll-to-top button positioning */
            .scroll-to-top {
                bottom: 80px; /* Move up to avoid conflict with chapter navigation */
                left: 10px;
                width: 45px;
                height: 45px;
            }
            
            /* Mobile chapter images - fit to screen */
            .chapter-page {
                max-width: 100%;
                width: 100%;
            }
            .chapter-reader {
                max-width: 100%;
            }
        }
        
        /* Extra small screens */
        @media (max-width: 480px) {
            .manhwa-card {
                aspect-ratio: 3/4;
                min-height: 200px;
            }
            
            .manhwa-cover {
                height: 200px;
            }
            
            .read-now-btn {
                min-height: 42px;
                font-size: 0.8rem;
                font-weight: 700;
            }
        }
        
        }
    </style>
    
    @stack('styles')
</head>
<body class="text-gray-100 font-roboto">
    <div id="vanta-bg"></div>
    
    <!-- Navigation -->
    <nav class="border-b border-purple-500/20 bg-black/80 backdrop-blur-md fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center logo-container relative">
                            <img src="{{ asset('full_logo.png') }}" alt="HTech Manhwa Full Logo" class="h-10 md:h-12 lg:h-14 w-auto logo-glow max-w-xs relative z-10">
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-purple-400 bg-gray-800' : 'text-gray-300 hover:text-purple-400' }}">Home</a>
                            <a href="{{ route('manhwas.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('manhwas.*') ? 'text-purple-400 bg-gray-800' : 'text-gray-300 hover:text-purple-400' }}">Library</a>
                            <a href="{{ route('genres.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('genres.*') ? 'text-purple-400 bg-gray-800' : 'text-gray-300 hover:text-purple-400' }}">Genres</a>
                            <a href="{{ route('manhwas.index', ['sort' => 'latest']) }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:text-purple-400">Updates</a>
                            <a href="{{ route('admin.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.*') ? 'text-purple-400 bg-gray-800' : 'text-gray-300 hover:text-purple-400' }}">Admin</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSearch()" class="p-2 rounded-full text-gray-300 hover:text-purple-400 hover:bg-black">
                            <i data-feather="search"></i>
                        </button>
                        <div class="relative">
                            <button class="p-2 rounded-full text-gray-300 hover:text-purple-400 hover:bg-black">
                                <i data-feather="user"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar (Hidden by default) -->
            <div id="searchBar" class="hidden bg-black/90 backdrop-blur-md border-t border-purple-500/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <form method="GET" action="{{ route('manhwas.index') }}" class="flex">
                        <input type="text" name="search" placeholder="Search for manhwa titles, authors..." 
                               value="{{ request('search') }}"
                               class="flex-1 bg-gray-900 text-white rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-r-lg transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="relative z-10">
            @yield('content')
        </main>

    <!-- Footer -->
    <footer class="border-t border-purple-500/30 py-12 px-4 sm:px-6 lg:px-8 relative z-10 bg-black">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4 logo-container relative">
                        <img src="{{ asset('full_logo.png') }}" alt="HTech Manhwa Full Logo" class="h-8 w-auto logo-glow max-w-48 relative z-10">
                    </div>
                    <p class="text-gray-400">The future of manhwa reading is here. Experience stories like never before.</p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-300 mb-4">Explore</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('manhwas.index', ['sort' => 'popular']) }}" class="text-gray-400 hover:text-purple-400">Popular</a></li>
                        <li><a href="{{ route('manhwas.index', ['sort' => 'latest']) }}" class="text-gray-400 hover:text-purple-400">New Releases</a></li>
                        <li><a href="{{ route('genres.index') }}" class="text-gray-400 hover:text-purple-400">Genres</a></li>
                        <li><a href="{{ route('manhwas.index') }}" class="text-gray-400 hover:text-purple-400">Authors</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-300 mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><span class="text-gray-400">Local Network Only</span></li>
                        <li><span class="text-gray-400">Personal Use</span></li>
                        <li><span class="text-gray-400">Open Source</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-300 mb-4">Stats</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400">{{ \App\Models\Manhwa::count() }} Manhwa</li>
                        <li class="text-gray-400">{{ \App\Models\Chapter::count() }} Chapters</li>
                        <li class="text-gray-400">{{ \App\Models\Genre::count() }} Genres</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-purple-500/30 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>© 2025 HTech Manhwa. Built with Laravel for local network reading. All manhwa are property of their respective owners.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" title="Back to top">
        <i data-feather="arrow-up"></i>
    </button>

    </div> <!-- Close smooth-scrollbar container -->

    <script>
        // Initialize Vanta background
        VANTA.GLOBE({
            el: "#vanta-bg",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 1.00,
            scaleMobile: 1.00,
            color: 0xa855f7,
            backgroundColor: 0x000000,
            size: 0.8
        });
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Initialize Feather icons
        feather.replace();
        
        // Background image loading detection
        function detectBackgroundLoaded() {
            const img = new Image();
            img.onload = function() {
                document.body.classList.add('bg-loaded');
                console.log('Background image loaded successfully');
            };
            img.onerror = function() {
                console.warn('Background image failed to load, keeping fallback');
            };
            img.src = '{{ asset("images/backgrounds/background.png") }}';
        }
        
        // Detect background image loading
        detectBackgroundLoaded();
        
        // Search toggle function
        function toggleSearch() {
            const searchBar = document.getElementById('searchBar');
            searchBar.classList.toggle('hidden');
            if (!searchBar.classList.contains('hidden')) {
                searchBar.querySelector('input').focus();
            }
        }
        
        // Auto-hide navigation and scroll-to-top functionality
        let lastScrollY = window.scrollY;
        const nav = document.querySelector('nav');
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        function handleScroll() {
            const currentScrollY = window.scrollY;
            
            // Auto-hide navigation
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scrolling down - hide nav
                nav.classList.add('nav-hidden');
            } else {
                // Scrolling up - show nav
                nav.classList.remove('nav-hidden');
            }
            
            // Show/hide scroll to top button
            if (currentScrollY > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
            
            lastScrollY = currentScrollY;
        }
        
        // Scroll to top function
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Event listeners
        window.addEventListener('scroll', handleScroll, { passive: true });
        scrollToTopBtn.addEventListener('click', scrollToTop);
        
        // Re-initialize feather icons after adding scroll button
        setTimeout(() => {
            feather.replace();
        }, 100);
    </script>
    
    @stack('scripts')
</body>
</html>
