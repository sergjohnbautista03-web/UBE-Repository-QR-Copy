@extends('layouts.admin')
@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')

{{-- SUMMARY CARDS --}}
@php
    $allResearchers = $users->getCollection()->filter(fn($u) => $u->role === 'researcher');
    $students    = $allResearchers->filter(fn($u) => !is_null($u->graduation_year));
    $faculty     = $allResearchers->filter(fn($u) => is_null($u->graduation_year));
    $graduated   = $students->filter(fn($u) => $u->isGraduated())->count();
    $currentYear = (int) date('Y');
@endphp

<div class="summary-grid">
    <div class="summary-card sc-total">
        <div class="sc-icon sc-i-total">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="sc-info"><div class="sc-val">{{ $users->total() }}</div><div class="sc-lbl">Total Users</div></div>
    </div>

    <div class="summary-card sc-researcher">
        <div class="sc-icon sc-i-researcher">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        </div>
        <div class="sc-info"><div class="sc-val">{{ $allResearchers->count() }}</div><div class="sc-lbl">Researchers</div></div>
    </div>

    <div class="summary-card sc-graduated">
        <div class="sc-icon sc-i-graduated">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="sc-info"><div class="sc-val">{{ $graduated }}</div><div class="sc-lbl">Graduated</div></div>
    </div>
</div>

@if(session('success'))
    <div class="mu-alert mu-alert-success">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mu-alert mu-alert-error">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        {{ session('error') }}
    </div>
@endif

{{-- FILTER BAR --}}
<form method="GET" class="mu-filter-card">
    <div class="mu-search-wrap">
        <span class="mu-search-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email...">
    </div>

    <div class="mu-select-wrap">
        <select name="role" onchange="this.form.submit()">
            <option value="">All Roles</option>
            <option value="user"       {{ request('role') == 'user'       ? 'selected' : '' }}>Regular User</option>
            <option value="researcher" {{ request('role') == 'researcher' ? 'selected' : '' }}>Researcher</option>
        </select>
    </div>

    <div class="mu-select-wrap" id="researcherTypeWrap" style="{{ request('role') == 'researcher' ? '' : 'display:none' }}">
        <select name="member_type">
            <option value="">All Types</option>
            <option value="student" {{ request('member_type') == 'student' ? 'selected' : '' }}>Student</option>
            <option value="faculty" {{ request('member_type') == 'faculty' ? 'selected' : '' }}>Faculty</option>
        </select>
    </div>

    <button type="submit" class="mu-btn mu-btn-primary">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter
    </button>
    <a href="{{ route('admin.users') }}" class="mu-btn mu-btn-ghost">Clear</a>
    <a href="{{ route('admin.create-admin') }}" class="mu-btn mu-btn-outline mu-add-btn">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Admin
    </a>
</form>

