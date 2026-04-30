<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Kasir Toko MZ</title>
    <!-- Favicon Toko -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 576 512%22><path fill=%22%23764ba2%22 d=%22M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0H109.6C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9l-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-31.2 0-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5s-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5s-58.4-16.7-73.4-41.5c-15 24.8-42.2 41.5-73.4 41.5c-5.5 0-10.9-.4-16.2-1.1l-.1 0c-1.3 10.9-2.1 21.9-2.1 33.1V464c0 26.5 21.5 48 48 48H448c26.5 0 48-21.5 48-48V288c0-11.2-.8-22.2-2.1-33.1z%22/></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 400px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: bold;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="login-header">
            <h3><i class="fas fa-store"></i> Toko MZ</h3>
            <p>Sistem Kasir</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-login">Login</button>
            </form>
            <hr>
            <div class="text-center text-muted small">
                <p>Demo Account:</p>
                <p>Admin: admin@tokomz.com / admin123</p>
                <p>Kasir: kasir@tokomz.com / kasir123</p>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted">&copy; {{ date('Y') }} Sistem Kasir Toko MZ.<br>Copyright by <strong>mysyrf</strong></small>
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>