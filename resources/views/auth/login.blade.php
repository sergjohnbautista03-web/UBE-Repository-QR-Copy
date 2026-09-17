@extends('layouts.app')
@section('title', 'Login — Ube Repository')

@section('content')
<div class="auth-split">

    {{-- LEFT PANEL --}}
    <div class="auth-left">
        <div class="auth-left-inner">
            <div class="auth-logo-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <div class="brand-eyebrow">Ube Repository</div>
            <h1 class="auth-brand-title">Welcome </h1>
            <p class="auth-brand-sub">Sign in to access your research dashboard and continue contributing to the community.</p>
            <div class="brand-feature-list">
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Findings of research papers at PHILCST</span></div>
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Submit and manage your work</span></div>
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Connect with your department</span></div>
            </div>
            <div class="deco-ring deco-ring-1"></div>
            <div class="deco-ring deco-ring-2"></div>
            <div class="deco-ring deco-ring-3"></div>
            <div class="deco-glow"></div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="auth-right">
        <div class="auth-right-inner">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    </div>
                    <div>
                        <h2 class="form-heading">Sign In</h2>
                        <p class="form-subheading">Enter your credentials to continue</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="your@email.com" required autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrap password-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Your password" required autocomplete="off">
                            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-row-inline">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="btn-auth">
                        <span>Sign In</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ route('register') }}">Create one here</a></p>
                <a href="{{ route('home') }}" class="back-link">← Back to Repository</a>
            </div>
        </div>
    </div>
</div>

<style>
.auth-split { display: flex; min-height: calc(100vh - 64px); }

/* LEFT */
.auth-left {
    flex: 0 0 44%;
    background: #1c044a;
    background-image:
        radial-gradient(ellipse at 30% 25%, rgba(120,40,230,.65) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 80%, rgba(65,10,155,.5) 0%, transparent 50%);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}
.auth-left-inner { padding: 64px 52px; position: relative; z-index: 2; max-width: 360px; }
.auth-logo-wrap {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center; margin-bottom: 32px;
}
.brand-eyebrow {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 2px; color: rgba(190,150,255,.7); margin-bottom: 12px;
}
.auth-brand-title {
    font-family: var(--font-body); font-size: 52px; font-weight: 800;
    color: #fff; line-height: 1.05; letter-spacing: -2px; margin-bottom: 18px;
}
.auth-brand-sub { font-size: 13.5px; color: rgba(255,255,255,.5); line-height: 1.75; margin-bottom: 34px; }
.brand-feature-list { display: flex; flex-direction: column; gap: 11px; }
.brand-feature { display: flex; align-items: center; gap: 10px; font-size: 13px; color: rgba(255,255,255,.55); }
.brand-feature-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(175,120,255,.85); flex-shrink: 0; }
.deco-ring { position: absolute; border-radius: 50%; border: 1px solid rgba(255,255,255,.07); pointer-events: none; }
.deco-ring-1 { width: 330px; height: 330px; top: -100px; right: -100px; }
.deco-ring-2 { width: 210px; height: 210px; bottom: 50px; right: -60px; border-color: rgba(255,255,255,.05); }
.deco-ring-3 { width: 120px; height: 120px; bottom: -30px; left: 70px; background: rgba(255,255,255,.03); }
.deco-glow {
    position: absolute; width: 300px; height: 300px; border-radius: 50%;
    background: radial-gradient(circle, rgba(120,50,255,.18) 0%, transparent 70%);
    bottom: -80px; right: -80px; pointer-events: none;
}

/* RIGHT */
.auth-right { flex: 1; background: #f2eef9; display: flex; align-items: center; justify-content: center; }
.auth-right-inner { width: 100%; max-width: 420px; padding: 48px 32px; }

/* FORM CARD */
.form-card {
    background: #fff; border-radius: 20px;
    border: 1px solid rgba(107,47,160,.1);
    padding: 30px 30px 26px;
    box-shadow: 0 6px 36px rgba(59,15,122,.07), 0 1px 6px rgba(0,0,0,.04);
    margin-bottom: 20px;
}
.form-card-header {
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 26px; padding-bottom: 22px;
    border-bottom: 1px solid #f0eaf9;
}
.form-card-icon {
    width: 42px; height: 42px; border-radius: 11px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    color: #6b2fa0; flex-shrink: 0;
}
.form-heading {
    font-family: var(--font-body); font-size: 20px; font-weight: 800;
    color: #1a0638; letter-spacing: -.4px; margin: 0 0 2px;
}
.form-subheading { font-size: 12.5px; color: #a090bc; margin: 0; }

/* INPUTS */
.auth-form .form-group { margin-bottom: 16px; }
.auth-form .form-group label {
    display: block; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px; color: #5b3d8a; margin-bottom: 7px;
}
.input-wrap { position: relative; }
.input-icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #c0aee0; display: flex; align-items: center; pointer-events: none;
}
.auth-form input[type="email"],
.auth-form input[type="password"],
.auth-form input[type="text"] {
    width: 100%; padding: 11px 14px 11px 38px;
    border: 1.5px solid #e8dff5; border-radius: 11px;
    background: #faf8ff; font-size: 14px; color: #1a0638;
    transition: border-color .15s, box-shadow .15s, background .15s;
    box-sizing: border-box;
}
.auth-form input:focus {
    outline: none; border-color: #7c3aed; background: #fff;
    box-shadow: 0 0 0 3.5px rgba(124,58,237,.1);
}
.password-wrap input { padding-right: 44px; }
.toggle-pw {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #c0aee0; padding: 0; line-height: 1;
}
.toggle-pw:hover { color: #7c3aed; }
.form-row-inline {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
}
.checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b5e8a; cursor: pointer; }
.checkbox-label input[type="checkbox"] { accent-color: #7c3aed; }
.forgot-link { font-size: 13px; color: #7c3aed; font-weight: 600; text-decoration: none; }
.forgot-link:hover { text-decoration: underline; }

/* BUTTON */
.btn-auth {
    width: 100%; padding: 13px 20px;
    background: #3b0f7a; color: #fff;
    font-size: 14.5px; font-weight: 700; border: none; border-radius: 50px;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 20px rgba(59,15,122,.3);
    transition: all .18s ease; letter-spacing: .1px;
}
.btn-auth:hover { background: #2d0a5e; transform: translateY(-1.5px); box-shadow: 0 8px 28px rgba(59,15,122,.42); }
.btn-auth:active { transform: translateY(0); }

/* FOOTER */
.auth-footer { text-align: center; }
.auth-footer p { font-size: 13.5px; color: #888; margin-bottom: 8px; }
.auth-footer a { color: #7c3aed; font-weight: 700; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }
.back-link { font-size: 12px !important; color: #bbb !important; font-weight: 400 !important; display: block; margin-top: 4px; }

.alert-error {
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
    padding: 12px 16px; margin-bottom: 18px; color: #b91c1c; font-size: 13px;
}

@media (max-width: 860px) {
    .auth-split { flex-direction: column; }
    .auth-left { flex: none; min-height: 240px; }
    .auth-brand-title { font-size: 36px; }
    .auth-left-inner { padding: 40px 32px; }
    .auth-right-inner { padding: 32px 20px; max-width: 100%; }
}
</style>

@push('scripts')
<script>
    setTimeout(function() {
        const alert = document.querySelector('.alert-success');
        if (alert) { alert.style.transition = 'opacity 0.5s'; alert.style.opacity = '0'; setTimeout(() => alert.remove(), 500); }
    }, 3000);
</script>
@endpush
@endsection