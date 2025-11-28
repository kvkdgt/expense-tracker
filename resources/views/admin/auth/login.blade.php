<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Login - Spendly</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif; background: #0f172a; color: #fff; min-height: 100vh; display: flex; }
        .wrapper { display: flex; width: 100%; min-height: 100vh; }
        .sidebar { flex: 1; background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #f97316 140%); display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px; color: #fff; }
        .sidebar h2 { font-size: 2.5rem; margin-bottom: 16px; }
        .sidebar p { font-size: 1.05rem; line-height: 1.6; color: rgba(255,255,255,0.85); }
        .main { flex: 1; background: #fff; color: #0f172a; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .card { width: 100%; max-width: 420px; background: #fff; border-radius: 24px; padding: 36px; border: 1px solid #e2e8f0; box-shadow: 0 15px 50px rgba(15,23,42,0.12); }
        h1 { margin-bottom: 12px; font-size: 1.9rem; font-weight: 800; }
        p { margin-bottom: 24px; color: #475569; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #475569; }
        input { width: 100%; padding: 14px; border-radius: 14px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 1rem; }
        input:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.15); }
        button { width: 100%; padding: 16px; border: none; border-radius: 14px; background: linear-gradient(135deg, #f97316, #fb923c); color: white; font-weight: 700; cursor: pointer; font-size: 1rem; }
        button:hover { opacity: 0.95; }
        .alert { padding: 14px 16px; border-radius: 14px; margin-bottom: 16px; font-weight: 600; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
        .alert-success { background: #d1fae5; color: #047857; }
        .footer { margin-top: 18px; text-align: center; }
        .footer a { color: #f97316; text-decoration: none; font-weight: 600; }
        @media (max-width: 1024px) { .sidebar { display: none; } .main { flex: 1; background: #0f172a; } .card { background: rgba(255,255,255,0.95); } }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Spendly Admin</h2>
            <p>Review insights, users, and transactions securely with your admin credentials.</p>
        </div>
        <div class="main">
            <div class="card">
                <h1>Admin Login</h1>
                <p>Only authorized personnel can access this panel.</p>

                @if($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif
                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember" style="margin:0;font-weight:500;">Stay signed in</label>
                    </div>
                    <button type="submit">Sign In</button>
                </form>

                <div class="footer">
                    <a href="{{ route('admin.password.request') }}">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

