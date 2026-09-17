<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ube Repository') — Online Research Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<!-- TOP NAV -->
<header class="top-nav">
    <a href="{{ route('home') }}" class="logo-wrap">
        <img src="{{ asset('images/cropped-philcst766.png') }}" alt="Ube Repository" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <span class="logo-fallback" style="display:none">UBE</span>
    </a>

    <nav class="main-nav">
        <button class="nav-toggle" onclick="document.querySelector('.main-nav').classList.toggle('open')">☰</button>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('faq') }}">FAQ</a>
        <a href="{{ route('contact') }}">Contact</a>

        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-badge admin">
            ⚙ {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
            </a>
            @else
            @if(auth()->user()->role === 'researcher')
            <a href="{{ route('research.my-submissions') }}" class="{{ request()->routeIs('research.my-submissions') ? 'active' : '' }}">My Submissions</a>
            <a href="{{ route('research.submit') }}" class="nav-submit">Submit</a>
        @endif
         <a href="{{ route('research.pinned') }}" class="{{ request()->routeIs('research.pinned') ? 'active' : '' }}">Pinned</a>
        @endif

            <div class="user-menu">
    <button class="user-btn">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="avatar" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
        @else
            <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        @endif
        {{ Str::limit(auth()->user()->name, 14) }}
        <span class="caret">▾</span>
    </button>
    <div class="user-dropdown">
    <div class="dropdown-header">
        <div class="dd-avatar-row">
            @if(auth()->user()->profile_photo)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="avatar" class="dd-avatar-img">
            @else
                <div class="dd-avatar-big">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ auth()->user()->email }}</small>
            </div>
        </div>
        <span class="role-badge {{ auth()->user()->role }}">
            <span class="dd-role-dot"></span>{{ ucfirst(auth()->user()->role) }}
        </span>
    </div>

    <a href="{{ route('profile.edit') }}" class="dropdown-item">
        <div class="dd-item-icon dd-icon-profile">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
            <span class="dd-item-label">My Profile</span>
            <span class="dd-item-sub">Manage your account</span>
        </div>
    </a>

    <div class="dd-sep"></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item dd-signout">
            <div class="dd-item-icon dd-icon-out">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </div>
            <span class="dd-item-label">Sign Out</span>
        </button>
    </form>
</div>
        @else
            <a href="{{ route('login') }}" class="nav-login">Login</a>
            <a href="{{ route('register') }}" class="nav-register">Register</a>
        @endauth
    </nav>
</header>

@hasSection('hero')
<div class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-content">
        @yield('hero')
    </div>
</div>
@endif

<!-- Flash Messages -->
@if(session('success'))
    <div class="flash flash-success" id="flashMsg">
        <span>✓</span> {{ session('success') }}
        <button onclick="this.parentElement.remove()">×</button>
    </div>
@endif
@if(session('error'))
    <div class="flash flash-error" id="flashMsg">
        <span>✕</span> {{ session('error') }}
        <button onclick="this.parentElement.remove()">×</button>
    </div>
@endif

@yield('content')

<footer class="site-footer">
    <div class="footer-inner">

        <div class="footer-top">
            <!-- Brand -->
            <div class="footer-col">
                <span class="footer-logo">Ube Repository</span>
                <p class="footer-tagline">"Preserving Knowledge, Empowering Research"</p>
                <p class="footer-desc">The official online research portal of the Philippine College of Science and Technology.</p>
                <div class="footer-socials">
                    <a href="https://www.facebook.com/PhilCST94" target="_blank" title="Facebook">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="mailto:repository@philcst.edu.ph" title="Email">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4 class="footer-col-title">Quick Links</h4>
                <ul class="footer-links-list">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    @auth
                    @unless(auth()->user()->isAdmin())
                    <li><a href="{{ route('research.submit') }}">Submit Research</a></li>
                    @endunless
                    @endauth
                </ul>
            </div>

            <!-- Address -->
            <div class="footer-col">
                <h4 class="footer-col-title">Address</h4>
                <p class="footer-address">
                    Philippine College of Science and Technology<br>
                    Calasiao, Pangasinan<br>
                    Philippines 2418
                </p>
                <p class="footer-address" style="margin-top:10px;">
                    (075) 522-8032<br>
                    philcstreg@yahoo.com
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Ube Repository. All rights reserved.</p>
            <p>Philippine College of Science and Technology</p>
        </div>

    </div>
</footer>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>