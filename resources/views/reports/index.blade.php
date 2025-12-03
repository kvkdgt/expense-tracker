@extends('layouts.app')

@section('title', 'Reports - Expense Tracker')

@section('styles')
<style>
    .filter-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }

    .filter-section.full {
        grid-template-columns: 1fr;
    }

    .type-selector {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        background: var(--bg-card);
        padding: 4px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
    }

    .type-btn {
        flex: 1;
        padding: 12px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.875rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .type-btn.active {
        background: var(--accent);
        color: white;
    }

    .type-btn.active.income {
        background: var(--success);
    }

    .type-btn.active.expense {
        background: var(--danger);
    }

    .period-buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 20px;
    }

    .period-btn {
        padding: 10px 12px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text);
        font-weight: 600;
        font-size: 0.8125rem;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .period-btn.active {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .date-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .category-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px;
        margin-bottom: 12px;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .category-name {
        font-weight: 700;
        font-size: 0.9375rem;
        color: var(--text);
    }

    .category-amount {
        font-weight: 800;
        font-size: 1.125rem;
    }

    .category-amount.income {
        color: var(--success);
    }

    .category-amount.expense {
        color: var(--danger);
    }

    .category-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .progress-bar {
        height: 8px;
        background: var(--border);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-fill.income {
        background: var(--success);
    }

    .progress-fill.expense {
        background: var(--danger);
    }

    .summary-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
        margin-bottom: 24px;
        text-align: center;
    }

    .summary-label {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-bottom: 8px;
        font-weight: 600;
    }

    .summary-value {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .summary-value.income {
        color: var(--success);
    }

    .summary-value.expense {
        color: var(--danger);
    }

    .summary-period {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .filter-section {
            grid-template-columns: 1fr;
        }

        .period-buttons {
            grid-template-columns: repeat(2, 1fr);
        }

        .date-inputs {
            grid-template-columns: 1fr;
        }

        .category-item {
            padding: 12px;
        }

        .summary-value {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="mobile-header">
    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">S</div>
        <div>
            <h1 class="mobile-header-title" style="font-size: 1rem; margin-bottom: 2px;">Spendly</h1>
            <p class="mobile-header-subtitle" style="font-size: 0.75rem;">{{ Auth::user()->name }}</p>
        </div>
    </div>
    <button class="mobile-theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        </svg>
    </button>
</div>

<div class="main-header">
    <div>
        <h1 class="main-header-title">Category Reports</h1>
        <p class="main-header-subtitle">View your income and expenses by category</p>
    </div>
</div>

<div class="main-content">
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">Filters</h3>
        
        <!-- Type Selector -->
        <div class="type-selector">
            <button class="type-btn income {{ $type === 'income' ? 'active' : '' }}" onclick="setType('income')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                </svg>
                Income
            </button>
            <button class="type-btn expense {{ $type === 'expense' ? 'active' : '' }}" onclick="setType('expense')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                </svg>
                Expense
            </button>
        </div>

        <!-- Period Buttons -->
        <div class="period-buttons">
            <button class="period-btn {{ $period === 'daily' ? 'active' : '' }}" onclick="setPeriod('daily')">Daily</button>
            <button class="period-btn {{ $period === 'weekly' ? 'active' : '' }}" onclick="setPeriod('weekly')">Weekly</button>
            <button class="period-btn {{ $period === 'monthly' ? 'active' : '' }}" onclick="setPeriod('monthly')">Monthly</button>
            <button class="period-btn {{ $period === 'custom' ? 'active' : '' }}" onclick="setPeriod('custom')">Custom</button>
        </div>

        <!-- Date Inputs (shown only for custom) -->
        <div id="customDates" class="filter-section {{ $period === 'custom' ? '' : 'full' }}" style="display: {{ $period === 'custom' ? 'grid' : 'none' }};">
            <div class="date-inputs">
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" id="startDate" class="form-input" value="{{ $startDate }}">
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" id="endDate" class="form-input" value="{{ $endDate }}">
                </div>
            </div>
        </div>

        <button class="btn btn-primary btn-block" onclick="loadReport()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
            </svg>
            Generate Report
        </button>
    </div>

    <!-- Summary Card -->
    <div id="summaryCard" style="display: none;">
        <div class="summary-card">
            <p class="summary-label" id="summaryLabel">Total</p>
            <p class="summary-value" id="summaryValue">₹0</p>
            <p class="summary-period" id="summaryPeriod">-</p>
        </div>
    </div>

    <!-- Results -->
    <div id="reportResults" style="display: none;">
        <div class="card">
            <h3 class="card-title">Category Breakdown</h3>
            <div id="categoryList" style="margin-top: 20px;"></div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" style="display: none; text-align: center; padding: 60px;">
        <div style="width: 48px; height: 48px; border: 4px solid var(--border); border-top-color: var(--accent); border-radius: 50%; margin: 0 auto 20px; animation: spin 1s linear infinite;"></div>
        <p style="color: var(--text-muted); font-weight: 500;">Loading report...</p>
    </div>

    <!-- Empty State -->
    <div id="emptyState" style="display: none;">
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                </svg>
                <h3>No data found</h3>
                <p>No transactions found for the selected period</p>
            </div>
        </div>
    </div>
</div>

<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <span>Home</span>
    </a>
    <a href="{{ route('transactions.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <span>History</span>
    </a>
    <a href="{{ route('comparison.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span>Compare</span>
    </a>
    <a href="{{ route('reports.index') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
        </svg>
        <span>Reports</span>
    </a>
    <a href="{{ route('settings.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.87l.214-1.281z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Settings</span>
    </a>
</nav>
@endsection

@section('scripts')
<script>
    let currentType = '{{ $type }}';
    let currentPeriod = '{{ $period }}';

    function setType(type) {
        currentType = type;
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.closest('.type-btn').classList.add('active');
    }

    function setPeriod(period) {
        currentPeriod = period;
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        const customDates = document.getElementById('customDates');
        if (period === 'custom') {
            customDates.style.display = 'grid';
        } else {
            customDates.style.display = 'none';
        }
    }

    async function loadReport() {
        const period = currentPeriod;
        const type = currentType;
        let startDate, endDate;

        if (period === 'custom') {
            startDate = document.getElementById('startDate').value;
            endDate = document.getElementById('endDate').value;
            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }
            if (new Date(startDate) > new Date(endDate)) {
                alert('Start date must be before end date');
                return;
            }
        } else {
            // Dates will be calculated on server
            startDate = '';
            endDate = '';
        }

        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('reportResults').style.display = 'none';
        document.getElementById('summaryCard').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';

        try {
            const response = await fetch('{{ route("reports.data") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    period: period,
                    type: type,
                    start_date: startDate,
                    end_date: endDate
                })
            });

            const data = await response.json();
            document.getElementById('loadingState').style.display = 'none';

            if (!data.categories || data.categories.length === 0) {
                document.getElementById('emptyState').style.display = 'block';
                return;
            }

            // Update summary
            document.getElementById('summaryLabel').textContent = `Total ${type === 'income' ? 'Income' : 'Expense'}`;
            document.getElementById('summaryValue').textContent = '₹' + formatNumber(data.total);
            document.getElementById('summaryValue').className = 'summary-value ' + type;
            document.getElementById('summaryPeriod').textContent = data.period_label;
            document.getElementById('summaryCard').style.display = 'block';

            // Update category list
            const categoryList = document.getElementById('categoryList');
            categoryList.innerHTML = data.categories.map(cat => `
                <div class="category-item">
                    <div class="category-header">
                        <span class="category-name">${cat.category}</span>
                        <span class="category-amount ${type}">₹${formatNumber(cat.total)}</span>
                    </div>
                    <div class="category-meta">
                        <span>${cat.count} transaction${cat.count !== 1 ? 's' : ''}</span>
                        <span>${cat.percentage}% of total</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill ${type}" style="width: ${cat.percentage}%"></div>
                    </div>
                </div>
            `).join('');

            document.getElementById('reportResults').style.display = 'block';
        } catch (error) {
            document.getElementById('loadingState').style.display = 'none';
            alert('Error loading report. Please try again.');
            console.error(error);
        }
    }

    function formatNumber(num) {
        return Math.abs(num).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Load report on page load
    window.addEventListener('DOMContentLoaded', function() {
        loadReport();
    });
</script>
@endsection

