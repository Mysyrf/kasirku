<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kasir Toko MZ</title>
    <!-- Favicon Toko -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 576 512%22><path fill=%22%23764ba2%22 d=%22M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0H109.6C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9l-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-31.2 0-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5s-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5s-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5c-5.5 0-10.9-.4-16.2-1.1l-.1 0c-1.3 10.9-2.1 21.9-2.1 33.1V464c0 26.5 21.5 48 48 48H448c26.5 0 48-21.5 48-48V288c0-11.2-.8-22.2-2.1-33.1z%22/></svg>">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f0f2f5;
            font-weight: 600;
            border-radius: 15px 15px 0 0 !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        
        .product-card {
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-store"></i> Toko MZ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Menu KASIR - semua user bisa akses -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('cashier') ? 'active' : '' }}" href="{{ route('cashier') }}">
                            <i class="fas fa-cash-register"></i> Kasir
                        </a>
                    </li>
                    
                    <!-- Menu LAPORAN - semua user bisa akses -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fas fa-chart-line"></i> Laporan
                        </a>
                    </li>
                    
                    <!-- Menu ONLY ADMIN -->
                    @auth
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="fas fa-boxes"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}">
                            <i class="fas fa-truck-loading"></i> Stok Masuk
                        </a>
                    </li>
                    @endif
                    @endauth
                    
                    <!-- Dropdown User -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> 
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                User
                            @endauth
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4 mb-5" style="min-height: 75vh;">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center mt-auto py-3 text-muted">
        <small>&copy; {{ date('Y') }} Sistem Kasir Toko MZ. Copyright by <strong>mysyrf</strong>.</small>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }
    </script>
    
    @stack('scripts')
</body>
</html>