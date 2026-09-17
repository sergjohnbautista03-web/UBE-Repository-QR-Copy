@extends('layouts.admin')
@section('title', 'Create Admin')
@section('page-title', 'Create Admin')

@section('content')

<div style="max-width:600px; margin:0 auto;">
    <div class="ca-card">

        {{-- HEADER --}}
        <div class="ca-header">
            <div class="ca-header-left">
                <div class="ca-header-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h3 class="ca-header-title">Create Admin Account</h3>
                    <p class="ca-header-sub">Add a new administrator to the system</p>
                </div>
            </div>
            <a href="{{ route('super-admin.admins') }}" class="ca-back-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back
            </a>
        </div>

        @if($errors->any())
            <div class="ca-alert-error">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.store-admin') }}" class="ca-form" novalidate>
            @csrf

            {{-- SECTION 1 --}}
            <div class="ca-section-label"><span class="ca-section-num">1</span> Personal Information</div>

            <div class="ca-form-row">
                <div class="ca-form-group">
                    <label>First Name <span class="ca-req">*</span></label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}"
                        placeholder="Juan" oninput="validateName(this)"
                        style="{{ $errors->has('firstname') ? 'border-color:#ef4444;' : '' }}">
                    @error('firstname')<small class="ca-field-err">{{ $message }}</small>@enderror
                </div>
                <div class="ca-form-group">
                    <label>Last Name <span class="ca-req">*</span></label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}"
                        placeholder="dela Cruz" oninput="validateName(this)"
                        style="{{ $errors->has('lastname') ? 'border-color:#ef4444;' : '' }}">
                    @error('lastname')<small class="ca-field-err">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="ca-form-group">
                <label>Middle Name <span class="ca-opt">(Optional)</span></label>
                <input type="text" name="middlename" value="{{ old('middlename') }}"
                    placeholder="Leave blank if not applicable" oninput="validateName(this)">
                @error('middlename')<small class="ca-field-err">{{ $message }}</small>@enderror
            </div>

            {{-- SECTION 2 --}}
            <div class="ca-section-label"><span class="ca-section-num">2</span> Account Credentials</div>

            <div class="ca-form-group">
                <label>Email Address <span class="ca-req">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    style="{{ $errors->has('email') ? 'border-color:#ef4444;' : '' }}">
                @error('email')<small class="ca-field-err">{{ $message }}</small>@enderror
            </div>

            <div class="ca-form-row">
                <div class="ca-form-group">
                    <label>Password <span class="ca-req">*</span></label>
                    <div class="ca-pw-wrap">
                        <input type="password" id="password" name="password"
                            placeholder="Min. 8 characters"
                            oninput="validatePassword(this)"
                            style="{{ $errors->has('password') ? 'border-color:#ef4444;' : '' }}">
                        <button type="button" class="ca-toggle-pw" onclick="togglePassword('password', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')<small class="ca-field-err">{{ $message }}</small>@enderror
                </div>
                <div class="ca-form-group">
                    <label>Confirm Password <span class="ca-req">*</span></label>
                    <div class="ca-pw-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Repeat password"
                            oninput="validateConfirm(this)"
                            style="{{ $errors->has('password_confirmation') ? 'border-color:#ef4444;' : '' }}">
                        <button type="button" class="ca-toggle-pw" onclick="togglePassword('password_confirmation', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password_confirmation')<small class="ca-field-err">{{ $message }}</small>@enderror
                </div>
            </div>

            {{-- INFO NOTICE --}}
            <div class="ca-notice">
                <div class="ca-notice-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <p>This will create a new <strong>Admin</strong> account. Only you (Super Admin) can manage admin accounts.</p>
            </div>

            {{-- ACTIONS --}}
            <div class="ca-actions">
                <a href="{{ route('super-admin.admins') }}" class="ca-btn ca-btn-ghost">Cancel</a>
                <button type="submit" class="ca-btn ca-btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Create Admin
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* CARD */
.ca-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(107,47,160,.1);
    box-shadow: 0 4px 32px rgba(59,15,122,.06), 0 1px 4px rgba(0,0,0,.03);
    overflow: hidden;
    margin-bottom: 40px;
}

