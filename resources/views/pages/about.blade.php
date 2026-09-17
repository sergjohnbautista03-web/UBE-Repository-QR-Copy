@extends('layouts.app')
@section('title', 'About — Ube Repository')

@section('content')

@php
$colleges = [
    'College of Accountancy and Business Education' => [
        'icon' => '',
        'description' => 'Prepares students for careers in business, accounting, and hospitality through rigorous academic training and practical experience.',
        'program' => [
            'Accountancy' => 'Covers financial accounting, auditing, taxation, and management advisory services preparing students for the CPA board exam.',
            'Business Administration-Marketing Mngt.' => 'Focuses on marketing strategies, consumer behavior, brand management, and business development.',
            'Hospitality Management' => 'Trains students in hotel operations, food and beverage management, and hospitality industry practices.',
            'Tourism Management' => 'Covers travel and tour operations, tourism planning, and sustainable tourism development.',
        ],
    ],
    'College of Computer Studies' => [
        'icon' => '',
        'description' => 'Produces competent IT professionals equipped with the latest knowledge in computing, software development, and information systems.',
        'program' => [
            'Computer Science' => 'Focuses on algorithms, software engineering, artificial intelligence, and advanced computing concepts.',
            'Information Technology' => 'Covers network administration, database management, web development, and IT infrastructure.',
        ],
    ],
    'College of Criminal Justice Education' => [
        'icon' => '',
        'description' => 'Trains future law enforcement officers, criminologists, and justice professionals dedicated to public safety and crime prevention.',
        'program' => [
            'Criminology' => 'Studies crime, criminal behavior, law enforcement, forensic science, and the criminal justice system.',
        ],
    ],
    'College of Education' => [
        'icon' => '',
        'description' => 'Develops highly competent and values-driven educators committed to delivering quality education at all levels.',
        'program' => [
            'Elementary Education' => 'Prepares teachers for grades 1-6, covering curriculum development, child psychology, and teaching strategies.',
            'Secondary Education-General Science' => 'Focuses on science education methodologies, preparing teachers for high school science subjects.',
        ],
    ],
    'College of Engineering' => [
        'icon' => '',
        'description' => 'Produces world-class engineers equipped with technical knowledge and practical skills to address real-world engineering challenges.',
        'program' => [
            'Civil Engineering' => 'Covers structural design, construction management, geotechnical engineering, and infrastructure development.',
            'Computer Engineering' => 'Combines hardware and software engineering, covering embedded systems, digital electronics, and computer architecture.',
            'Electrical Engineering' => 'Focuses on power systems, electrical machines, control systems, and renewable energy technologies.',
            'Electronics Engineering' => 'Covers analog and digital electronics, telecommunications, microprocessors, and signal processing.',
            'Mechanical Engineering' => 'Studies thermodynamics, fluid mechanics, machine design, and manufacturing processes.',
        ],
    ],
    'College of Maritime Studies' => [
        'icon' => '',
        'description' => 'Trains future maritime professionals and seafarers with the technical expertise and competence required by international maritime standards.',
        'program' => [
            'Marine Engineering' => 'Covers marine diesel engines, ship systems, naval architecture, and maritime safety operations.',
            'Transportation' => 'Focuses on navigation, ship handling, maritime law, and transport logistics management.',
        ],
    ],
];
@endphp

{{-- ══ HERO ══ --}}
<div class="ab-hero">
    <div class="ab-hero-grid"></div>
    <div class="ab-glow ab-glow-1"></div>
    <div class="ab-glow ab-glow-2"></div>
    <div class="ab-glow ab-glow-3"></div>
    <div class="ab-hero-inner">
        <div class="ab-eyebrow"><span class="ab-eyebrow-dot"></span> Philippine College of Science and Technology</div>
        <h1>About Ube Repository</h1>
        <p>The official online research portal of PhilCST — preserving knowledge, empowering research</p>
    </div>
</div>

