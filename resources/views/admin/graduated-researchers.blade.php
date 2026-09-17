@extends('layouts.admin')
@section('title', 'Graduated Researchers')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Graduated Researchers</h1>
        <p class="page-subtitle">
            Already graduated — they can still log in but <strong>can no longer submit research</strong>.
            Their published research remains under their name as author.
        </p>
    </div>
    <a href="{{ route('admin.researcher-accounts') }}" class="btn btn-outline">← All Researchers</a>
</div>

{{-- Info Banner --}}
<div class="info-banner">
    <span class="info-icon"></span>
    <div>
        <strong>How does Graduated status work?</strong><br>
        <span>Once a researcher's graduation year has passed, they are automatically marked as <em>Graduated</em>.
        They can still log in and view all research, but can no longer submit new ones.
        Their name remains as the author of all their previously submitted research.</span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            Graduated Accounts
            <span class="badge badge-graduated">{{ $graduated->total() }}</span>
        </h2>
    </div>
    <div class="card-body">
        @if($graduated->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"></div>
                <p>No graduated researchers yet.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Year Level (at registration)</th>
                        <th>Course</th>
                        <th>Graduation Year</th>
                        <th>Graduated Since</th>
                        <th>Researches</th>
                        <th>Access</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graduated as $i => $user)
                        <tr>
                            <td>{{ $graduated->firstItem() + $i }}</td>
                            <td>
                                <div class="user-name">{{ $user->name }}</div>
                                <small class="graduated-tag">Read-only access</small>
                            </td>
                            <td class="muted">{{ $user->email }}</td>
                            <td>{{ $user->student_id ?? '—' }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ $user->year_level_label }}</span>
                            </td>
                            <td>{{ $user->course_duration ? $user->course_duration . '-Year' : '—' }}</td>
                            <td>
                                <span class="grad-year text-graduated">{{ $user->graduation_year }}</span>
                            </td>
                            <td>
                                <span class="muted">
                                    {{ $user->graduated_at ? $user->graduated_at->format('M d, Y') : '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $user->researches_count }}</span>
                                <br>
                                <small class="muted">as author</small>
                            </td>
                            <td>
                                <div class="access-info">
                                    <span class="access-item access-yes">Login</span>
                                    <span class="access-item access-yes">Browse</span>
                                    <span class="access-item access-yes">Author</span>
                                    <span class="access-item access-no">Submit</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.user.show', $user) }}"
                                       class="btn btn-sm btn-secondary">View</a>
                                    <button type="button"
                                            class="btn btn-sm btn-primary"
                                            onclick="openRestoreModal({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->course_duration ?? 4 }})">
                                        Restore
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $graduated->links() }}</div>
        @endif
    </div>
</div>

{{-- Restore Modal --}}
@include('admin.partials.restore-researcher-modal')

<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;}
.page-title{font-size:1.5rem;font-weight:700;margin:0;}
.page-subtitle{color:var(--muted);font-size:.875rem;margin:4px 0 0;max-width:600px;}
.info-banner{display:flex;align-items:flex-start;gap:12px;background:#ede9fe;border:1px solid #c4b5fd;border-radius:12px;padding:14px 16px;margin-bottom:1.5rem;font-size:.875rem;color:#4c1d95;line-height:1.6;}
.info-icon{font-size:1.25rem;flex-shrink:0;margin-top:1px;}
.card{background:var(--card-bg,#fff);border:1px solid var(--border,#e5e7eb);border-radius:12px;overflow:hidden;}
.card-header{padding:1rem 1.5rem;border-bottom:1px solid var(--border,#e5e7eb);display:flex;align-items:center;gap:10px;}
.card-title{font-size:1rem;font-weight:600;margin:0;display:flex;align-items:center;gap:8px;}
.card-body{padding:1.5rem;}
.table-responsive{overflow-x:auto;}
.table{width:100%;border-collapse:collapse;font-size:.875rem;}
.table th{text-align:left;padding:10px 12px;background:var(--table-head-bg,#f9fafb);font-weight:600;color:var(--muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid var(--border,#e5e7eb);}
.table td{padding:12px;border-bottom:1px solid var(--border,#e5e7eb);vertical-align:middle;}
.table tbody tr:last-child td{border-bottom:none;}
.table tbody tr:hover{background:var(--table-hover,#f9fafb);}
.user-name{font-weight:600;}
.graduated-tag{font-size:.7rem;color:#7c3aed;display:block;margin-top:2px;}
.muted{color:var(--muted);font-size:.8125rem;}
.grad-year{font-weight:700;font-size:.95rem;}
.text-graduated{color:#7c3aed;}
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:.75rem;font-weight:600;}
.badge-graduated{background:#ede9fe;color:#7c3aed;}
.badge-info{background:#dbeafe;color:#2563eb;}
.badge-secondary{background:var(--border,#e5e7eb);color:var(--text,#111);}
.access-info{display:flex;flex-direction:column;gap:2px;}
.access-item{font-size:.7rem;white-space:nowrap;}
.access-yes{color:#16a34a;}
.access-no{color:#dc2626;}
.btn{display:inline-flex;align-items:center;gap:4px;padding:8px 16px;border-radius:8px;font-size:.875rem;font-weight:500;border:none;cursor:pointer;text-decoration:none;transition:opacity .15s;}
.btn:hover{opacity:.85;}
.btn-sm{padding:4px 10px;font-size:.75rem;}
.btn-primary{background:var(--primary,#7c3aed);color:#fff;}
.btn-secondary{background:var(--border,#e5e7eb);color:var(--text,#111);}
.btn-outline{background:transparent;border:1.5px solid var(--border,#e5e7eb);color:var(--text,#111);}
.action-btns{display:flex;gap:6px;flex-wrap:wrap;}
.alert{display:flex;align-items:center;gap:8px;padding:12px 16px;border-radius:8px;margin-bottom:1rem;font-size:.875rem;}
.alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0;}
.empty-state{text-align:center;padding:3rem 1rem;color:var(--muted);}
.empty-icon{font-size:2.5rem;margin-bottom:.5rem;}
.pagination-wrapper{margin-top:1rem;}
</style>
@endsection