@extends('layouts.app')
@section('title', 'My Profile — Ube Repository')

@section('content')

<div class="prof-root">

    {{-- ══ HERO ══ --}}
    <div class="prof-hero">
        <div class="prof-hero-grid"></div>
        <div class="prof-glow prof-g1"></div>
        <div class="prof-glow prof-g2"></div>
        <div class="prof-hero-inner">
            <div class="prof-eyebrow"><span class="prof-eyebrow-dot"></span> Account</div>
            <h1>My Profile</h1>
            <p>Manage your personal information and account settings</p>
        </div>
    </div>

    {{-- ══ BODY ══ --}}
    <div class="prof-body">

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:20px; border-radius:12px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Profile Photo --}}
        <div class="prof-card">
            <div class="prof-card-label">Profile Photo</div>
            <div class="prof-photo-row">
                <div class="prof-avatar-wrap">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile Photo" class="prof-avatar-img">
                    @else
                        <div class="prof-avatar-initials">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <div class="prof-avatar-ring"></div>
                </div>

                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="prof-photo-form">
                    @csrf
                    @method('PATCH')
                    <div class="prof-photo-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                    <label class="prof-field-label">Upload New Photo</label>
                    <input type="file" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/gif" class="prof-file-input">
                    <small class="prof-hint">JPG, PNG, GIF — max 2MB</small>
                    <div class="prof-photo-actions">
                        <button type="submit" class="prof-btn prof-btn--primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17,8 12,3 7,8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload Photo
                        </button>
                        @if(auth()->user()->profile_photo)
                            <a href="{{ route('profile.photo.remove') }}" onclick="return confirm('Remove profile photo?')" class="prof-btn prof-btn--ghost">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3,6 5,6 21,6"/><path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2v2"/></svg>
                                Remove
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Personal Information --}}
        <div class="prof-card">
            <div class="prof-card-label">Personal Information</div>
            <h2 class="prof-card-title">Account Details</h2>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="prof-form-grid">
                    <div class="prof-field">
                        <label class="prof-field-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="prof-input">
                    </div>
                    <div class="prof-field">
                        <label class="prof-field-label">Email Address</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled class="prof-input prof-input--disabled">
                        <small class="prof-hint">Email cannot be changed.</small>
                    </div>
                    <div class="prof-field">
                        <label class="prof-field-label">Department</label>
                        <input type="text" name="department" value="{{ old('department', auth()->user()->department) }}" class="prof-input">
                    </div>
                    @if(auth()->user()->student_id)
                    <div class="prof-field">
                        <label class="prof-field-label">Student / Employee ID</label>
                        <input type="text" value="{{ auth()->user()->student_id }}" disabled class="prof-input prof-input--disabled">
                    </div>
                    @endif
                </div>

                <div class="prof-form-actions">
                    <button type="submit" class="prof-btn prof-btn--primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17,21 17,13 7,13 7,21"/><polyline points="7,3 7,8 15,8"/></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="prof-card">
            <div class="prof-card-label">Security</div>
            <h2 class="prof-card-title">Change Password</h2>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PATCH')

                <div class="prof-form-grid">
                    <div class="prof-field prof-field--full">
                        <label class="prof-field-label">Current Password</label>
                        <div class="password-wrap">
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password" required class="prof-input">
                            <button type="button" class="toggle-pw" onclick="togglePassword('current_password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                    <div class="prof-field">
                        <label class="prof-field-label">New Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" placeholder="Minimum 8 characters" required class="prof-input">
                            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                    <div class="prof-field">
                        <label class="prof-field-label">Confirm New Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required class="prof-input">
                            <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="prof-form-actions">
                    <button type="submit" class="prof-btn prof-btn--primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<style>
.prof-root { font-family: var(--font-body); background: #eeeaf6; min-height: 100vh; }

/* HERO */
.prof-hero {
    position: relative; background: #3b0f7a;
    overflow: hidden; padding: 44px 44px 48px; text-align: center;
}
.prof-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px), linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);
    background-size: 38px 38px;
}
.prof-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.prof-g1 { width: 500px; height: 500px; background: #7c3aed; opacity: .22; top: -200px; right: -100px; }
.prof-g2 { width: 280px; height: 280px; background: #a855f7; opacity: .15; bottom: -80px; left: 18%; }
.prof-hero-inner { position: relative; z-index: 2; }
.prof-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600;
    color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1.2px;
    margin-bottom: 12px;
}
.prof-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #c084fc; }
.prof-hero h1 {
    font-family: var(--font-body); font-size: 38px; font-weight: 700;
    color: white; margin-bottom: 8px; letter-spacing: -.5px;
}
.prof-hero p { font-size: 14px; color: rgba(255,255,255,.5); }

/* BODY */
.prof-body { max-width: 720px; margin: 0 auto; padding: 36px 40px 64px; display: flex; flex-direction: column; gap: 20px; }

/* CARD */
.prof-card {
    background: white; border-radius: 20px;
    border: 1px solid rgba(109,40,217,.08);
    padding: 32px 36px;
    box-shadow: 0 1px 3px rgba(46,16,101,.04), 0 4px 20px rgba(46,16,101,.08);
    position: relative; overflow: hidden;
}
.prof-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #7c3aed, #a855f7, #c084fc);
}
.prof-card-label {
    display: inline-flex; align-items: center;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    color: var(--purple-main); background: var(--purple-ghost);
    border: 1px solid var(--purple-pale);
    padding: 3px 11px; border-radius: 50px; margin-bottom: 14px;
}
.prof-card-title {
    font-family: var(--font-head); font-size: 18px;
    color: var(--purple-deep); margin-bottom: 22px;
}

