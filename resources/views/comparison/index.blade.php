@extends('layouts.app')

@section('title', 'Compare - Expense Tracker')

@section('styles')
<style>
    .month-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        width: 100%;
        margin-bottom: 24px;
    }

    .month-grid .form-label {
        margin-bottom: 6px;
    }

    .comparison-stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        width: 100%;
        margin-bottom: 24px;
    }

    .income-savings-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 20px;
        border-radius: var(--radius-sm);
        gap: 12px;
    }

    .income-savings-item {
        flex: 1;
        min-width: 0;
        overflow: hidden;
    }

    .income-savings-item p {
        word-break: break-word;
        overflow-wrap: break-word;
        line-height: 1.3;
    }

    .income-savings-item p:last-child {
        font-size: clamp(1rem, 2.5vw, 1.375rem);
    }

    .income-savings-item.center {
        text-align: center;
    }

    .income-savings-item.right {
        text-align: right;
    }

    .comparison-card {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .comparison-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .comparison-title {
        word-break: break-word;
        overflow-wrap: break-word;
        flex: 1;
        min-width: 0;
    }

    .comparison-badge {
        flex-shrink: 0;
        white-space: nowrap;
    }

    .comparison-values {
        flex-wrap: wrap;
        gap: 8px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .comparison-values span {
        word-break: break-word;
        overflow-wrap: break-word;
    }

    @media (max-width: 640px) {
        .month-grid {
            grid-template-columns: 1fr;
        }

        .comparison-stats-row {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .income-savings-card {
            padding: 12px;
            gap: 6px;
            margin-top: 16px;
        }

        .income-savings-item {
            min-width: 0;
            flex: 1 1 0;
        }

        .income-savings-item p {
            font-size: 0.6875rem !important;
            margin-bottom: 2px !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            line-height: 1.3 !important;
        }

        .income-savings-item p:last-child {
            font-size: clamp(0.75rem, 2.5vw, 1rem) !important;
            font-weight: 800 !important;
            line-height: 1.2 !important;
            word-break: keep-all !important;
        }

        .comparison-stats-row .stat-card {
            padding: 14px !important;
        }

        .comparison-stats-row .stat-value {
            font-size: clamp(1rem, 3vw, 1.25rem) !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            line-height: 1.2 !important;
        }

        .comparison-stats-row .stat-card {
            overflow: hidden;
        }

        .comparison-card {
            padding: 12px !important;
            margin-bottom: 10px !important;
        }

        .comparison-header {
            margin-bottom: 8px !important;
        }

        .comparison-title {
            font-size: 0.875rem !important;
        }

        .comparison-badge {
            padding: 4px 10px !important;
            font-size: 0.75rem !important;
        }

        .comparison-values {
            font-size: clamp(0.75rem, 2vw, 0.8125rem) !important;
            gap: 4px !important;
        }

        .comparison-values span {
            display: block;
            margin-bottom: 2px;
        }

        .comparison-message {
            margin-top: 6px !important;
            font-size: 0.75rem !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        .comparison-header {
            align-items: flex-start !important;
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
        <h1 class="main-header-title">Compare Months</h1>
        <p class="main-header-subtitle">See how your spending changes between different months</p>
    </div>
</div>

<div class="main-content">
    <div class="card mb-6" style="width: 100%; max-width: 100%;">
        <h3 style="font-weight: 700; margin-bottom: 20px; color: var(--text);">Select Months</h3>
        <div class="month-grid">
            <div>
                <label class="form-label">First Month</label>
                <input type="month" id="month1" class="form-input" value="{{ $month1 }}">
            </div>
            <div>
                <label class="form-label">Second Month</label>
                <input type="month" id="month2" class="form-input" value="{{ $month2 }}">
            </div>
        </div>
        <button class="btn btn-primary btn-block" onclick="loadComparison()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Compare Now
        </button>
    </div>

    <div id="comparisonResults" style="display: none; width: 100%; max-width: 100%;">
        <div class="comparison-stats-row">
            <div class="stat-card expense" style="width: 100%;">
                <p class="stat-label" id="month1Label">Month 1</p>
                <p class="stat-value expense" id="month1Expense">₹0</p>
                <p style="font-size: 0.8125rem; color: var(--text-muted);">Total Expenses</p>
            </div>
            <div class="stat-card expense" style="width: 100%;">
                <p class="stat-label" id="month2Label">Month 2</p>
                <p class="stat-value expense" id="month2Expense">₹0</p>
                <p style="font-size: 0.8125rem; color: var(--text-muted);">Total Expenses</p>
            </div>
            <div class="stat-card balance" style="width: 100%;">
                <p class="stat-label">Difference</p>
                <p class="stat-value" id="expenseDifference">₹0</p>
                <p id="expenseMessage" style="font-size: 0.8125rem;"></p>
            </div>
        </div>

        <div class="grid-equal" style="width: 100%; max-width: 100%;">
            <div class="card" style="width: 100%; max-width: 100%;">
                <h3 class="card-title">Income</h3>
                <div class="income-savings-card" style="background: var(--success-bg);">
                    <div class="income-savings-item">
                        <p style="font-size: 0.75rem; color: var(--success); margin-bottom: 4px;" id="month1LabelIncome">Month 1</p>
                        <p style="font-size: 1.375rem; font-weight: 800; color: var(--success);" id="month1Income">₹0</p>
                    </div>
                    <div class="income-savings-item center">
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Change</p>
                        <p style="font-size: 1.375rem; font-weight: 800;" id="incomeDifference">₹0</p>
                    </div>
                    <div class="income-savings-item right">
                        <p style="font-size: 0.75rem; color: var(--success); margin-bottom: 4px;" id="month2LabelIncome">Month 2</p>
                        <p style="font-size: 1.375rem; font-weight: 800; color: var(--success);" id="month2Income">₹0</p>
                    </div>
                </div>
            </div>
            <div class="card" style="width: 100%; max-width: 100%;">
                <h3 class="card-title">Savings</h3>
                <div class="income-savings-card" style="background: #ede9fe;">
                    <div class="income-savings-item">
                        <p style="font-size: 0.75rem; color: #8b5cf6; margin-bottom: 4px;" id="month1LabelSavings">Month 1</p>
                        <p style="font-size: 1.375rem; font-weight: 800;" id="month1Savings">₹0</p>
                    </div>
                    <div class="income-savings-item center">
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px;">Change</p>
                        <p style="font-size: 1.375rem; font-weight: 800;" id="savingsDifference">₹0</p>
                    </div>
                    <div class="income-savings-item right">
                        <p style="font-size: 0.75rem; color: #8b5cf6; margin-bottom: 4px;" id="month2LabelSavings">Month 2</p>
                        <p style="font-size: 1.375rem; font-weight: 800;" id="month2Savings">₹0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="width: 100%; max-width: 100%;">
            <h3 class="card-title">Category Breakdown</h3>
            <div id="categoryList" style="margin-top: 20px; width: 100%;"></div>
        </div>
    </div>

    <div id="loadingState" style="display: none; text-align: center; padding: 60px;">
        <div style="width: 48px; height: 48px; border: 4px solid var(--border); border-top-color: var(--orange); border-radius: 50%; margin: 0 auto 20px; animation: spin 1s linear infinite;"></div>
        <p style="color: var(--text-muted); font-weight: 500;">Loading comparison...</p>
    </div>

    <div id="emptyState" style="display: none;">
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <h3>No data to compare</h3>
                <p>Add some transactions first</p>
            </div>
        </div>
    </div>
</div>

<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('transactions.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        <span>History</span>
    </a>
    <a href="{{ route('comparison.index') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
        <span>Compare</span>
    </a>
    <a href="{{ route('reports.index') }}" class="nav-item">
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
    async function loadComparison() {
        const m1 = document.getElementById('month1').value, m2 = document.getElementById('month2').value;
        if (!m1 || !m2) { alert('Select both months'); return; }
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('comparisonResults').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';

        try {
            const r = await fetch('{{ route("comparison.compare") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ month1: m1, month2: m2 }) });
            const d = await r.json();
            document.getElementById('loadingState').style.display = 'none';

            if (!d.month1.expense && !d.month2.expense && !d.month1.income && !d.month2.income) { document.getElementById('emptyState').style.display = 'block'; return; }

            ['', 'Income', 'Savings'].forEach(s => { document.getElementById('month1Label' + s).textContent = d.month1.label; document.getElementById('month2Label' + s).textContent = d.month2.label; });

            document.getElementById('month1Expense').textContent = '₹' + fmt(d.month1.expense);
            document.getElementById('month2Expense').textContent = '₹' + fmt(d.month2.expense);
            document.getElementById('month1Income').textContent = '₹' + fmt(d.month1.income);
            document.getElementById('month2Income').textContent = '₹' + fmt(d.month2.income);
            document.getElementById('month1Savings').textContent = '₹' + fmt(d.month1.savings);
            document.getElementById('month2Savings').textContent = '₹' + fmt(d.month2.savings);

            document.getElementById('month1Savings').style.color = d.month1.savings >= 0 ? '#10b981' : '#ef4444';
            document.getElementById('month2Savings').style.color = d.month2.savings >= 0 ? '#10b981' : '#ef4444';

            const ed = d.differences.expense, edEl = document.getElementById('expenseDifference');
            edEl.textContent = (ed >= 0 ? '+' : '') + '₹' + fmt(ed);
            edEl.style.color = ed <= 0 ? '#10b981' : '#ef4444';
            document.getElementById('expenseMessage').innerHTML = ed < 0 ? `<span style="color:#10b981">You saved ₹${fmt(Math.abs(ed))}</span>` : ed > 0 ? `<span style="color:#ef4444">You spent ₹${fmt(ed)} more</span>` : '<span style="color:var(--text-muted)">Same spending</span>';

            const id = d.differences.income, idEl = document.getElementById('incomeDifference');
            idEl.textContent = (id >= 0 ? '+' : '') + '₹' + fmt(id);
            idEl.style.color = id >= 0 ? '#10b981' : '#ef4444';

            const sd = d.differences.savings, sdEl = document.getElementById('savingsDifference');
            sdEl.textContent = (sd >= 0 ? '+' : '') + '₹' + fmt(sd);
            sdEl.style.color = sd >= 0 ? '#10b981' : '#ef4444';

            document.getElementById('categoryList').innerHTML = d.categories.length ? d.categories.map(c => `
                <div class="comparison-card">
                    <div class="comparison-header">
                        <span class="comparison-title">${c.name}</span>
                        <span class="comparison-badge ${c.trend}">${c.trend === 'down' ? '↓' : c.trend === 'up' ? '↑' : '='} ₹${fmt(Math.abs(c.difference))}</span>
                    </div>
                    <div class="comparison-values"><span>${d.month1.label}: ₹${fmt(c.month1)}</span><span>${d.month2.label}: ₹${fmt(c.month2)}</span></div>
                    <p class="comparison-message">${c.message}</p>
                </div>
            `).join('') : '<p style="text-align:center;color:var(--text-muted);padding:24px;">No categories to compare</p>';

            document.getElementById('comparisonResults').style.display = 'block';
        } catch { document.getElementById('loadingState').style.display = 'none'; alert('Error'); }
    }
    function fmt(n) { return Math.abs(n).toLocaleString('en-IN'); }
    loadComparison();
</script>
@endsection
