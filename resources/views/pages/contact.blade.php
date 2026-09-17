@extends('layouts.app')
@section('title', 'Contact — Ube Repository')

@section('content')

<div class="ct-root">

{{-- HERO --}}
<div class="ct-hero">
    <div class="ct-hero-grid"></div>
    <div class="ct-glow ct-glow-1"></div>
    <div class="ct-glow ct-glow-2"></div>
    <div class="ct-glow ct-glow-3"></div>
    <div class="ct-hero-inner">
        <div class="ct-eyebrow"><span class="ct-eyebrow-dot"></span> Reach Out</div>
        <h1>Contact Us</h1>
        <p>Get in touch with the Ube Repository team</p>
    </div>
</div>

{{-- BODY --}}
<div class="ct-body">
    <div class="ct-grid">

        {{-- Left: Contact Info --}}
        <div class="ct-card ct-info">
            <div class="ct-card-label">Contact Information</div>
            <h2>Get In Touch</h2>
            <p>Have questions, concerns, or feedback about the Ube Repository? We'd love to hear from you. Reach us through any of the following channels.</p>

            <div class="ct-cards">
                <div class="contact-card">
                    <div class="ct-card-icon-wrap ct-icon-loc">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <strong>Address</strong>
                        <p>Philippine College of Science and Technology<br>Calasiao, Pangasinan, Philippines 2418</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="ct-card-icon-wrap ct-icon-mail">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <strong>Email</strong>
                        <p>philcstreg@yahoo.com</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="ct-card-icon-wrap ct-icon-phone">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.23h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.82a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <strong>Phone</strong>
                        <p>(075) 522-8032<br>Monday – Friday, 8AM – 5PM</p>
                    </div>
                </div>
                <div class="contact-card">
                    <div class="ct-card-icon-wrap ct-icon-web">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <div>
                        <strong>Website</strong>
                        <p>www.philcst.edu.ph</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Map --}}
        <div class="ct-card ct-map-panel">
            <div class="ct-card-label">Location</div>
            <h2>Our Location</h2>
            <p>Visit us at the Philippine College of Science and Technology in Calasiao, Pangasinan.</p>
            <div class="map-wrap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.123456789!2d120.4167!3d15.9167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3391c4b0b0b0b0b1%3A0x0!2sPhilippine+College+of+Science+and+Technology!5e0!3m2!1sen!2sph!4v1234567890"
                    width="100%" height="100%"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <a href="https://maps.google.com/?q=Philippine+College+of+Science+and+Technology+Calasiao+Pangasinan"
               target="_blank" class="ct-maps-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Open in Google Maps
            </a>
        </div>

    </div>
</div>
</div>

<style>
.ct-root { font-family: var(--font-body); background: #eeeaf6; min-height: 100vh; }

/* ── HERO ── */
.ct-hero {
    position: relative;
    background: #3b0f7a;
    overflow: hidden;
    padding: 52px 44px 56px;
    text-align: center;
}
.ct-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
    background-size: 38px 38px;
}
.ct-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.ct-glow-1 { width: 500px; height: 500px; background: #7c3aed; opacity: .22; top: -200px; right: -100px; }
.ct-glow-2 { width: 280px; height: 280px; background: #a855f7; opacity: .15; bottom: -80px; left: 18%; }
.ct-glow-3 { width: 180px; height: 180px; background: #6d28d9; opacity: .18; top: 10px; left: -50px; }
.ct-hero-inner { position: relative; z-index: 2; }
.ct-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600;
    color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 14px;
}
.ct-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #c084fc; }
.ct-hero h1 {
    font-family: var(--font-body);
    font-size: 40px; font-weight: 700;
    color: white; margin-bottom: 10px;
    letter-spacing: -.6px; line-height: 1.1;
}
.ct-hero p { color: rgba(255,255,255,.55); font-size: 15px; }

/* ── BODY ── */
.ct-body { max-width: 1100px; margin: 0 auto; padding: 44px 40px 64px; }
.ct-grid {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 24px; align-items: start;
}

/* ── CARDS ── */
.ct-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(109,40,217,.08);
    padding: 32px;
    box-shadow: 0 1px 3px rgba(46,16,101,.04), 0 4px 20px rgba(46,16,101,.08);
    position: relative; overflow: hidden;
}
.ct-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #7c3aed, #a855f7, #c084fc);
}
.ct-card-label {
    display: inline-flex; align-items: center;
    font-size: 10.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px;
    color: var(--purple-main); background: var(--purple-ghost);
    border: 1px solid var(--purple-pale);
    padding: 3px 11px; border-radius: 50px; margin-bottom: 14px;
}
.ct-card h2 {
    font-family: var(--font-head); font-size: 22px;
    color: var(--purple-deep); margin-bottom: 8px;
}
.ct-card > p {
    font-size: 13.5px; color: var(--muted); line-height: 1.65; margin-bottom: 22px;
}

/* ── CONTACT CARDS ── */
.ct-cards { display: flex; flex-direction: column; gap: 12px; }
.contact-card {
    display: flex; gap: 14px; align-items: flex-start;
    background: var(--purple-ghost);
    border: 1px solid rgba(109,40,217,.08);
    border-radius: 12px; padding: 16px 18px;
    transition: transform .2s, box-shadow .2s;
}
.contact-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(46,16,101,.1); }
.ct-card-icon-wrap {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ct-icon-loc   { background: #ede9fe; color: #5b21b6; }
.ct-icon-mail  { background: #dbeafe; color: #1e40af; }
.ct-icon-phone { background: #d1fae5; color: #065f46; }
.ct-icon-web   { background: #fef3c7; color: #92400e; }
.contact-card strong {
    display: block; font-size: 11px; font-weight: 700;
    color: var(--purple-deep); margin-bottom: 3px;
    text-transform: uppercase; letter-spacing: .5px;
}
.contact-card p { font-size: 13.5px; color: var(--text); line-height: 1.6; margin: 0; }

/* ── MAP ── */
.ct-map-panel {}
.map-wrap {
    width: 100%; height: 360px;
    border-radius: 12px; overflow: hidden;
    border: 1px solid rgba(109,40,217,.1);
    margin-bottom: 14px;
}
.ct-maps-btn {
    display: inline-flex; align-items: center; gap: 8px;
    width: 100%; justify-content: center;
    background: var(--purple-main); color: white;
    font-weight: 700; font-size: 13.5px;
    padding: 12px 24px; border-radius: 50px;
    text-decoration: none; transition: all .2s;
    box-shadow: 0 4px 16px rgba(107,47,160,.3);
}
.ct-maps-btn:hover { background: var(--purple-deep); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(107,47,160,.4); color: white; }

@media(max-width: 768px) {
    .ct-hero  { padding: 36px 20px 40px; }
    .ct-hero h1 { font-size: 28px; }
    .ct-body  { padding: 24px 16px 48px; }
    .ct-grid  { grid-template-columns: 1fr; }
    .map-wrap { height: 260px; }
}
</style>

@endsection