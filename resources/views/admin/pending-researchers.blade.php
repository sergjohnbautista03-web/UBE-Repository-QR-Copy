@extends('layouts.admin')
@section('title', 'Pending Researchers')
@section('page-title', 'Pending Researcher Approvals')

@section('content')

@if(session('success'))
    <div class="pr-flash" id="flashMsg">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- SEARCH BAR --}}
<form method="GET" class="pr-search-bar">
    <div class="pr-search-wrap">
        <span class="pr-search-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, or ID...">
    </div>
    <button type="submit" class="pr-btn pr-btn-primary">Search</button>
    <a href="{{ route('admin.pending-researchers') }}" class="pr-btn pr-btn-ghost">Clear</a>
</form>

{{-- CARD --}}
<div class="pr-card">
    <div class="pr-card-header">
        <div class="pr-card-header-left">
            <div class="pr-card-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="23 11 17 11"/><line x1="20" y1="8" x2="20" y2="14"/></svg>
            </div>
            <div>
                <h3 class="pr-card-title">Pending Approvals</h3>
                <p class="pr-card-sub">Review and approve researcher account requests</p>
            </div>
        </div>
        <span class="pr-count-badge">{{ $pendingResearchers->total() ?? $pendingResearchers->count() }} pending</span>
    </div>

    <div class="pr-table-wrap">
        <table class="pr-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student / Employee ID</th>
                    <th>Department</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingResearchers as $researcher)
                <tr>
                    <td>
                        <div class="pr-name-cell">
                            <div class="pr-avatar">{{ strtoupper(substr($researcher->name, 0, 1)) }}</div>
                            <span class="pr-name">{{ $researcher->name }}</span>
                        </div>
                    </td>
                    <td class="pr-email">{{ $researcher->email }}</td>
                    <td>
                        <span class="pr-id-badge">{{ $researcher->student_id ?? 'N/A' }}</span>
                    </td>
                    <td class="pr-dept">{{ $researcher->department ?? '—' }}</td>
                    <td class="pr-date">{{ $researcher->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="pr-actions-cell">
                            <form method="POST" action="{{ route('admin.approve-researcher', $researcher) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="pr-btn pr-btn-approve">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.reject-researcher', $researcher) }}"
                                onsubmit="return confirm('Reject and delete this account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pr-btn pr-btn-reject">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="pr-empty">
                        <div class="pr-empty-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="23 11 17 11"/></svg>
                        </div>
                        <p>No pending researcher approvals.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pendingResearchers->hasPages())
        <div class="pr-pagination">
            {{ $pendingResearchers->links() }}
        </div>
    @endif
</div>

<style>
/* FLASH */
.pr-flash {
    display: flex; align-items: center; gap: 10px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    border-radius: 12px; padding: 13px 18px;
    margin-bottom: 20px; font-size: 13.5px;
    color: #15803d; font-weight: 600;
}

/* SEARCH BAR */
.pr-search-bar {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 20px; flex-wrap: wrap;
}
.pr-search-wrap {
    position: relative; flex: 1; min-width: 240px;
}
.pr-search-icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #c0aee0; display: flex; align-items: center; pointer-events: none;
}
.pr-search-bar input {
    width: 100%; padding: 10px 14px 10px 38px;
    border: 1.5px solid #e8dff5; border-radius: 50px;
    background: #fff; font-size: 14px; color: #1a0638;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box; font-family: inherit;
}
.pr-search-bar input:focus {
    outline: none; border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,.1);
}