{{-- ══ BODY ══ --}}
<div class="ab-body">

    {{-- What is Ube Repository --}}
    <div class="ab-card ab-what">
        <div class="ab-card-label">Overview</div>
        <div class="ab-section-content">
            <h2>What is Ube Repository?</h2>
            <p>Ube Repository is the official digital research portal of the <strong>Philippine College of Science and Technology (PhilCST)</strong>, located in Calasiao, Pangasinan, Philippines.</p>
            <p>Our mission is to promote a culture of research and academic excellence by making research outputs accessible to the entire PhilCST community and beyond.</p>
        </div>
    </div>

    {{-- Mission & Vision --}}
    <div class="ab-mv-grid">
        <div class="ab-mv-card">
            <div class="ab-mv-icon"></div>
            <h3>Mission</h3>
            <p>To provide a reliable, accessible, and organized platform for the preservation and dissemination of research outputs produced by the PhilCST academic community, fostering a culture of inquiry and knowledge sharing.</p>
        </div>
        <div class="ab-mv-card">
            <div class="ab-mv-icon"></div>
            <h3>Vision</h3>
            <p>To become the leading academic research repository in the region, recognized for its comprehensive collection, ease of access, and contribution to evidence-based learning and innovation in science and technology.</p>
        </div>
    </div>

    {{-- Features --}}
    <div class="ab-card">
        <div class="ab-card-label">Features</div>
        <h2 class="ab-section-heading">What You Can Do</h2>
        <div class="ab-features-grid">

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Search & Browse</h4>
                <p>Search thousands of research papers by keyword, department, year, or type.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Search by title, author, abstract, or keyword</li>
                        <li>Filter by department, college, or year range</li>
                        <li>Browse all research by college and program</li>
                        <li>Sort by most viewed or most recent</li>
                    </ul>
                </div>
            </div>

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Submit Research</h4>
                <p>Researchers can submit their work for review and publication on the portal.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Fill in research details — title, abstract, keywords</li>
                        <li>Select your college and program</li>
                        <li>Submitted research goes through admin review before publishing</li>
                    </ul>
                </div>
            </div>

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Pin & Save</h4>
                <p>Save your favorite research papers for quick access anytime.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Pin any research paper with one click</li>
                        <li>Access all pinned papers from your Pinned Papers page</li>
                        <li>Unpin anytime when no longer needed</li>
                        <li>Available for all registered users</li>
                    </ul>
                </div>
            </div>

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Download Papers</h4>
                <p>Download approved research papers directly from the platform.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Researchers can download full PDF files</li>
                        <li>Regular users can view papers in browser</li>
                        <li>All downloads are tracked for analytics</li>
                        <li>Files are securely stored and managed</li>
                    </ul>
                </div>
            </div>

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Browse by Department</h4>
                <p>Explore research outputs organized by college and department.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Browse all 6 colleges of PhilCST</li>
                        <li>Filter by specific program within each college</li>
                        <li>See how many papers exist per department and program</li>
                        <li>Quickly find research relevant to your field</li>
                    </ul>
                </div>
            </div>

            <div class="ab-feature" onclick="toggleFeature(this)">
                <span class="ab-feature-icon-wrap"></span>
                <h4>Quality Assured</h4>
                <p>All submissions are reviewed and approved by system administrators.</p>
                <div class="feature-expand">
                    <ul>
                        <li>Every submission is reviewed by an admin before publishing</li>
                        <li>Rejected submissions receive a reason for rejection</li>
                        <li>Researchers are notified of approval status</li>
                        <li>Ensures only quality research is published</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    {{-- Colleges & Departments --}}
    <div class="ab-card">
        <div class="ab-card-label">Colleges</div>
        <h2 class="ab-section-heading">Colleges & Departments</h2>
        <p class="ab-section-sub">Click a college to view its programs</p>

        <div class="colleges-accordion">
            @foreach($colleges as $college => $data)
            <div class="college-item" id="college-{{ $loop->index }}">
                <button class="college-header" onclick="toggleCollege({{ $loop->index }})">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div class="ab-college-icon-wrap">{{ $data['icon'] }}</div>
                        <div style="text-align:left;">
                            <div style="font-weight:700; font-size:15px; color:var(--purple-deep);">{{ $college }}</div>
                            <div style="font-size:12px; color:var(--muted); margin-top:2px;">{{ count($data['program']) }} {{ count($data['program']) == 1 ? 'program' : 'programs' }}</div>
                        </div>
                    </div>
                    <span class="college-chevron" id="chevron-{{ $loop->index }}">▾</span>
                </button>
                <div class="college-body" id="body-{{ $loop->index }}" style="display:none;">
                    <p class="ab-college-desc">{{ $data['description'] }}</p>
                    <div class="courses-grid">
                        @foreach($data['program'] as $course => $desc)
                        <div class="course-card">
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:8px;">
                                <h4 style="font-family:var(--font-head); font-size:15px; color:var(--purple-deep); margin:0;">{{ $course }}</h4>
                                <a href="{{ route('research.course', [$college, $course]) }}" class="ab-view-link">
                                    View Research →
                                </a>
                            </div>
                            <p style="font-size:13px; color:var(--muted); line-height:1.6; margin:0;">{{ $desc }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

<style>
/* ── ROOT ── */
.ab-body { max-width: 980px; margin: 0 auto; padding: 44px 40px 64px; display: flex; flex-direction: column; gap: 24px; }

/* ── HERO ── */
.ab-hero {
    position: relative;
    background: #3b0f7a;
    overflow: hidden;
    padding: 52px 44px 56px;
    text-align: center;
}
.ab-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
    background-size: 38px 38px;
}
.ab-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.ab-glow-1 { width: 500px; height: 500px; background: #7c3aed; opacity: .22; top: -200px; right: -100px; }
.ab-glow-2 { width: 280px; height: 280px; background: #a855f7; opacity: .15; bottom: -80px; left: 18%; }
.ab-glow-3 { width: 180px; height: 180px; background: #6d28d9; opacity: .18; top: 10px; left: -50px; }
.ab-hero-inner { position: relative; z-index: 2; }
.ab-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600;
    color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: 1.2px;
    margin-bottom: 14px;
}
.ab-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #c084fc; }
.ab-hero h1 {
    font-family: var(--font-body);
    font-size: 42px; font-weight: 700;
    color: white; margin-bottom: 10px;
    letter-spacing: -.6px; line-height: 1.1;
}
.ab-hero p { color: rgba(255,255,255,.55); font-size: 15px; max-width: 520px; margin: 0 auto; line-height: 1.6; }

/* ── CARDS ── */
.ab-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(109,40,217,.08);
    padding: 36px 40px;
    box-shadow: 0 1px 3px rgba(46,16,101,.04), 0 4px 20px rgba(46,16,101,.08);
    position: relative;
    overflow: hidden;
}
.ab-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #7c3aed, #a855f7, #c084fc);
}
.ab-card-label {
    display: inline-flex;
    align-items: center;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    color: var(--purple-main);
    background: var(--purple-ghost);
    border: 1px solid var(--purple-pale);
    padding: 3px 11px; border-radius: 50px;
    margin-bottom: 16px;
}
.ab-section-content h2,
.ab-section-heading {
    font-family: var(--font-head);
    font-size: 26px; color: var(--purple-deep);
    margin-bottom: 14px; letter-spacing: -.3px;
}
.ab-section-content p {
    color: var(--text); line-height: 1.8; font-size: 15px; margin-bottom: 12px;
}
.ab-section-sub {
    text-align: center; color: var(--muted);
    font-size: 13.5px; margin-top: -8px; margin-bottom: 28px;
}

