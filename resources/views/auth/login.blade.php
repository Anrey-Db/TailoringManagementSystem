<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tailoring Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Base blue theme matching application primary color */
        :root {
            --tms-blue-600: #0d6efd; /* bootstrap primary */
            --tms-blue-700: #0b5ed7;
            --tms-blue-500: #3d7bfd;
            --tms-bg: linear-gradient(135deg, #e9f2ff 0%, #d9e9ff 100%);
            --card-bg: #ffffff;
        }

        html, body {
            height: 100%;
        }

        body {
            background: var(--tms-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 460px;
        }

        .login-card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.08);
            padding: 36px;
            border: 1px solid rgba(13, 110, 253, 0.06);
        }

        .login-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .login-header h1 {
            color: var(--tms-blue-700);
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-header p {
            color: #5b6b82;
            font-size: 14px;
            margin-bottom: 0;
        }

        .form-group { margin-bottom: 18px; }

        .form-control {
            border: 1px solid rgba(13,110,253,0.12);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            border-color: var(--tms-blue-600);
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.12);
        }

        .btn-login {
            background: linear-gradient(90deg, var(--tms-blue-600), var(--tms-blue-700));
            border: none;
            color: white;
            padding: 11px;
            border-radius: 8px;
            font-weight: 700;
            width: 100%;
            box-shadow: 0 6px 18px rgba(13,110,253,0.12);
        }

        .btn-login:hover { opacity: 0.95; }

        .remember-me { display:flex; align-items:center; gap:8px; margin-bottom:12px; }

        .login-footer { text-align:center; margin-top:18px; border-top:1px solid rgba(0,0,0,0.04); padding-top:18px; }

        .login-footer a { color: var(--tms-blue-600); font-weight:600; text-decoration:none; }

        .alert { margin-bottom: 12px; border-radius:8px; }

        .error-message { color:#dc3545; font-size:12px; margin-top:6px; display:block; }
        input.is-invalid { border-color:#dc3545; }

        @media (max-width: 576px) {
            .login-card { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Login to your account</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Login Failed!</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="Enter your email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Enter your password" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-login">Login</button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Sign up here</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
