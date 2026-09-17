@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="stats-grid">

    <div class="stat-card stat-purple" style="cursor:pointer;" onclick="openModal('researches')">
        <div class="stat-accent acc-purple"></div>
        <div class="stat-info">
            <span class="stat-label">Total Researches</span>
            <span class="stat-number">{{ number_format($stats['total_researches']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.8"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414A1 1 0 0 1 19 9.414V19a2 2 0 0 1-2 2z"/></svg>
        </div>
    </div>

    <div class="stat-card stat-amber" style="cursor:pointer;" onclick="openModal('pending')">
        <div class="stat-accent acc-amber"></div>
        <div class="stat-info">
            <span class="stat-label">Pending Review</span>
            <span class="stat-number">{{ number_format($stats['pending']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg>
        </div>
    </div>

    <div class="stat-card stat-green" style="cursor:pointer;" onclick="openModal('approved')">
        <div class="stat-accent acc-green"></div>
        <div class="stat-info">
            <span class="stat-label">Approved</span>
            <span class="stat-number">{{ number_format($stats['approved']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/></svg>
        </div>
    </div>

    <div class="stat-card stat-red" style="cursor:pointer;" onclick="openModal('rejected')">
        <div class="stat-accent acc-red"></div>
        <div class="stat-info">
            <span class="stat-label">Rejected</span>
            <span class="stat-number">{{ number_format($stats['rejected']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
    </div>

    <div class="stat-card stat-blue" style="cursor:pointer;" onclick="openModal('users')">
        <div class="stat-accent acc-blue"></div>
        <div class="stat-info">
            <span class="stat-label">Total Users</span>
            <span class="stat-number">{{ number_format($stats['total_users']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>

    <div class="stat-card stat-teal">
        <div class="stat-accent acc-teal"></div>
        <div class="stat-info">
            <span class="stat-label">Total Views</span>
            <span class="stat-number">{{ number_format($stats['total_views']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
    </div>

    <div class="stat-card stat-indigo">
        <div class="stat-accent acc-indigo"></div>
        <div class="stat-info">
            <span class="stat-label">Downloads</span>
            <span class="stat-number">{{ number_format($stats['total_downloads']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
    </div>

    <div class="stat-card stat-rose" style="cursor:pointer;" onclick="openModal('researchers')">
        <div class="stat-accent acc-rose"></div>
        <div class="stat-info">
            <span class="stat-label">Researchers</span>
            <span class="stat-number">{{ number_format($stats['researchers']) }}</span>
        </div>
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
    </div>

</div>

<!-- MAIN GRID -->
<div class="admin-dashboard-grid">
    <div class="admin-card col-span-2">
        <div class="admin-card-header">
            <h3>Pending Approvals</h3>
            <a href="{{ route('admin.researches', ['status' => 'pending']) }}" class="btn btn-sm btn-outline">View All</a>
        </div>
        @if($pendingResearches->isEmpty())
            <div class="empty-sm">No pending submissions</div>
        @else
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th><th>Author</th><th>Department</th><th>Type</th><th>Submitted</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingResearches as $r)
                    <tr>
                        <td><a href="{{ route('admin.research.show', $r) }}" class="table-link">{{ Str::limit($r->title, 45) }}</a></td>
                        <td>{{ $r->author_name }}</td>
                        <td>{{ Str::limit($r->department, 30) }}</td>
                        <td><span class="type-badge type-{{ $r->type }} badge-sm">{{ $r->getTypeLabel() }}</span></td>
                        <td>{{ $r->created_at->diffForHumans() }}</td>
                        <td class="action-cell" style="display:flex; gap:6px; align-items:center;">
                           <form method="POST" action="{{ route('admin.research.approve', $r) }}" style="display:contents">
                        @csrf
                        <button type="submit" class="btn-approve-sm">Approve</button>
                           </form>
                        <button type="button" class="btn-reject-sm" onclick="openRejectModal({{ $r->id }})">Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><h3>By Department</h3></div>
        @foreach($researchByDept as $dept)
        <div class="dept-bar-row">
            <span class="dept-bar-label">{{ Str::limit($dept->department, 30) }}</span>
            <div class="dept-bar-wrap">
                <div class="dept-bar" style="width: {{ $stats['approved'] > 0 ? min(100, ($dept->count / $stats['approved']) * 100) : 0 }}%"></div>
            </div>
            <span class="dept-bar-count">{{ $dept->count }}</span>
        </div>
        @endforeach
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><h3>Top Viewed</h3></div>
        @foreach($topResearches as $i => $r)
        <div class="top-item">
            <span class="top-rank">{{ $i + 1 }}</span>
            <div class="top-info">
                <a href="{{ route('admin.research.show', $r) }}" class="top-title">{{ Str::limit($r->title, 45) }}</a>
                <span> {{ number_format($r->view_count) }} views</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="admin-card col-span-2">
        <div class="admin-card-header">
            <h3>Recently Approved</h3>
            <a href="{{ route('admin.researches', ['status' => 'approved']) }}" class="btn btn-sm btn-outline">View All</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr><th>Title</th><th>Author</th><th>Department</th><th>Year</th><th>Views</th></tr>
                </thead>
                <tbody>
                    @foreach($recentResearches as $r)
                    <tr>
                        <td><a href="{{ route('admin.research.show', $r) }}" class="table-link">{{ Str::limit($r->title, 50) }}</a></td>
                        <td>{{ $r->author_name }}</td>
                        <td>{{ Str::limit($r->department, 30) }}</td>
                        <td>{{ $r->year_published }}</td>
                        <td>{{ number_format($r->view_count) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Stat Detail Modal -->
<div id="statModal" class="modal-overlay" style="display:none;">
    <div class="modal-box" style="width:800px; max-width:95vw; max-height:85vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h3 id="modalTitle" style="font-family:var(--font-head); color:var(--purple-deep);">Details</h3>
            <div style="display:flex; gap:8px;">
                <button onclick="printModal()" class="btn btn-outline btn-sm">🖨 Print</button>
                <button onclick="closeModal()" style="background:none; border:none; font-size:22px; cursor:pointer; color:var(--muted);">×</button>
            </div>
        </div>
        <div id="modalBody"></div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <h3>Reject Research</h3>
        <p>Please provide a reason for rejection:</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reason" rows="4" placeholder="Explain why this submission is being rejected..." required style="width:100%;padding:10px;border:2px solid #e8dff5;border-radius:6px;margin:12px 0;font-size:14px;"></textarea>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" class="btn btn-ghost" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-red">Reject</button>
            </div>
        </form>
    </div>
</div>

@php
$allResearchersJson = $allResearchers->map(fn($u) => ['name' => $u->name, 'email' => $u->email, 'department' => $u->department, 'joined' => $u->created_at->format('M d, Y')]);
$allResearchesJson = $allResearches->map(fn($r) => ['title' => $r->title, 'author' => $r->author_name, 'department' => $r->department, 'status' => $r->status, 'year' => $r->year_published]);
$allUsersJson = $allUsers->map(fn($u) => ['name' => $u->name, 'email' => $u->email, 'role' => $u->role, 'department' => $u->department, 'joined' => $u->created_at->format('M d, Y')]);
$pendingJson = $allResearches->where('status','pending')->values()->map(fn($r) => ['title' => $r->title, 'author' => $r->author_name, 'department' => $r->department, 'year' => $r->year_published]);
$approvedJson = $allResearches->where('status','approved')->values()->map(fn($r) => ['title' => $r->title, 'author' => $r->author_name, 'department' => $r->department, 'year' => $r->year_published]);
$rejectedJson = $allResearches->where('status','rejected')->values()->map(fn($r) => ['title' => $r->title, 'author' => $r->author_name, 'department' => $r->department, 'year' => $r->year_published]);
@endphp

@endsection

@push('scripts')
<script>
const modalData = {
    researchers: {
        title: 'All Researchers ({{ $stats["researchers"] }})',
        headers: ['Name','Email','Department','Joined'],
        rows: @json($allResearchersJson),
        keys: ['name','email','department','joined'],
        printable: true
    },
    researches: {
        title: 'All Researches ({{ $stats["total_researches"] }})',
        headers: ['Title','Author','Department','Status','Year'],
        rows: @json($allResearchesJson),
        keys: ['title','author','department','status','year'],
        printable: true
    },
    users: {
        title: 'All Users ({{ $stats["total_users"] }})',
        headers: ['Name','Email','Role','Department','Joined'],
        rows: @json($allUsersJson),
        keys: ['name','email','role','department','joined'],
        printable: true
    },
    pending: {
        title: 'Pending Review ({{ $stats["pending"] }})',
        headers: ['Title','Author','Department','Year'],
        rows: @json($pendingJson),
        keys: ['title','author','department','year'],
        printable: false
    },
    approved: {
        title: 'Approved ({{ $stats["approved"] }})',
        headers: ['Title','Author','Department','Year'],
        rows: @json($approvedJson),
        keys: ['title','author','department','year'],
        printable: false
    },
    rejected: {
        title: 'Rejected ({{ $stats["rejected"] }})',
        headers: ['Title','Author','Department','Year'],
        rows: @json($rejectedJson),
        keys: ['title','author','department','year'],
        printable: false
    },
};

let currentModal = null;

function openModal(type) {
    currentModal = type;
    const data = modalData[type];
    document.getElementById('modalTitle').textContent = data.title;

    let html = '<div style="overflow-x:auto;"><table style="width:100%;border-collapse:collapse;font-size:13px;">';
    html += '<thead><tr>';
    data.headers.forEach(h => {
        html += `<th style="text-align:left;padding:10px 12px;background:var(--purple-ghost);color:var(--purple-deep);font-size:11px;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid var(--border);">${h}</th>`;
    });
    html += '</tr></thead><tbody>';

    if (data.rows.length === 0) {
        html += `<tr><td colspan="${data.headers.length}" style="text-align:center;padding:24px;color:var(--muted);">No data found.</td></tr>`;
    } else {
        data.rows.forEach(row => {
            html += '<tr>';
            data.keys.forEach(key => {
                const val = row[key] || '—';
                html += `<td style="padding:10px 12px;border-bottom:1px solid #f3eefb;vertical-align:middle;">${val}</td>`;
            });
            html += '</tr>';
        });
    }

    html += '</tbody></table></div>';
    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('statModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('statModal').style.display = 'none';
    currentModal = null;
}

function printModal() {
    if (!currentModal) return;
    const data = modalData[currentModal];
    let rows = '';
    data.rows.forEach(row => {
        rows += '<tr>';
        data.keys.forEach(key => {
            rows += `<td style="padding:8px 10px;border:1px solid #ddd;">${row[key] || '—'}</td>`;
        });
        rows += '</tr>';
    });

    let headers = '';
    data.headers.forEach(h => {
        headers += `<th style="padding:8px 10px;background:#f0e8f8;border:1px solid #ddd;text-align:left;">${h}</th>`;
    });

    const content = `
        <html><head><title>${data.title}</title>
        <style>
            body{font-family:Arial,sans-serif;padding:30px;}
            h1{color:#52297a;}
            table{width:100%;border-collapse:collapse;font-size:13px;}
            th{background:#f0e8f8;color:#52297a;}
            p{color:#666;margin-bottom:20px;}
        </style>
        </head><body>
        <h1>Ube Repository</h1>
        <h2 style="color:#52297a;">${data.title}</h2>
        <p>Printed on: ${new Date().toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'})}</p>
        <table><thead><tr>${headers}</tr></thead><tbody>${rows}</tbody></table>
        </body></html>
    `;

    const blob = new Blob([content], {type: 'text/html'});
    const url = URL.createObjectURL(blob);
    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed;top:0;left:0;width:0;height:0;border:none;visibility:hidden;';
    iframe.src = url;
    document.body.appendChild(iframe);
    iframe.onload = function() {
        iframe.contentWindow.print();
        iframe.contentWindow.onafterprint = function() {
            document.body.removeChild(iframe);
            URL.revokeObjectURL(url);
        };
    };
}

function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/researches/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endpush