/* HEADER */
.ca-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px 32px;
    background: linear-gradient(160deg, #fdfbff 0%, #f8f4fe 100%);
    border-bottom: 1px solid #f0eaf9;
}
.ca-header-left { display: flex; align-items: center; gap: 14px; }
.ca-header-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    color: #6b2fa0; flex-shrink: 0;
}
.ca-header-title { font-size: 17px; font-weight: 800; color: #1a0638; margin: 0 0 2px; letter-spacing: -.3px; }
.ca-header-sub { font-size: 12.5px; color: #a090bc; margin: 0; }
.ca-back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; background: #fff;
    border: 1.5px solid #e2d5f4; border-radius: 50px;
    font-size: 13px; font-weight: 600; color: #6b2fa0; text-decoration: none;
    transition: all .15s;
}
.ca-back-btn:hover { background: #f4f0fc; border-color: #c4a8e8; }

/* ALERT */
.ca-alert-error {
    display: flex; align-items: flex-start; gap: 10px;
    margin: 20px 32px 0; background: #fef2f2;
    border: 1px solid #fecaca; border-radius: 12px;
    padding: 14px 16px; color: #b91c1c; font-size: 13px;
}
.ca-alert-error p { margin: 0 0 3px; }
.ca-alert-error p:last-child { margin: 0; }

/* FORM */
.ca-form { padding: 28px 32px; }

/* SECTION LABELS */
.ca-section-label {
    display: flex; align-items: center; gap: 10px;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1px; color: #6b2fa0;
    margin: 24px 0 16px; padding-bottom: 10px; border-bottom: 1px solid #f0eaf9;
}
.ca-section-label:first-child { margin-top: 0; }
.ca-section-num {
    width: 22px; height: 22px; border-radius: 50%;
    background: #f0eaf9; border: 1.5px solid #e0d4f5;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #6b2fa0; flex-shrink: 0;
}

/* ROWS & GROUPS */
.ca-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.ca-form-group { display: flex; flex-direction: column; margin-bottom: 16px; }
.ca-form-group label {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .7px; color: #5b3d8a; margin-bottom: 7px;
}
.ca-req { color: #e53e3e; }
.ca-opt { font-weight: 400; text-transform: none; letter-spacing: 0; color: #b0a0cc; font-size: 11px; }
.ca-field-err { color: #ef4444; font-size: 12px; margin-top: 4px; display: block; }

/* INPUTS */
.ca-form-group input[type="text"],
.ca-form-group input[type="email"],
.ca-form-group input[type="password"] {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #e8dff5; border-radius: 10px;
    background: #faf8ff; font-size: 14px; color: #1a0638;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box; font-family: inherit;
}
.ca-form-group input:focus {
    outline: none; border-color: #7c3aed; background: #fff;
    box-shadow: 0 0 0 3px rgba(124,58,237,.1);
}

/* PASSWORD WRAP */
.ca-pw-wrap { position: relative; }
.ca-pw-wrap input { padding-right: 40px; }
.ca-toggle-pw {
    position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #c0aee0; padding: 0; line-height: 1;
}
.ca-toggle-pw:hover { color: #7c3aed; }

/* NOTICE */
.ca-notice {
    display: flex; align-items: flex-start; gap: 12px;
    background: #eff6ff; border: 1px solid #bfdbfe;
    border-radius: 12px; padding: 14px 18px;
    margin-bottom: 24px; font-size: 13.5px; color: #1e40af;
}
.ca-notice-icon {
    width: 28px; height: 28px; border-radius: 50%;
    background: #dbeafe; border: 1.5px solid #93c5fd;
    display: flex; align-items: center; justify-content: center;
    color: #2563eb; flex-shrink: 0;
}
.ca-notice p { margin: 0; line-height: 1.6; }
.ca-notice strong { font-weight: 700; }

/* ACTIONS */
.ca-actions {
    display: flex; align-items: center; justify-content: flex-end; gap: 10px;
    padding-top: 20px; border-top: 1px solid #f0eaf9;
}
.ca-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 22px; border-radius: 50px;
    font-size: 13.5px; font-weight: 700;
    cursor: pointer; text-decoration: none; border: none;
    transition: all .16s ease;
}
.ca-btn-primary {
    background: #3b0f7a; color: #fff;
    box-shadow: 0 4px 16px rgba(59,15,122,.28);
}
.ca-btn-primary:hover { background: #2d0a5e; transform: translateY(-1px); box-shadow: 0 7px 22px rgba(59,15,122,.38); }
.ca-btn-ghost {
    background: #fff; color: #8b7aaa; border: 1.5px solid #e8dff5;
}
.ca-btn-ghost:hover { background: #f4f0fc; color: #3b0f7a; }

@media (max-width: 560px) {
    .ca-header, .ca-form { padding-left: 20px; padding-right: 20px; }
    .ca-form-row { grid-template-columns: 1fr; }
}
</style>

@push('scripts')
<script>
function setFieldError(input, message) {
    input.style.borderColor = '#ef4444';
    const group = input.closest('.ca-form-group') || input.closest('.form-group');
    let msg = group.querySelector('.field-error');
    if (!msg) {
        msg = document.createElement('small');
        msg.className = 'field-error';
        msg.style.cssText = 'color:#ef4444; font-size:12px; display:block; margin-top:4px;';
        group.appendChild(msg);
    }
    msg.textContent = message;
}
function clearFieldError(input) {
    input.style.borderColor = '';
    const group = input.closest('.ca-form-group') || input.closest('.form-group');
    const msg = group ? group.querySelector('.field-error') : null;
    if (msg) msg.remove();
}
function validateName(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    if (/[0-9]/.test(input.value) || /[^a-zA-Z\s]/.test(input.value)) {
        setFieldError(input, 'Letters only — no numbers or special characters.');
    } else { clearFieldError(input); input.style.borderColor = '#10b981'; }
}
function validatePassword(input) {
    const v = input.value;
    if (!v.length) { clearFieldError(input); return; }
    const msgs = [];
    if (v.length < 8) msgs.push('at least 8 characters');
    if (!/[0-9]/.test(v)) msgs.push('one number (0-9)');
    if (!/[@#$%!]/.test(v)) msgs.push('one special character (@, #, $, %, !)');
    if (msgs.length) { setFieldError(input, 'Password must include: ' + msgs.join(', ') + '.'); }
    else { clearFieldError(input); input.style.borderColor = '#10b981'; }
    validateConfirm(document.getElementById('password_confirmation'));
}
function validateConfirm(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    if (input.value !== document.getElementById('password').value) {
        setFieldError(input, 'Passwords do not match.');
    } else { clearFieldError(input); input.style.borderColor = '#10b981'; }
}
</script>
@endpush

@endsection