/* ── WHAT CARD ── */
.ab-what .ab-section-content { max-width: 780px; }

/* ── MISSION & VISION ── */
.ab-mv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.ab-mv-card {
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(109,40,217,.08);
    border-top: 4px solid var(--purple-main);
    padding: 28px 30px;
    box-shadow: 0 1px 3px rgba(46,16,101,.04), 0 4px 16px rgba(46,16,101,.07);
    transition: transform .2s, box-shadow .2s;
}
.ab-mv-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(46,16,101,.12); }
.ab-mv-icon { font-size: 32px; margin-bottom: 14px; display: block; }
.ab-mv-card h3 {
    font-family: var(--font-head); font-size: 21px;
    color: var(--purple-deep); margin-bottom: 10px;
}
.ab-mv-card p { color: var(--text); line-height: 1.75; font-size: 14px; }

/* ── FEATURES ── */
.ab-features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.ab-feature {
    background: var(--purple-ghost);
    border-radius: 14px;
    border: 1px solid var(--purple-pale);
    padding: 22px 20px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
}
.ab-feature:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(46,16,101,.1); border-color: var(--purple-light); }
.ab-feature.active {
    border-color: var(--purple-main);
    border-width: 2px;
    background: white;
    box-shadow: 0 4px 20px rgba(107,47,160,.12);
}
.ab-feature-icon { font-size: 28px; display: block; margin-bottom: 10px; }
.ab-feature h4 {
    font-family: var(--font-head); font-size: 15px;
    color: var(--purple-deep); margin-bottom: 7px;
}
.ab-feature p { font-size: 13px; color: var(--muted); line-height: 1.6; }
.feature-expand {
    display: none; margin-top: 14px; padding-top: 14px;
    border-top: 1px solid var(--border); text-align: left;
}
.feature-expand ul { padding-left: 18px; margin: 0; }
.feature-expand ul li { font-size: 13px; color: var(--text); line-height: 1.8; margin-bottom: 4px; }