/* PHOTO SECTION */
.prof-photo-row { display: flex; align-items: flex-start; gap: 28px; flex-wrap: wrap; }
.prof-avatar-wrap {
    position: relative; width: 96px; height: 96px; flex-shrink: 0;
}
.prof-avatar-img {
    width: 96px; height: 96px; border-radius: 50%;
    object-fit: cover; position: relative; z-index: 1;
    border: 3px solid white;
    box-shadow: 0 4px 16px rgba(107,47,160,.2);
}
.prof-avatar-initials {
    width: 96px; height: 96px; border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    display: flex; align-items: center; justify-content: center;
    font-size: 36px; font-weight: 800; color: white;
    border: 3px solid white;
    box-shadow: 0 4px 16px rgba(107,47,160,.25);
    position: relative; z-index: 1;
}
.prof-avatar-ring {
    position: absolute; inset: -4px; border-radius: 50%;
    border: 2px dashed #c4b5fd; animation: prof-spin 12s linear infinite;
}
@keyframes prof-spin { to { transform: rotate(360deg); } }

.prof-photo-form { flex: 1; }
.prof-photo-info { margin-bottom: 14px; }
.prof-photo-info strong { display: block; font-size: 16px; font-weight: 700; color: var(--purple-deep); margin-bottom: 3px; }
.prof-photo-info span { font-size: 12.5px; color: var(--muted); background: var(--purple-ghost); border: 1px solid var(--purple-pale); padding: 2px 10px; border-radius: 50px; }

.prof-file-input { display: block; font-size: 13px; color: var(--text); margin-bottom: 6px; width: 100%; }
.prof-hint { font-size: 12px; color: var(--muted); display: block; margin-bottom: 14px; }
.prof-photo-actions { display: flex; gap: 10px; flex-wrap: wrap; }

/* FORM */
.prof-field-label {
    display: block; font-size: 11.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .6px;
    color: var(--purple-deep); margin-bottom: 7px;
}
.prof-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 20px; }
.prof-field { display: flex; flex-direction: column; }
.prof-field--full { grid-column: span 2; }
.prof-input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px; background: var(--purple-ghost);
    font-family: var(--font-body); font-size: 14px; color: var(--text);
    transition: border-color .15s, box-shadow .15s;
}
.prof-input:focus {
    border-color: var(--purple-main); background: white;
    box-shadow: 0 0 0 3px rgba(107,47,160,.1); outline: none;
}
.prof-input--disabled { opacity: .55; cursor: not-allowed; background: #f4f0fb; }
.prof-form-actions { display: flex; }

/* BUTTONS */
.prof-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 50px;
    font-size: 13.5px; font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer; border: none; transition: all .2s;
    letter-spacing: .2px; text-decoration: none;
}
.prof-btn--primary {
    background: #3b0f7a; color: white;
    box-shadow: 0 4px 16px rgba(59,15,122,.3);
}
.prof-btn--primary:hover {
    background: #2d0a5e; transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(59,15,122,.4);
}
.prof-btn--ghost {
    background: var(--purple-ghost); color: var(--purple-main);
    border: 1px solid var(--purple-light);
}
.prof-btn--ghost:hover { background: var(--purple-pale); }

/* password-wrap override */
.prof-card .password-wrap input { border-radius: 10px; background: var(--purple-ghost); border: 1.5px solid var(--border); }
.prof-card .password-wrap input:focus { border-color: var(--purple-main); background: white; box-shadow: 0 0 0 3px rgba(107,47,160,.1); }

@media(max-width: 768px) {
    .prof-hero  { padding: 32px 20px 36px; }
    .prof-hero h1 { font-size: 28px; }
    .prof-body  { padding: 24px 16px 48px; gap: 16px; }
    .prof-card  { padding: 22px 20px; }
    .prof-form-grid { grid-template-columns: 1fr; }
    .prof-field--full { grid-column: span 1; }
    .prof-photo-row { flex-direction: column; align-items: center; text-align: center; }
}
</style>

@endsection