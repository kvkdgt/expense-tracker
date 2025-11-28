<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spendly Summary</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #0f172a; color: #e2e8f0; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 32px 20px; }
        .card { background: #111827; border-radius: 20px; padding: 28px; border: 1px solid #1f2937; }
        h1 { margin: 0 0 8px; font-size: 24px; color: #fff; }
        p { margin: 4px 0; line-height: 1.5; }
        .highlight { font-size: 32px; font-weight: 800; }
        .income { color: #10b981; }
        .expense { color: #f97316; }
        .net { color: #38bdf8; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 24px 0; }
        .stat { background: #0b1120; border-radius: 16px; padding: 16px; text-align: center; border: 1px solid #1f2937; }
        .stat-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-bottom: 8px; }
        .categories { margin-top: 24px; }
        .category-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #1f2937; }
        .category-item:last-child { border-bottom: none; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <h1>{{ $summary['period_label'] }}</h1>
            <p>Here is your {{ $summary['period'] }} income & expense summary.</p>

            <div class="grid">
                <div class="stat">
                    <div class="stat-label">Income</div>
                    <div class="highlight income">₹{{ number_format($summary['total_income'], 2) }}</div>
                </div>
                <div class="stat">
                    <div class="stat-label">Expense</div>
                    <div class="highlight expense">₹{{ number_format($summary['total_expense'], 2) }}</div>
                </div>
                <div class="stat">
                    <div class="stat-label">Net</div>
                    <div class="highlight net">₹{{ number_format($summary['net'], 2) }}</div>
                </div>
            </div>

            <p>You logged <strong>{{ $summary['transactions_count'] }}</strong> transactions during this period.</p>

            @if($summary['top_categories']->count())
                <div class="categories">
                    <h3 style="margin-bottom: 12px;">Top Spending Categories</h3>
                    @foreach($summary['top_categories'] as $category)
                        <div class="category-item">
                            <span>{{ $category['category'] }}</span>
                            <strong>₹{{ number_format($category['total'], 2) }}</strong>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="footer">
            You are receiving this email because you are subscribed to Spendly summaries.<br>
            Update your preferences any time inside the app.
        </div>
    </div>
</body>
</html>