/* ── COLLEGES ACCORDION ── */
.colleges-accordion { display: flex; flex-direction: column; gap: 10px; }
.college-item {
    background: var(--purple-ghost);
    border: 1px solid rgba(109,40,217,.1);
    border-radius: 14px; overflow: hidden;
    transition: box-shadow .2s;
}
.college-item:hover { box-shadow: 0 4px 16px rgba(46,16,101,.08); }
.college-header {
    width: 100%; padding: 16px 22px;
    display: flex; align-items: center; justify-content: space-between;
    background: none; border: none; cursor: pointer;
    transition: background .2s; font-family: var(--font-body);
}
.college-header:hover { background: var(--purple-pale); }
.ab-college-icon-wrap {
    width: 44px; height: 44px; border-radius: 12px;
    background: white; border: 1px solid var(--purple-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(107,47,160,.08);
}
.college-chevron { font-size: 16px; color: var(--purple-main); transition: transform .3s; }
.college-body { padding: 0 22px 22px; }
.ab-college-desc { font-size: 14px; color: var(--muted); margin-bottom: 18px; line-height: 1.7; }
.courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
.course-card {
    background: white;
    border: 1px solid rgba(109,40,217,.08);
    border-radius: 12px; padding: 16px;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 1px 4px rgba(46,16,101,.05);
}
.course-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(46,16,101,.1); }
.ab-view-link {
    flex-shrink: 0; font-size: 11px; font-weight: 700;
    background: var(--purple-pale); color: var(--purple-main);
    padding: 4px 10px; border-radius: 20px;
    text-decoration: none; white-space: nowrap;
    transition: all .2s;
}
.ab-view-link:hover { background: var(--purple-main); color: white; }

/* ── RESPONSIVE ── */
@media(max-width: 768px) {
    .ab-hero  { padding: 36px 20px 40px; }
    .ab-hero h1 { font-size: 28px; }
    .ab-body  { padding: 24px 16px 48px; gap: 18px; }
    .ab-card  { padding: 24px 20px; }
    .ab-mv-grid { grid-template-columns: 1fr; }
    .ab-features-grid { grid-template-columns: 1fr; }
    .courses-grid { grid-template-columns: 1fr; }
}
</style>

@push('scripts')
<script>
function toggleFeature(card) {
    const expand = card.querySelector('.feature-expand');
    const isOpen = expand.style.display === 'block';
    document.querySelectorAll('.feature-expand').forEach(e => e.style.display = 'none');
    document.querySelectorAll('.ab-feature').forEach(c => c.classList.remove('active'));
    if (!isOpen) {
        expand.style.display = 'block';
        card.classList.add('active');
    }
}

function toggleCollege(index) {
    const body = document.getElementById('body-' + index);
    const chevron = document.getElementById('chevron-' + index);
    if (body.style.display === 'none') {
        body.style.display = 'block';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        body.style.display = 'none';
        chevron.style.transform = 'rotate(0deg)';
    }
}
</script>
@endpush

@endsection