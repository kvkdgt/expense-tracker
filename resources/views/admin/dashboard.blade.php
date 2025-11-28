<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Spendly</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; margin: 0; }
        header { padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); background: #111c2e; }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand-icon { width: 44px; height: 44px; border-radius: 14px; background: linear-gradient(135deg, #f97316, #fb923c); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem; }
        main { padding: 32px 28px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
        .card { background: #16223a; border-radius: 18px; padding: 20px; border: 1px solid rgba(255,255,255,0.05); }
        .card h3 { margin: 0 0 8px; font-size: 0.9rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; }
        .card p { margin: 0; font-size: 1.8rem; font-weight: 700; }
        button { border: none; border-radius: 12px; padding: 10px 16px; background: rgba(255,255,255,0.08); color: #e2e8f0; cursor: pointer; font-weight: 600; }
        button:hover { opacity: 0.8; }
        form { margin: 0; }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <div class="brand-icon">S</div>
            <div>
                <div style="font-weight: 700;">Spendly Admin</div>
                <small style="color:#94a3b8;">Secure Operations</small>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </header>

    <main>
        <h1 style="margin-bottom: 24px;">Overview</h1>
        <div class="stats">
            <div class="card">
                <h3>Total Users</h3>
                <p>{{ $userCount }}</p>
            </div>
            <div class="card">
                <h3>Transactions</h3>
                <p>{{ $transactionCount }}</p>
            </div>
            <div class="card">
                <h3>Income Logged</h3>
                <p>₹{{ number_format($totalIncome, 0) }}</p>
            </div>
            <div class="card">
                <h3>Expense Logged</h3>
                <p>₹{{ number_format($totalExpense, 0) }}</p>
            </div>
        </div>
    </main>
</body>
</html>