{{-- TABLE CARD --}}
<div class="mu-card">
    <div class="mu-card-header">
        <div class="mu-card-header-left">
            <div class="mu-card-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <h3 class="mu-card-title">All Users</h3>
                <p class="mu-card-sub">Manage accounts and permissions</p>
            </div>
        </div>
        <span class="mu-count-badge">{{ $users->total() }} user(s)</span>
    </div>

    <div class="mu-table-wrap">
        <table class="mu-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>ID</th>
                    <th>Grad. Year</th>
                    <th>Researcher Status</th>
                    <th>Last Seen</th>
                    <th>Papers</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $isResearcher = $user->role === 'researcher';
                    $isFaculty    = $isResearcher && is_null($user->graduation_year);
                    $isStudent    = $isResearcher && !is_null($user->graduation_year);
                    $grad         = $user->graduation_year;
                    $years        = $grad ? ($grad - $currentYear) : null;
                    $isDropped    = $isResearcher && $user->isDroppedOut();
                    $isIrregular  = $isResearcher && $user->isIrregular();
                    $isGraduated  = $isResearcher && $user->isGraduated();

                    if (!$isResearcher) {
                        $rowClass = '';
                    } elseif ($isDropped) {
                        $rowClass = 'row-dropout';
                    } elseif ($isGraduated) {
                        $rowClass = 'row-graduated';
                    } elseif ($isIrregular) {
                        $rowClass = 'row-irregular';
                    } elseif ($years !== null && $years <= 1) {
                        $rowClass = 'row-warning';
                    } else {
                        $rowClass = '';
                    }
                @endphp
                <tr class="mu-row {{ !$user->is_active ? 'mu-row-inactive' : '' }} {{ $rowClass }}">
                    <td>
                        <a href="{{ route('admin.user.show', $user) }}" class="mu-name-cell">
                            <div class="mu-avatar" style="background:{{ ['#f0eaf9','#dbeafe','#dcfce7','#fef9c3','#ffe4e6'][crc32($user->name) % 5] }};color:{{ ['#6b2fa0','#1d4ed8','#15803d','#92400e','#be123c'][crc32($user->name) % 5] }};">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="mu-name">{{ $user->name }}</div>
                                @if($isDropped)
                                    <small class="mu-sub-tag mu-tag-dropout">Dropped Out</small>
                                @elseif($isGraduated)
                                    <small class="mu-sub-tag mu-tag-graduated">Graduated</small>
                                @elseif($isIrregular)
                                    <small class="mu-sub-tag mu-tag-irregular">Irregular</small>
                                @elseif($isFaculty)
                                    <small class="mu-sub-tag mu-tag-faculty">Faculty</small>
                                @endif
                            </div>
                        </a>
                    </td>
                    <td class="mu-email">{{ $user->email }}</td>
                    <td>
                        @if($isStudent)
                            <span class="mu-role-badge mu-role-student">Student</span>
                        @elseif($isFaculty)
                            <span class="mu-role-badge mu-role-faculty">Faculty</span>
                        @else
                            <span class="mu-role-badge mu-role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                        @endif
                    </td>
                    <td class="mu-dept">{{ $user->department ? Str::limit($user->department, 24) : '—' }}</td>
                    <td class="mu-id">{{ $user->student_id ?? '—' }}</td>
                    <td>
                        @if($isFaculty)
                            <span class="mu-perm">Permanent</span>
                        @elseif($grad)
                            <span class="mu-grad-year {{ $isGraduated ? 'gy-past' : ($years <= 1 ? 'gy-soon' : 'gy-future') }}">
                                {{ $grad }}
                                <small>{{ $isGraduated ? 'graduated' : ($years === 0 ? 'this year' : $years . 'yr left') }}</small>
                            </span>
                        @else
                            <span class="mu-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if(!$isResearcher)
                            <span class="mu-muted">—</span>
                        @elseif($isDropped)
                            <span class="mu-res-status rs-dropout">Dropped Out</span>
                        @elseif($isGraduated)
                            <span class="mu-res-status rs-graduated">Graduated</span>
                        @elseif($isIrregular)
                            <span class="mu-res-status rs-irregular">Irregular</span>
                        @elseif($years === 0)
                            <span class="mu-res-status rs-graduating">Graduating</span>
                        @else
                            <span class="mu-res-status rs-active">Active</span>
                        @endif
                    </td>
                    <td>
                        @if($user->isOnline())
                            <span class="mu-online"><span class="mu-dot mu-dot-pulse"></span>Online</span>
                        @elseif($user->last_seen_at)
                            <span class="mu-away" title="{{ $user->last_seen_at->format('M d, Y h:i A') }}">
                                {{ $user->last_seen_at->diffForHumans() }}
                            </span>
                        @else
                            <span class="mu-muted">Never</span>
                        @endif
                    </td>
                    <td>
                        <span class="mu-papers">{{ $user->researches_count }}</span>
                    </td>
                    <td>
                        <div class="mu-actions">
                            <a href="{{ route('admin.user.show', $user) }}" class="act act-view">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                View
                            </a>

                            <form method="POST" action="{{ route('admin.user.toggle', $user) }}" style="display:contents"
                                  onsubmit="return confirm('{{ $user->is_active ? 'Deactivate' : 'Activate' }} {{ addslashes($user->name) }}?')">
                                @csrf
                                <button type="submit" class="act {{ $user->is_active ? 'act-deactivate' : 'act-activate' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="mu-empty">
                        <div class="mu-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <p>No users found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mu-pagination">{{ $users->links('vendor.pagination.custom') }}</div>
</div>

