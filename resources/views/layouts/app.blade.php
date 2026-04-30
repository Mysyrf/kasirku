<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Kasir Lima Saudara Grup</title>
    <!-- Favicon Toko -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    
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
        
        /* Unified Theme Colors */
        .bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .text-primary {
            color: #764ba2 !important;
        }
        
        .table-dark {
            --bs-table-bg: #4c3c6d;
            --bs-table-border-color: #5c4a85;
            --bs-table-striped-bg: #42345e;
        }
        
        .btn-info {
            background-color: #667eea;
            border-color: #667eea;
            color: white;
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
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 35px; border-radius: 5px; background: white; padding: 2px;" class="me-2">
                Lima Saudara Grup
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('products.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-boxes"></i> Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="fas fa-box"></i> Daftar Produk</a></li>
                            <li><a class="dropdown-item" href="{{ route('products.price') }}"><i class="fas fa-tags"></i> Manajemen Harga</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('products.priceHistory') }}"><i class="fas fa-history"></i> Riwayat Harga</a></li>
                        </ul>
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
        <small>&copy; {{ date('Y') }} Sistem Kasir Lima Saudara Grup. Copyright by <strong>mysyrf</strong>.</small>
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