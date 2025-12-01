<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spendly {{ ucfirst($period) }} Statement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .email-wrapper {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
        }
        
        .header {
            background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #ffffff;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.95;
            color: #ffffff;
        }
        
        .content {
            padding: 32px 24px;
            background: #ffffff;
        }
        
        .summary-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 20px;
        }
        
        .summary-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .summary-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .summary-value {
            font-size: 24px;
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
            margin-top: 32px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .statement-table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .statement-header {
            background: #f1f5f9;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .statement-header th {
            padding: 14px 12px;
            text-align: left;
            font-size: 11px;
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
            transition: background 0.2s;
        }
        
        .statement-row:hover {
            background: #f8fafc;
        }
        
        .statement-row:last-child {
            border-bottom: none;
        }
        
        .statement-row td {
            padding: 16px 12px;
            font-size: 14px;
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
            font-size: 12px;
            font-style: italic;
        }
        
        .statement-amount {
            text-align: right;
            font-weight: 700;
            font-size: 15px;
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
            margin-top: 24px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .totals-row:last-child {
            border-bottom: none;
            font-weight: 800;
            font-size: 16px;
            padding-top: 16px;
            margin-top: 8px;
            border-top: 2px solid #e2e8f0;
        }
        
        .totals-label {
            color: #64748b;
            font-weight: 600;
        }
        
        .totals-value {
            font-weight: 700;
            color: #1e293b;
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
            margin-top: 32px;
        }
        
        .category-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .category-item:last-child {
            border-bottom: none;
        }
        
        .category-name {
            color: #475569;
            font-weight: 500;
        }
        
        .category-amount {
            color: #1e293b;
            font-weight: 700;
        }
        
        .footer {
            background: #f8fafc;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0;
        }
        
        @media only screen and (max-width: 600px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .statement-header th,
            .statement-row td {
                padding: 10px 8px;
                font-size: 12px;
            }
            
            .statement-date {
                font-size: 11px;
            }
            
            .statement-amount {
                font-size: 13px;
            }
            
            .content {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Spendly</h1>
            <p>{{ $summary['period_label'] }} Statement</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p style="background: #dbeafe; color: #1e40af; padding: 12px; border-radius: 8px; margin-bottom: 24px; font-size: 13px; border-left: 4px solid #3b82f6;">
                <strong>📎 PDF Statement Attached</strong><br>
                Your detailed transaction statement is attached to this email as a PDF document.
            </p>
            <!-- Summary Section -->
            <div class="summary-section">
                <p style="color: #64748b; font-size: 14px; margin-bottom: 0;">
                    Your {{ $summary['period'] }} financial summary
                </p>
                <div class="summary-grid">
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
            
            <!-- Transaction Statement -->
            <div class="statement-section">
                <h2 class="section-title">Transaction Statement</h2>
                
                @if($summary['transactions']->count() > 0)
                <table class="statement-table">
                    <thead class="statement-header">
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
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
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Spendly</strong> - Your Personal Expense Tracker</p>
            <p>This is an automated {{ $summary['period'] }} statement. You are receiving this because you have an active account.</p>
            <p style="margin-top: 12px; color: #94a3b8;">© {{ date('Y') }} Spendly. All rights reserved.</p>
        </div>
    </div>
</body>
</html>