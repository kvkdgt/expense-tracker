@extends('layouts.app')

@section('title', 'Settings - Spendly')

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
    <button class="mobile-theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
        </svg>
    </button>
</div>

<!-- Desktop Header -->
<div class="main-header">
    <div>
        <h1 class="main-header-title">Settings</h1>
        <p class="main-header-subtitle">Manage your app preferences and account settings</p>
    </div>
</div>

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Appearance Section -->
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">Appearance</h3>
        
        <div class="settings-item">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Theme</p>
                    <p class="settings-item-subtitle">Choose between light and dark mode</p>
                </div>
                <div class="theme-toggle-settings" onclick="toggleTheme()">
                    <div class="theme-toggle-switch"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- App Section -->
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">App</h3>
        
        <div class="settings-item" id="installAppItem" style="display: none;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--success-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Install App</p>
                    <p class="settings-item-subtitle">Add Spendly to your home screen</p>
                </div>
                <button class="btn btn-primary btn-sm" onclick="installApp()">Install</button>
            </div>
        </div>

        <div class="settings-item">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Version</p>
                    <p class="settings-item-subtitle">Spendly v1.0.0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Section -->
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">Account</h3>
        
        <div class="settings-item">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);">
                    <span style="color: white; font-weight: 700; font-size: 1rem;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title" id="userNameDisplay">{{ Auth::user()->name }}</p>
                    <p class="settings-item-subtitle" id="userEmailDisplay">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <div class="settings-item" onclick="openEditProfileModal()" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Edit Profile</p>
                    <p class="settings-item-subtitle">Update your name and email</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>

        <div class="settings-item" onclick="openChangePasswordModal()" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Change Password</p>
                    <p class="settings-item-subtitle">Update your account password</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Statements Section -->
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">Download Statements</h3>
        
        <div class="settings-item" onclick="downloadStatement('daily')" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Daily Statement</p>
                    <p class="settings-item-subtitle">Download yesterday's transaction statement (PDF)</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>

        <div class="settings-item" onclick="downloadStatement('weekly')" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Weekly Statement</p>
                    <p class="settings-item-subtitle">Download last week's transaction statement (PDF)</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>

        <div class="settings-item" onclick="downloadStatement('monthly')" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Monthly Statement</p>
                    <p class="settings-item-subtitle">Download last month's transaction statement (PDF)</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Data & Privacy Section -->
    <div class="card mb-6">
        <h3 class="card-title" style="margin-bottom: 20px;">Data & Privacy</h3>
        
        <div class="settings-item" onclick="exportData()" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--accent-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Export Data</p>
                    <p class="settings-item-subtitle">Download your transactions as CSV</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>

        <div class="settings-item">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--danger-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9M4.51 9.9l12.78 12.78M9.88 9.88l-3.29-3.29m7.53 7.53l3.29-3.29M9.88 9.88A2.25 2.25 0 0112 8.25h3.75a2.25 2.25 0 012.15 1.586l-3.29 3.29M9.88 9.88l-3.29-3.29m0 0A2.25 2.25 0 014.12 7.35l3.29 3.29M4.12 7.35L7.35 4.12" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title">Clear Cache</p>
                    <p class="settings-item-subtitle">Clear app cache and stored data</p>
                </div>
                <button class="btn btn-secondary btn-sm" onclick="clearCache()">Clear</button>
            </div>
        </div>

        <div class="settings-item" onclick="deleteAccount()" style="cursor: pointer;">
            <div class="settings-item-content">
                <div class="settings-item-icon" style="background: var(--danger-bg);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9M4.51 9.9l12.78 12.78M9.88 9.88l-3.29-3.29m7.53 7.53l3.29-3.29M9.88 9.88A2.25 2.25 0 0112 8.25h3.75a2.25 2.25 0 012.15 1.586l-3.29 3.29M9.88 9.88l-3.29-3.29m0 0A2.25 2.25 0 014.12 7.35l3.29 3.29M4.12 7.35L7.35 4.12" />
                    </svg>
                </div>
                <div class="settings-item-details">
                    <p class="settings-item-title" style="color: var(--danger);">Delete Account</p>
                    <p class="settings-item-subtitle">Permanently delete your account and all data</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--danger);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <div class="card">
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-danger btn-block" style="background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal-overlay" id="editProfileModal">
    <div class="modal">
        <div class="modal-handle"></div>
        <h2 class="modal-title">Edit Profile</h2>
        <form id="editProfileForm">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="profileName" class="form-input" value="{{ Auth::user()->name }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" id="profileEmail" class="form-input" value="{{ Auth::user()->email }}" required>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeEditProfileModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal-overlay" id="changePasswordModal">
    <div class="modal">
        <div class="modal-handle"></div>
        <h2 class="modal-title">Change Password</h2>
        <form id="changePasswordForm">
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" id="currentPassword" class="form-input" placeholder="Enter current password" required>
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" id="newPassword" class="form-input" placeholder="Enter new password" required>
                <p class="form-hint">Minimum 6 characters</p>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="confirmPassword" class="form-input" placeholder="Confirm new password" required>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeChangePasswordModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 2;">Change Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal-overlay" id="deleteAccountModal">
    <div class="modal">
        <div class="modal-handle"></div>
        <h2 class="modal-title" style="color: var(--danger);">Delete Account</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 0.9375rem;">
            This action cannot be undone. All your transactions, categories, and account data will be permanently deleted.
        </p>
        <form id="deleteAccountForm">
            <div class="form-group">
                <label class="form-label">Enter your password to confirm</label>
                <input type="password" name="password" id="deletePassword" class="form-input" placeholder="Enter your password" required>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 8px;">
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeDeleteAccountModal()">Cancel</button>
                <button type="submit" class="btn btn-danger" style="flex: 2; background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger);">Delete Account</button>
            </div>
        </form>
    </div>
