@extends('layouts.app')

@section('title', 'Transactions - Expense Tracker')

@section('styles')
<style>
    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        width: 100%;
        margin-bottom: 20px;
    }

    .filter-grid .form-label {
        margin-bottom: 6px;
    }

    @media (max-width: 640px) {
        .filter-grid {
            grid-template-columns: 1fr;
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
        <h1 class="main-header-title">Transactions</h1>
        <p class="main-header-subtitle">View and manage all your income & expenses</p>
    </div>
    <button class="btn btn-primary add-btn-desktop" onclick="openAddModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Add Transaction
    </button>
</div>

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-6">
        <form method="GET" action="{{ route('transactions.index') }}" id="filterForm" style="width: 100%; max-width: 100%;">
            <div class="filter-grid">
                <div>
                    <label class="form-label">From</label>
                    <input type="date" name="start_date" class="form-input" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="form-label">To</label>
                    <input type="date" name="end_date" class="form-input" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="filter-bar" style="width: 100%;">
                <button type="submit" name="type" value="" class="filter-chip {{ !request('type') ? 'active' : '' }}">All</button>
                <button type="submit" name="type" value="expense" class="filter-chip {{ request('type') === 'expense' ? 'active' : '' }}">Expenses</button>
                <button type="submit" name="type" value="income" class="filter-chip {{ request('type') === 'income' ? 'active' : '' }}">Income</button>
            </div>
        </form>
    </div>

    @if($transactions->count() > 0)
        @php $grouped = $transactions->groupBy(fn($i) => $i->transaction_date->format('Y-m-d')); @endphp
        @foreach($grouped as $date => $dayTx)
            <div style="margin-bottom: 20px;">
                <p style="font-size: 0.8125rem; font-weight: 700; color: var(--text-muted); margin-bottom: 12px; padding-left: 4px;">
                    {{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}
                </p>
                <div class="card" style="padding: 12px;">
                    <div class="transaction-list">
                        @foreach($dayTx as $tx)
                        <div class="transaction-item" style="width: 100%; max-width: 100%;">
                            <div class="transaction-icon {{ $tx->type }}">{{ $tx->type === 'income' ? '💵' : '💳' }}</div>
                            <div class="transaction-details" style="flex: 1; min-width: 0;">
                                <p class="transaction-category" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $tx->category->name }}</p>
                                @if($tx->note)<p class="transaction-date" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $tx->note }}</p>@endif
                            </div>
                            <div style="text-align: right; flex-shrink: 0;">
                                <span class="transaction-amount {{ $tx->type }}">{{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 0) }}</span>
                                <div style="margin-top: 6px;">
                                    <button onclick="deleteTransaction({{ $tx->id }})" class="btn btn-danger btn-sm" style="padding: 6px 12px; font-size: 0.75rem;">Delete</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        @if($transactions->hasPages())
        <div class="pagination">
            @if(!$transactions->onFirstPage())<a href="{{ $transactions->previousPageUrl() }}">← Prev</a>@endif
            @foreach($transactions->getUrlRange(max(1, $transactions->currentPage() - 2), min($transactions->lastPage(), $transactions->currentPage() + 2)) as $page => $url)
                @if($page == $transactions->currentPage())<span class="active">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
            @endforeach
            @if($transactions->hasMorePages())<a href="{{ $transactions->nextPageUrl() }}">Next →</a>@endif
        </div>
        @endif
    @else
        <div class="card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3>No transactions found</h3>
                <p>Try adjusting filters or add a new transaction</p>
            </div>
        </div>
    @endif
</div>

<button class="fab" onclick="openAddModal()">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
</button>

<nav class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('transactions.index') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        <span>History</span>
    </a>
    <a href="{{ route('comparison.index') }}" class="nav-item">
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
                    <input type="text" name="category_name" id="categoryInput" class="form-input" placeholder="e.g., Lunch, Rent" autocomplete="off" required>
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
            <div style="display: flex; gap: 12px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('addModal');
    const categoryInput = document.getElementById('categoryInput');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');
    const typeButtons = document.querySelectorAll('.type-btn');
    const typeInput = document.getElementById('transactionType');
    let debounceTimer;

    function openAddModal() { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeAddModal() { modal.classList.remove('active'); document.body.style.overflow = ''; document.getElementById('addTransactionForm').reset(); document.getElementById('dateInput').value = new Date().toISOString().split('T')[0]; typeButtons.forEach(b => { b.classList.remove('active'); if (b.dataset.type === 'expense') b.classList.add('active'); }); typeInput.value = 'expense'; suggestionsDropdown.style.display = 'none'; }

    modal.addEventListener('click', e => { if (e.target === modal) closeAddModal(); });
    typeButtons.forEach(b => { b.addEventListener('click', () => { typeButtons.forEach(x => x.classList.remove('active')); b.classList.add('active'); typeInput.value = b.dataset.type; categoryInput.value = ''; suggestionsDropdown.style.display = 'none'; }); });

    categoryInput.addEventListener('input', () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(fetchSuggestions, 200); });
    categoryInput.addEventListener('focus', fetchSuggestions);

    function fetchSuggestions() { fetch(`/api/category-suggestions?type=${typeInput.value}&q=${encodeURIComponent(categoryInput.value.trim())}`, { headers: { 'Accept': 'application/json' } }).then(r => r.json()).then(s => { if (s.length) { suggestionsDropdown.innerHTML = s.map(x => `<div class="suggestion-item" onclick="selectSuggestion('${x}')">${x}</div>`).join(''); suggestionsDropdown.style.display = 'block'; } else { suggestionsDropdown.style.display = 'none'; } }); }
    function selectSuggestion(n) { categoryInput.value = n; suggestionsDropdown.style.display = 'none'; document.getElementById('amountInput').focus(); }
    document.addEventListener('click', e => { if (!e.target.closest('.input-wrapper')) suggestionsDropdown.style.display = 'none'; });

    document.getElementById('addTransactionForm').addEventListener('submit', async e => { e.preventDefault(); const d = Object.fromEntries(new FormData(e.target)); try { const r = await fetch('{{ route("transactions.store") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }, body: JSON.stringify(d) }); if ((await r.json()).success) { closeAddModal(); window.location.reload(); } } catch { alert('Error'); } });

    async function deleteTransaction(id) { if (!confirm('Delete this transaction?')) return; try { const r = await fetch(`/transactions/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' } }); if (r.ok) window.location.reload(); } catch {} }

    document.querySelectorAll('#filterForm input[type="date"]').forEach(i => { i.addEventListener('change', () => document.getElementById('filterForm').submit()); });
</script>
@endsection
