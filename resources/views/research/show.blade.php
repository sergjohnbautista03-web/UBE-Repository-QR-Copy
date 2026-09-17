@extends('layouts.app')
@section('title', $research->title . ' — Ube Repository')

@section('content')
<div class="page-container page-narrow">
    <div class="breadcrumb-nav">
        <a href="{{ route('home') }}">Home</a>
        <span>›</span>
        <a href="{{ route('research.department', rawurlencode($research->department)) }}">{{ $research->department }}</a>
        <span>›</span>
        <span>{{ Str::limit($research->title, 50) }}</span>
    </div>

    <div class="rd-card">

        {{-- HEADER --}}
        <div class="rd-header">
            <div class="rd-header-top">
                <span class="type-badge type-{{ $research->type }}">{{ $research->getTypeLabel() }}</span>
                <div class="rd-stats">
                    <span class="rd-stat">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        {{ number_format($research->view_count) }} views
                    </span>
                    <span class="rd-stat">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        {{ number_format($research->download_count) }} downloads
                    </span>
                </div>
            </div>

            <h1 class="rd-title">{{ $research->title }}</h1>

            <div class="rd-meta">
                <div class="rd-meta-item">
                    <span class="rd-meta-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <span class="rd-meta-val">{{ $research->author_name }}</span>
                </div>
                <div class="rd-meta-sep">·</div>
                <div class="rd-meta-item">
                    <span class="rd-meta-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </span>
                    <span class="rd-meta-val">{{ $research->department }}</span>
                </div>
                <div class="rd-meta-sep">·</div>
                <div class="rd-meta-item">
                    <span class="rd-meta-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </span>
                    <span class="rd-meta-val">{{ $research->year_published }}</span>
                </div>
            </div>

            @if($research->keywords)
                <div class="rd-keywords">
                    @foreach(explode(',', $research->keywords) as $kw)
                        <span class="keyword-chip">{{ trim($kw) }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ABSTRACT --}}
        <div class="rd-body">
            <div class="rd-section-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Abstract
            </div>
            <p class="abstract-text">{{ $research->abstract }}</p>

            @if($research->program)
                <div class="rd-program-tag">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    {{ $research->program }}
                </div>
            @endif
        </div>

        {{-- ACTIONS --}}
        <div class="rd-actions">
            @auth
                @if(auth()->user()->isAdmin())
                    {{-- ── ADMIN ── --}}
                    @if($research->file_path)
                        <button type="button" onclick="toggleViewer()" class="rd-btn rd-btn-outline" id="viewerBtn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            View Full Paper
                        </button>

                        <a href="{{ route('admin.research.download-file', $research) }}" class="rd-btn rd-btn-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Download
                        </a>
                    @endif

                @else
                    {{-- ── RESEARCHER / USER / STUDENT ── --}}
                    @php $isPinned = auth()->user()->pinnedResearches->contains($research->id); @endphp

                    <button type="button" class="rd-btn rd-btn-pin {{ $isPinned ? 'pinned' : '' }}"
                            id="pinBtn" onclick="togglePin({{ $research->id }}, this)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $isPinned ? 'Pinned' : 'Pin This' }}
                    </button>

                    @if($research->file_path)
                        <button type="button" onclick="toggleViewer()" class="rd-btn rd-btn-outline" id="viewerBtn">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            View Full Paper
                        </button>

                        @if(auth()->id() === $research->user_id)
                            <a href="{{ route('research.download', $research) }}" class="rd-btn rd-btn-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download
                            </a>
                        @else
                            <button type="button" class="rd-btn rd-btn-disabled">
                                🔒 Download restricted
                            </button>
                        @endif
                    @endif
                @endif

            @else
                {{-- ── GUEST ── --}}
                <a href="{{ route('login') }}" class="rd-btn rd-btn-primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Login to View
                </a>
            @endauth

            <a href="{{ route('home') }}" class="rd-btn rd-btn-ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Back
            </a>
        </div>

        {{-- FILE VIEWER — ALL AUTHENTICATED USERS WITH FILE ACCESS --}}
        @auth
        @if($research->file_path)
        <div id="fileViewer" style="display:none; border-top:1px solid var(--border);">
            <div class="rd-viewer-bar">
                <span class="rd-viewer-name">{{ $research->file_name }}</span>
                <div style="display:flex; gap:8px;">
                    @if(auth()->user()->isAdmin())
                        <button type="button" onclick="printFile()" class="btn btn-sm btn-outline">Print</button>
                    @endif
                    <button type="button" onclick="toggleViewer()" class="btn btn-sm btn-ghost">Close</button>
                </div>
            </div>
            <iframe
                id="pdfFrame"
                src="{{ auth()->user()->isAdmin()
                    ? route('admin.research.view-file', $research)
                    : route('research.view-file', $research) }}"
                style="width:100%; height:80vh; border:none; display:block;"
                allowfullscreen>
            </iframe>
        </div>
        @endif
        @endauth

        {{-- LOGIN NOTICE --}}
        @guest
        <div class="rd-login-notice">
            <div class="rd-login-notice-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <p>
                <a href="{{ route('login') }}">Sign in</a> or
                <a href="{{ route('register') }}">create an account</a>
                to view this research paper and access all features.
            </p>
        </div>
        @endguest

    </div>
</div>

<style>
/* ── CARD ──────────────────────────────────────── */
.rd-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid rgba(107,47,160,.1);
    box-shadow: 0 4px 32px rgba(59,15,122,.06), 0 1px 4px rgba(0,0,0,.04);
    overflow: hidden;
    margin-bottom: 32px;
}

