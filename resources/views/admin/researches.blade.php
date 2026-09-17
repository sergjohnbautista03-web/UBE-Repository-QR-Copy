@extends('layouts.admin')
@section('title', 'Manage Researches')
@section('page-title', 'Manage Researches')

@section('content')

<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
    <a href="{{ route('admin.add-research') }}" class="btn btn-primary">+ Add Research</a>
</div>

<!-- Filters -->
<form method="GET" class="filter-bar">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or author..." class="filter-input">
    <select name="status" class="filter-select">
        <option value="">All Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>
    <select name="department" class="filter-select">
        <option value="">All Departments</option>
        @foreach($departments as $dept)
            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('admin.researches') }}" class="btn btn-ghost">Clear</a>
</form>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($researches as $r)
                <tr>
                    <td><a href="{{ route('admin.research.show', $r) }}" class="table-link">{{ Str::limit($r->title, 50) }}</a></td>
                    <td>{{ $r->author_name }}</td>
                    <td>{{ Str::limit($r->department, 28) }}</td>
                    <td><span class="type-badge type-{{ $r->type }} badge-sm">{{ $r->getTypeLabel() }}</span></td>
                    <td>{{ $r->year_published }}</td>
                    <td><span class="status-badge status-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                    <td>{{ number_format($r->view_count) }}</td>
                    <td class="action-cell" style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
    <a href="{{ route('admin.research.show', $r) }}" class="btn-action-view">View</a>
    @if($r->status == 'pending')
        <form method="POST" action="{{ route('admin.research.approve', $r) }}" style="display:contents">
            @csrf
            <button type="submit" class="btn-action-approve">✓ Approve</button>
        </form>
        <button type="button" class="btn-action-reject" onclick="openRejectModal({{ $r->id }})">✕ Reject</button>
    @endif
    <form method="POST" action="{{ route('admin.research.delete', $r) }}" style="display:contents"
          onsubmit="return confirm('Delete this research permanently?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-action-delete">Delete</button>
    </form>
</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted" style="padding:30px">No researches found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $researches->links() }}</div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <h3>Reject Submission</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Reason for Rejection</label>
                <textarea name="reason" rows="4" placeholder="Explain why..." required style="width:100%;padding:10px;border:2px solid #e8dff5;border-radius:6px;margin-top:8px;"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:15px">
                <button type="button" class="btn btn-ghost" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-red">Reject</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/researches/' + id + '/reject';
    document.getElementById('rejectModal').style.display = 'flex';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}
</script>
@endpush