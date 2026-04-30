<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Kasir Lima Saudara Grup</title>
    <!-- Favicon Toko -->
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
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
            <h3><img src="{{ asset('logo.png') }}" alt="Logo" style="height: 60px; border-radius: 10px; background: white; padding: 5px; margin-bottom: 10px;"><br> Lima Saudara Grup</h3>
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
                <small class="text-muted">&copy; {{ date('Y') }} Sistem Kasir Lima Saudara Grup.<br>Copyright by <strong>mysyrf</strong></small>
            </div>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>