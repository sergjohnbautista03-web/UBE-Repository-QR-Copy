@extends('layouts.app')
@section('title', 'Register — Ube Repository')

@section('content')
<div class="auth-split">

    {{-- LEFT PANEL --}}
    <div class="auth-left">
        <div class="auth-left-inner">
            <div class="auth-logo-wrap">
                <span style="font-size:22px; color:white; line-height:1;">✦</span>
            </div>
            <div class="brand-eyebrow">Ube Research Repository</div>
            <h1 class="auth-brand-title">Create Account</h1>
            <p class="auth-brand-sub">Join the Ube Repository community and start sharing your research with the world.</p>
            <div class="brand-feature-list">
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Publish your research instantly</span></div>
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Collaborate across departments</span></div>
                <div class="brand-feature"><span class="brand-feature-dot"></span><span>Track views and citations</span></div>
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
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h2 class="form-heading">New Account</h2>
                        <p class="form-subheading">Fill in your details to get started</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="auth-form" autocomplete="off">
                    @csrf

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}"
                                placeholder="Juan" required autofocus autocomplete="off"
                                oninput="validateNameField(this)"
                                style="{{ $errors->has('firstname') ? 'border-color:#ef4444;' : '' }}">
                            @error('firstname')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}"
                                placeholder="dela Cruz" required autocomplete="off"
                                oninput="validateNameField(this)"
                                style="{{ $errors->has('lastname') ? 'border-color:#ef4444;' : '' }}">
                            @error('lastname')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="middlename">Middle Name <span class="opt">(Optional)</span></label>
                            <input type="text" id="middlename" name="middlename" value="{{ old('middlename') }}"
                                placeholder="Leave blank if N/A" autocomplete="off"
                                oninput="validateNameField(this)">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="your@email.com" required autocomplete="off"
                                oninput="validateEmail(this)"
                                style="{{ $errors->has('email') ? 'border-color:#ef4444;' : '' }}">
                            @error('email')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="password-wrap">
                                <input type="password" id="password" name="password"
                                    placeholder="Min. 8 characters" required autocomplete="new-password"
                                    oninput="validatePassword(this)"
                                    style="{{ $errors->has('password') ? 'border-color:#ef4444;' : '' }}">
                                <button type="button" class="toggle-pw" onclick="togglePassword('password', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            @error('password')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="password-wrap">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Re-enter password" required autocomplete="new-password"
                                    oninput="validateConfirmPassword(this)"
                                    style="{{ $errors->has('password_confirmation') ? 'border-color:#ef4444;' : '' }}">
                                <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation', this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            @error('password_confirmation')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="form-divider"><span>Account Details</span></div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="role">Account Type</label>
                            <select id="role" name="role" required onchange="toggleRoleFields(this)"
                                style="{{ $errors->has('role') ? 'border-color:#ef4444;' : '' }}">
                                <option value="">Select role</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Student in PhilCST</option>
                                <option value="researcher" {{ old('role') == 'researcher' ? 'selected' : '' }}>Researcher (Can submit)</option>
                            </select>
                            @error('role')<small class="field-err">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select id="department" name="department"
                                {{ old('role') == 'user' || !old('role') ? 'disabled' : '' }}
                                style="{{ old('role') == 'user' || !old('role') ? 'opacity:.5;cursor:not-allowed;' : '' }}">
                                <option value="">Select department</option>
                                <option value="College of Accountancy and Business Education" {{ old('department') == 'College of Accountancy and Business Education' ? 'selected' : '' }}>College of Accountancy and Business Education</option>
                                <option value="College of Computer Studies" {{ old('department') == 'College of Computer Studies' ? 'selected' : '' }}>College of Computer Studies</option>
                                <option value="College of Criminal Justice Education" {{ old('department') == 'College of Criminal Justice Education' ? 'selected' : '' }}>College of Criminal Justice Education</option>
                                <option value="College of Education" {{ old('department') == 'College of Education' ? 'selected' : '' }}>College of Education</option>
                                <option value="College of Engineering" {{ old('department') == 'College of Engineering' ? 'selected' : '' }}>College of Engineering and Architecture</option>
                                <option value="College of Maritime Studies" {{ old('department') == 'College of Maritime Studies' ? 'selected' : '' }}>College of Maritime Studies</option>
                            </select>
                        </div>
                    </div>

                    {{-- PhilCST Student fields (role = user) --}}
                    <div id="philcstFields" style="display:{{ old('role') == 'user' ? 'block' : 'none' }}">
                        <div class="form-group">
                            <label for="philcst_student_id">
                                Student ID <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" id="philcst_student_id" name="philcst_student_id"
                                value="{{ old('philcst_student_id') }}"
                                placeholder="e.g. 2024-12345" autocomplete="off"
                                oninput="validateStudentId(this)"
                                style="{{ $errors->has('philcst_student_id') ? 'border-color:#ef4444;' : '' }}">
                            @error('philcst_student_id')<small class="field-err">{{ $message }}</small>@enderror
                        </div>

                        {{-- PhilCST student info box --}}
                        <div class="researcher-info-box" style="display:flex; margin-bottom:14px;">
                            <div class="info-row info-row-highlight">
                                <span class="info-icon"></span>
                                <span>As a <strong>PhilCST Student</strong>, you have full access to all research documents.</span>
                            </div>
                            <div class="info-row">
                                <span class="info-icon"></span>
                                <span>You can view full research papers from all departments.</span>
                            </div>
                            <div class="info-row">
                                <span class="info-icon"></span>
                                <span>Your Student ID is used to verify your enrollment at PhilCST. Outside visitors cannot register.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Researcher-only fields --}}
                    <div id="researcherFields" style="display:{{ old('role') == 'researcher' ? 'block' : 'none' }}">

                        {{-- Student or Faculty toggle --}}
                        <div class="member-type-row" id="memberTypeRow">
                            <label class="member-type-label">I am a:</label>
                            <div class="member-type-options">
                                <label class="member-type-card {{ old('member_type') == 'student' ? 'selected' : '' }}" id="studentCard" onclick="selectMemberType('student')">
                                    <input type="radio" name="member_type" value="student" id="memberStudent"
                                        {{ old('member_type') == 'student' ? 'checked' : '' }}>
                                    <span class="member-type-icon"></span>
                                    <span class="member-type-title">Student</span>
                                    <span class="member-type-sub">Has graduation year</span>
                                </label>
                                <label class="member-type-card {{ old('member_type') == 'faculty' ? 'selected' : '' }}" id="facultyCard" onclick="selectMemberType('faculty')">
                                    <input type="radio" name="member_type" value="faculty" id="memberFaculty"
                                        {{ old('member_type') == 'faculty' ? 'checked' : '' }}>
                                    <span class="member-type-icon"></span>
                                    <span class="member-type-title">Faculty</span>
                                    <span class="member-type-sub">Permanent access</span>
                                </label>
                            </div>
                            @error('member_type')<small class="field-err">{{ $message }}</small>@enderror
                        </div>

                        {{-- Student fields --}}
                        <div id="studentFields" style="display:{{ old('member_type') == 'student' ? 'block' : 'none' }}">
                            <div class="form-group">
                                <label for="student_id">Student ID <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="student_id" name="student_id"
                                    value="{{ old('student_id') }}" placeholder="e.g. 2024-12345" autocomplete="off"
                                    style="{{ $errors->has('student_id') ? 'border-color:#ef4444;' : '' }}">
                                @error('student_id')<small class="field-err">{{ $message }}</small>@enderror
                            </div>
                            <div class="form-group">
                                <label for="year_level">Current Year Level <span style="color:#ef4444;">*</span></label>
                                <select id="year_level" name="year_level" onchange="updateStudentInfo()">
                                    <option value="">Select Year Level</option>
                                    <option value="1" {{ old('year_level') == 1 ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_level') == 2 ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_level') == 3 ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_level') == 4 ? 'selected' : '' }}>4th Year</option>
                                </select>
                                <input type="hidden" name="course_duration" value="4">
                                @error('year_level')<small class="field-err">{{ $message }}</small>@enderror
                            </div>

                            {{-- Student info box --}}
                            <div id="studentInfoBox" style="display:{{ old('year_level') ? 'flex' : 'none' }};" class="researcher-info-box">
                                <div class="info-row info-row-highlight">
                                    <span class="info-icon"></span>
                                    <span>You have <strong id="yearsRemainingText">—</strong> as an active researcher.</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"></span>
                                    <span>After <strong id="gradYearText">—</strong>, you can still log in and view research, but you will no longer be able to submit new papers.</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"></span>
                                    <span>Your submitted research will remain published under your name.</span>
                                </div>
                            </div>
                        </div>

                        {{-- Faculty fields --}}
                        <div id="facultyFields" style="display:{{ old('member_type') == 'faculty' ? 'block' : 'none' }}">
                            <div class="form-group">
                                <label for="employee_id">Employee ID <span style="color:#ef4444;">*</span></label>
                                <input type="text" id="employee_id" name="employee_id"
                                    value="{{ old('employee_id') }}" placeholder="e.g. EMP-2024-001" autocomplete="off"
                                    style="{{ $errors->has('employee_id') ? 'border-color:#ef4444;' : '' }}">
                                @error('employee_id')<small class="field-err">{{ $message }}</small>@enderror
                            </div>

                            {{-- Faculty info box --}}
                            <div class="researcher-info-box" style="display:flex;">
                                <div class="info-row info-row-highlight">
                                    <span class="info-icon"></span>
                                    <span>As a <strong>Faculty Member</strong>, you have permanent researcher access.</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"></span>
                                    <span>You can submit research anytime — your account does not expire.</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-icon"></span>
                                    <span>Your submitted research will remain published under your name.</span>
                                </div>
                            </div>
                        </div>

                    </div>{{-- end #researcherFields --}}

                    <button type="submit" class="btn-auth">
                        <span>Create Account</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
                <a href="{{ route('home') }}" class="back-link">← Back to Repository</a>
            </div>
        </div>
    </div>
</div>

<style>
.auth-split{display:flex;min-height:calc(100vh - 64px);}
.auth-left{flex:0 0 38%;background:#1c044a;background-image:radial-gradient(ellipse at 30% 25%,rgba(120,40,230,.65) 0%,transparent 50%),radial-gradient(ellipse at 80% 80%,rgba(65,10,155,.5) 0%,transparent 50%);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;}
.auth-left-inner{padding:64px 48px;position:relative;z-index:2;max-width:340px;}
.auth-logo-wrap{width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-bottom:30px;}
.brand-eyebrow{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:rgba(190,150,255,.7);margin-bottom:12px;}
.auth-brand-title{font-family:var(--font-body);font-size:48px;font-weight:800;color:#fff;line-height:1.05;letter-spacing:-2px;margin-bottom:18px;}
.auth-brand-sub{font-size:13.5px;color:rgba(255,255,255,.5);line-height:1.75;margin-bottom:32px;}
.brand-feature-list{display:flex;flex-direction:column;gap:11px;}
.brand-feature{display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,.55);}
.brand-feature-dot{width:6px;height:6px;border-radius:50%;background:rgba(175,120,255,.85);flex-shrink:0;}
.deco-ring{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.07);pointer-events:none;}
.deco-ring-1{width:330px;height:330px;top:-100px;right:-100px;}
.deco-ring-2{width:210px;height:210px;bottom:50px;right:-60px;border-color:rgba(255,255,255,.05);}
.deco-ring-3{width:120px;height:120px;bottom:-30px;left:60px;background:rgba(255,255,255,.03);}
.deco-glow{position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(120,50,255,.18) 0%,transparent 70%);bottom:-80px;right:-80px;pointer-events:none;}
.auth-right{flex:1;background:#f2eef9;display:flex;align-items:center;justify-content:center;overflow-y:auto;}
.auth-right-inner{width:100%;max-width:680px;padding:44px 40px;}
.form-card{background:#fff;border-radius:20px;border:1px solid rgba(107,47,160,.1);padding:30px 30px 26px;box-shadow:0 6px 36px rgba(59,15,122,.07),0 1px 6px rgba(0,0,0,.04);margin-bottom:20px;}
.form-card-header{display:flex;align-items:center;gap:14px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f0eaf9;}
.form-card-icon{width:42px;height:42px;border-radius:11px;background:#f0eaf9;border:1px solid #e2d5f4;display:flex;align-items:center;justify-content:center;color:#6b2fa0;flex-shrink:0;}
.form-heading{font-family:var(--font-body);font-size:20px;font-weight:800;color:#1a0638;letter-spacing:-.4px;margin:0 0 2px;}
.form-subheading{font-size:12.5px;color:#a090bc;margin:0;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.auth-form .form-group{margin-bottom:14px;}
.auth-form .form-group label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#5b3d8a;margin-bottom:6px;}
.opt{font-weight:400;text-transform:none;letter-spacing:0;color:#b0a0cc;font-size:10.5px;}
.auth-form input[type="text"],.auth-form input[type="email"],.auth-form input[type="password"],.auth-form select{width:100%;padding:10px 13px;border:1.5px solid #e8dff5;border-radius:10px;background:#faf8ff;font-size:13.5px;color:#1a0638;transition:border-color .15s,box-shadow .15s;box-sizing:border-box;}
.auth-form input:focus,.auth-form select:focus{outline:none;border-color:#7c3aed;background:#fff;box-shadow:0 0 0 3px rgba(124,58,237,.1);}
.field-err{color:#ef4444;font-size:12px;display:block;margin-top:3px;}
.password-wrap{position:relative;}
.password-wrap input{padding-right:40px;}
.toggle-pw{position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#c0aee0;padding:0;line-height:1;}
.toggle-pw:hover{color:#7c3aed;}
.form-divider{display:flex;align-items:center;gap:10px;margin:16px 0 14px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#b0a0cc;}
.form-divider::before,.form-divider::after{content:'';flex:1;height:1px;background:#f0eaf9;}
#researcherFields{background:#faf8ff;border:1px solid #e8dff5;border-radius:12px;padding:14px 16px;margin-bottom:14px;}
#philcstFields{background:#faf8ff;border:1px solid #e8dff5;border-radius:12px;padding:14px 16px;margin-bottom:14px;}

/* ── Member Type Selector ── */
.member-type-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#5b3d8a;margin-bottom:8px;}
.member-type-options{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;}
.member-type-card{display:flex;flex-direction:column;align-items:center;gap:4px;padding:14px 12px;border:1.5px solid #e8dff5;border-radius:12px;cursor:pointer;background:#fff;transition:all .15s;text-align:center;}
.member-type-card:hover{border-color:#c4a8e8;background:#f4f0fc;}
.member-type-card.selected{border-color:#7c3aed;background:#f0eaf9;box-shadow:0 0 0 3px rgba(124,58,237,.1);}
.member-type-card input[type="radio"]{display:none;}
.member-type-icon{font-size:22px;line-height:1;}
.member-type-title{font-size:13px;font-weight:700;color:#1a0638;}
.member-type-sub{font-size:11px;color:#a090bc;}

/* ── Info Box ── */
.researcher-info-box{background:#f0eaf9;border:1.5px solid #c4a8e8;border-radius:12px;padding:14px 16px;margin-top:12px;display:flex;flex-direction:column;gap:10px;}
.info-row{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:#3b0f7a;line-height:1.55;}
.info-row-highlight{background:#fff;border:1px solid #e2d5f4;border-radius:8px;padding:8px 12px;font-weight:500;}
.info-icon{font-size:15px;flex-shrink:0;margin-top:1px;}

.btn-auth{width:100%;padding:13px 20px;background:#3b0f7a;color:#fff;font-size:14.5px;font-weight:700;border:none;border-radius:50px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 20px rgba(59,15,122,.3);transition:all .18s ease;margin-top:4px;}
.btn-auth:hover{background:#2d0a5e;transform:translateY(-1.5px);box-shadow:0 8px 28px rgba(59,15,122,.42);}
.btn-auth:active{transform:translateY(0);}
.auth-footer{text-align:center;}
.auth-footer p{font-size:13.5px;color:#888;margin-bottom:8px;}
.auth-footer a{color:#7c3aed;font-weight:700;text-decoration:none;}
.auth-footer a:hover{text-decoration:underline;}
.back-link{font-size:12px!important;color:#bbb!important;font-weight:400!important;display:block;margin-top:4px;}
.alert-error{background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#b91c1c;font-size:13px;}
@media(max-width:960px){.auth-split{flex-direction:column;}.auth-left{flex:none;min-height:220px;}.auth-brand-title{font-size:34px;}.auth-left-inner{padding:36px 28px;}.auth-right-inner{padding:28px 16px;max-width:100%;}.form-row{grid-template-columns:1fr;}.member-type-options{grid-template-columns:1fr 1fr;}}
</style>

<script>
function setFieldError(input, message) {
    input.style.borderColor = '#ef4444';
    const container = input.closest('.form-group') || input.parentNode.parentNode;
    let msg = container.querySelector('.field-error');
    if (!msg) { msg = document.createElement('small'); msg.className = 'field-error'; msg.style.cssText = 'color:#ef4444;font-size:12px;display:block;margin-top:4px;'; container.appendChild(msg); }
    msg.textContent = message;
}
function clearFieldError(input) {
    input.style.borderColor = '';
    const container = input.closest('.form-group') || input.parentNode.parentNode;
    const msg = container.querySelector('.field-error');
    if (msg) msg.remove();
}
function validateNameField(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    if (/[0-9]/.test(input.value) || /[^a-zA-Z\s\-\.]/.test(input.value)) { setFieldError(input, 'Letters only — no numbers or special characters.'); }
    else { clearFieldError(input); input.style.borderColor = '#10b981'; }
}
function validateEmail(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    const validDomains = ['gmail.com','yahoo.com','outlook.com','hotmail.com','philcst.edu.ph','icloud.com'];
    const hasAt = input.value.includes('@'), domain = hasAt ? input.value.split('@')[1]?.toLowerCase() : '';
    if (hasAt && domain && !validDomains.includes(domain)) { setFieldError(input, 'Use a valid email domain (gmail.com, yahoo.com, etc.)'); }
    else if (hasAt && domain && validDomains.includes(domain)) { clearFieldError(input); input.style.borderColor = '#10b981'; }
    else { clearFieldError(input); }
}
function validatePassword(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    const v = input.value, msgs = [];
    if (v.length < 8) msgs.push('8+ characters');
    if (!/[0-9]/.test(v)) msgs.push('one number');
    if (!/[@#$%!]/.test(v)) msgs.push('one special char (@#$%!)');
    if (msgs.length) { setFieldError(input, 'Need: ' + msgs.join(', ')); }
    else { clearFieldError(input); input.style.borderColor = '#10b981'; }
    validateConfirmPassword(document.getElementById('password_confirmation'));
}
function validateConfirmPassword(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    if (input.value !== document.getElementById('password').value) { setFieldError(input, 'Passwords do not match.'); }
    else { clearFieldError(input); input.style.borderColor = '#10b981'; }
}
function validateStudentId(input) {
    if (!input.value.length) { clearFieldError(input); return; }
    clearFieldError(input);
    input.style.borderColor = '#10b981';
}

function toggleRoleFields(select) {
    const researcherFields = document.getElementById('researcherFields');
    const philcstFields    = document.getElementById('philcstFields');
    const deptSelect       = document.getElementById('department');

    // Reset both panels first
    researcherFields.style.display = 'none';
    philcstFields.style.display    = 'none';
    selectMemberType(null);

    if (select.value === 'researcher') {
        researcherFields.style.display = 'block';
        deptSelect.disabled            = false;
        deptSelect.style.opacity       = '1';
        deptSelect.style.cursor        = 'pointer';
    } else if (select.value === 'user') {
        philcstFields.style.display = 'block';
        deptSelect.disabled         = true;
        deptSelect.value            = '';
        deptSelect.style.opacity    = '.5';
        deptSelect.style.cursor     = 'not-allowed';
    } else {
        // Nothing selected — disable department
        deptSelect.disabled      = true;
        deptSelect.value         = '';
        deptSelect.style.opacity = '.5';
        deptSelect.style.cursor  = 'not-allowed';
    }
}

function selectMemberType(type) {
    const studentCard   = document.getElementById('studentCard');
    const facultyCard   = document.getElementById('facultyCard');
    const studentFields = document.getElementById('studentFields');
    const facultyFields = document.getElementById('facultyFields');
    const studentInput  = document.getElementById('memberStudent');
    const facultyInput  = document.getElementById('memberFaculty');

    studentCard.classList.remove('selected');
    facultyCard.classList.remove('selected');
    studentFields.style.display = 'none';
    facultyFields.style.display = 'none';

    if (type === 'student') {
        studentCard.classList.add('selected');
        studentFields.style.display = 'block';
        studentInput.checked = true;
    } else if (type === 'faculty') {
        facultyCard.classList.add('selected');
        facultyFields.style.display = 'block';
        facultyInput.checked = true;
    }
}

const yearsMap  = { 1: 3, 2: 2, 3: 1, 4: 0 };
const yearLabel = { 1: '3 years', 2: '2 years', 3: '1 year', 4: 'less than a year' };

function updateStudentInfo() {
    const yl      = parseInt(document.getElementById('year_level').value);
    const infoBox = document.getElementById('studentInfoBox');
    if (!yl) { infoBox.style.display = 'none'; return; }

    const currentYear    = new Date().getFullYear();
    const graduationYear = currentYear + (yearsMap[yl] ?? 0);

    document.getElementById('yearsRemainingText').textContent = yearLabel[yl] ?? 'less than a year';
    document.getElementById('gradYearText').textContent       = graduationYear;
    infoBox.style.display = 'flex';
}

// Restore state on validation error
(function () {
    const roleSelect = document.getElementById('role');
    const oldMember  = "{{ old('member_type') }}";
    const oldYear    = "{{ old('year_level') }}";

    if (!roleSelect || !roleSelect.value) return;

    if (roleSelect.value === 'researcher') {
        document.getElementById('researcherFields').style.display = 'block';
        document.getElementById('department').disabled            = false;
        document.getElementById('department').style.opacity       = '1';
        document.getElementById('department').style.cursor        = 'pointer';
        if (oldMember) selectMemberType(oldMember);
        if (oldYear)   updateStudentInfo();
    } else if (roleSelect.value === 'user') {
        document.getElementById('philcstFields').style.display = 'block';
        document.getElementById('department').disabled         = true;
        document.getElementById('department').style.opacity    = '.5';
        document.getElementById('department').style.cursor     = 'not-allowed';
    }
})();
</script>
@endsection