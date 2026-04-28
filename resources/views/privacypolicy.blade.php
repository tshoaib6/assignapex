{{-- resources/views/apexassign-legal.blade.php --}}
@php
    // You can pass these from the controller or set here:
    $appName     = $appName ?? 'Apexassign';
// change to your real logo path
    $fallbackUrl = $fallbackUrl ?? url('/dashboard');             // fallback if history.back() is unavailable
    $lastUpdated = $lastUpdated ?? now()->format('d M Y'); // e.g., 12 Aug 2025
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $appName }} • Privacy Policy & Terms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
   <style>
    :root{
        /* Light theme + blue brand */
        --bg1:#ffffff; --bg2:#ffffff;
        --card:#ffffff;
        --stroke:#e5e7eb;
        --text:#0f172a;      /* slate-900 */
        --muted:#475569;     /* slate-600 */
        --accent:#2563eb;    /* blue-600 */
        --accent2:#1d4ed8;   /* blue-700 */
        --link:#1d4ed8;      /* blue-700 */
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
        margin:0;
        color:var(--text);
        font:16px/1.6 system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial;
        background:#fff;
    }
    a{color:var(--link); text-decoration:none}
    a:hover{text-decoration:underline}

    .wrap{max-width:1100px; margin:48px auto; padding:24px}
    .header{
        position:sticky; top:0; z-index:20; backdrop-filter:saturate(140%) blur(6px);
        background:#fff;
        border-bottom:1px solid var(--stroke);
    }
    .hdr-inner{max-width:1100px; margin:0 auto; padding:14px 24px; display:flex; align-items:center; gap:16px}
    .logo{display:flex; align-items:center; gap:10px; min-width:0}
    .logo img{height:36px; width:auto; display:block}
    .logo-name{font-weight:700; letter-spacing:.2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}

    .spacer{flex:1}
    .top-links a{margin-left:16px; font-weight:600; opacity:.9}
    .top-links a:hover{opacity:1}

    .back{
        display:inline-flex; align-items:center; gap:10px;
        padding:10px 14px; border:1px solid var(--stroke); border-radius:12px;
        background:#fff; cursor:pointer; font-weight:600;
        box-shadow:0 1px 0 rgba(0,0,0,.02);
    }
    .back svg{width:18px; height:18px}
    .back:focus{outline:2px solid var(--accent2); outline-offset:2px}

    .card{
        margin-top:24px; padding:28px;
        border:1px solid var(--stroke);
        border-radius:20px;
        background:var(--card);
        box-shadow:0 10px 30px rgba(2,6,23,.05);
    }

    /* Headings & text (no gradients; blue accents) */
    h1{
        margin:10px 0 16px;
        font-size:clamp(24px, 3.6vw, 40px);
        line-height:1.15; letter-spacing:.2px;
        background:none !important;
        -webkit-background-clip:initial; background-clip:initial;
        color:var(--text) !important;
    }
    h2{
        margin:22px 0 10px; font-size:clamp(18px, 2.2vw, 24px);
        color:#0b1220; border-left:4px solid var(--accent); padding-left:12px
    }
    h3{margin:18px 0 8px; font-size:18px; color:#0b1220}
    p{margin:8px 0; color:#334155}
    ul{margin:8px 0 8px 20px; padding:0}
    li{margin:6px 0; color:#334155}

    .meta{margin-top:18px; font-size:13px; color:#64748b; opacity:.95}
    .hr{
        height:1px; border:0; background:linear-gradient(90deg, transparent, var(--stroke), transparent);
        margin:24px 0;
    }

    /* Tags & jumps in blue */
    .jump{
        display:inline-flex; align-items:center; gap:8px; padding:10px 14px; margin-left:8px;
        border:1px solid var(--stroke); border-radius:12px; background:#fff;
    }
    .jump:hover{text-decoration:none; border-color:#cbd5e1}
    .tags{display:flex; flex-wrap:wrap; gap:10px; margin-top:10px}
    .tag{
        font-size:12px; padding:6px 10px; border:1px solid #bfdbfe;
        border-radius:999px; color:#1d4ed8; background:#eff6ff;
    }

    /* Reveal animations */
    .reveal{opacity:0; transform:translateY(14px); transition:opacity .6s ease, transform .6s ease}
    .reveal.show{opacity:1; transform:none}

    /* Print */
    @media print{
        body{background:#fff; color:#000}
        .header{position:static; background:#fff; border:none}
        .card{box-shadow:none; border:1px solid #ddd; background:#fff}
        a{color:#000 !important; text-decoration:underline}
        .back, .top-links{display:none}
    }
</style>

</head>
<body>
    <header class="header">
        <div class="hdr-inner">
            <button class="back" onclick="(history.length>1)?history.back():window.location.assign('{{ $fallbackUrl }}')" title="Go back">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </button>

           

            <div class="spacer"></div>

            <nav class="top-links" aria-label="In-page">
                <a class="jump" href="#privacy-policy">Privacy Policy</a>
                <a class="jump" href="#terms">Terms & Conditions</a>
            </nav>
        </div>
    </header>

    <main class="wrap">
        <article class="card">
            <h1 class="reveal">Apexassign Privacy Policy</h1>
            <div class="tags">
                <span class="tag">Compliant with Saudi PDPL</span>
                <span class="tag">Secure Processing</span>
                <span class="tag">Transparent Use</span>
            </div>

            <section id="privacy-policy" aria-labelledby="pp-intro">
                <h2 id="pp-intro" class="reveal">Introduction</h2>
                <p class="reveal">
                    Apexassign ("Assign System") is a digital platform developed by Apex Design to manage job assignments and tracking for drivers.
                    This Privacy Policy outlines how personal data is collected, used, shared, and protected in accordance with the
                    Saudi Personal Data Protection Law (PDPL) and related regulations.
                </p>

                <h3 class="reveal">Data Collection</h3>
                <p class="reveal">Apexassign collects the following personal data:</p>
                <ul>
                    <li class="reveal">Driver information (name, email, phone number)</li>
                    <li class="reveal">Location data (GPS coordinates)</li>
                    <li class="reveal">Job assignment data (job details, travel distance)</li>
                    <li class="reveal">Payment information (method, amount)</li>
                </ul>

                <h3 class="reveal">Legal Basis for Processing</h3>
                <p class="reveal">
                    Data is processed based on user consent, contractual necessity, and legitimate business interests, as permitted under the PDPL.
                </p>

                <h3 class="reveal">Data Use</h3>
                <p class="reveal">Collected data is used for:</p>
                <ul>
                    <li class="reveal">Assigning and tracking jobs</li>
                    <li class="reveal">Processing payments</li>
                    <li class="reveal">Calculating travel distances</li>
                    <li class="reveal">Improving system performance and user experience</li>
                </ul>

                <h3 class="reveal">Data Sharing</h3>
                <p class="reveal">Data may be shared with:</p>
                <ul>
                    <li class="reveal">Apex Design (system owner)</li>
                    <li class="reveal">Payment processors (for secure transactions)</li>
                    <li class="reveal">Authorized third-party service providers (for system maintenance)</li>
                </ul>
                <p class="reveal">
                    All third parties are contractually bound to comply with Saudi data protection standards.
                </p>

                <h3 class="reveal">International Data Transfers</h3>
                <p class="reveal">
                    Personal data will not be transferred outside Saudi Arabia unless compliant with PDPL provisions,
                    including standard contractual clauses and SDAIA guidelines.
                </p>

                <h3 class="reveal">Data Security</h3>
                <p class="reveal">
                    Apexassign implements technical and organizational measures to protect personal data from unauthorized access, disclosure,
                    alteration, or destruction, including encryption, access controls, and breach response protocols.
                </p>

                <h3 class="reveal">User Rights</h3>
                <p class="reveal">Users have the right to:</p>
                <ul>
                    <li class="reveal">Access their personal data</li>
                    <li class="reveal">Request correction or deletion</li>
                    <li class="reveal">Object to certain types of processing</li>
                    <li class="reveal">File complaints with the Saudi Data &amp; AI Authority (SDAIA)</li>
                </ul>
                <p class="reveal">Requests can be submitted via the platform’s support channel.</p>

                <h3 class="reveal">Policy Updates</h3>
                <p class="reveal">
                    This Privacy Policy may be updated to reflect legal or operational changes. Users will be notified of significant updates.
                </p>

                <div class="meta reveal">Last updated: {{ $lastUpdated }}</div>
            </section>

            <hr class="hr">

            <section id="terms" aria-labelledby="tc-intro">
                <h1 class="reveal">Apexassign Terms and Conditions</h1>

                <h2 id="tc-intro" class="reveal">Introduction</h2>
                <p class="reveal">
                    These Terms govern the use of Apexassign. By accessing or using the system, users agree to comply with these Terms and applicable Saudi laws.
                </p>

                <h3 class="reveal">Definitions</h3>
                <ul>
                    <li class="reveal"><strong>User:</strong> Any individual using Apexassign, including drivers and clients.</li>
                    <li class="reveal"><strong>Job:</strong> Assignments created and managed through the system.</li>
                </ul>

                <h3 class="reveal">System Use</h3>
                <p class="reveal">Users agree to:</p>
                <ul>
                    <li class="reveal">Use Apexassign for its intended purpose</li>
                    <li class="reveal">Provide accurate and lawful information</li>
                    <li class="reveal">Comply with Saudi regulations, including the PDPL and Anti-Cyber Crime Law</li>
                </ul>

                <h3 class="reveal">Job Assignments</h3>
                <ul>
                    <li class="reveal">Apexassign facilitates job assignments between clients and drivers.</li>
                    <li class="reveal">Drivers must complete assigned jobs responsibly.</li>
                    <li class="reveal">Clients must provide accurate job details.</li>
                </ul>

                <h3 class="reveal">Payment Terms</h3>
                <ul>
                    <li class="reveal">Payments are processed securely through Apexassign.</li>
                    <li class="reveal">Users agree to the platform’s payment terms and conditions.</li>
                </ul>

                <h3 class="reveal">Intellectual Property</h3>
                <ul>
                    <li class="reveal">Apexassign and its content are owned by Apex Design.</li>
                    <li class="reveal">Users may not reproduce, modify, or distribute content without written permission.</li>
                </ul>

                <h3 class="reveal">Disclaimer</h3>
                <ul>
                    <li class="reveal">Apexassign is provided "as-is" without warranties.</li>
                    <li class="reveal">Apex Design is not liable for damages resulting from system use, except as required by Saudi law.</li>
                </ul>

                <h3 class="reveal">Governing Law</h3>
                <p class="reveal">These Terms are governed by the laws of the Kingdom of Saudi Arabia.</p>

                <h3 class="reveal">Changes to Terms</h3>
                <p class="reveal">
                    Apex Design may update these Terms. Continued use of the system implies acceptance of the updated Terms.
                </p>

                <div class="meta reveal">Last updated: {{ $lastUpdated }}</div>
            </section>
        </article>
    </main>

    <script>
        // Reveal on scroll with staggered delays
        (function(){
            const els = Array.from(document.querySelectorAll('.reveal'));
            const obs = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if(entry.isIntersecting){
                        // Add a tiny stagger based on DOM order
                        const idx = els.indexOf(entry.target);
                        entry.target.style.transitionDelay = (idx % 8) * 60 + 'ms';
                        entry.target.classList.add('show');
                        obs.unobserve(entry.target);
                    }
                });
            }, {threshold: .12});
            els.forEach(el => obs.observe(el));
        })();
    </script>
</body>
</html>
