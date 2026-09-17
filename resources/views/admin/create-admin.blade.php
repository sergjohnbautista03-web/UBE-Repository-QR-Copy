@extends('layouts.admin')
@section('title', 'Create Admin Account')
@section('page-title', 'Create Admin Account')

@section('content')

<div class="create-admin-wrap">

    <!-- Left: Info Panel -->
    <div class="admin-info-panel">
        <div class="info-icon"></div>
        <h2>Admin Privileges</h2>
        <p>Administrator accounts have full access to the system including:</p>
        <ul class="privilege-list">
            <li><span class="priv-icon">✓</span> Approve or reject research submissions</li>
            <li><span class="priv-icon">✓</span> Manage all user accounts</li>
            <li><span class="priv-icon">✓</span> View analytics and statistics</li>
            <li><span class="priv-icon">✓</span> Delete any research or user</li>
            <li><span class="priv-icon">✓</span> Create additional admin accounts</li>
        </ul>
        <div class="info-warning">
            <span>⚠</span>
            <p>Only create admin accounts for trusted personnel. Admin access cannot be limited or scoped.</p>
        </div>
    </div>

    <!-- Right: Form -->
    <div class="admin-form-panel">
        <div class="form-panel-header">
            <div class="form-panel-icon">👤</div>
            <div>
                <h3>New Administrator</h3>
                <p>Fill in the details below to create the account</p>
            </div>
        </div>

        @if($errors->any())
            <div class="ca-alert">
                @foreach($errors->all() as $error)
                    <p>⚠ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.store-admin') }}" class="ca-form">
            @csrf

            <div class="ca-form-group">
                <label for="name">
                    <span class="ca-label-icon"></span>
                    Full Name
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    placeholder="e.g. John Bautista" required autocomplete="off">
            </div>

            <div class="ca-form-group">
                <label for="email">
                    <span class="ca-label-icon"></span>
                    Email Address
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="e.g. admin@philcst.edu.ph" required autocomplete="off">
            </div>

            <div class="ca-password-wrap">
                <input type="password" id="password" name="password"
                    placeholder="Minimum 8 characters" required autocomplete="new-password">
                <button type="button" class="ca-toggle-pw" onclick="togglePassword('password', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
                </button>
            </div>

            <div class="ca-password-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm your password" required autocomplete="new-password">
                <button type="button" class="ca-toggle-pw" onclick="togglePassword('password_confirmation', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
                </button>
            </div>

            <div class="ca-form-actions">
                <a href="{{ route('admin.users') }}" class="ca-btn-cancel">Cancel</a>
                <button type="submit" class="ca-btn-submit">
                    <span></span> Create Admin Account
                </button>
            </div>
        </form>
    </div>

</div>

<style>
.create-admin-wrap {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 24px;
    max-width: 900px;
    align-items: start;
}

