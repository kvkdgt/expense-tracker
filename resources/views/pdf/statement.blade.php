<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spendly {{ ucfirst($period) }} Statement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            background: #ffffff;
        }
        
        .header {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            padding: 30px;
            color: #ffffff;
            margin-bottom: 30px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo-section h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 5px;
            color: #ffffff;
        }
        
        .logo-section p {
            font-size: 12px;
            opacity: 0.95;
            color: #ffffff;
        }
        
        .statement-info {
            text-align: right;
            color: #ffffff;
        }
        
        .statement-info p {
            margin: 3px 0;
            font-size: 11px;
        }
        
        .statement-info strong {
            font-size: 14px;
        }
        
        .user-info {
            background: #f8fafc;
            padding: 20px 30px;
            margin-bottom: 25px;
            border-left: 4px solid #f97316;
        }
        
        .user-info p {
            margin: 5px 0;
            font-size: 11px;
            color: #475569;
        }
        
        .user-info strong {
            color: #1e293b;
            font-size: 12px;
        }
        
        .summary-section {
            background: #f8fafc;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-card {
            display: table-cell;
            width: 33.33%;
            background: #ffffff;
            padding: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .summary-card:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        
        .summary-card:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        
        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .summary-value {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.2;
        }
        
        .summary-value.income {
            color: #10b981;
        }
        
        .summary-value.expense {
            color: #ef4444;
        }
        
        .summary-value.net {
            color: #3b82f6;
        }
        
        .statement-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f97316;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .statement-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            margin-bottom: 20px;
        }
        
        .statement-header {
            background: #f1f5f9;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .statement-header th {
            padding: 12px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            font-weight: 700;
        }
        
        .statement-header th:last-child {
            text-align: right;
        }
        
        .statement-row {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .statement-row:nth-child(even) {
            background: #f8fafc;
        }
        
        .statement-row td {
            padding: 12px 10px;
            font-size: 10px;
            color: #1e293b;
        }
        
        .statement-date {
            font-weight: 600;
            color: #475569;
            white-space: nowrap;
        }
        
        .statement-category {
            color: #64748b;
            font-weight: 500;
        }
        
        .statement-note {
            color: #94a3b8;
            font-size: 9px;
            font-style: italic;
        }
        
        .statement-amount {
            text-align: right;
            font-weight: 700;
            font-size: 11px;
            white-space: nowrap;
        }
        
        .statement-amount.income {
            color: #10b981;
        }
        
        .statement-amount.expense {
            color: #ef4444;
        }
        
        .statement-amount.income::before {
            content: '+';
        }
        
        .statement-amount.expense::before {
            content: '-';
        }
        
        .totals-section {
            margin-top: 20px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .totals-row:last-child {
            margin-bottom: 0;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
        }
        
        .totals-label {
            display: table-cell;
            width: 70%;
            color: #64748b;
            font-weight: 600;
            font-size: 11px;
        }
        
        .totals-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-weight: 700;
            color: #1e293b;
            font-size: 12px;
        }
        
        .totals-row:last-child .totals-value {
            font-size: 14px;
            font-weight: 800;
        }
        
        .totals-value.income {
            color: #10b981;
        }
        
        .totals-value.expense {
            color: #ef4444;
        }
        
        .totals-value.net {
            color: #3b82f6;
        }
        
        .categories-section {
            margin-top: 30px;
        }
        
        .category-item {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .category-item:last-child {
            border-bottom: none;
        }
        
        .category-name {
            display: table-cell;
            width: 70%;
            color: #475569;
            font-weight: 500;
            font-size: 10px;
        }
        
        .category-amount {
            display: table-cell;
            width: 30%;
            text-align: right;
            color: #1e293b;
            font-weight: 700;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }
        
        .footer p {
            font-size: 9px;
            color: #64748b;
            margin: 3px 0;
        }
        
        .footer strong {
            color: #1e293b;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo-section">
                <h1>Spendly</h1>
                <p>Personal Expense Tracker</p>
            </div>
            <div class="statement-info">
                <p><strong>{{ $summary['period_label'] }} Statement</strong></p>
                <p>Generated: {{ now()->format('d M Y, h:i A') }}</p>
                <p>Period: {{ $summary['start']->format('d M Y') }} - {{ $summary['end']->format('d M Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- User Info -->
    <div class="user-info">
        <p><strong>Account Holder:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Statement Period:</strong> {{ $summary['period_label'] }}</p>
    </div>
    
    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Financial Summary</div>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-card">
                    <div class="summary-label">Total Income</div>
                    <div class="summary-value income">₹{{ number_format($summary['total_income'], 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Total Expense</div>
                    <div class="summary-value expense">₹{{ number_format($summary['total_expense'], 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Net Balance</div>
                    <div class="summary-value net">₹{{ number_format($summary['net'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction Statement -->
    <div class="statement-section">
        <h2 class="section-title">Transaction Details</h2>
        
        @if($summary['transactions']->count() > 0)
        <table class="statement-table">
            <thead class="statement-header">
                <tr>
                    <th style="width: 15%;">Date</th>
                    <th style="width: 20%;">Category</th>
                    <th style="width: 35%;">Description</th>
                    <th style="width: 30%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['transactions'] as $transaction)
                <tr class="statement-row">
                    <td class="statement-date">{{ $transaction['date'] }}</td>
                    <td class="statement-category">{{ $transaction['category'] }}</td>
                    <td>
                        @if($transaction['note'])
                            <span class="statement-note">{{ $transaction['note'] }}</span>
                        @else
                            <span style="color: #cbd5e1;">—</span>
                        @endif
                    </td>
                    <td class="statement-amount {{ $transaction['type'] }}">
                        ₹{{ number_format($transaction['amount'], 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Statement Totals -->
        <div class="totals-section">
            <div class="totals-row">
                <span class="totals-label">Total Income</span>
                <span class="totals-value income">+₹{{ number_format($summary['total_income'], 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Total Expense</span>
                <span class="totals-value expense">-₹{{ number_format($summary['total_expense'], 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="totals-label">Net Balance</span>
                <span class="totals-value net">₹{{ number_format($summary['net'], 2) }}</span>
            </div>
        </div>
        @else
        <p style="text-align: center; color: #94a3b8; padding: 40px 20px;">
            No transactions found for this period.
        </p>
        @endif
    </div>
    
    <!-- Top Categories -->
    @if($summary['top_categories']->count() > 0)
    <div class="categories-section">
        <h2 class="section-title">Top Spending Categories</h2>
        @foreach($summary['top_categories'] as $category)
        <div class="category-item">
            <span class="category-name">{{ $category['category'] }}</span>
            <span class="category-amount">₹{{ number_format($category['total'], 2) }}</span>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <p><strong>Spendly</strong> - Your Personal Expense Tracker</p>
        <p>This is an automated {{ $summary['period'] }} statement generated on {{ now()->format('d M Y, h:i A') }}</p>
        <p>© {{ date('Y') }} Spendly. All rights reserved.</p>
        <p style="margin-top: 10px; font-size: 8px; color: #cbd5e1;">This statement contains {{ $summary['transactions_count'] }} transaction(s) for the period {{ $summary['start']->format('d M Y') }} to {{ $summary['end']->format('d M Y') }}</p>
    </div>
</body>
</html>
