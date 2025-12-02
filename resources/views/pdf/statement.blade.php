<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spendly {{ ucfirst($period) }} Statement</title>
    <style>
        @page {
            margin: 0.5cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            background: #ffffff;
            padding: 0;
            margin: 0;
        }
        
        .header {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            padding: 15px 20px;
            color: #ffffff;
            margin-bottom: 15px;
        }
        
        .header-content {
            display: table;
            width: 100%;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: middle;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        
        .logo-section h1 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 2px;
            color: #ffffff;
        }
        
        .logo-section p {
            font-size: 9px;
            opacity: 0.95;
            color: #ffffff;
        }
        
        .statement-info p {
            margin: 2px 0;
            font-size: 9px;
        }
        
        .statement-info strong {
            font-size: 11px;
        }
        
        .user-info {
            background: #f8fafc;
            padding: 12px 20px;
            margin-bottom: 15px;
            border-left: 3px solid #f97316;
        }
        
        .user-info-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .user-info-row:last-child {
            margin-bottom: 0;
        }
        
        .user-info-label {
            display: table-cell;
            width: 30%;
            font-weight: 700;
            color: #1e293b;
            font-size: 9px;
        }
        
        .user-info-value {
            display: table-cell;
            width: 70%;
            color: #475569;
            font-size: 9px;
        }
        
        .summary-section {
            background: #f8fafc;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-title {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            width: 33.33%;
            background: #ffffff;
            padding: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .summary-table td:first-child {
            border-left: none;
        }
        
        .summary-table td:last-child {
            border-right: none;
        }
        
        .summary-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 6px;
            font-weight: 600;
        }
        
        .summary-value {
            font-size: 18px;
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
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #f97316;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .statement-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            margin-bottom: 12px;
            font-size: 9px;
        }
        
        .statement-header {
            background: #f1f5f9;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .statement-header th {
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
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
            padding: 8px 6px;
            font-size: 9px;
            color: #1e293b;
        }
        
        .statement-date {
            font-weight: 600;
            color: #475569;
            white-space: nowrap;
            width: 15%;
        }
        
        .statement-category {
            color: #64748b;
            font-weight: 500;
            width: 20%;
        }
        
        .statement-note {
            color: #94a3b8;
            font-size: 8px;
            font-style: italic;
            width: 35%;
        }
        
        .statement-amount {
            text-align: right;
            font-weight: 700;
            font-size: 9px;
            white-space: nowrap;
            width: 30%;
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
            margin-top: 12px;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-table tr:last-child {
            border-bottom: none;
            border-top: 2px solid #e2e8f0;
            margin-top: 8px;
        }
        
        .totals-table td {
            padding: 6px 0;
        }
        
        .totals-label {
            color: #64748b;
            font-weight: 600;
            font-size: 9px;
            width: 70%;
        }
        
        .totals-value {
            text-align: right;
            font-weight: 700;
            color: #1e293b;
            font-size: 10px;
            width: 30%;
        }
        
        .totals-table tr:last-child .totals-value {
            font-size: 12px;
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
            margin-top: 15px;
        }
        
        .category-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .category-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        .category-table tr:last-child {
            border-bottom: none;
        }
        
        .category-table td {
            padding: 6px 0;
        }
        
        .category-name {
            color: #475569;
            font-weight: 500;
            font-size: 9px;
            width: 70%;
        }
        
        .category-amount {
            text-align: right;
            color: #1e293b;
            font-weight: 700;
            font-size: 9px;
            width: 30%;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }
        
        .footer p {
            font-size: 8px;
            color: #64748b;
            margin: 2px 0;
        }
        
        .footer strong {
            color: #1e293b;
        }
        
        .no-transactions {
            text-align: center;
            color: #94a3b8;
            padding: 30px 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-section">
                    <h1>Spendly</h1>
                    <p>Personal Expense Tracker</p>
                </div>
            </div>
            <div class="header-right">
                <div class="statement-info">
                    <p><strong>{{ $summary['period_label'] }} Statement</strong></p>
                    <p>Generated: {{ now()->format('d M Y, h:i A') }}</p>
                    <p>Period: {{ $summary['start']->format('d M Y') }} - {{ $summary['end']->format('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Info -->
    <div class="user-info">
        <div class="user-info-row">
            <span class="user-info-label">Account Holder:</span>
            <span class="user-info-value">{{ $user->name }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">Email:</span>
            <span class="user-info-value">{{ $user->email }}</span>
        </div>
        <div class="user-info-row">
            <span class="user-info-label">Statement Period:</span>
            <span class="user-info-value">{{ $summary['period_label'] }}</span>
        </div>
    </div>
    
    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Financial Summary</div>
        <table class="summary-table">
            <tr>
                <td>
                    <div class="summary-label">Total Income</div>
                    <div class="summary-value income">₹{{ number_format($summary['total_income'], 2) }}</div>
                </td>
                <td>
                    <div class="summary-label">Total Expense</div>
                    <div class="summary-value expense">₹{{ number_format($summary['total_expense'], 2) }}</div>
                </td>
                <td>
                    <div class="summary-label">Net Balance</div>
                    <div class="summary-value net">₹{{ number_format($summary['net'], 2) }}</div>
                </td>
            </tr>
        </table>
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
                    <td class="statement-note">
                        @if($transaction['note'])
                            {{ $transaction['note'] }}
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
            <table class="totals-table">
                <tr>
                    <td class="totals-label">Total Income</td>
                    <td class="totals-value income">+₹{{ number_format($summary['total_income'], 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">Total Expense</td>
                    <td class="totals-value expense">-₹{{ number_format($summary['total_expense'], 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">Net Balance</td>
                    <td class="totals-value net">₹{{ number_format($summary['net'], 2) }}</td>
                </tr>
            </table>
        </div>
        @else
        <p class="no-transactions">No transactions found for this period.</p>
        @endif
    </div>
    
    <!-- Top Categories -->
    @if($summary['top_categories']->count() > 0)
    <div class="categories-section">
        <h2 class="section-title">Top Spending Categories</h2>
        <table class="category-table">
            @foreach($summary['top_categories'] as $category)
            <tr>
                <td class="category-name">{{ $category['category'] }}</td>
                <td class="category-amount">₹{{ number_format($category['total'], 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <p><strong>Spendly</strong> - Your Personal Expense Tracker</p>
        <p>This is an automated {{ $summary['period'] }} statement generated on {{ now()->format('d M Y, h:i A') }}</p>
        <p>© {{ date('Y') }} Spendly. All rights reserved.</p>
        <p style="margin-top: 6px; font-size: 7px; color: #cbd5e1;">This statement contains {{ $summary['transactions_count'] }} transaction(s) for the period {{ $summary['start']->format('d M Y') }} to {{ $summary['end']->format('d M Y') }}</p>
    </div>
</body>
</html>
