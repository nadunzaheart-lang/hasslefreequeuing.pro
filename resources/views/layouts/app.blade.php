<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Registrar Queuing System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signout.css') }}">
</head>
<body>

<div class="app-container">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" width="50" height="50">
            <div class="brand-text">
                <h2>Registrar</h2>
                <span>Queuing System</span>
            </div>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">MAIN MENU</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <span class="nav-icon">▦</span> <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">☷</span> <span>Queue</span>
            </a>
            <a href="{{ route('admin.accounts') }}" class="nav-item {{ request()->is('admin/accounts') ? 'active' : '' }}">
                <span class="nav-icon">♙</span> <span>Accounts</span>
            </a>
            <a href="{{ route('admin.windows') }}" class="nav-item {{ request()->is('admin/windows') ? 'active' : '' }}">
                <span class="nav-icon">▥</span> <span>Windows & Courses</span>
            </a>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">MANAGEMENT</span>
            <a href="{{ route('admin.history') }}" class="nav-item {{ request()->is('admin/history') ? 'active' : '' }}">
                <span class="nav-icon">◷</span> <span>Queue History</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">▤</span> <span>Reports</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">⚙</span> <span>Settings</span>
            </a>
        </div>

        <div class="sidebar-bottom">
            <div class="system-status">
                <span class="status-dot"></span>
                <div>
                    <strong>System Online</strong>
                    <small>All services running</small>
                </div>
            </div>
            <div class="sidebar-user">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="user-info">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>System Admin</span>
                </div>
            </div>

            <!-- SIGN OUT (button + confirmation modal) -->
            @include('partials.signout')
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-button" id="menuButton">☰</button>
                <div>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-description', 'Monitor and manage the registrar queue.')</p>
                </div>
            </div>
            <div class="topbar-right">
                <div class="current-date">
                    <span class="date-icon">◷</span>
                    <div>
                        <strong id="currentDate">Loading...</strong>
                        <small id="currentTime">--:--:--</small>
                    </div>
                </div>
                <div class="notification-button"><span>♢</span><i></i></div>
                <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            </div>
        </header>

        <section class="page-content">
            @yield('content')
        </section>
    </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/signout.js') }}"></script>
@yield('scripts')
</body>
</html>
