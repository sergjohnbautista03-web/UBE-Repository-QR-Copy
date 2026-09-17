@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
@php
    $philcstAccess = session('philcst_access');
    $submitFields = ['title', 'author_name', 'year_published', 'submission_category', 'department', 'course', 'type', 'keywords', 'abstract', 'file'];
    $researcherApplyFields = ['member_type', 'year_level', 'employee_id', 'verification_documents', 'verification_documents.*', 'research_documents', 'research_documents.*'];
    $hasSubmitErrors = $errors->hasAny($submitFields);
    $hasResearcherApplyErrors = $errors->hasAny($researcherApplyFields);
    $researcherApplyErrors = collect($researcherApplyFields)
        ->flatMap(fn ($field) => $errors->get($field))
        ->flatten()
        ->filter(fn ($error) => is_string($error) && $error !== '')
        ->unique()
        ->values();
    $submitDepartment = old('department', $user->department);
    $isFacultyAccount = $user->role === 'researcher' && is_null($user->graduation_year);
    $isStudentResearcher = $user->role === 'researcher' && ! is_null($user->graduation_year);
    $canUseDepartmentKey = $user->role === 'user' || $isFacultyAccount;
    $dashboardRoleLabel = $isFacultyAccount ? 'Faculty' : ($isStudentResearcher ? 'Student Researcher' : ucfirst($user->role));
    $submissionCategories = \App\Models\Research::submissionCategories();
    $journalTypes = \App\Models\Research::typesForCategory(\App\Models\Research::SUBMISSION_CATEGORY_JOURNAL);
