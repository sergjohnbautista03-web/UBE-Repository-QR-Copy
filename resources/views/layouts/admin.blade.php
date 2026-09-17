<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Ube Repository Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-logo"></span>
        <div>
            <span class="sidebar-title">{{ auth()->user()->isSuperAdmin() ? 'Ube Super Admin' : 'Ube Admin' }}</span>
            <span class="sidebar-sub">Research Portal</span>
        </div>
    </div>

    {{-- Dashboard --}}
<a href="{{ route('admin.dashboard') }}" class="sidelink {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg></span>
    Dashboard
</a>

{{-- Researches --}}
<a href="{{ route('admin.researches') }}" class="sidelink {{ request()->routeIs('admin.researches*') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414A1 1 0 0 1 19 9.414V19a2 2 0 0 1-2 2z"/></svg></span>
    Researches
    @php $pending = \App\Models\Research::pending()->count(); @endphp
    @if($pending > 0)<span class="badge-count">{{ $pending }}</span>@endif
</a>

{{-- Users --}}
<a href="{{ route('admin.users') }}" class="sidelink {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
    Users
</a>

{{-- Pending Researchers --}}
<a href="{{ route('admin.pending-researchers') }}" class="sidelink {{ request()->routeIs('admin.pending*') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></span>
    Pending Researchers
    @php $pendingR = \App\Models\User::where('role','researcher')->where('is_approved',false)->count(); @endphp
    @if($pendingR > 0)<span class="badge-count">{{ $pendingR }}</span>@endif
</a>


{{-- Graduated Researchers --}}
<a href="{{ route('admin.graduated-researchers') }}" class="sidelink {{ request()->routeIs('admin.graduated-researchers') ? 'active' : '' }}">
    <span class="sidelink-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>
        </svg>
    </span>
    Graduated Researchers
</a>

{{-- Manage Admins (SuperAdmin only) --}}
@if(auth()->user()->isSuperAdmin())
<a href="{{ route('super-admin.admins') }}" class="sidelink {{ request()->routeIs('super-admin.*') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
    Manage Admins
</a>
@endif

<hr class="sidebar-divider">

{{-- View Site --}}
<a href="{{ route('home') }}" class="sidelink">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/></svg></span>
    View Site
</a>

{{-- Reports --}}
<a href="{{ route('admin.reports') }}" class="sidelink {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
    Reports
</a>

{{-- Trash (SuperAdmin only) --}}
@if(auth()->user()->isSuperAdmin())
<a href="{{ route('super-admin.trash') }}" class="sidelink {{ request()->routeIs('super-admin.trash*') ? 'active' : '' }}">
    <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/></svg></span>
    Trash
</a>
@endif

{{-- Sign Out --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="sidelink sidelink-logout">
        <span class="sidelink-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
        Sign Out
    </button>
</form>

    <div class="sidebar-user">
    <span class="user-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
    <div style="flex:1; min-width:0;">
        <strong>{{ Str::limit(auth()->user()->name, 18) }}</strong>
        <small>{{ auth()->user()->isSuperAdmin() ? 'Super Administrator' : 'Administrator' }}</small>
    </div>
</div>
</aside>

<main class="admin-main">
    <div class="admin-topbar">
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->format('F j, Y') }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="flash flash-success">
            <span>✓</span> {{ session('success') }}
            <button onclick="this.parentElement.remove()">×</button>
        </div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">
            <span>✕</span> {{ session('error') }}
            <button onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @yield('content')
</main>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    setTimeout(function() {
        const flash = document.querySelector('.flash-success, .flash-error');
        if (flash) {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
@stack('scripts')
</body>
</html>