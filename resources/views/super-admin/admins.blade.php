@extends('layouts.admin')
@section('title', 'Manage Admins')
@section('page-title', 'Manage Admins')

@section('content')

{{-- TOP BAR --}}
<div class="ma-topbar">
    <div>
        <p class="ma-topbar-desc">Manage all administrator accounts in the system.</p>
    </div>
    <a href="{{ route('super-admin.create-admin') }}" class="ma-add-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Admin
    </a>
</div>

{{-- CARD --}}
<div class="ma-card">
    <div class="ma-card-header">
        <div class="ma-card-header-left">
            <div class="ma-card-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <h3 class="ma-card-title">Super Administrator Accounts</h3>
                <p class="ma-card-sub">Full system access — manage with care</p>
            </div>
        </div>
        <span class="ma-count-badge">{{ $admins->count() }} admin(s)</span>
    </div>

    <div class="ma-table-wrap">
        <table class="ma-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>
                        <div class="ma-name-cell">
                            <div class="ma-avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</div>
                            <div>
                                <span class="ma-name">{{ $admin->name }}</span>
                                @if($admin->is_super_admin)
                                    <span class="ma-super-badge">Super Admin</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="ma-email">{{ $admin->email }}</td>
                    <td>
                        @if($admin->is_active)
                            <span class="ma-status ma-status-active">
                                <span class="ma-status-dot"></span> Active
                            </span>
                        @else
                            <span class="ma-status ma-status-inactive">
                                <span class="ma-status-dot"></span> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="ma-date">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td>
                        @if(!$admin->is_super_admin)
                        <div class="ma-actions-cell">
                            <form method="POST" action="{{ route('super-admin.toggle-admin', $admin) }}">
                                @csrf
                                <button type="submit" class="ma-btn {{ $admin->is_active ? 'ma-btn-amber' : 'ma-btn-green' }}">
                                    {{ $admin->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('super-admin.delete-admin', $admin) }}"
                                onsubmit="return confirm('Are you sure you want to delete this admin account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ma-btn ma-btn-red">Delete</button>
                            </form>
                        </div>
                        @else
                            <span class="ma-protected">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Protected
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="ma-empty">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <p>No admin accounts found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* TOP BAR */
.ma-topbar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px;
}
.ma-topbar-desc { font-size: 13.5px; color: var(--muted); margin: 0; }
.ma-add-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px;
    background: #3b0f7a; color: #fff;
    font-size: 13.5px; font-weight: 700;
    border-radius: 50px; text-decoration: none; border: none;
    box-shadow: 0 4px 16px rgba(59,15,122,.28);
    transition: all .16s ease;
}
.ma-add-btn:hover {
    background: #2d0a5e; transform: translateY(-1px);
    box-shadow: 0 7px 22px rgba(59,15,122,.38); color: #fff;
}

/* CARD */
.ma-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(107,47,160,.1);
    box-shadow: 0 4px 32px rgba(59,15,122,.06), 0 1px 4px rgba(0,0,0,.03);
    overflow: hidden;
}
.ma-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 22px 28px;
    background: linear-gradient(160deg, #fdfbff 0%, #f8f4fe 100%);
    border-bottom: 1px solid #f0eaf9;
    flex-wrap: wrap; gap: 12px;
}
.ma-card-header-left { display: flex; align-items: center; gap: 14px; }
.ma-card-icon {
    width: 42px; height: 42px; border-radius: 11px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    color: #6b2fa0; flex-shrink: 0;
}
.ma-card-title { font-size: 16px; font-weight: 800; color: #1a0638; margin: 0 0 2px; letter-spacing: -.3px; }
.ma-card-sub { font-size: 12px; color: #a090bc; margin: 0; }
.ma-count-badge {
    padding: 5px 14px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    border-radius: 50px; font-size: 12px; font-weight: 700; color: #6b2fa0;
}

/* TABLE */
.ma-table-wrap { overflow-x: auto; }
.ma-table {
    width: 100%; border-collapse: collapse;
}
.ma-table thead tr {
    border-bottom: 1px solid #f0eaf9;
}
.ma-table th {
    padding: 12px 20px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .7px;
    color: #a090bc; text-align: left;
    background: #fdfbff;
}
.ma-table tbody tr {
    border-bottom: 1px solid #f9f6fe;
    transition: background .12s;
}
.ma-table tbody tr:last-child { border-bottom: none; }
.ma-table tbody tr:hover { background: #fdfbff; }
.ma-table td { padding: 14px 20px; vertical-align: middle; }

/* NAME CELL */
.ma-name-cell { display: flex; align-items: center; gap: 10px; }
.ma-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: #f0eaf9; border: 1.5px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 800; color: #6b2fa0;
    flex-shrink: 0;
}
.ma-name { font-size: 14px; font-weight: 700; color: #1a0638; display: block; }
.ma-super-badge {
    display: inline-block; margin-top: 3px;
    background: #fef3c7; color: #92400e;
    font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    border: 1px solid #fde68a;
}
.ma-email { font-size: 13.5px; color: #5b3d8a; }
.ma-date { font-size: 13px; color: #a090bc; }

/* STATUS */
.ma-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12.5px; font-weight: 700;
}
.ma-status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.ma-status-active {
    background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d;
}
.ma-status-active .ma-status-dot { background: #22c55e; }
.ma-status-inactive {
    background: #f9fafb; border: 1px solid #e5e7eb; color: #9ca3af;
}
.ma-status-inactive .ma-status-dot { background: #d1d5db; }

/* ACTION BUTTONS */
.ma-actions-cell { display: flex; gap: 8px; }
.ma-btn {
    display: inline-flex; align-items: center;
    padding: 7px 14px; border-radius: 50px;
    font-size: 12.5px; font-weight: 700;
    cursor: pointer; border: none; transition: all .14s ease;
}
.ma-btn-amber { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.ma-btn-amber:hover { background: #fde68a; }
.ma-btn-green { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.ma-btn-green:hover { background: #bbf7d0; }
.ma-btn-red { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.ma-btn-red:hover { background: #fecaca; }

/* PROTECTED */
.ma-protected {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12.5px; color: #a090bc; font-weight: 600;
}

/* EMPTY STATE */
.ma-empty {
    text-align: center; padding: 48px 20px !important;
    color: #c0aee0;
}
.ma-empty svg { margin: 0 auto 12px; display: block; }
.ma-empty p { margin: 0; font-size: 14px; }
</style>

@endsection