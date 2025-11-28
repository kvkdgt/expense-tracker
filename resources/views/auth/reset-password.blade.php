<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <title>Reset Password - Spendly</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --bg: #f8fafc;
            --white: #ffffff;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --orange: #f97316;
            --orange-light: #fb923c;
            --orange-bg: #fff7ed;
            --danger: #ef4444;
            --success: #10b981;
            --border: #e2e8f0;
            --radius: 20px;
            --radius-sm: 12px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            flex: 1;
            background: linear-gradient(135deg, var(--orange) 0%, var(--orange-light) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .sidebar-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 420px;
        }

        .sidebar-icon {
            width: 88px;
            height: 88px;
            background: rgba(255,255,255,0.25);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 36px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .sidebar-icon svg { width: 48px; height: 48px; color: white; }

        .sidebar-title {
            font-size: 2.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
        }

        .sidebar-text {
            font-size: 1.125rem;
            color: rgba(255,255,255,0.9);
            line-height: 1.8;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: var(--white);
        }

        .container {
            width: 100%;
            max-width: 420px;
        }

        .header {
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--text);
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.0625rem;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 36px;
            border: 2px solid var(--border);
        }

        .form-group { margin-bottom: 24px; }

        .form-label {
            display: block;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .form-input {
            width: 100%;
            padding: 16px 18px;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
        }

        .form-input::placeholder { color: var(--text-muted); }

        .btn {
            width: 100%;
            padding: 18px;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 1.0625rem;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            background: linear-gradient(135deg, var(--orange) 0%, var(--orange-light) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.35);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(249, 115, 22, 0.4);
        }

        .footer {
            text-align: center;
            margin-top: 28px;
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .footer a {
            color: var(--orange);
            text-decoration: none;
            font-weight: 700;
        }

        .footer a:hover { text-decoration: underline; }

        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        .alert-error {
            background: #fee2e2;
            color: var(--danger);
        }

        @media (max-width: 1024px) {
            .sidebar { display: none; }
            .main { padding: 24px; }
        }

        @media (max-width: 480px) {
            .card { padding: 28px 24px; }
            .header h1 { font-size: 1.875rem; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-content">
                <div class="sidebar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="sidebar-title">Spendly</h2>
                <p class="sidebar-text">Lock in a stronger password to keep your data safe across every device.</p>
            </div>
        </div>

        <div class="main">
            <div class="container">
                <div class="header">
                    <h1>Create new password</h1>
                    <p>Set a secure password to finish the reset.</p>
                </div>

                <div class="card">
                    @if($errors->any())
                        <div class="alert alert-error">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-input" placeholder="New password" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm password" required>
                        </div>

                        <button type="submit" class="btn">Reset Password</button>
                    </form>
                </div>

                <div class="footer">
                    Back to <a href="{{ route('login') }}">sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


