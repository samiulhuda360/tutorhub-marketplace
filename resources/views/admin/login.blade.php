<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - TutorHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #006A4E 0%, #004A35 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
            padding: 48px 40px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            background: #f0fdf4;
            border-radius: 100px;
            margin-bottom: 16px;
        }

        .logo-flag {
            width: 32px;
            height: 32px;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #006A4E;
        }

        .admin-title {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .admin-subtitle {
            color: #6B7280;
            font-size: 14px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #006A4E;
            box-shadow: 0 0 0 3px rgba(0, 106, 78, 0.1);
        }

        .form-input.error {
            border-color: #EF4444;
        }

        .error-message {
            color: #EF4444;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .remember-section {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-section input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-section label {
            font-size: 14px;
            color: #4B5563;
            cursor: pointer;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: #006A4E;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .login-btn:hover {
            background: #004A35;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 106, 78, 0.3);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            color: #9CA3AF;
            font-size: 14px;
        }

        .back-link {
            text-align: center;
        }

        .back-link a {
            color: #006A4E;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .admin-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: linear-gradient(135deg, #006A4E, #00C878);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="admin-icon">🔐</div>
            <div class="logo-badge">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 60'%3E%3Ccircle cx='30' cy='30' r='28' fill='%23006A4E'/%3E%3Ccircle cx='30' cy='30' r='15' fill='%23F42A41'/%3E%3C/svg%3E" alt="BD Flag" class="logo-flag">
                <span class="logo-text">TutorHub</span>
            </div>
            <h1 class="admin-title">Admin Login</h1>
            <p class="admin-subtitle">Access the admin dashboard</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <strong>⚠️ Error:</strong>
                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    placeholder="admin@tutorhub.test"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
                @error('email')
                    <div class="error-message">
                        ⚠️ {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                    placeholder="Enter your password"
                    required
                >
                @error('password')
                    <div class="error-message">
                        ⚠️ {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="remember-section">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="login-btn">
                Login to Dashboard
            </button>
        </form>

        <div class="divider">───────  or  ───────</div>

        <div class="back-link">
            <a href="{{ route('home') }}">← Back to Homepage</a>
        </div>
    </div>
</body>
</html>
