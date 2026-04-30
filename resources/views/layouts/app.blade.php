<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Manager — @yield('title', 'Mes tâches')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon">✦</div>
        <div>
            <div class="brand-text">TaskManager</div>
            <div class="brand-sub">Manager Tasks</div>
        </div>
    </a>

    <div class="sidebar-section">
        <div class="sidebar-label">Menu principal</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            </span>
            Dashboard
        </a>
        <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="m9 14 2 2 4-4"/></svg>
            </span>
            Mes tâches
            @auth
                @php Auth::user()->loadCount('tasks'); @endphp
                @if(Auth::user()->tasks_count > 0)
                    <span class="nav-badge">{{ Auth::user()->tasks_count }}</span>
                @endif
            @endauth
        </a>
        <a href="{{ route('tasks.create') }}" class="nav-item {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
            </span>
            Nouvelle tâche
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-label">Compte</div>
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
            </span>
            Mon profil
        </a>
    </div>

    <div class="sidebar-spacer"></div>

    @auth
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="sidebar-logout" title="Déconnexion">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
    @endauth
</aside>

<!-- Main Content -->
<div class="main-content">
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
            <h1 class="topbar-title">@yield('title', 'Mes tâches')</h1>
        </div>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->translatedFormat('l d F Y') }}</span>
        </div>
    </header>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