</div>

<!-- Bottom Nav -->
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
    <a href="{{ route('reports.index') }}" class="nav-item">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
        </svg>
        <span>Reports</span>
    </a>
    <a href="{{ route('settings.index') }}" class="nav-item active">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.87l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.87l.214-1.281z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Settings</span>
    </a>
</nav>
@endsection

@section('styles')
<style>
    .settings-item {
        padding: 16px;
        background: var(--bg-input);
        border-radius: var(--radius-sm);
        margin-bottom: 12px;
        transition: background 0.2s;
    }

    .settings-item:hover {
        background: var(--border);
    }

    .settings-item-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .settings-item-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .settings-item-icon svg {
        color: var(--accent);
    }

    .settings-item-details {
        flex: 1;
        min-width: 0;
    }

    .settings-item-title {
        font-weight: 700;
        font-size: 0.9375rem;
        color: var(--text);
        margin-bottom: 2px;
    }

    .settings-item-subtitle {
        font-size: 0.8125rem;
        color: var(--text-muted);
    }

    .theme-toggle-settings {
        cursor: pointer;
    }

    .theme-toggle-settings .theme-toggle-switch {
        width: 52px;
        height: 28px;
        background: var(--border);
        border-radius: 14px;
        position: relative;
        transition: background 0.2s;
    }

    [data-theme="light"] .theme-toggle-settings .theme-toggle-switch {
        background: var(--accent);
    }

    .theme-toggle-settings .theme-toggle-switch::after {
        content: '';
        position: absolute;
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: transform 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    [data-theme="light"] .theme-toggle-settings .theme-toggle-switch::after {
        transform: translateX(24px);
    }

    .form-hint {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin-top: 6px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Show install button if available
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        window.deferredPrompt = e;
        document.getElementById('installAppItem').style.display = 'block';
    });

    if (window.matchMedia('(display-mode: standalone)').matches) {
        document.getElementById('installAppItem').style.display = 'none';
    }

    window.addEventListener('appinstalled', () => {
        document.getElementById('installAppItem').style.display = 'none';
        window.deferredPrompt = null;
    });

    async function installApp() {
        if (!window.deferredPrompt) {
            alert('App installation is not available. Please use your browser menu to add to home screen.');
            return;
        }
        
        window.deferredPrompt.prompt();
        const { outcome } = await window.deferredPrompt.userChoice;
        
        if (outcome === 'accepted') {
            document.getElementById('installAppItem').style.display = 'none';
        }
        
        window.deferredPrompt = null;
    }

    function clearCache() {
        if (confirm('Are you sure you want to clear the app cache? This will remove offline data.')) {
            if ('caches' in window) {
                caches.keys().then((names) => {
                    names.forEach((name) => {
                        caches.delete(name);
                    });
                    alert('Cache cleared successfully!');
                    window.location.reload();
                });
            } else {
                alert('Cache clearing is not supported in this browser.');
            }
        }
    }

    function exportData() {
        window.location.href = '{{ route("transactions.export") }}';
    }

    function downloadStatement(period) {
        window.location.href = `/statements/download/${period}`;
    }

    function deleteAccount() {
        if (confirm('Are you sure you want to delete your account? This action cannot be undone and will permanently delete all your data.')) {
            openDeleteAccountModal();
        }
    }

    function openDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('deleteAccountForm').reset();
    }

    // Edit Profile Modal
    function openEditProfileModal() {
        document.getElementById('editProfileModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditProfileModal() {
        document.getElementById('editProfileModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.getElementById('editProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch('{{ route("profile.update") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('userNameDisplay').textContent = result.user.name;
                document.getElementById('userEmailDisplay').textContent = result.user.email;
                closeEditProfileModal();
                alert('Profile updated successfully!');
            } else {
                alert(result.message || 'Error updating profile');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error updating profile');
        }
    });

    document.getElementById('editProfileModal').addEventListener('click', (e) => {
        if (e.target.id === 'editProfileModal') closeEditProfileModal();
    });

    // Change Password Modal
    function openChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('changePasswordForm').reset();
    }

    document.getElementById('changePasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch('{{ route("password.change") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                closeChangePasswordModal();
                alert('Password changed successfully!');
            } else {
                alert(result.message || 'Error changing password');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error changing password');
        }
    });

    document.getElementById('changePasswordModal').addEventListener('click', (e) => {
        if (e.target.id === 'changePasswordModal') closeChangePasswordModal();
    });

    // Delete Account Modal
    document.getElementById('deleteAccountForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);

        if (!confirm('This is your final confirmation. Your account and all data will be permanently deleted. This cannot be undone. Are you absolutely sure?')) {
            return;
        }

        try {
            const response = await fetch('{{ route("account.delete") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                alert('Account deleted successfully. You will be redirected to login.');
                window.location.href = result.redirect;
            } else {
                alert(result.message || 'Error deleting account');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error deleting account');
        }
    });

    document.getElementById('deleteAccountModal').addEventListener('click', (e) => {
        if (e.target.id === 'deleteAccountModal') closeDeleteAccountModal();
    });
</script>
@endsection