/* CARD */
.pr-card {
    background: #fff; border-radius: 20px;
    border: 1px solid rgba(107,47,160,.1);
    box-shadow: 0 4px 32px rgba(59,15,122,.06), 0 1px 4px rgba(0,0,0,.03);
    overflow: hidden;
}
.pr-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 28px;
    background: linear-gradient(160deg, #fdfbff 0%, #f8f4fe 100%);
    border-bottom: 1px solid #f0eaf9;
    flex-wrap: wrap; gap: 12px;
}
.pr-card-header-left { display: flex; align-items: center; gap: 14px; }
.pr-card-icon {
    width: 42px; height: 42px; border-radius: 11px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    color: #6b2fa0; flex-shrink: 0;
}
.pr-card-title { font-size: 16px; font-weight: 800; color: #1a0638; margin: 0 0 2px; letter-spacing: -.3px; }
.pr-card-sub { font-size: 12px; color: #a090bc; margin: 0; }
.pr-count-badge {
    padding: 5px 14px; background: #fef3c7;
    border: 1px solid #fde68a; border-radius: 50px;
    font-size: 12px; font-weight: 700; color: #92400e;
}

/* TABLE */
.pr-table-wrap { overflow-x: auto; }
.pr-table { width: 100%; border-collapse: collapse; }
.pr-table thead tr { border-bottom: 1px solid #f0eaf9; }
.pr-table th {
    padding: 12px 20px; font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #a090bc; text-align: left; background: #fdfbff;
}
.pr-table tbody tr {
    border-bottom: 1px solid #f9f6fe; transition: background .12s;
}
.pr-table tbody tr:last-child { border-bottom: none; }
.pr-table tbody tr:hover { background: #fdfbff; }
.pr-table td { padding: 14px 20px; vertical-align: middle; }

/* NAME */
.pr-name-cell { display: flex; align-items: center; gap: 10px; }
.pr-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: #f0eaf9; border: 1.5px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #6b2fa0; flex-shrink: 0;
}
.pr-name { font-size: 14px; font-weight: 700; color: #1a0638; }
.pr-email { font-size: 13.5px; color: #5b3d8a; }
.pr-dept { font-size: 13px; color: #6b5e8a; }
.pr-date { font-size: 13px; color: #a090bc; white-space: nowrap; }

/* ID BADGE */
.pr-id-badge {
    display: inline-block; padding: 4px 12px;
    background: #f3e8ff; border: 1px solid #e9d5ff;
    border-radius: 20px; font-size: 12.5px; font-weight: 700; color: #7c3aed;
}

/* ACTION BUTTONS */
.pr-actions-cell { display: flex; gap: 8px; }
.pr-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 50px;
    font-size: 12.5px; font-weight: 700;
    cursor: pointer; border: none; text-decoration: none;
    transition: all .14s ease; font-family: inherit;
}
.pr-btn-primary {
    background: #3b0f7a; color: #fff;
    box-shadow: 0 3px 12px rgba(59,15,122,.25);
}
.pr-btn-primary:hover { background: #2d0a5e; transform: translateY(-1px); }
.pr-btn-ghost {
    background: #fff; color: #8b7aaa; border: 1.5px solid #e8dff5;
}
.pr-btn-ghost:hover { background: #f4f0fc; color: #3b0f7a; }
.pr-btn-approve {
    background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;
}
.pr-btn-approve:hover { background: #dcfce7; }
.pr-btn-reject {
    background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;
}
.pr-btn-reject:hover { background: #fee2e2; }

/* EMPTY */
.pr-empty { text-align: center; padding: 48px 20px !important; color: #c0aee0; }
.pr-empty-icon {
    width: 56px; height: 56px; border-radius: 16px;
    background: #f4f0fc; border: 1px solid #e8dff5;
    display: flex; align-items: center; justify-content: center;
    color: #c0aee0; margin: 0 auto 12px;
}
.pr-empty p { margin: 0; font-size: 14px; }

/* PAGINATION */
.pr-pagination { padding: 16px 24px; border-top: 1px solid #f0eaf9; }
</style>

@push('scripts')
<script>
    setTimeout(function() {
        const el = document.getElementById('flashMsg');
        if (el) { el.style.transition = 'opacity 0.5s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }
    }, 3000);
</script>
@endpush

@endsection