/* ── HEADER ─────────────────────────────────────── */
.rd-header {
    padding: 32px 36px 28px;
    border-bottom: 1px solid #f0eaf9;
    background: linear-gradient(160deg, #fdfbff 0%, #f8f4fe 100%);
}
.rd-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 10px;
}
.rd-stats { display: flex; gap: 16px; }
.rd-stat {
    display: flex; align-items: center; gap: 5px;
    font-size: 12.5px; color: #a090bc; font-weight: 500;
}
.rd-title {
    font-family: var(--font-body);
    font-size: 26px; font-weight: 800;
    color: #1a0638; line-height: 1.3;
    letter-spacing: -.4px; margin-bottom: 18px;
}
.rd-meta {
    display: flex; align-items: center;
    flex-wrap: wrap; gap: 6px; margin-bottom: 16px;
}
.rd-meta-item { display: flex; align-items: center; gap: 6px; font-size: 13.5px; color: #5b3d8a; }
.rd-meta-icon { color: #b09ad4; display: flex; align-items: center; }
.rd-meta-val { font-weight: 600; }
.rd-meta-sep { color: #d4c5ed; font-size: 14px; }
.rd-keywords { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
.keyword-chip {
    display: inline-flex; align-items: center;
    padding: 4px 12px; background: #f0eaf9;
    border: 1px solid #e0d4f5; border-radius: 20px;
    font-size: 12px; font-weight: 600; color: #6b2fa0; letter-spacing: .1px;
}

/* ── BODY ────────────────────────────────────────── */
.rd-body { padding: 28px 36px; border-bottom: 1px solid #f0eaf9; }
.rd-section-label {
    display: flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: 1px; color: #6b2fa0; margin-bottom: 14px;
}
.abstract-text { font-size: 14.5px; color: #3d2b5c; line-height: 1.85; }
.rd-program-tag {
    display: inline-flex; align-items: center; gap: 7px;
    margin-top: 18px; padding: 7px 14px;
    background: #f4f0fc; border: 1px solid #e5d9f8;
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #5b3d8a;
}

/* ── ACTIONS ─────────────────────────────────────── */
.rd-actions {
    display: flex; align-items: center;
    gap: 10px; padding: 22px 36px; flex-wrap: wrap;
}
.rd-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 20px; border-radius: 50px;
    font-size: 13.5px; font-weight: 700;
    cursor: pointer; text-decoration: none; border: none;
    transition: all .16s ease; white-space: nowrap;
}
.rd-btn-primary { background: #3b0f7a; color: #fff; box-shadow: 0 3px 14px rgba(59,15,122,.28); }
.rd-btn-primary:hover { background: #2d0a5e; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,15,122,.38); color: #fff; }
.rd-btn-outline { background: #fff; color: #3b0f7a; border: 1.5px solid #d4c5ed; }
.rd-btn-outline:hover { background: #f4f0fc; border-color: #7c3aed; }
.rd-btn-ghost { background: transparent; color: #8b7aaa; border: 1.5px solid #e8dff5; }
.rd-btn-ghost:hover { background: #f4f0fc; color: #3b0f7a; }
.rd-btn-pin { background: #fff; color: #6b2fa0; border: 1.5px solid #d4c5ed; }
.rd-btn-pin:hover { background: #f4f0fc; border-color: #7c3aed; }
.rd-btn-pin.pinned { background: #3b0f7a; color: #fff; border-color: #3b0f7a; box-shadow: 0 3px 14px rgba(59,15,122,.28); }
.rd-btn-disabled { background: #f4f0fc; color: #b09ad4; border: 1.5px solid #e8dff5; cursor: not-allowed; opacity: .75; }

/* ── VIEWER BAR ──────────────────────────────────── */
.rd-viewer-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 24px; background: #faf8ff; border-bottom: 1px solid #f0eaf9;
}
.rd-viewer-name { font-size: 13px; font-weight: 600; color: #5b3d8a; }

/* ── LOGIN NOTICE ────────────────────────────────── */
.rd-login-notice {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 36px; background: #faf8ff; border-top: 1px solid #f0eaf9;
}
.rd-login-notice-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: #f0eaf9; border: 1px solid #e2d5f4;
    display: flex; align-items: center; justify-content: center;
    color: #6b2fa0; flex-shrink: 0;
}
.rd-login-notice p { font-size: 13.5px; color: #7a6a9a; margin: 0; }
.rd-login-notice a { color: #6b2fa0; font-weight: 700; text-decoration: none; }
.rd-login-notice a:hover { text-decoration: underline; }

/* ── RESPONSIVE ──────────────────────────────────── */
@media (max-width: 640px) {
    .rd-header, .rd-body, .rd-actions { padding-left: 20px; padding-right: 20px; }
    .rd-title { font-size: 20px; }
    .rd-meta { gap: 4px; }
    .rd-meta-sep { display: none; }
    .rd-meta-item { font-size: 12.5px; }
    .rd-actions { gap: 8px; }
    .rd-btn { padding: 9px 16px; font-size: 13px; }
}
</style>

@push('scripts')
<script>
function toggleViewer() {
    const viewer = document.getElementById('fileViewer');
    const btn = document.getElementById('viewerBtn');

    if (!viewer || !btn) return;

    if (viewer.style.display === 'none' || viewer.style.display === '') {
        viewer.style.display = 'block';
        btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Close Paper';
        viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        viewer.style.display = 'none';
        btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> View Full Paper';
    }
}

function printFile() {
    const frame = document.getElementById('pdfFrame');
    if (frame && frame.contentWindow) {
        frame.contentWindow.focus();
        frame.contentWindow.print();
    }
}
</script>
@endpush

@endsection