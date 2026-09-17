@extends('layouts.admin')
@section('title', 'Department Keys')
@section('page-title', 'Department Access Keys')

@section('content')
<div class="dk-grid">
    @if($canManageKeys)
        <section class="dk-card">
            <div class="dk-card-head">
                <div>
                    <h2>Create New Key</h2>
                    <p>Admins can rotate keys whenever a semester expires.</p>
                </div>
                <button type="button" class="dk-btn-primary dk-btn-toggle" onclick="toggleDepartmentKeyForm()">
                    <span id="dkToggleLabel">{{ $errors->any() ? 'Hide Form' : 'Create Key' }}</span>
                </button>
            </div>

            <div id="departmentKeyFormWrap" class="dk-form-wrap {{ $errors->any() ? 'is-open' : '' }}">
                @if($errors->any())
                    <div class="dk-alert">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.department-keys.store') }}" class="dk-form">
                    @csrf
                    <div class="dk-field">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="">Select department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}" {{ old('department') === $department ? 'selected' : '' }}>{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="dk-row">
                        <div class="dk-field">
                            <label for="semester">Semester</label>
                            <select id="semester" name="semester" required>
                                <option value="">Select semester</option>
                                <option value="1st Sem" {{ old('semester') === '1st Sem' ? 'selected' : '' }}>1st Sem</option>
                                <option value="2nd Sem" {{ old('semester') === '2nd Sem' ? 'selected' : '' }}>2nd Sem</option>
                            </select>
                        </div>
                        <div class="dk-field">
                            <label for="school_year">School Year</label>
                            <input type="text" id="school_year" name="school_year" value="{{ old('school_year') }}" placeholder="2026-2027" required>
                        </div>
                    </div>

                    <div class="dk-row">
                        <div class="dk-field">
                            <label for="access_key">Access Key</label>
                            <input type="text" id="access_key" name="access_key" value="{{ old('access_key') }}" placeholder="Enter new department key" required>
                        </div>
                        <div class="dk-field">
                            <label for="expires_at">Expires At</label>
                            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="dk-btn-primary">Save Department Key</button>
                </form>
            </div>
        </section>
    @else
        <section class="dk-card">
            <div class="dk-card-head">
                <div>
                    <h2>Assigned Department Key</h2>
                    <p>Below are the keys assigned by the main admin for {{ $adminDepartment }}.</p>
                </div>
            </div>
            <div class="dk-readonly-note">
                You can view your department key here, but only the main admin can create, rotate, activate, or deactivate keys.
            </div>
        </section>
    @endif

    <section class="dk-card">
        <div class="dk-card-head">
            <div>
                <h2>Existing Keys</h2>
                <p>Only one key per department can stay active at a time.</p>
            </div>
            <span class="dk-badge">{{ $departmentKeys->total() }} record(s)</span>
        </div>

        <form method="GET" class="dk-filter-bar">
            <div class="dk-filter-group">
                <label for="filter_department">Department</label>
                @if($canManageKeys)
                    <select id="filter_department" name="department">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ $selectedDepartment === $department ? 'selected' : '' }}>{{ $department }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="dk-fixed-filter">{{ $selectedDepartment ?? $adminDepartment }}</div>
                @endif
            </div>

            @if($canManageKeys)
                <div class="dk-filter-actions">
                    <button type="submit" class="dk-btn-primary dk-btn-filter">Filter</button>
                    @if(filled($selectedDepartment))
                        <a href="{{ route('admin.department-keys') }}" class="dk-btn-secondary dk-btn-link">Clear</a>
                    @endif
                </div>
            @endif
        </form>

        <div class="dk-table-wrap">
            <table class="dk-table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>School Year</th>
                        <th>Access Key</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>{{ $canManageKeys ? 'Action' : 'Access' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departmentKeys as $key)
                        <tr>
                            <td>{{ $key->department }}</td>
                            <td>{{ $key->semester }}</td>
                            <td>{{ $key->school_year }}</td>
                            <td>
                                @if($key->access_key_plain)
                                    <span class="dk-key-pill">{{ $key->access_key_plain }}</span>
                                @else
                                    <span class="dk-key-missing">Sent by email only</span>
                                @endif
                            </td>
                            <td>{{ $key->expires_at->format('M d, Y') }}</td>
                            <td>
                                @if($key->is_active && $key->expires_at->isFuture())
                                    <span class="dk-status dk-status-active">Active</span>
                                @elseif($key->is_active)
                                    <span class="dk-status dk-status-expired">Expired</span>
                                @else
                                    <span class="dk-status dk-status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($canManageKeys)
                                    <div class="dk-actions">
                                        <form method="POST" action="{{ route('admin.department-keys.toggle', $key) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dk-btn-secondary">
                                                {{ $key->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.department-keys.delete', $key) }}"
                                              onsubmit="return confirm('Delete this department key?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dk-btn-danger">Delete</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="dk-view-only">View Only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="dk-empty">No department keys yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($departmentKeys->hasPages())
            <div class="dk-pagination">
                {{ $departmentKeys->links('vendor.pagination.custom') }}
            </div>
        @endif
    </section>
</div>

<style>
.dk-grid{display:grid;grid-template-columns:minmax(320px,420px) minmax(0,1fr);gap:22px;align-items:start}
.dk-card{background:#fff;border:1px solid #ebe4f7;border-radius:20px;padding:24px;box-shadow:0 10px 30px rgba(59,15,122,.05)}
.dk-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:18px}
.dk-card-head h2{margin:0;font-size:20px;color:#2f144f}
.dk-card-head p{margin:6px 0 0;color:#7d6f98;font-size:13px}
.dk-badge{background:#f3ecff;color:#6a35a1;border:1px solid #dfcff8;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:700}
.dk-alert{margin-bottom:16px;padding:12px 14px;border-radius:12px;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;font-size:13px}
.dk-form-wrap{display:none;margin-top:4px}
.dk-form-wrap.is-open{display:block}
.dk-form{display:flex;flex-direction:column;gap:14px}
.dk-filter-bar{display:flex;justify-content:space-between;align-items:end;gap:14px;flex-wrap:wrap;margin:-2px 0 18px}
.dk-filter-group{display:flex;flex-direction:column;gap:6px;min-width:min(100%,340px)}
.dk-filter-group label{font-size:12px;font-weight:700;color:#5b3d8a;text-transform:uppercase;letter-spacing:.4px}
.dk-filter-group select,.dk-fixed-filter{width:100%;padding:11px 13px;border-radius:14px;border:1.5px solid #dccafb;background:#faf8ff;font-size:14px;color:#210b3d;min-height:46px}
.dk-filter-group select:focus{outline:none;border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.1);background:#fff}
.dk-fixed-filter{display:flex;align-items:center;background:#f5efff;color:#5f3890;font-weight:700}
.dk-filter-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.dk-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.dk-field label{display:block;margin-bottom:6px;font-size:12px;font-weight:700;color:#5b3d8a;text-transform:uppercase;letter-spacing:.4px}
.dk-field input,.dk-field select{width:100%;padding:11px 13px;border-radius:12px;border:1.5px solid #e8dff5;background:#faf8ff;font-size:14px;color:#210b3d}
.dk-field input:focus,.dk-field select:focus{outline:none;border-color:#7c3aed;box-shadow:0 0 0 3px rgba(124,58,237,.1);background:#fff}
.dk-btn-primary,.dk-btn-secondary{border:none;border-radius:12px;padding:11px 14px;font-weight:700;cursor:pointer}
.dk-btn-primary{background:#42127f;color:#fff}
.dk-btn-toggle{min-width:124px;white-space:nowrap}
.dk-btn-filter{min-width:104px}
.dk-btn-secondary{background:#f4effd;color:#5f3890;border:1px solid #e0d2f5}
.dk-btn-link{display:inline-flex;align-items:center;justify-content:center;text-decoration:none;min-height:44px}
.dk-btn-danger{border:none;border-radius:12px;padding:11px 14px;font-weight:700;cursor:pointer;background:#fff1f2;color:#be123c;border:1px solid #fecdd3}
.dk-readonly-note{padding:14px 16px;border-radius:14px;background:#f8f4ff;border:1px solid #e2d5f4;color:#6b5e8a;font-size:14px;line-height:1.6}
.dk-table-wrap{overflow:auto}
.dk-table{width:100%;border-collapse:collapse;font-size:14px}
.dk-table th{padding:12px 14px;text-align:left;color:#6f6189;background:#faf7ff;font-size:12px;text-transform:uppercase;letter-spacing:.5px}
.dk-table td{padding:14px;border-top:1px solid #f0e9fb;color:#2f144f;vertical-align:middle}
.dk-key-pill{display:inline-flex;padding:6px 10px;border-radius:999px;background:#efe7ff;border:1px solid #d8c6fb;color:#5f3890;font-weight:700;font-size:12px}
.dk-key-missing{color:#8d80a5;font-size:12px}
.dk-status{display:inline-flex;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700}
.dk-status-active{background:#dcfce7;color:#166534}
.dk-status-expired{background:#fee2e2;color:#b91c1c}
.dk-status-inactive{background:#e5e7eb;color:#4b5563}
.dk-actions{display:flex;align-items:center;gap:8px;flex-wrap:nowrap}
.dk-actions form{margin:0;flex:1 1 0}
.dk-actions .dk-btn-secondary,.dk-actions .dk-btn-danger{width:100%;min-width:104px;padding:9px 12px;font-size:13px;line-height:1;white-space:nowrap}
.dk-view-only{display:inline-flex;align-items:center;justify-content:center;min-width:96px;min-height:38px;padding:8px 16px;border-radius:999px;background:#f4f0fc;border:1px solid #e2d5f4;color:#8b7aaa;font-size:12px;font-weight:700;line-height:1;text-align:center;box-sizing:border-box}
.dk-empty{text-align:center;color:#8d80a5;padding:32px 16px}
.dk-pagination{margin-top:18px}
@media (max-width: 1040px){.dk-grid{grid-template-columns:1fr}.dk-row{grid-template-columns:1fr}}
@media (max-width: 700px){.dk-actions{flex-wrap:wrap}.dk-actions form{flex:1 1 100%}.dk-filter-bar{align-items:stretch}.dk-filter-group{min-width:100%}.dk-filter-actions{width:100%}.dk-filter-actions .dk-btn-primary,.dk-filter-actions .dk-btn-link{flex:1 1 0}}
</style>

@push('scripts')
<script>
function toggleDepartmentKeyForm() {
    const wrap = document.getElementById('departmentKeyFormWrap');
    const label = document.getElementById('dkToggleLabel');
    const isOpen = wrap.classList.toggle('is-open');

    label.textContent = isOpen ? 'Hide Form' : 'Create Key';
}
</script>
@endpush
@endsection