/* ── Info Panel ── */
.admin-info-panel {
    background: linear-gradient(160deg, var(--purple-deep) 0%, #4a1a72 100%);
    border-radius: 16px;
    padding: 32px 28px;
    color: white;
    position: sticky;
    top: 20px;
}
.info-icon {
    font-size: 40px;
    margin-bottom: 16px;
    display: block;
}
.admin-info-panel h2 {
    font-family: var(--font-head);
    font-size: 22px;
    color: white;
    margin-bottom: 10px;
}
.admin-info-panel > p {
    color: rgba(255,255,255,.7);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 20px;
}
.privilege-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 24px;
}
.privilege-list li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
    color: rgba(255,255,255,.85);
    line-height: 1.4;
}
.priv-icon {
    width: 20px; height: 20px;
    background: rgba(110,231,183,.2);
    color: #6ee7b7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}
.info-warning {
    display: flex;
    gap: 10px;
    background: rgba(251,191,36,.1);
    border: 1px solid rgba(251,191,36,.25);
    border-radius: 10px;
    padding: 14px;
}
.info-warning span { font-size: 18px; flex-shrink: 0; }
.info-warning p { font-size: 13px; color: rgba(255,255,255,.7); line-height: 1.5; margin: 0; }

/* ── Form Panel ── */
.admin-form-panel {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.form-panel-header {
    padding: 24px 28px;
    background: var(--purple-ghost);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 14px;
}
.form-panel-icon {
    width: 48px; height: 48px;
    background: white;
    border: 2px solid var(--border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: var(--shadow-sm);
}
.form-panel-header h3 {
    font-family: var(--font-head);
    font-size: 18px;
    color: var(--purple-deep);
    margin-bottom: 3px;
}
.form-panel-header p { font-size: 13px; color: var(--muted); }

.ca-form { padding: 28px; display: flex; flex-direction: column; gap: 20px; }

.ca-alert {
    margin: 16px 28px 0;
    background: #fee2e2;
    border: 1px solid #fecdd3;
    border-left: 3px solid #ef4444;
    border-radius: 8px;
    padding: 12px 16px;
}
.ca-alert p { font-size: 13px; color: #991b1b; }

.ca-form-group { display: flex; flex-direction: column; gap: 7px; }
.ca-form-group label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    color: var(--purple-deep);
}
.ca-label-icon { font-size: 14px; }

.ca-form-group input {
    width: 100%;
    padding: 11px 16px;
    border: 2px solid var(--border);
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 14px;
    color: var(--text);
    background: #fafafa;
    transition: all .2s;
}
.ca-form-group input:focus {
    outline: none;
    border-color: var(--purple-main);
    background: white;
    box-shadow: 0 0 0 3px rgba(107,47,160,.1);
}

.ca-password-wrap { position: relative; }
.ca-password-wrap input { padding-right: 48px; }
.ca-toggle-pw {
    position: absolute;
    right: 12px; top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: var(--muted);
    padding: 4px;
    border-radius: 4px;
    transition: background .2s;
    display: none;  /* ← hidden by default */
}
.ca-toggle-pw:hover { background: var(--purple-ghost); }

/* Password strength */
.password-strength { margin-top: 8px; }
.strength-track {
    height: 4px;
    background: var(--border);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 5px;
}
.strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 4px;
    transition: width .3s, background .3s;
}
.strength-label { font-size: 11px; color: var(--muted); }

.ca-form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    align-items: center;
    padding-top: 8px;
    border-top: 1px solid var(--border);
    margin-top: 4px;
}
.ca-btn-cancel {
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    background: var(--purple-ghost);
    border: 1px solid var(--border);
    transition: all .2s;
}
.ca-btn-cancel:hover { background: var(--purple-pale); color: var(--text); }

.ca-btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    background: linear-gradient(135deg, var(--purple-main), var(--purple-deep));
    color: white;
    border: none;
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(107,47,160,.3);
}
.ca-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(107,47,160,.4);
}
.ca-btn-submit:active { transform: translateY(0); }

@media (max-width: 768px) {
    .create-admin-wrap { grid-template-columns: 1fr; }
    .admin-info-panel { position: static; }
}
</style>

@push('scripts')
<script>
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    let strength = 0;
    if (val.length >= 8) strength++;
    if (/[A-Z]/.test(val)) strength++;
    if (/[0-9]/.test(val)) strength++;
    if (/[^A-Za-z0-9]/.test(val)) strength++;
    const configs = [
        { width: '0%',   color: '#e5e7eb', text: 'Enter a password' },
        { width: '25%',  color: '#ef4444', text: 'Weak' },
        { width: '50%',  color: '#f59e0b', text: 'Fair' },
        { width: '75%',  color: '#3b82f6', text: 'Good' },
        { width: '100%', color: '#10b981', text: 'Strong ✓' },
    ];
    const c = configs[val.length === 0 ? 0 : strength];
    fill.style.width = c.width;
    fill.style.background = c.color;
    label.textContent = c.text;
    label.style.color = c.color;
});
</script>
@endpush

@endsection