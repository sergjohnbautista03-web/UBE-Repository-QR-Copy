@extends('layouts.app')
@section('title', 'FAQ — Ube Repository')

@section('content')

<div class="faq-root">

{{-- HERO --}}
<div class="faq-hero">
    <div class="faq-hero-grid"></div>
    <div class="faq-glow faq-glow-1"></div>
    <div class="faq-glow faq-glow-2"></div>
    <div class="faq-glow faq-glow-3"></div>
    <div class="faq-hero-inner">
        <div class="faq-eyebrow"><span class="faq-eyebrow-dot"></span> Help Center</div>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about the Ube Repository</p>
    </div>
</div>

{{-- BODY --}}
<div class="faq-body">

    <div class="faq-category">
        <div class="faq-cat-header">
            <span class="faq-cat-icon"></span>
            <h2 class="faq-cat-title">Account & Registration</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Who can register an account?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>Anyone from the PhilCST community can register. There are two account types: <strong>Regular User</strong> (A Regular User can browse and search research papers, view research details and abstracts, and view PDF files directly in the browser without the ability to download them. They can also pin research papers for later reference and filter research by department, year, and other criteria.) and
                        <strong>Researcher</strong> (on the other hand, has all the capabilities of a Regular User, with additional privileges. Researchers can submit their own research for administrative review and approval.).</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Is registration free?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>Yes, registration is completely free for all PhilCST students, faculty, and staff.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    What is the difference between a Regular User, a Researcher, and an External User in the system?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p><strong>Regular User</strong> — refers to a PhilCST student or a registered user within the institution who has not been verified as a researcher. They can browse, search, view research details and abstracts, and access the full document within the system, but are restricted from downloading PDF files and submitting research papers.</p>

                    <p style="margin-top:8px"><strong>Researcher</strong> — refers to a verified PhilCST student, faculty member, or employee who has provided a valid Student or Employee ID during registration. This verification grants them extended privileges, including the ability to download research papers in PDF format and submit their own research outputs for administrative review and approval.</p>

                    <p style="margin-top:8px"><strong>External Users</strong> — refers to visitors or non-PhilCST individuals who can only browse publicly available information. They are limited to viewing research titles, details, and abstracts, without access to the full document.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="faq-category">
        <div class="faq-cat-header">
            <span class="faq-cat-icon"></span>
            <h2 class="faq-cat-title">Submitting Research</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    How do I submit a research paper?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>You must be registered as a <strong>Researcher</strong> to submit. Once logged in, click <strong>"Submit Research"</strong> from the navigation menu. Fill in all required fields including title, abstract, department, year, and upload your file.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    What file formats are accepted?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>The following file formats are accepted: <strong>PDF</strong>. The maximum file size is <strong>30MB</strong>.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    How long does the approval process take?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>After submission, your research will be reviewed by an administrator. The review process typically takes <strong>3–5 business days</strong>. You can check the status of your submission anytime in <strong>"My Submissions"</strong>.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    What happens if my submission is rejected?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>If your submission is rejected, you will see the rejection reason in your <strong>"My Submissions"</strong> page. You may revise your paper based on the feedback and resubmit.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="faq-category">
        <div class="faq-cat-header">
            <span class="faq-cat-icon"></span>
            <h2 class="faq-cat-title">Browsing & Downloading</h2>
        </div>
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    Do I need an account to browse research?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>No. Anyone can browse and search for research papers without an account. However, you need to be logged in to <strong>download</strong> papers and use the <strong>pin</strong> feature.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    How do I search for a specific research paper?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>Use the search bar on the left sidebar of the home page. You can search by <strong>keyword, title, author</strong>, and filter by <strong>department, research type, and year range</strong>.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFaq(this)">
                    What is the Pin feature?
                    <span class="faq-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6,9 12,15 18,9"/></svg>
                    </span>
                </button>
                <div class="faq-answer">
                    <p>The Pin feature allows you to bookmark research papers you find interesting. Pinned papers are saved to your <strong>"Pinned Research"</strong> list for quick access later. You need to be logged in to use this feature.</p>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<style>