@endphp
<div class="ud-shell">
    <div class="ud-layout">
        <aside class="ud-sidebar">
            <div class="ud-sidebar-card">
                <span class="ud-sidebar-kicker">User Panel</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->department ?? 'PHILCST User' }}</p>
                <div class="ud-sidebar-pills">
                    <span class="ud-pill">{{ $dashboardRoleLabel }}</span>
                    <span class="ud-pill {{ $user->is_approved ? 'is-approved' : 'is-pending' }}">
                        {{ $user->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </div>
            </div>

            <nav class="ud-side-nav">
                <a href="#dashboard-top" class="ud-side-link is-active" data-dashboard-link>Dashboard Overview</a>
                @if($canUseDepartmentKey)
                    <a href="#department-access" class="ud-side-link" data-dashboard-link>Enter Department Key</a>
                @endif
                <a href="{{ route('research.pinned') }}" class="ud-side-link">Pinned Papers</a>
                <a href="#profile-settings" class="ud-side-link" data-dashboard-link>Profile Settings</a>
                @if($user->isResearcher() && $user->is_approved)
                    <a href="#submit-research" class="ud-side-link" data-dashboard-link data-open-submit>Submit Research</a>
                    <a href="{{ route('research.my-submissions') }}" class="ud-side-link">My Submissions</a>
                @endif
            </nav>
        </aside>

        <div class="ud-main" id="dashboard-top">
            <section class="ud-hero">
                <div>
                    <span class="ud-kicker">User Dashboard</span>
                    <h1>{{ $user->name }}</h1>
                    <p>
                        @if($isFacultyAccount && ! $user->is_approved)
                            Your faculty account is under review. You can still browse the repository while waiting for approval.
                        @elseif($isFacultyAccount)
                            Your faculty account is active. You can submit and manage your studies here.
                        @elseif($user->role === 'researcher' && ! $user->is_approved)
                            Your researcher application is under review. You can still browse the repository while waiting for approval.
                        @elseif($user->role === 'researcher')
                            Your researcher account is active. You can submit and manage your studies here.
                        @else
                            Browse researches, save papers, and apply as researcher when you are ready to contribute your own work.
                        @endif
                    </p>
                </div>
                <div class="ud-status-stack">
                    <span class="ud-pill">{{ $dashboardRoleLabel }}</span>
                    <span class="ud-pill {{ $user->is_approved ? 'is-approved' : 'is-pending' }}">
                        {{ $user->is_approved ? 'Approved Account' : 'Pending Approval' }}
                    </span>
                    @if($user->department)
                        <span class="ud-pill">{{ $user->department }}</span>
                    @endif
                </div>
            </section>

            <section class="ud-stats">
                <article class="ud-stat-card">
                    <span class="ud-stat-label">Pinned Papers</span>
                    <strong>{{ number_format($stats['pinned']) }}</strong>
                    <small>Saved to your account</small>
                </article>
                <article class="ud-stat-card">
                    <span class="ud-stat-label">My Submissions</span>
                    <strong>{{ number_format($stats['submissions']) }}</strong>
                    <small>Researches linked to you</small>
                </article>
                <article class="ud-stat-card">
                    <span class="ud-stat-label">In My Department</span>
                    <strong>{{ number_format($stats['department_researches']) }}</strong>
                    <small>Approved studies in {{ $user->department ?? 'your unit' }}</small>
                </article>
            </section>

            <div class="ud-grid">
                <section class="ud-card">
                    <div class="ud-card-head">
                        <div>
                            <h2>Researcher Access</h2>
                            <p>Upgrade your account to contribute research.</p>
                        </div>
                    </div>

                    @if($user->role === 'user')
                        <form method="POST" action="{{ route('researcher.apply') }}" enctype="multipart/form-data" class="ud-apply-form">
                            @csrf
                            @if($hasResearcherApplyErrors)
                                <div class="ud-status-box is-error" style="margin:0 0 4px;">
                                    <strong>Application not submitted</strong>
                                    @foreach($researcherApplyErrors as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <div class="ud-form-row">
                                <div class="ud-field">
                                    <label for="member_type">Application Type</label>
                                    <select id="member_type" name="member_type" onchange="toggleResearcherApplicationFields()">
                                        <option value="">Select type</option>
                                        <option value="student" {{ old('member_type') === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="faculty" {{ old('member_type') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                                    </select>
                                </div>
                                <div class="ud-field">
                                    <label>Department</label>
                                    <input type="text" value="{{ $user->department ?? 'No department assigned' }}" readonly>
                                </div>
                                <div class="ud-field" id="applicationYearWrap" style="{{ old('member_type') === 'student' ? '' : 'display:none;' }}">
                                    <label for="year_level">Year Level</label>
                                    <select id="year_level" name="year_level">
                                        <option value="">Select year level</option>
                                        @foreach([3, 4] as $level)
                                            <option value="{{ $level }}" {{ (string) old('year_level') === (string) $level ? 'selected' : '' }}>{{ $level }}{{ $level === 1 ? 'st' : ($level === 2 ? 'nd' : ($level === 3 ? 'rd' : 'th')) }} Year</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="ud-form-row">
                                <div class="ud-field" id="applicationStudentNote" style="{{ old('member_type') === 'faculty' ? 'display:none;' : '' }}">
                                    <label>Student ID</label>
                                    <input type="text" value="{{ $user->student_id }}" readonly>
                                </div>
                                <div class="ud-field" id="applicationEmployeeWrap" style="{{ old('member_type') === 'faculty' ? '' : 'display:none;' }}">
                                    <label for="employee_id">Employee ID</label>
                                    <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}">
                                </div>
                            </div>

                            <div class="ud-field">
                                <label for="verification_documents">Credential Documents</label>
                                <input type="file" id="verification_documents" name="verification_documents[]" multiple>
                                <small>Upload school ID, faculty ID, or other verified credentials.</small>
                            </div>

                            <div class="ud-field">
                                <label for="research_documents">Supporting Research Files</label>
                                <input type="file" id="research_documents" name="research_documents[]" multiple>
                                <small>Upload proposal, manuscript, abstract, or other proof of research activity.</small>
                            </div>

                            <button type="submit" class="ud-btn-primary">Apply as Researcher</button>
                        </form>
                    @elseif($user->role === 'researcher' && ! $user->is_approved)
                        <div class="ud-status-box is-pending">
                            <strong>Application submitted</strong>
                            <p>Your account is currently waiting for dean/admin approval. You will gain researcher privileges once approved.</p>
                        </div>
                    @else
                        <div class="ud-status-box is-approved">
                            <strong>Researcher access granted</strong>
                            <p>Your researcher account is active. You can now submit and manage your own studies in the repository.</p>
                        </div>
                    @endif
                </section>
            </div>

            @if($canUseDepartmentKey)
            <section class="ud-card" id="department-access">
                <div class="ud-card-head">
                    <div>
                        <h2>Department Access</h2>
                        <p>Enter your department key here to unlock full document viewing.</p>
                    </div>
                </div>

                <div class="ud-access-wrap">
                    <div class="ud-access-status {{ $philcstAccess ? 'is-active' : 'is-inactive' }}">
                        <strong>{{ $philcstAccess ? 'Department access is active' : 'Department access is not active yet' }}</strong>
                        <p>
                            @if($philcstAccess)
                                Full document viewing is unlocked until {{ \Illuminate\Support\Carbon::parse($philcstAccess['expires_at'])->format('M d, Y h:i A') }} for {{ $philcstAccess['department'] }}.
                            @else
                                Even if your account is approved, you still need a valid department key before you can view full research documents.
                            @endif
                        </p>
                    </div>

                    <form method="POST" action="{{ route('philcst-access.activate') }}" class="ud-apply-form">
                        @csrf
                        <div class="ud-form-row">
                            <div class="ud-field">
                                <label for="dashboard_department">Department</label>
                                <select id="dashboard_department" name="department" required>
                                    <option value="">Select department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}" {{ old('department', $user->department) === $department ? 'selected' : '' }}>{{ $department }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="ud-field">
                                <label for="dashboard_access_key">Department Key</label>
                                <input type="password" id="dashboard_access_key" name="access_key" placeholder="Enter active department key" required>
                            </div>
                        </div>

                        <button type="submit" class="ud-btn-primary">Activate Department Access</button>
                    </form>
                </div>
            </section>
            @endif

            <section class="ud-card" id="profile-settings">
                <div class="ud-card-head">
                    <div>
                        <h2>Profile Settings</h2>
                        <p>Manage your photo, account details, and password without leaving the dashboard.</p>
                    </div>
                </div>

                <div class="ud-profile-grid">
                    <div class="ud-profile-panel">
                        <span class="ud-mini-kicker">Profile Photo</span>
                        <div class="ud-photo-block">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="ud-profile-photo">
                            @else
                                <div class="ud-profile-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                            <div class="ud-photo-meta">
                                <strong>{{ $user->name }}</strong>
                                <span>{{ $user->isDepartmentDean() ? 'Dean' : $dashboardRoleLabel }}</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" class="ud-inline-form">
                            @csrf
                            @method('PATCH')
                            <div class="ud-field">
                                <label for="dashboard_profile_photo">Upload New Photo</label>
                                <input type="file" id="dashboard_profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <small>JPG, PNG, or GIF up to 2MB.</small>
                            </div>
                            <div class="ud-inline-actions">
                                <button type="submit" class="ud-btn-primary">Upload Photo</button>
                                @if($user->profile_photo)
                                    <a href="{{ route('profile.photo.remove') }}" onclick="return confirm('Remove profile photo?')" class="ud-btn-secondary">Remove</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="ud-profile-panel">
                        <span class="ud-mini-kicker">Account Details</span>
                        <div class="ud-inline-form">
                            <div class="ud-form-row">
                                <div class="ud-field">
                                    <label>Full Name</label>
                                    <input type="text" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="ud-field">
                                    <label>Email Address</label>
                                    <input type="email" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="ud-form-row">
                                <div class="ud-field">
                                    <label>Department</label>
                                    <input type="text" value="{{ $user->department ?: 'No department assigned' }}" readonly>
                                </div>
                                <div class="ud-field">
                                    <label>Student / Employee ID</label>
                                    <input type="text" value="{{ $user->student_id ?: 'No ID assigned' }}" readonly>
                                </div>
                            </div>
                            <div class="ud-lock-note">
                                This account information is assigned by your dean or administrator and cannot be edited here.
                            </div>
                        </div>
                    </div>

                    <div class="ud-profile-panel ud-profile-panel--full">
                        <span class="ud-mini-kicker">Security</span>
                        <div class="ud-inline-form">
                            <div class="ud-security-lock">
                                <strong>Password updates are locked</strong>
                                <p>Your login credentials are managed by your dean or administrator. Contact them if you need a password reset.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if($user->isResearcher() && $user->is_approved)
            <div class="ud-modal {{ $hasSubmitErrors ? 'is-visible' : '' }}" id="submitResearchModal">
                <div class="ud-modal-backdrop" data-close-submit></div>
                <div class="ud-modal-dialog ud-modal-dialog--wide" role="dialog" aria-modal="true" aria-labelledby="submitResearchTitle">
                    <div class="ud-modal-head">
                        <div>
                            <span class="ud-mini-kicker">Research Submission</span>
                            <h2 id="submitResearchTitle">Submit Research</h2>
                            <p>Share your academic work without leaving the dashboard.</p>
                        </div>
                        <button type="button" class="ud-modal-close" data-close-submit aria-label="Close submit research modal">×</button>
                    </div>

                    @if($hasSubmitErrors)
                        <div class="ud-modal-alert">
                            @foreach($errors->only($submitFields) as $fieldErrors)
                                @foreach($fieldErrors as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('research.store') }}" enctype="multipart/form-data" class="ud-submit-form">
                        @csrf

                        <div class="ud-submit-section">
                            <span class="ud-submit-step">1</span>
                            <h3>Basic Information</h3>
                        </div>
                        <div class="ud-form-row">
                            <div class="ud-field ud-field-span-2">
                                <label for="submit_title">Research Title</label>
                                <input type="text" id="submit_title" name="title" value="{{ old('title') }}" placeholder="Enter the complete title of your research" required>
                            </div>
                        </div>
                        <div class="ud-form-row">
                            <div class="ud-field">
                                <label for="submit_author_name">Author Name</label>
                                <input type="text" id="submit_author_name" name="author_name" value="{{ old('author_name', $user->name) }}" required>
                            </div>
                            <div class="ud-field">
                                <label for="submit_year_published">Year Published</label>
                                <input type="number" id="submit_year_published" name="year_published" value="{{ old('year_published', date('Y')) }}" min="2021" max="{{ date('Y') + 1 }}" required>
                            </div>
                        </div>

                        <div class="ud-submit-section">
                            <span class="ud-submit-step">2</span>
                            <h3>Department and Program</h3>
                        </div>
                        <div class="ud-form-row">
                            <div class="ud-field">
                                <label for="submit_submission_category">Submission Category</label>
                                <select id="submit_submission_category" name="submission_category" required>
                                    @foreach($submissionCategories as $value => $label)
                                        <option value="{{ $value }}" {{ old('submission_category', \App\Models\Research::SUBMISSION_CATEGORY_RESEARCH) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ud-form-row">
                            <div class="ud-field">
                                <label>Department</label>
                                <input type="text" value="{{ $user->department ?: 'No department assigned' }}" readonly>
                                <input type="hidden" id="submit_department" name="department" value="{{ $submitDepartment }}">
                            </div>
                            <div class="ud-field">
                                <label for="submit_course">Program</label>
                                <select id="submit_course" name="course" required {{ $submitDepartment ? '' : 'disabled' }}>
                                    <option value="">{{ $submitDepartment ? '-- Select Program --' : '-- No Department Assigned --' }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="ud-form-row">
                            <div class="ud-field">
                                <label for="submit_type" id="submit_type_label">Research Type</label>
                                <select id="submit_type" name="type" required {{ $submitDepartment ? '' : 'disabled' }}>
                                    <option value="">{{ $submitDepartment ? '-- Select Type --' : '-- No Department Assigned --' }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="ud-submit-section">
                            <span class="ud-submit-step">3</span>
                            <h3>Content and Keywords</h3>
                        </div>
                        <div class="ud-field">
                            <label for="submit_keywords">Keywords</label>
                            <input type="text" id="submit_keywords" name="keywords" value="{{ old('keywords') }}" placeholder="Separate keywords with commas">
                        </div>
                        <div class="ud-field">
                            <label for="submit_abstract">Abstract or Description</label>
                            <textarea id="submit_abstract" name="abstract" rows="7" placeholder="Provide a comprehensive summary of your research" required>{{ old('abstract') }}</textarea>
                        </div>

                        <div class="ud-submit-section">
                            <span class="ud-submit-step">4</span>
                            <h3>File Upload</h3>
                        </div>
                        <div class="ud-field">
                            <label for="submit_file">Research PDF</label>
                            <input type="file" id="submit_file" name="file" accept=".pdf" required>
                            <small>PDF only, up to 30MB.</small>
                        </div>

                        <div class="ud-submit-note">
                            Your submission will be reviewed by an administrator before it becomes publicly visible.
                        </div>

                        <div class="ud-inline-actions">
                            <button type="button" class="ud-btn-secondary" data-close-submit>Cancel</button>
                            <button type="submit" class="ud-btn-primary">Submit for Review</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <div class="ud-grid">
                <section class="ud-card">
                    <div class="ud-card-head">
                        <div>
                    <h2>Recently Pinned</h2>
                    <p>Your latest saved papers.</p>
                </div>
            </div>
            <div class="ud-list">
                @forelse($recentPinned as $research)
                    <a href="{{ route('research.show', $research) }}" class="ud-list-item">
                        <strong>{{ $research->title }}</strong>
                        <span>{{ $research->author_name }}</span>
                    </a>
                @empty
                    <div class="ud-empty">No pinned papers yet.</div>
                @endforelse
            </div>
                </section>

                <section class="ud-card">
                    <div class="ud-card-head">
                        <div>
                            <h2>{{ $user->department ? 'Latest in ' . $user->department : 'Latest Researches' }}</h2>
                            <p>Fresh additions from your department.</p>
                        </div>
                    </div>
                    <div class="ud-list">
                        @forelse($recentDepartmentResearches as $research)
                            <a href="{{ route('research.show', $research) }}" class="ud-list-item">
                                <strong>{{ $research->title }}</strong>
                                <span>{{ $research->year_published }} • {{ $research->author_name }}</span>
                            </a>
                        @empty
                            <div class="ud-empty">No recent department researches yet.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<style>
.ud-shell{max-width:1200px;margin:0 auto;padding:34px 24px 44px}
html{scroll-behavior:smooth}
.ud-layout{display:grid;grid-template-columns:280px minmax(0,1fr);gap:20px;align-items:start}
.ud-sidebar{position:sticky;top:88px}
.ud-sidebar-card{padding:22px;border-radius:24px;background:linear-gradient(145deg,#2b0d4e 0%,#522087 100%);color:#fff;box-shadow:0 20px 40px rgba(59,15,122,.16);margin-bottom:16px}
.ud-sidebar-kicker{display:inline-flex;padding:6px 10px;border-radius:999px;background:rgba(255,255,255,.12);font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px}
.ud-sidebar-card h2{margin:0 0 6px;font-size:26px;line-height:1.05}
.ud-sidebar-card p{margin:0 0 14px;color:rgba(255,255,255,.8);line-height:1.55}
.ud-sidebar-pills{display:flex;flex-wrap:wrap;gap:8px}
.ud-side-nav{display:grid;gap:10px}
.ud-side-nav > .ud-side-link{
    display:flex;
    align-items:center;
    width:100%;
    padding:14px 16px;
    border-radius:18px;
    background:#fff;
    border:1px solid #efe7fb;
    box-shadow:0 10px 24px rgba(59,15,122,.05);
    text-decoration:none;
    font-family:var(--font-body);
    font-size:14px;
    line-height:1.4;
    font-weight:700;
    color:#36125f !important;
    letter-spacing:-.01em;
}
.ud-side-nav > .ud-side-link:hover,
.ud-side-nav > .ud-side-link.is-active{
    background:#f7f1ff;
    border-color:#e7d8fb;
    color:#36125f !important;
}
.ud-main{min-width:0}
.ud-hero{display:flex;justify-content:space-between;gap:24px;align-items:flex-start;padding:28px 30px;border-radius:28px;background:linear-gradient(135deg,#2a0d4f 0%,#5c2093 60%,#7c3aed 100%);color:#fff;box-shadow:0 24px 48px rgba(59,15,122,.18);margin-bottom:24px}
.ud-kicker{display:inline-flex;padding:6px 10px;border-radius:999px;background:rgba(255,255,255,.12);font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px}
.ud-hero h1{margin:0 0 10px;font-size:40px;line-height:1.02;letter-spacing:-.04em}
.ud-hero p{margin:0;max-width:720px;color:rgba(255,255,255,.82);line-height:1.65}
.ud-status-stack{display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end}
.ud-pill{display:inline-flex;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.12);font-size:12px;font-weight:700}
.ud-pill.is-approved{background:#dcfce7;color:#166534}
.ud-pill.is-pending{background:#fef3c7;color:#92400e}
.ud-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:24px}
.ud-stat-card{padding:20px;border-radius:22px;background:#fff;border:1px solid #efe7fb;box-shadow:0 10px 26px rgba(59,15,122,.06)}
.ud-stat-label{display:block;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#9586b0;margin-bottom:10px}
.ud-stat-card strong{display:block;font-size:34px;line-height:1;color:#240a42}
.ud-stat-card small{display:block;margin-top:10px;color:#85779d;line-height:1.45}
.ud-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:18px}
.ud-card{background:#fff;border:1px solid #efe7fb;border-radius:24px;box-shadow:0 12px 30px rgba(59,15,122,.05);overflow:hidden}
.ud-card[id]{scroll-margin-top:108px}
.ud-card-head{padding:22px 24px 16px;border-bottom:1px solid #f2ecfb}
.ud-card-head h2{margin:0 0 6px;font-size:22px;color:#23093f}
.ud-card-head p{margin:0;color:#8c7ba8;font-size:13px}
.ud-apply-form{padding:20px 24px 24px;display:flex;flex-direction:column;gap:14px}
.ud-form-row{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
.ud-field{display:flex;flex-direction:column;gap:7px}
.ud-field label{font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:#8f80aa}
.ud-field input,.ud-field select,.ud-field textarea{width:100%;padding:12px 14px;border-radius:14px;border:1.5px solid #e9dff7;background:#fff;font:inherit;color:#210b3d}
.ud-field input:focus,.ud-field select:focus,.ud-field textarea:focus{outline:none;border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.1)}
.ud-field input[readonly]{background:#f7f2fe;color:#6b2fa0;font-weight:700}
.ud-field textarea{resize:vertical;min-height:160px}
.ud-field small{color:#9082aa;font-size:12px;line-height:1.4}
.ud-field-span-2{grid-column:1 / -1}
.ud-btn-primary{align-self:flex-start;padding:12px 18px;border:none;border-radius:14px;background:#3b0f7a;color:#fff;font-weight:700;cursor:pointer;box-shadow:0 12px 24px rgba(59,15,122,.2)}
.ud-status-box{margin:20px 24px 24px;padding:18px;border-radius:18px;border:1px solid}
.ud-status-box strong{display:block;font-size:18px;margin-bottom:8px}
.ud-status-box p{margin:0;line-height:1.6}
.ud-status-box.is-pending{background:#fff9eb;border-color:#f7d38c;color:#8a5b00}
.ud-status-box.is-approved{background:#ecfdf3;border-color:#bbf7d0;color:#166534}
.ud-status-box.is-error{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
.ud-access-wrap{padding:20px 24px 24px}
.ud-access-status{border-radius:18px;padding:16px 18px;margin-bottom:16px;border:1px solid}
.ud-access-status strong{display:block;margin-bottom:6px}
.ud-access-status p{margin:0;line-height:1.6}
.ud-access-status.is-active{background:#ecfdf3;border-color:#bbf7d0;color:#166534}
.ud-access-status.is-inactive{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
.ud-profile-grid{padding:20px 24px 24px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.ud-profile-panel{padding:18px;border-radius:20px;background:#fcfaff;border:1px solid #efe7fb}
.ud-profile-panel--full{grid-column:1 / -1}
.ud-mini-kicker{display:inline-flex;padding:6px 10px;border-radius:999px;background:#f2eaff;color:#6d28d9;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:14px}
.ud-photo-block{display:flex;align-items:center;gap:14px;margin-bottom:16px}
.ud-profile-photo,.ud-profile-initial{width:74px;height:74px;border-radius:50%;flex-shrink:0}
.ud-profile-photo{object-fit:cover;border:3px solid #fff;box-shadow:0 10px 22px rgba(59,15,122,.12)}
.ud-profile-initial{display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#7c3aed,#4c1d95);color:#fff;font-size:30px;font-weight:800;box-shadow:0 10px 22px rgba(59,15,122,.16)}
.ud-photo-meta strong{display:block;color:#240a42;margin-bottom:5px}
.ud-photo-meta span{display:inline-flex;padding:6px 10px;border-radius:999px;background:#f2eaff;color:#6d28d9;font-size:12px;font-weight:700}
.ud-inline-form{display:flex;flex-direction:column;gap:14px}
.ud-inline-actions{display:flex;gap:10px;flex-wrap:wrap}
.ud-lock-note,.ud-security-lock{padding:15px 16px;border-radius:16px;border:1px solid #e9dff7;background:#faf7ff;color:#6b5b87;line-height:1.6}
.ud-security-lock strong{display:block;color:#240a42;margin-bottom:6px}
.ud-security-lock p{margin:0}
.ud-btn-secondary{display:inline-flex;align-items:center;justify-content:center;padding:12px 18px;border-radius:14px;border:1px solid #dac9f4;background:#fff;color:#5b21b6;font-weight:700;text-decoration:none}
.ud-modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;padding:24px;z-index:1200}
.ud-modal.is-visible{display:flex}
.ud-modal-backdrop{position:absolute;inset:0;background:rgba(21,8,43,.55);backdrop-filter:blur(4px)}
.ud-modal-dialog{position:relative;width:min(760px,100%);max-height:calc(100vh - 48px);overflow:auto;border-radius:28px;background:#fff;box-shadow:0 24px 60px rgba(22,6,50,.28)}
.ud-modal-dialog--wide{width:min(920px,100%)}
.ud-modal-head{display:flex;justify-content:space-between;gap:16px;padding:24px 26px 18px;border-bottom:1px solid #f0eaf9}
.ud-modal-head h2{margin:0 0 6px;color:#23093f;font-size:28px}
.ud-modal-head p{margin:0;color:#8c7ba8}
.ud-modal-close{width:44px;height:44px;border:none;border-radius:14px;background:#f6f1ff;color:#5b21b6;font-size:28px;line-height:1;cursor:pointer}
.ud-modal-alert{margin:18px 26px 0;padding:14px 16px;border:1px solid #fecaca;border-radius:16px;background:#fef2f2;color:#b91c1c}
.ud-modal-alert p{margin:0 0 4px}
.ud-modal-alert p:last-child{margin-bottom:0}
.ud-submit-form{padding:22px 26px 26px;display:flex;flex-direction:column;gap:16px}
.ud-submit-section{display:flex;align-items:center;gap:10px;padding-top:6px}
.ud-submit-section h3{margin:0;color:#240a42;font-size:16px}
.ud-submit-step{display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:999px;background:#f2eaff;color:#6d28d9;font-size:12px;font-weight:800}
.ud-submit-note{padding:14px 16px;border-radius:16px;border:1px solid #fcd34d;background:#fffbeb;color:#92400e;line-height:1.6}
.ud-list{padding:18px 24px 24px;display:grid;gap:10px}
.ud-list-item{display:block;padding:14px 16px;border-radius:16px;background:#fcfbff;border:1px solid #efe7fb;text-decoration:none}
.ud-list-item strong{display:block;color:#240a42;margin-bottom:5px}
.ud-list-item span{font-size:13px;color:#8b7aaa}
.ud-empty{padding:16px;border-radius:16px;background:#faf7ff;color:#9b8db6;text-align:center}
@media (max-width: 1100px){.ud-layout{grid-template-columns:1fr}.ud-sidebar{position:static}}
@media (max-width: 980px){.ud-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.ud-grid{grid-template-columns:1fr}.ud-hero{flex-direction:column}.ud-profile-grid{grid-template-columns:1fr}}
@media (max-width: 640px){.ud-shell{padding:24px 16px 34px}.ud-hero{padding:22px}.ud-hero h1{font-size:32px}.ud-stats{grid-template-columns:1fr}.ud-form-row{grid-template-columns:1fr}.ud-photo-block{align-items:flex-start;flex-direction:column}.ud-modal{padding:12px}.ud-modal-head,.ud-submit-form{padding-left:18px;padding-right:18px}}
</style>

@push('scripts')
<script>
const submitDeptData = {
    'College of Accountancy and Business Education': {
        courses: ['Accountancy', 'Business Administration-Marketing Mngt.', 'Hospitality Management', 'Tourism Management'],
        types: ['Thesis', 'Feasibility Study', 'Descriptive Research', 'Correlational Research', 'Quantitative Research']
    },
    'College of Computer Studies': {
        courses: ['Computer Science', 'Information Technology'],
        types: ['Capstone 1', 'Capstone 2', 'Thesis', 'Applied Research']
    },
    'College of Criminal Justice Education': {
        courses: ['Criminology'],
        types: ['Thesis', 'Descriptive Research', 'Qualitative Research', 'Mixed Methods Research']
    },
    'College of Education': {
        courses: ['Elementary Education', 'Secondary Education-General Science'],
        types: ['Thesis', 'Action Research', 'Descriptive Research', 'Experimental Research']
    },
    'College of Engineering': {
        courses: ['Civil Engineering', 'Computer Engineering', 'Electrical Engineering', 'Electronics Engineering', 'Mechanical Engineering'],
        types: ['Capstone 1', 'Capstone 2', 'Thesis', 'Applied Research', 'Experimental Research']
    },
    'College of Engineering and Architecture': {
        courses: ['Civil Engineering', 'Computer Engineering', 'Electrical Engineering', 'Electronics Engineering', 'Mechanical Engineering'],
        types: ['Capstone 1', 'Capstone 2', 'Thesis', 'Applied Research', 'Experimental Research']
    },
    'College of Maritime Studies': {
        courses: ['Marine Engineering', 'Transportation'],
        types: ['Thesis', 'Applied Research', 'Descriptive Research', 'Quantitative Research']
    }
};
const submitJournalTypes = @json($journalTypes);

function toggleResearcherApplicationFields() {
    const memberTypeField = document.getElementById('member_type');
    const yearWrap = document.getElementById('applicationYearWrap');
    const studentNote = document.getElementById('applicationStudentNote');
    const employeeWrap = document.getElementById('applicationEmployeeWrap');

    if (!memberTypeField || !yearWrap || !studentNote || !employeeWrap) {
        return;
    }

    const memberType = memberTypeField.value;
    yearWrap.style.display = memberType === 'student' ? '' : 'none';
    studentNote.style.display = memberType === 'faculty' ? 'none' : '';
    employeeWrap.style.display = memberType === 'faculty' ? '' : 'none';
}

toggleResearcherApplicationFields();

document.querySelectorAll('[data-dashboard-link]').forEach((link) => {
    link.addEventListener('click', () => {
        document.querySelectorAll('[data-dashboard-link]').forEach((item) => item.classList.remove('is-active'));
        link.classList.add('is-active');
    });
});

if (window.location.hash) {
    const activeLink = document.querySelector(`[data-dashboard-link][href="${window.location.hash}"]`);
    if (activeLink) {
        document.querySelectorAll('[data-dashboard-link]').forEach((item) => item.classList.remove('is-active'));
        activeLink.classList.add('is-active');
    }
}

const submitResearchModal = document.getElementById('submitResearchModal');

function toggleSubmitResearchModal(shouldOpen) {
    if (!submitResearchModal) {
        return;
    }

    submitResearchModal.classList.toggle('is-visible', shouldOpen);
    document.body.style.overflow = shouldOpen ? 'hidden' : '';
}

document.querySelectorAll('[data-open-submit]').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();
        document.querySelectorAll('[data-dashboard-link]').forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        toggleSubmitResearchModal(true);
    });
});

document.querySelectorAll('[data-close-submit]').forEach((button) => {
    button.addEventListener('click', () => toggleSubmitResearchModal(false));
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && submitResearchModal?.classList.contains('is-visible')) {
        toggleSubmitResearchModal(false);
    }
});

function populateSubmitOptions() {
    const department = document.getElementById('submit_department')?.value;
    const category = document.getElementById('submit_submission_category')?.value || 'research';
    const courseSelect = document.getElementById('submit_course');
    const typeSelect = document.getElementById('submit_type');
    const typeLabel = document.getElementById('submit_type_label');

    if (!courseSelect || !typeSelect || !typeLabel) {
        return;
    }

    const currentCourse = @json(old('course'));
    const currentType = @json(old('type'));

    courseSelect.innerHTML = '';
    typeSelect.innerHTML = '';
    typeLabel.textContent = category === 'journal' ? 'Journal Type' : 'Research Type';

    if (!department || !submitDeptData[department]) {
        courseSelect.innerHTML = '<option value="">-- No Department Assigned --</option>';
        typeSelect.innerHTML = '<option value="">-- No Department Assigned --</option>';
        courseSelect.disabled = true;
        typeSelect.disabled = true;
        return;
    }

    courseSelect.innerHTML = '<option value="">-- Select Program --</option>';
    typeSelect.innerHTML = '<option value="">-- Select Type --</option>';

    submitDeptData[department].courses.forEach((course) => {
        const option = document.createElement('option');
        option.value = course;
        option.textContent = course;
        if (currentCourse === course) {
            option.selected = true;
        }
        courseSelect.appendChild(option);
    });

    const types = category === 'journal' ? submitJournalTypes : submitDeptData[department].types;
    types.forEach((type) => {
        const option = document.createElement('option');
        option.value = type;
        option.textContent = type;
        if (currentType === type) {
            option.selected = true;
        }
        typeSelect.appendChild(option);
    });

    courseSelect.disabled = false;
    typeSelect.disabled = false;
}

populateSubmitOptions();
document.getElementById('submit_submission_category')?.addEventListener('change', populateSubmitOptions);

if (submitResearchModal?.classList.contains('is-visible')) {
    document.body.style.overflow = 'hidden';
}
</script>
@endpush
@endsection
