@extends('layouts.app')

@section('title', 'Dashboard - Expense Tracker')

@section('content')
<!-- Mobile Header -->
<div class="mobile-header">
    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%); display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">S</div>
        <div>
            <h1 class="mobile-header-title" style="font-size: 1rem; margin-bottom: 2px;">Spendly</h1>
            <p class="mobile-header-subtitle" style="font-size: 0.75rem;">{{ Auth::user()->name }}</p>
        </div>
    </div>
    <div style="display: flex; gap: 8px;">
        <button class="mobile-theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
            </svg>
        </button>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" style="background: var(--accent-bg); border: none; color: var(--accent); cursor: pointer; padding: 10px; border-radius: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- Desktop Header -->
<div class="main-header">
    <div>
        <h1 class="main-header-title">Dashboard</h1>
        <p class="main-header-subtitle">Welcome back! Here's your financial overview for {{ $currentMonth->format('F Y') }}</p>
    </div>
    <button class="btn btn-primary add-btn-desktop" onclick="openAddModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Transaction
    </button>
</div>

<div class="main-content">
    <!-- Daily Cards -->
    <div class="daily-cards-row">
        <div class="daily-card daily-income">
            <div class="daily-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                </svg>
            </div>
            <div class="daily-card-content">
                <p class="daily-card-label">Today's Income</p>
                <p class="daily-card-value">₹{{ number_format($todayIncome, 0) }}</p>
            </div>
        </div>
        <div class="daily-card daily-expense">
            <div class="daily-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                </svg>
            </div>
            <div class="daily-card-content">
                <p class="daily-card-label">Today's Expense</p>
                <p class="daily-card-value">₹{{ number_format($todayExpense, 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card balance">
            <div class="stat-icon balance">💰</div>
            <p class="stat-label">Current Balance</p>
            <p class="stat-value balance">₹{{ number_format($currentMonthIncome - $currentMonthExpense, 0) }}</p>
        </div>
        <div class="stat-card income">
            <div class="stat-icon income">📈</div>
            <p class="stat-label">Total Income</p>
            <p class="stat-value income">₹{{ number_format($currentMonthIncome, 0) }}</p>
            @php $incomeChange = $lastMonthIncome > 0 ? (($currentMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100 : 0; @endphp
            <span class="stat-change {{ $incomeChange >= 0 ? 'positive' : 'negative' }}">
                {{ $incomeChange >= 0 ? '↑' : '↓' }} {{ abs(round($incomeChange)) }}%
            </span>
        </div>
        <div class="stat-card expense">
            <div class="stat-icon expense">🛒</div>
            <p class="stat-label">Total Expenses</p>
            <p class="stat-value expense">₹{{ number_format($currentMonthExpense, 0) }}</p>
            @php $expenseChange = $lastMonthExpense > 0 ? (($currentMonthExpense - $lastMonthExpense) / $lastMonthExpense) * 100 : 0; @endphp
            <span class="stat-change {{ $expenseChange <= 0 ? 'positive' : 'negative' }}">
                {{ $expenseChange >= 0 ? '↑' : '↓' }} {{ abs(round($expenseChange)) }}%
            </span>
        </div>
        <div class="stat-card savings">
            <div class="stat-icon savings">🎯</div>
            <p class="stat-label">Savings Rate</p>
            @php $savingsRate = $currentMonthIncome > 0 ? (($currentMonthIncome - $currentMonthExpense) / $currentMonthIncome) * 100 : 0; @endphp
            <p class="stat-value savings">{{ round($savingsRate) }}%</p>
        </div>
    </div>

    <!-- Chart & Top Expenses -->
    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Spending Overview</h2>
                <span style="font-size: 0.8125rem; color: var(--text-muted); font-weight: 500;">Last 30 days</span>
            </div>
            <div class="chart-container">
                <canvas id="spendingChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Top Categories</h2>
            </div>
            @if($categoryExpenses->count() > 0)
            <div class="transaction-list" style="max-height: 300px; overflow-y: auto;">
                @foreach($categoryExpenses as $category)
                <div class="transaction-item">
                    <div class="transaction-icon expense">🏷️</div>
                    <div class="transaction-details">
                        <p class="transaction-category">{{ $category->name }}</p>
                        <p class="transaction-date">{{ round(($category->total / max($currentMonthExpense, 1)) * 100) }}% of total</p>
                    </div>
                    <span class="transaction-amount expense">₹{{ number_format($category->total, 0) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state" style="padding: 32px;">
                <p style="color: var(--text-muted);">No expenses recorded yet</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Transactions</h2>
            <a href="{{ route('transactions.index') }}" class="text-link">View All →</a>
        </div>
        @if($recentTransactions->count() > 0)
        <div class="transaction-list">
            @foreach($recentTransactions as $transaction)
            <div class="transaction-item">
                <div class="transaction-icon {{ $transaction->type }}">
                    {{ $transaction->type === 'income' ? '💵' : '💳' }}
                </div>
                <div class="transaction-details">
                    <p class="transaction-category">{{ $transaction->category->name }}</p>
                    <p class="transaction-date">{{ $transaction->transaction_date->format('M d, Y') }}{{ $transaction->note ? ' • '.$transaction->note : '' }}</p>
                </div>
                <span class="transaction-amount {{ $transaction->type }}">
                    {{ $transaction->type === 'income' ? '+' : '-' }}₹{{ number_format($transaction->amount, 0) }}
                </span>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3>No transactions yet</h3>
            <p>Click the button above to add your first transaction</p>
        </div>
        @endif
    </div>
</div>

<!-- FAB -->
<button class="fab" onclick="openAddModal()">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
</button>

<!-- Bottom Nav -->
<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="nav-item active">
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

<!-- Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal-handle"></div>
        <h2 class="modal-title">Add Transaction</h2>
        <form id="addTransactionForm">
            <div class="type-toggle">
                <button type="button" class="type-btn expense active" data-type="expense">Expense</button>
                <button type="button" class="type-btn income" data-type="income">Income</button>
            </div>
            <input type="hidden" name="type" id="transactionType" value="expense">
            <div class="form-group" style="margin-top: 24px;">
                <label class="form-label">Category</label>
                <div class="input-wrapper">
                    <input type="text" name="category_name" id="categoryInput" class="form-input" placeholder="e.g., Lunch, Rent, Salary" autocomplete="off" required>
                    <div class="suggestions-dropdown" id="suggestionsDropdown" style="display: none;"></div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Amount (₹)</label>
                <input type="number" name="amount" id="amountInput" class="form-input" placeholder="0" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="transaction_date" id="dateInput" class="form-input" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Note (Optional)</label>
                <input type="text" name="note" id="noteInput" class="form-input" placeholder="Add a note...">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const dailyData = @json($dailyData);
    const ctx = document.getElementById('spendingChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(249, 115, 22, 0.25)');
    gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

    const gradientGreen = ctx.createLinearGradient(0, 0, 0, 300);
    gradientGreen.addColorStop(0, 'rgba(34, 197, 94, 0.25)');
    gradientGreen.addColorStop(1, 'rgba(34, 197, 94, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyData.map(d => new Date(d.date).getDate()),
            datasets: [{
                label: 'Expenses',
                data: dailyData.map(d => d.expense),
                borderColor: '#f97316',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                borderWidth: 3,
            }, {
                label: 'Income',
                data: dailyData.map(d => d.income),
                borderColor: '#22c55e',
                backgroundColor: gradientGreen,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: { color: '#a1a1aa', usePointStyle: true, padding: 20, font: { size: 12, family: 'Inter', weight: '600' } }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#71717a', font: { size: 11, family: 'Inter' } } },
                y: { grid: { color: '#27272a' }, ticks: { color: '#71717a', font: { size: 11, family: 'Inter' }, callback: v => '₹' + v.toLocaleString('en-IN') } }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });

    const modal = document.getElementById('addModal');
    const categoryInput = document.getElementById('categoryInput');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');
    const typeButtons = document.querySelectorAll('.type-btn');
    const typeInput = document.getElementById('transactionType');
    let debounceTimer;

    function openAddModal() { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeAddModal() {
        modal.classList.remove('active'); document.body.style.overflow = '';
        document.getElementById('addTransactionForm').reset();
        document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
        typeButtons.forEach(btn => { btn.classList.remove('active'); if (btn.dataset.type === 'expense') btn.classList.add('active'); });
        typeInput.value = 'expense'; suggestionsDropdown.style.display = 'none';
    }

    modal.addEventListener('click', e => { if (e.target === modal) closeAddModal(); });
    typeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            typeButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active'); typeInput.value = btn.dataset.type;
            categoryInput.value = ''; suggestionsDropdown.style.display = 'none';
        });
    });

    categoryInput.addEventListener('input', () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(fetchSuggestions, 200); });
    categoryInput.addEventListener('focus', fetchSuggestions);

    function fetchSuggestions() {
        fetch(`/api/category-suggestions?type=${typeInput.value}&q=${encodeURIComponent(categoryInput.value.trim())}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json()).then(s => {
            if (s.length) { suggestionsDropdown.innerHTML = s.map(x => `<div class="suggestion-item" onclick="selectSuggestion('${x}')">${x}</div>`).join(''); suggestionsDropdown.style.display = 'block'; }
            else { suggestionsDropdown.style.display = 'none'; }
        });
    }

    function selectSuggestion(n) { categoryInput.value = n; suggestionsDropdown.style.display = 'none'; document.getElementById('amountInput').focus(); }
    document.addEventListener('click', e => { if (!e.target.closest('.input-wrapper')) suggestionsDropdown.style.display = 'none'; });

    document.getElementById('addTransactionForm').addEventListener('submit', async e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target));
        try {
            const r = await fetch('{{ route("transactions.store") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(data) });
            const res = await r.json();
            if (res.success) { closeAddModal(); window.location.reload(); } else { alert('Error'); }
        } catch { alert('Error'); }
    });
</script>
@endsection