.faq-root { font-family: var(--font-body); background: #eeeaf6; min-height: 100vh; }

/* ── HERO ── */
.faq-hero {
    position: relative;
    background: #3b0f7a;
    overflow: hidden;
    padding: 52px 44px 56px;
    text-align: center;
}
.faq-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
    background-size: 38px 38px;
}
.faq-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.faq-glow-1 { width: 500px; height: 500px; background: #7c3aed; opacity: .22; top: -200px; right: -100px; }
.faq-glow-2 { width: 280px; height: 280px; background: #a855f7; opacity: .15; bottom: -80px; left: 18%; }
.faq-glow-3 { width: 180px; height: 180px; background: #6d28d9; opacity: .18; top: 10px; left: -50px; }
.faq-hero-inner { position: relative; z-index: 2; }
.faq-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600;
    color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: 1.2px;
    margin-bottom: 14px;
}
.faq-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #c084fc; }
.faq-hero h1 {
    font-family: var(--font-body);
    font-size: 40px; font-weight: 700;
    color: white; margin-bottom: 10px;
    letter-spacing: -.6px; line-height: 1.1;
}
.faq-hero p { color: rgba(255,255,255,.55); font-size: 15px; }

/* ── BODY ── */
.faq-body { max-width: 820px; margin: 0 auto; padding: 44px 40px 64px; display: flex; flex-direction: column; gap: 20px; }

/* ── CATEGORY ── */
.faq-category {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(109,40,217,.08);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(46,16,101,.04), 0 4px 20px rgba(46,16,101,.08);
    position: relative;
}
.faq-category::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #7c3aed, #a855f7, #c084fc);
}
.faq-cat-header {
    display: flex; align-items: center; gap: 12px;
    padding: 22px 28px 16px;
    border-bottom: 1px solid rgba(109,40,217,.08);
}
.faq-cat-icon {
    width: 40px; height: 40px; border-radius: 11px;
    background: var(--purple-ghost);
    border: 1px solid var(--purple-pale);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
}
.faq-cat-title {
    font-family: var(--font-head);
    font-size: 19px; color: var(--purple-deep);
    margin: 0;
}

.faq-list { padding: 12px 16px 16px; display: flex; flex-direction: column; gap: 8px; }

/* ── FAQ ITEM ── */
.faq-item {
    border: 1px solid rgba(109,40,217,.07);
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow .2s;
    background: var(--purple-ghost);
}
.faq-item:has(.faq-question.open) {
    background: white;
    box-shadow: 0 2px 12px rgba(46,16,101,.08);
    border-color: rgba(109,40,217,.15);
}

.faq-question {
    width: 100%; padding: 15px 20px;
    background: none; border: none;
    text-align: left; font-family: var(--font-body);
    font-size: 14.5px; font-weight: 600;
    color: var(--purple-deep); cursor: pointer;
    display: flex; justify-content: space-between; align-items: center;
    gap: 12px; transition: background .15s;
}
.faq-question:hover { background: var(--purple-pale); }
.faq-question.open { background: white; color: var(--purple-main); }
.faq-arrow {
    display: flex; align-items: center;
    color: var(--purple-main);
    transition: transform .3s; flex-shrink: 0;
}
.faq-question.open .faq-arrow { transform: rotate(180deg); }

.faq-answer {
    display: none;
    padding: 0 20px 16px;
    border-top: 1px solid rgba(109,40,217,.08);
    background: white;
}
.faq-answer p {
    font-size: 14px; color: var(--text);
    line-height: 1.75; padding-top: 14px;
}

@media(max-width: 768px) {
    .faq-hero  { padding: 36px 20px 40px; }
    .faq-hero h1 { font-size: 28px; }
    .faq-body  { padding: 24px 16px 48px; gap: 16px; }
    .faq-cat-header { padding: 18px 20px 14px; }
    .faq-list  { padding: 10px 12px 14px; }
    .faq-question { font-size: 14px; padding: 13px 16px; }
}
</style>

@push('scripts')
<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = btn.classList.contains('open');
    document.querySelectorAll('.faq-question.open').forEach(q => {
        q.classList.remove('open');
        q.nextElementSibling.style.display = 'none';
    });
    if (!isOpen) {
        btn.classList.add('open');
        answer.style.display = 'block';
    }
}
</script>
@endpush

@endsection