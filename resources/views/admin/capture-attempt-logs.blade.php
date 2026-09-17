@extends('layouts.admin')
@section('title', 'Capture Attempt Logs')
@section('page-title', 'Capture Attempt Logs')

@section('content')
@php
    $activeFilters = collect([
        request('search') ? 'Search: ' . request('search') : null,
        request('event_type') ? 'Event: ' . str_replace('_', ' ', ucfirst(request('event_type'))) : null,
        request('department') ? 'Department: ' . request('department') : null,
        request('date') ? 'Date: ' . \Carbon\Carbon::parse(request('date'))->format('M d, Y') : null,
    ])->filter()->values();
@endphp

<section class="cal-shell">
    <div class="cal-hero">
        <div class="cal-hero-copy">
            <span class="cal-kicker">Security Audit Trail</span>
            <h2>Protected viewer activity at a glance</h2>
            <p>Review viewer-level events, trace suspicious behavior, and isolate capture attempts without losing context.</p>
        </div>

        @if($activeFilters->isNotEmpty())
            <div class="cal-active-filters">
                @foreach($activeFilters as $filter)
                    <span class="cal-filter-chip">{{ $filter }}</span>
                @endforeach
            </div>
        @endif
    </div>

    <div class="cal-filter-card">
        <form method="GET" action="{{ route('super-admin.capture-attempt-logs') }}" class="cal-filter-form">
            <div class="cal-filter-group cal-filter-group-search">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Viewer, email, paper title">
            </div>

            <div class="cal-filter-group">
                <label>Event</label>
                <select name="event_type">
                    <option value="">All Events</option>
                    @foreach($eventTypes as $eventType)
                        <option value="{{ $eventType }}" {{ request('event_type') === $eventType ? 'selected' : '' }}>
                            {{ str_replace('_', ' ', ucfirst($eventType)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="cal-filter-group">
                <label>Department</label>
                <select name="department">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="cal-filter-group">
                <label>Date</label>
                <input type="date" name="date" value="{{ request('date') }}">
            </div>

            <div class="cal-filter-actions">
                <button type="submit" class="cal-btn cal-btn-primary">Apply Filters</button>
                <a href="{{ route('super-admin.capture-attempt-logs') }}" class="cal-btn cal-btn-ghost">Reset</a>
            </div>
        </form>
    </div>

    <div class="cal-summary-grid">
        <article class="cal-summary-card cal-summary-card-primary">
            <div class="cal-summary-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 12h18"/>
                    <path d="M7 8h10"/>
                    <path d="M9 16h6"/>
                </svg>
            </div>
            <div>
                <strong>{{ $logs->total() }}</strong>
                <span>Total logged attempts</span>
            </div>
        </article>

        <article class="cal-summary-card">
            <div class="cal-summary-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <strong>{{ $logs->getCollection()->pluck('viewer_name')->filter()->unique()->count() }}</strong>
                <span>Unique viewers on this page</span>
            </div>
        </article>

        <article class="cal-summary-card">
            <div class="cal-summary-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 6h16"/>
                    <path d="M4 12h16"/>
                    <path d="M4 18h10"/>
                </svg>
            </div>
            <div>
                <strong>{{ $logs->getCollection()->pluck('event_type')->filter()->unique()->count() }}</strong>
                <span>Event types on this page</span>
            </div>
        </article>
    </div>

    <div class="cal-card">
        @if($logs->isEmpty())
            <div class="cal-empty">
                <div class="cal-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </div>
                <strong>No capture attempt logs found.</strong>
                <span>Once protected viewer events are recorded, the audit trail will appear here.</span>
            </div>
        @else
            <div class="cal-table-wrap">
                <table class="cal-table">
                    <thead>
                        <tr>
                            <th>Viewer</th>
                            <th>Research</th>
                            <th>Event</th>
                            <th>Department</th>
                            <th>Logged At</th>
                            <th>Context</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            @php
                                $loggedAt = $log->created_at?->copy()->timezone('Asia/Manila');
                                $eventLabel = str_replace('_', ' ', ucfirst($log->event_type));
                                $details = $log->details ?? [];
                            @endphp
                            <tr>
                                <td>
                                    <div class="cal-viewer-name">{{ $log->viewer_name ?: 'Unknown Viewer' }}</div>
                                    <div class="cal-viewer-meta">{{ $log->viewer_email ?: 'No email recorded' }}</div>
                                    @if($log->user)
                                        <div class="cal-subline">Account: {{ $log->user->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="cal-research-title">
                                        @if($log->research)
                                            <a href="{{ route('admin.research.show', $log->research) }}">{{ $log->research->title }}</a>
                                        @else
                                            Deleted research record
                                        @endif
                                    </div>
                                    <div class="cal-viewer-meta">{{ $log->research?->author_name ?: 'Unknown author' }}</div>
                                </td>
                                <td>
                                    <span class="cal-pill">{{ $eventLabel }}</span>
                                    <div class="cal-subline">Scope: {{ ucfirst($log->viewer_scope) }}</div>
                                </td>
                                <td>
                                    <div class="cal-cell-title">{{ $log->viewer_department ?: ($log->research?->department ?: 'No department') }}</div>
                                    <div class="cal-viewer-meta">{{ $log->ip_address ?: 'No IP recorded' }}</div>
                                </td>
                                <td>
                                    <div class="cal-cell-title">{{ $loggedAt?->format('M d, Y') }}</div>
                                    <div class="cal-viewer-meta">{{ $loggedAt?->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="cal-context-line">Agent: {{ \Illuminate\Support\Str::limit($log->user_agent ?: 'Unavailable', 48) }}</div>
                                    <div class="cal-context-line">Key: {{ $details['key'] ?? 'n/a' }}</div>
                                    <div class="cal-context-line">Reason: {{ $details['reason'] ?? 'n/a' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cal-pagination">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</section>

<style>
.cal-shell{
    display:grid;
    gap:18px;
}

.cal-hero{
    position:relative;
    overflow:hidden;
    padding:28px 30px;
    border-radius:26px;
    background:
        radial-gradient(circle at top right, rgba(102,126,234,.18), transparent 30%),
        radial-gradient(circle at left bottom, rgba(236,72,153,.12), transparent 28%),
        linear-gradient(135deg, #ffffff 0%, #f8f4ff 52%, #f2edff 100%);
    border:1px solid rgba(132,92,214,.14);
    box-shadow:0 18px 44px rgba(69,26,122,.08);
}

.cal-kicker{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-bottom:12px;
    font-size:11px;
    font-weight:800;
    letter-spacing:.18em;
    text-transform:uppercase;
    color:#8d78bb;
}

.cal-kicker::before{
    content:"";
    width:30px;
    height:1px;
    background:linear-gradient(90deg, #6d28d9, transparent);
}

.cal-hero h2{
    margin:0 0 8px;
    font-size:30px;
    line-height:1.1;
    letter-spacing:-.04em;
    color:#1f123e;
}

.cal-hero p{
    max-width:720px;
    margin:0;
    color:#7f71a7;
    line-height:1.7;
    font-size:14px;
}

.cal-active-filters{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:20px;
}

.cal-filter-chip{
    display:inline-flex;
    align-items:center;
    padding:8px 13px;
    border-radius:999px;
    background:rgba(255,255,255,.84);
    border:1px solid rgba(157,128,220,.2);
    color:#5b3d8a;
    font-size:12px;
    font-weight:600;
    backdrop-filter:blur(8px);
}

.cal-filter-card,
.cal-card{
    background:#fff;
    border-radius:24px;
    border:1px solid rgba(120,87,183,.12);
    box-shadow:0 14px 34px rgba(51,23,92,.06);
}

.cal-filter-card{
    padding:22px;
}

.cal-filter-form{
    display:grid;
    grid-template-columns:minmax(240px, 1.8fr) repeat(3, minmax(150px, .9fr)) auto;
    gap:14px;
    align-items:end;
}

.cal-filter-group{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.cal-filter-group label{
    font-size:11px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.15em;
    color:#9a87bf;
}

.cal-filter-group input,
.cal-filter-group select{
    height:52px;
    padding:0 16px;
    border:1.5px solid #e8dcfb;
    border-radius:16px;
    background:linear-gradient(180deg, #fefcff 0%, #f8f4ff 100%);
    font-size:14px;
    color:#221248;
    font-family:inherit;
    width:100%;
    box-sizing:border-box;
    transition:border-color .16s ease, box-shadow .16s ease, transform .16s ease;
}

.cal-filter-group input:focus,
.cal-filter-group select:focus{
    outline:none;
    border-color:#7c3aed;
    box-shadow:0 0 0 4px rgba(124,58,237,.12);
    transform:translateY(-1px);
}

.cal-filter-actions{
    display:flex;
    gap:10px;
    align-items:center;
}

.cal-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:132px;
    height:52px;
    padding:0 20px;
    border-radius:18px;
    font-size:13px;
    font-weight:800;
    border:none;
    text-decoration:none;
    cursor:pointer;
    font-family:inherit;
    transition:transform .16s ease, box-shadow .16s ease, background .16s ease, color .16s ease;
}

.cal-btn:hover{
    transform:translateY(-1px);
}

.cal-btn-primary{
    background:linear-gradient(135deg, #5b21b6 0%, #43188e 100%);
    color:#fff;
    box-shadow:0 14px 24px rgba(91,33,182,.24);
}

.cal-btn-ghost{
    background:#fff;
    color:#7d69a8;
    border:1.5px solid #e9ddfb;
}

.cal-summary-grid{
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:14px;
}

.cal-summary-card{
    display:flex;
    align-items:center;
    gap:16px;
    padding:20px 22px;
    border-radius:22px;
    background:#fff;
    border:1px solid rgba(124,58,237,.1);
    box-shadow:0 12px 28px rgba(57,26,101,.05);
}

.cal-summary-card-primary{
    background:linear-gradient(135deg, #1f1146 0%, #34166c 100%);
    color:#fff;
}

.cal-summary-icon{
    display:grid;
    place-items:center;
    width:52px;
    height:52px;
    border-radius:18px;
    background:#f4edff;
    color:#6d28d9;
    flex-shrink:0;
}

.cal-summary-card-primary .cal-summary-icon{
    background:rgba(255,255,255,.14);
    color:#fff;
}

.cal-summary-card strong{
    display:block;
    margin-bottom:4px;
    font-size:32px;
    line-height:1;
    letter-spacing:-.05em;
    color:#1f123e;
}

.cal-summary-card span{
    font-size:13px;
    color:#8576a9;
}

.cal-summary-card-primary strong,
.cal-summary-card-primary span{
    color:#fff;
}

.cal-card{
    overflow:hidden;
}

.cal-table-wrap{
    overflow-x:auto;
}

.cal-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
}

.cal-table th{
    padding:18px 22px;
    background:linear-gradient(180deg, #faf7ff 0%, #f7f2ff 100%);
    color:#9885bd;
    font-size:11px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.16em;
    text-align:left;
    border-bottom:1px solid #efe7fb;
}

.cal-table td{
    padding:20px 22px;
    vertical-align:top;
    border-bottom:1px solid #f3ecfc;
    font-size:13px;
    color:#26164a;
    background:#fff;
}

.cal-table tbody tr:hover td{
    background:#fcfaff;
}

.cal-viewer-name,
.cal-cell-title,
.cal-research-title a{
    font-weight:800;
    color:#1f123e;
    text-decoration:none;
    line-height:1.5;
}

.cal-research-title a:hover{
    color:#6d28d9;
}

.cal-viewer-meta{
    margin-top:5px;
    color:#8e7faf;
    font-size:12px;
    line-height:1.55;
}

.cal-subline{
    margin-top:7px;
    color:#695489;
    font-size:12px;
    line-height:1.55;
}

.cal-pill{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width:92px;
    min-height:56px;
    padding:8px 14px;
    border-radius:999px;
    background:linear-gradient(135deg, #ede4ff 0%, #e6dcfb 100%);
    color:#6b21c9;
    font-size:12px;
    font-weight:800;
    line-height:1.35;
    text-align:center;
    box-shadow:inset 0 0 0 1px rgba(124,58,237,.08);
}

.cal-context-line{
    color:#816fa6;
    font-size:12px;
    line-height:1.6;
    word-break:break-word;
}

.cal-context-line + .cal-context-line{
    margin-top:4px;
}

.cal-empty{
    display:grid;
    place-items:center;
    gap:10px;
    padding:72px 24px;
    text-align:center;
    color:#8475a7;
}

.cal-empty-icon{
    display:grid;
    place-items:center;
    width:68px;
    height:68px;
    border-radius:22px;
    background:linear-gradient(135deg, #f4ecff 0%, #ece4ff 100%);
    color:#6d28d9;
}

.cal-empty strong{
    color:#241248;
    font-size:18px;
}

.cal-empty span{
    max-width:480px;
    font-size:14px;
    line-height:1.7;
}

.cal-pagination{
    padding:18px 22px 22px;
    background:#fff;
}

@media (max-width: 1180px){
    .cal-filter-form{
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .cal-filter-group-search,
    .cal-filter-actions{
        grid-column:auto;
    }

    .cal-summary-grid{
        grid-template-columns:1fr;
    }
}

@media (max-width: 700px){
    .cal-hero{
        padding:22px 20px;
        border-radius:22px;
    }

    .cal-hero h2{
        font-size:24px;
    }

    .cal-filter-card,
    .cal-card{
        border-radius:20px;
    }

    .cal-filter-card{
        padding:18px;
    }

    .cal-filter-form{
        grid-template-columns:1fr;
    }

    .cal-filter-actions{
        flex-direction:column;
    }

    .cal-btn{
        width:100%;
    }

    .cal-table th,
    .cal-table td{
        padding:16px;
    }
}
</style>
@endsection
