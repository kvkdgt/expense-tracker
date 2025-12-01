<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { width: 100%; max-width: 420px; background: #1e293b; border-radius: 20px; padding: 32px; border: 1px solid rgba(255,255,255,0.05); }
        h1 { margin-bottom: 10px; font-size: 1.6rem; }
        p { margin-bottom: 24px; color: #94a3b8; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input { width: 100%; padding: 14px; border-radius: 12px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #f8fafc; }
        input:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,0.2); }
        button { width: 100%; padding: 14px; border: none; border-radius: 12px; background: linear-gradient(135deg, #f97316, #fb923c); color: white; font-weight: 700; cursor: pointer; }
        .alert { padding: 12px; border-radius: 12px; margin-bottom: 16px; font-weight: 600; }
        .alert-error { background: #fee2e2; color: #b91c1c; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .actions { margin-top: 20px; text-align: center; }
        .actions a { color: #fbbf24; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Reset Access</h1>
        <p>Enter the admin email to receive a secure reset link.</p>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <button type="submit">Send Reset Link</button>
        </form>

        <div class="actions">
            <a href="{{ route('admin.login') }}">Back to login</a>
        </div>
    </div>
</body>
</html>



