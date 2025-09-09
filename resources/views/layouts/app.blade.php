<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HTech Manhwa')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        
        .navbar-dark {
            background-color: #2c2c2c !important;
        }
        
        .card {
            background-color: #2c2c2c;
            border-color: #404040;
        }
        
        .card-title, .card-text {
            color: #ffffff;
        }
        
        .btn-primary {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
        }
        
        .btn-primary:hover {
            background-color: #5b4cdb;
            border-color: #5b4cdb;
        }
        
        .manhwa-cover {
            height: 300px;
            object-fit: cover;
        }
        
        .chapter-page {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        
        .chapter-navigation {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        @media (max-width: 768px) {
            .manhwa-cover {
                height: 200px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-book-open me-2"></i>HTech Manhwa
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manhwas.index') }}">
                            <i class="fas fa-book me-1"></i>Browse
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 HTech Manhwa. Built with Laravel for local network reading.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