<style>
.summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1rem;margin-bottom:1.25rem;}
.summary-card{background:#fff;border-radius:14px;padding:1rem 1.1rem;display:flex;align-items:center;gap:12px;border:1px solid #f0eaf9;box-shadow:0 2px 10px rgba(59,15,122,.05);position:relative;overflow:hidden;transition:transform .15s;}
.summary-card:hover{transform:translateY(-2px);}
.sc-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.sc-i-total{background:#f0eaf9;color:#6b2fa0;border-top:3px solid #e2d5f4;}
.sc-i-researcher{background:#ede9fe;color:#7c3aed;}
.sc-i-graduated{background:#dcfce7;color:#16a34a;}
.sc-total{border-top:3px solid #e2d5f4;}
.sc-researcher{border-top:3px solid #7c3aed;}
.sc-graduated{border-top:3px solid #16a34a;}
.sc-val{font-size:1.6rem;font-weight:800;line-height:1;color:#1a0638;letter-spacing:-1px;}
.sc-lbl{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#a090bc;margin-top:3px;}

.mu-alert{display:flex;align-items:center;gap:8px;padding:11px 16px;border-radius:10px;margin-bottom:1rem;font-size:.875rem;font-weight:500;}
.mu-alert-success{background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;}
.mu-alert-error{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}

.mu-filter-card{background:#fff;border-radius:14px;border:1px solid rgba(107,47,160,.1);box-shadow:0 2px 14px rgba(59,15,122,.05);padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.mu-search-wrap{position:relative;flex:1;min-width:200px;}
.mu-search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#c0aee0;display:flex;align-items:center;pointer-events:none;}
.mu-filter-card input[type="text"]{width:100%;padding:9px 14px 9px 36px;border:1.5px solid #e8dff5;border-radius:50px;background:#faf8ff;font-size:13px;color:#1a0638;transition:border-color .15s;box-sizing:border-box;font-family:inherit;}
.mu-filter-card input:focus{outline:none;border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.1);}
.mu-select-wrap{min-width:140px;}
.mu-select-wrap select{width:100%;padding:9px 13px;border:1.5px solid #e8dff5;border-radius:50px;background:#faf8ff;font-size:13px;color:#1a0638;transition:border-color .15s;box-sizing:border-box;font-family:inherit;}
.mu-select-wrap select:focus{outline:none;border-color:#7c3aed;}
.mu-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:50px;font-size:12.5px;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .14s;font-family:inherit;white-space:nowrap;}
.mu-btn-primary{background:#3b0f7a;color:#fff;box-shadow:0 3px 12px rgba(59,15,122,.25);}
.mu-btn-primary:hover{background:#2d0a5e;transform:translateY(-1px);}
.mu-btn-ghost{background:#fff;color:#8b7aaa;border:1.5px solid #e8dff5;}
.mu-btn-ghost:hover{background:#f4f0fc;color:#3b0f7a;}
.mu-btn-outline{color:#6b2fa0;border:1.5px solid #e2d5f4;background:#faf8ff;}
.mu-btn-outline:hover{background:#f0eaf9;}
.mu-add-btn{margin-left:auto;}

.mu-card{background:#fff;border-radius:18px;border:1px solid rgba(107,47,160,.1);box-shadow:0 4px 28px rgba(59,15,122,.06);overflow:hidden;}
.mu-card-header{display:flex;align-items:center;justify-content:space-between;padding:18px 24px;background:linear-gradient(160deg,#fdfbff,#f8f4fe);border-bottom:1px solid #f0eaf9;flex-wrap:wrap;gap:12px;}
.mu-card-header-left{display:flex;align-items:center;gap:12px;}
.mu-card-icon{width:38px;height:38px;border-radius:10px;background:#f0eaf9;border:1px solid #e2d5f4;display:flex;align-items:center;justify-content:center;color:#6b2fa0;flex-shrink:0;}
.mu-card-title{font-size:15px;font-weight:800;color:#1a0638;margin:0 0 2px;letter-spacing:-.3px;}
.mu-card-sub{font-size:11.5px;color:#a090bc;margin:0;}
.mu-count-badge{padding:5px 14px;background:#f0eaf9;border:1px solid #e2d5f4;border-radius:50px;font-size:12px;font-weight:700;color:#6b2fa0;}

.mu-table-wrap{overflow-x:auto;}
.mu-table{width:100%;border-collapse:collapse;font-size:.8125rem;}
.mu-table thead tr{background:linear-gradient(135deg,#faf8ff,#f5f0fd);border-bottom:2px solid #ede8fa;}
.mu-table th{padding:10px 14px;font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9070c0;text-align:left;white-space:nowrap;}
.mu-table tbody tr{border-bottom:1px solid #faf7ff;transition:background .12s;}
.mu-table tbody tr:last-child{border-bottom:none;}
.mu-table tbody tr:hover{background:#fdf9ff;}
.mu-table td{padding:11px 14px;vertical-align:middle;}
.mu-row-inactive{opacity:.5;}
.row-graduated{background:#fdfbff !important;}
.row-warning{background:#fffdf0 !important;}
.row-irregular{background:#fffbeb !important;}
.row-dropout{background:#fff8f8 !important;}

.mu-name-cell{display:flex;align-items:center;gap:10px;text-decoration:none;}
.mu-avatar{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;border:1.5px solid rgba(0,0,0,.06);}
.mu-name{font-size:.875rem;font-weight:700;color:#1a0638;}
.mu-name-cell:hover .mu-name{color:#7c3aed;}
.mu-sub-tag{font-size:.65rem;font-weight:600;display:block;margin-top:1px;}
.mu-tag-dropout{color:#dc2626;}
.mu-tag-graduated{color:#7c3aed;}
.mu-tag-irregular{color:#b45309;}
.mu-tag-faculty{color:#6b2fa0;}

.mu-role-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:.7rem;font-weight:700;}
.mu-role-user{background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;}
.mu-role-researcher{background:#ede9fe;color:#6b2fa0;border:1px solid #ddd6fe;}
.mu-role-student{background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;}
.mu-role-faculty{background:#ede9fe;color:#6b2fa0;border:1px solid #ddd6fe;}

.mu-email{font-size:.8125rem;color:#5b3d8a;}
.mu-dept{font-size:.8125rem;color:#a090bc;max-width:160px;}
.mu-id{font-size:.75rem;color:#a090bc;font-family:monospace;}
.mu-muted{color:#c0aee0;font-size:.8125rem;}
.mu-perm{font-size:.75rem;color:#6b2fa0;font-weight:600;}

.mu-grad-year{display:flex;flex-direction:column;gap:1px;}
.mu-grad-year{font-size:.9375rem;font-weight:800;letter-spacing:-.5px;}
.mu-grad-year small{font-size:.65rem;font-weight:600;}
.gy-past{color:#7c3aed;} .gy-past small{color:#a090bc;}
.gy-soon{color:#92400e;} .gy-soon small{color:#b45309;}
.gy-future{color:#15803d;} .gy-future small{color:#16a34a;}

.mu-res-status{display:inline-flex;align-items:center;padding:3px 9px;border-radius:6px;font-size:.7rem;font-weight:700;}
.rs-active{background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;}
.rs-graduating{background:#fef9c3;color:#92400e;border:1px solid #fde68a;}
.rs-graduated{background:#ede9fe;color:#6b2fa0;border:1px solid #ddd6fe;}
.rs-irregular{background:#fef9c3;color:#b45309;border:1px solid #fde68a;}
.rs-dropout{background:#fee2e2;color:#dc2626;border:1px solid #fecaca;}

.mu-online{display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;color:#15803d;}
.mu-away{font-size:.75rem;color:#6b7280;}
.mu-dot{width:7px;height:7px;border-radius:50%;background:#22c55e;flex-shrink:0;}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(1.4);}}
.mu-dot-pulse{animation:pulse-dot 1.8s ease-in-out infinite;}

.mu-papers{display:inline-flex;align-items:center;justify-content:center;min-width:26px;height:22px;padding:0 7px;background:#f4f0fc;border-radius:6px;font-size:.75rem;font-weight:700;color:#6b2fa0;border:1px solid #e2d5f4;}

.mu-actions{display:flex;gap:4px;flex-wrap:wrap;}
.act{display:inline-flex;align-items:center;gap:3px;padding:4px 9px;border-radius:6px;font-size:.68rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .13s;white-space:nowrap;font-family:inherit;}
.act-view{background:#f4f0fc;color:#6b2fa0;border:1px solid #e2d5f4;} .act-view:hover{background:#ede8fa;}
.act-deactivate{background:#fef3c7;color:#92400e;border:1px solid #fde68a;} .act-deactivate:hover{background:#fde68a;}
.act-activate{background:#dcfce7;color:#15803d;border:1px solid #bbf7d0;} .act-activate:hover{background:#bbf7d0;}

.mu-empty{text-align:center;padding:48px 20px !important;color:#c0aee0;}
.mu-empty-icon{width:48px;height:48px;border-radius:12px;background:#f4f0fc;border:1px solid #e8dff5;display:flex;align-items:center;justify-content:center;color:#c0aee0;margin:0 auto 12px;}
.mu-empty p{font-size:14px;margin:0;}

.mu-pagination{padding:12px 18px;border-top:1px solid #f0eaf9;}

@media(max-width:700px){.mu-filter-card{flex-direction:column;align-items:stretch;}.mu-add-btn{margin-left:0;}}
</style>

<script>
document.querySelector('select[name="role"]').addEventListener('change', function() {
    document.getElementById('researcherTypeWrap').style.display =
        this.value === 'researcher' ? '' : 'none';
});
</script>
@endsection