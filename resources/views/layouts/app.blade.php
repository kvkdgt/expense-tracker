<!DOCTYPE html>
<html lang="en" data-theme="{{ Auth::user()->theme_preference ?? 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a0a0f" id="theme-color-meta">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" id="status-bar-style">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icon.svg">
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <title>@yield('title', 'Spendly')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        /* Dark Theme (Default) */
        :root,
        [data-theme="dark"] {
            --bg: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #16161f;
            --bg-input: #1e1e2a;
            --text: #ffffff;
            --text-secondary: #a1a1aa;
            --text-muted: #71717a;
            --accent: #f97316;
            --accent-light: #fb923c;
            --accent-bg: rgba(249, 115, 22, 0.15);
            --success: #22c55e;
            --success-bg: rgba(34, 197, 94, 0.15);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.15);
            --border: #27272a;
            --radius: 20px;
            --radius-sm: 14px;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        /* Light Theme */
        [data-theme="light"] {
            --bg: #f8fafc;
            --bg-secondary: #f1f5f9;
            --bg-card: #ffffff;
            --bg-input: #f8fafc;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent: #f97316;
            --accent-light: #fb923c;
            --accent-bg: rgba(249, 115, 22, 0.12);
            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.12);
            --danger: #ef4444;
            --danger-bg: rgba(239, 68, 68, 0.12);
            --border: #e2e8f0;
            --radius: 20px;
            --radius-sm: 14px;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        [data-theme="light"] .chart-container canvas {
            filter: brightness(1.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            min-height: 100dvh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .splash-screen {
            position: fixed;
            inset: 0;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 2000;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .splash-screen.hide {
            opacity: 0;
            visibility: hidden;
        }

        .splash-icon {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: white;
            font-weight: 800;
            font-size: 1.75rem;
        }


        /* Desktop Layout */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            padding: 32px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 40px;
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-icon svg {
            width: 26px;
            height: 26px;
            color: white;
        }

        .sidebar-logo-text {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--text);
        }

        .sidebar-nav {
            flex: 1;
        }

        .sidebar-nav-label {
            font-size: 0.6875rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 16px;
            margin-bottom: 12px;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            margin-bottom: 4px;
            transition: all 0.2s;
        }

        .sidebar-nav-item:hover {
            background: var(--bg-card);
            color: var(--text);
        }

        .sidebar-nav-item.active {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .sidebar-nav-item svg {
            width: 22px;
            height: 22px;
        }

        .sidebar-user {
            padding: 16px;
            background: var(--bg-card);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
            color: white;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-weight: 700;
            font-size: 0.9375rem;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-email {
            font-size: 0.8125rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-logout {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sidebar-logout:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: var(--bg-input);
            border-radius: var(--radius-sm);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }

        .theme-toggle:hover {
            background: var(--bg-card);
        }

        .theme-toggle-icon {
            width: 20px;
            height: 20px;
            color: var(--text-secondary);
        }

        .theme-toggle-text {
            flex: 1;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .theme-toggle-switch {
            width: 48px;
            height: 26px;
            background: var(--border);
            border-radius: 13px;
            position: relative;
            transition: background 0.2s;
        }

        [data-theme="light"] .theme-toggle-switch {
            background: var(--accent);
        }

        .theme-toggle-switch::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform 0.2s;
        }

        [data-theme="light"] .theme-toggle-switch::after {
            transform: translateX(22px);
        }

        .main-wrapper {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
        }

        .main-header {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            padding: 24px 40px;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text);
        }

        .main-header-subtitle {
            color: var(--text-muted);
            font-size: 0.9375rem;
            margin-top: 4px;
        }

        .main-content {
            padding: 32px 40px;
            max-width: 1400px;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 24px;
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* Stats */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 24px;
            border: 1px solid var(--border);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 1.5rem;
        }

        .stat-icon.balance { background: var(--accent-bg); }
        .stat-icon.income { background: var(--success-bg); }
        .stat-icon.expense { background: var(--danger-bg); }

        .stat-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
        }

        .stat-value.balance { color: var(--accent); }
        .stat-value.income { color: var(--success); }
        .stat-value.expense { color: var(--danger); }

        .stat-change {
            font-size: 0.8125rem;
            font-weight: 600;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .stat-change.positive { background: var(--success-bg); color: var(--success); }
        .stat-change.negative { background: var(--danger-bg); color: var(--danger); }

        /* Daily Cards */
        .daily-cards-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .daily-card {
            background: var(--bg-card);
            border-radius: var(--radius-sm);
            padding: 16px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .daily-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .daily-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .daily-income .daily-card-icon {
            background: var(--success-bg);
            color: var(--success);
        }

        .daily-expense .daily-card-icon {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .daily-card-content {
            flex: 1;
            min-width: 0;
        }

        .daily-card-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .daily-card-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
        }

        .daily-income .daily-card-value {
            color: var(--success);
        }

        .daily-expense .daily-card-value {
            color: var(--danger);
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .grid-equal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Chart */
        .chart-container {
            height: 300px;
        }

        /* Transaction List */
        .transaction-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: var(--bg-input);
            border-radius: var(--radius-sm);
            transition: background 0.2s;
        }

        .transaction-item:hover {
            background: var(--border);
        }

        .transaction-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            flex-shrink: 0;
        }

        .transaction-icon.income { background: var(--success-bg); }
        .transaction-icon.expense { background: var(--accent-bg); }

        .transaction-details {
            flex: 1;
            min-width: 0;
        }

        .transaction-category {
            font-weight: 700;
            font-size: 0.9375rem;
            color: var(--text);
        }

        .transaction-date {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .transaction-amount {
            font-weight: 800;
            font-size: 1rem;
        }

        .transaction-amount.income { color: var(--success); }
        .transaction-amount.expense { color: var(--text); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: var(--radius-sm);
            font-weight: 700;
            font-size: 0.9375rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: var(--bg-input);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .btn-block { width: 100%; }
        .btn-sm { padding: 10px 18px; font-size: 0.875rem; }

        /* Forms */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: var(--bg-input);
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-bg);
        }

        .form-input::placeholder { color: var(--text-muted); }

        /* Type Toggle */
        .type-toggle {
            display: flex;
            background: var(--bg-input);
            border-radius: var(--radius-sm);
            padding: 6px;
        }

        .type-btn {
            flex: 1;
            padding: 14px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 0.9375rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .type-btn.active { color: white; }
        .type-btn.expense.active { background: var(--accent); }
        .type-btn.income.active { background: var(--success); }

        /* Suggestions */
        .input-wrapper { position: relative; }

        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            margin-top: 4px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }

        .suggestion-item {
            padding: 14px 18px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.2s;
        }

        .suggestion-item:hover { background: var(--bg-input); }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            color: var(--border);
        }

        .empty-state h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--text-muted);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            width: 100%;
            max-width: 100%;
            background: var(--bg-card);
            border-radius: 28px 28px 0 0;
            padding: 28px 20px calc(28px + var(--safe-bottom));
            transform: translateY(100%);
            transition: transform 0.3s;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal {
            transform: translateY(0);
        }

        .modal-handle {
            width: 48px;
            height: 5px;
            background: var(--border);
            border-radius: 3px;
            margin: 0 auto 24px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 28px;
            color: var(--text);
        }

        /* Comparison */
        .comparison-card {
            background: var(--bg-input);
            border-radius: var(--radius-sm);
            padding: 18px;
            margin-bottom: 12px;
        }

        .comparison-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .comparison-title { font-weight: 700; color: var(--text); }

        .comparison-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 700;
        }

        .comparison-badge.up { background: var(--danger-bg); color: var(--danger); }
        .comparison-badge.down { background: var(--success-bg); color: var(--success); }
        .comparison-badge.same { background: var(--bg-secondary); color: var(--text-muted); }

        .comparison-values {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .comparison-message {
            margin-top: 10px;
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        /* Filter */
        .filter-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 10px 20px;
            background: var(--bg-input);
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            border: 2px solid var(--border);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-chip:hover { border-color: var(--accent); color: var(--accent); }
        .filter-chip.active {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 32px;
        }

        .pagination a, .pagination span {
            padding: 10px 16px;
            background: var(--bg-card);
            border-radius: 10px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .active { background: var(--accent); color: white; border-color: var(--accent); }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .alert-error {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* FAB - Mobile */
        .fab { display: none; }

        /* Add Button Desktop */
        .add-btn-desktop { display: flex; }

        /* Bottom Nav - Mobile */
        .bottom-nav { display: none; }

        /* Mobile Header */
        .mobile-header { display: none; }

        /* Link */
        .text-link { color: var(--accent); text-decoration: none; font-weight: 600; }
        .text-link:hover { text-decoration: underline; }

        .mb-6 { margin-bottom: 1.5rem; }

        /* ===== MOBILE STYLES ===== */
        @media (max-width: 1024px) {
            .sidebar { display: none; }
            .main-wrapper { margin-left: 0; }
            .main-header { display: none; }
            .stats-row { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .grid-2, .grid-equal { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            body {
                overflow-x: hidden;
                width: 100%;
                position: relative;
                min-height: 100vh;
                min-height: 100dvh;
            }

            .app-wrapper {
                width: 100%;
                max-width: 100vw;
                overflow-x: hidden;
            }

            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                background: var(--bg-secondary);
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 50;
                width: 100%;
                max-width: 100vw;
            }

            .mobile-header-title {
                font-size: 1.25rem;
                font-weight: 800;
                color: var(--text);
            }

            .mobile-header-subtitle {
                font-size: 0.8125rem;
                color: var(--text-muted);
            }

            .mobile-theme-toggle {
                background: var(--bg-input);
                border: 1px solid var(--border);
                border-radius: 10px;
                padding: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .mobile-theme-toggle:hover {
                background: var(--bg-card);
            }

            .mobile-theme-toggle svg {
                width: 20px;
                height: 20px;
                color: var(--text-secondary);
            }

            .main-content {
                padding: 20px 16px;
                padding-bottom: calc(100px + var(--safe-bottom));
                width: 100%;
                max-width: 100vw;
                overflow-x: hidden;
            }

            .card {
                padding: 20px;
                border-radius: var(--radius-sm);
                width: 100%;
                max-width: 100%;
            }

            .stat-card {
                padding: 18px;
                width: 100%;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
            }

            .chart-container {
                height: 220px;
                width: 100%;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                width: 100%;
            }

            .daily-cards-row {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                width: 100%;
                margin-bottom: 24px;
            }

            .daily-card {
                padding: 14px;
            }

            .daily-card-icon {
                width: 36px;
                height: 36px;
            }

            .daily-card-value {
                font-size: 1.125rem;
            }

            .daily-card-label {
                font-size: 0.6875rem;
            }

            .grid-2, .grid-equal {
                grid-template-columns: 1fr;
                gap: 16px;
                width: 100%;
            }

            .transaction-item {
                width: 100%;
                max-width: 100%;
            }

            .add-btn-desktop {
                display: none;
            }

            .fab {
                display: flex;
                position: fixed;
                bottom: calc(90px + var(--safe-bottom));
                right: 20px;
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
                border-radius: 50%;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 24px rgba(249, 115, 22, 0.4);
                cursor: pointer;
                border: none;
                z-index: 101;
            }

            .fab svg {
                width: 30px;
                height: 30px;
                color: white;
            }

            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: var(--bg-secondary);
                border-top: 1px solid var(--border);
                padding: 12px 8px calc(var(--safe-bottom) + 12px);
                justify-content: space-around;
                z-index: 100;
                width: 100%;
                max-width: 100vw;
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                color: var(--text-muted);
                text-decoration: none;
                font-size: 0.6875rem;
                font-weight: 600;
                padding: 8px 8px;
                border-radius: 12px;
                transition: all 0.2s;
                min-width: 0;
                flex: 1;
                max-width: 100px;
            }

            .nav-item svg {
                width: 24px;
                height: 24px;
            }

            .nav-item.active {
                color: var(--accent);
                background: var(--accent-bg);
            }

            .filter-bar {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding-bottom: 4px;
            }

            .filter-bar::-webkit-scrollbar {
                display: none;
            }

            .filter-chip {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .form-input, .form-group, .type-toggle {
                width: 100%;
                max-width: 100%;
            }

            .modal {
                width: 100%;
                max-width: 100%;
                border-radius: 28px 28px 0 0;
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="splash-screen" id="appSplash">
        <div class="splash-icon">S</div>
        <p style="font-weight:700; letter-spacing:0.1em;">SPENDLY</p>
        <p style="color:var(--text-muted); margin-top:8px; font-size:0.875rem;">Loading your finance hub…</p>
    </div>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="sidebar-logo-text">Spendly</span>
            </div>

            <nav class="sidebar-nav">
                <p class="sidebar-nav-label">Menu</p>
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('transactions.index') }}" class="sidebar-nav-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Transactions
                </a>
                <a href="{{ route('comparison.index') }}" class="sidebar-nav-item {{ request()->routeIs('comparison.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Compare
                </a>
                <a href="{{ route('reports.index') }}" class="sidebar-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                    </svg>
                    Reports
                </a>
                <a href="{{ route('settings.index') }}" class="sidebar-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.87l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
            </nav>


            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar-logout" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-wrapper">
            @yield('content')
        </main>
    </div>

    <script>
        window.csrfToken = '{{ csrf_token() }}';
        window.currentTheme = '{{ Auth::user()->theme_preference ?? 'dark' }}';
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount);
        }

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered: ', registration);
                        
                        // Check for updates every hour
                        setInterval(() => {
                            registration.update();
                        }, 3600000);
                    })
                    .catch((registrationError) => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

        // PWA Install Prompt - Store for Settings page
        window.deferredPrompt = null;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
        });

        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            window.deferredPrompt = null;
        });

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Update theme immediately for instant feedback
            html.setAttribute('data-theme', newTheme);
            updateMetaTags(newTheme);
            
            // Save to database
            fetch('{{ route("theme.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: newTheme })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      window.currentTheme = newTheme;
                  } else {
                      // Revert on error
                      html.setAttribute('data-theme', currentTheme);
                      updateMetaTags(currentTheme);
                  }
              }).catch(error => {
                  console.error('Error updating theme:', error);
                  // Revert on error
                  html.setAttribute('data-theme', currentTheme);
                  updateMetaTags(currentTheme);
              });
        }

        function updateMetaTags(theme) {
            const themeColorMeta = document.getElementById('theme-color-meta');
            const statusBarStyle = document.getElementById('status-bar-style');
            
            if (theme === 'light') {
                themeColorMeta.setAttribute('content', '#ffffff');
                statusBarStyle.setAttribute('content', 'default');
            } else {
                themeColorMeta.setAttribute('content', '#0a0a0f');
                statusBarStyle.setAttribute('content', 'black-translucent');
            }
        }

        // Initialize meta tags & splash on load
        document.addEventListener('DOMContentLoaded', function() {
            updateMetaTags(window.currentTheme);
            const splash = document.getElementById('appSplash');
            const splashKey = 'spendlySplashSeen';
            if (splash) {
                if (window.sessionStorage.getItem(splashKey)) {
                    splash.remove();
                } else {
                    setTimeout(() => {
                        splash.classList.add('hide');
                        setTimeout(() => splash.remove(), 700);
                    }, 700);
                    window.sessionStorage.setItem(splashKey, '1');
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
