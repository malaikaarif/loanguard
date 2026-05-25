<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoanGuard — AI Loan Risk Analyzer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }

        /* NAV */
        .navbar { background: rgba(26,26,46,0.97); backdrop-filter: blur(10px); padding: 16px 0; }
        .navbar-brand span { color: #e94560; }

        /* HERO */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex; align-items: center; position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(233,69,96,0.15) 0%, transparent 70%);
            top: -100px; right: -100px; border-radius: 50%;
        }
        .hero::after {
            content: ''; position: absolute; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(9,132,227,0.1) 0%, transparent 70%);
            bottom: -50px; left: -50px; border-radius: 50%;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(233,69,96,0.15); border: 1px solid rgba(233,69,96,0.3);
            color: #e94560; padding: 6px 16px; border-radius: 20px;
            font-size: 13px; font-weight: 600; margin-bottom: 24px;
        }
        .hero h1 { font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; color: #fff; line-height: 1.15; }
        .hero h1 span { color: #e94560; }
        .hero p { color: rgba(255,255,255,0.7); font-size: 1.1rem; max-width: 520px; }
        .btn-hero-primary {
            background: #e94560; border: none; color: #fff;
            padding: 14px 32px; border-radius: 12px; font-weight: 600;
            font-size: 15px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-primary:hover { background: #c0392b; transform: translateY(-2px); color: #fff; }
        .btn-hero-secondary {
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2);
            color: #fff; padding: 14px 32px; border-radius: 12px; font-weight: 600;
            font-size: 15px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.15); color: #fff; }

        /* STATS BAR */
        .stats-bar { background: #fff; padding: 40px 0; border-bottom: 1px solid #f0f2f5; }
        .stat-item { text-align: center; padding: 0 20px; }
        .stat-item .number { font-size: 2rem; font-weight: 800; color: #1a1a2e; }
        .stat-item .label { color: #6c757d; font-size: 14px; }

        /* FEATURES */
        .features { background: #f8f9fa; padding: 100px 0; }
        .feature-card {
            background: #fff; border-radius: 20px; padding: 36px 28px;
            border: 1px solid #f0f2f5; transition: all 0.3s; height: 100%;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .feature-icon {
            width: 60px; height: 60px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 20px;
        }
        .section-badge {
            display: inline-block; background: rgba(233,69,96,0.1);
            color: #e94560; padding: 4px 14px; border-radius: 20px;
            font-size: 12px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 12px;
        }

        /* HOW IT WORKS */
        .how-it-works { background: #fff; padding: 100px 0; }
        .step-number {
            width: 48px; height: 48px; background: #e94560; color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 18px; margin: 0 auto 16px; flex-shrink: 0;
        }
        .step-connector {
            flex: 1; height: 2px; background: linear-gradient(90deg, #e94560, #0f3460);
            margin: 24px 8px 0; opacity: 0.3;
        }

        /* CTA */
        .cta-section {
            background: linear-gradient(135deg, #1a1a2e, #0f3460);
            padding: 100px 0; text-align: center;
        }

        /* FOOTER */
        footer { background: #1a1a2e; color: rgba(255,255,255,0.5); padding: 24px 0; text-align: center; font-size: 14px; }

        /* FLOATING CARD */
        .hero-card {
            background: rgba(255,255,255,0.05); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 28px;
            color: #fff;
        }
        .risk-bar { height: 8px; border-radius: 4px; background: rgba(255,255,255,0.1); overflow: hidden; margin: 8px 0 4px; }
        .risk-fill { height: 100%; border-radius: 4px; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand text-white fw-bold fs-4" href="#">
            <i class="bi bi-shield-check me-2" style="color:#e94560"></i>Loan<span>Guard</span>
        </a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light px-4" style="border-radius:10px">Login</a>
            <a href="{{ route('register') }}" class="btn btn-sm px-4" style="background:#e94560;color:#fff;border-radius:10px;border:none">Register</a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container py-5" style="position:relative;z-index:2">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-cpu-fill"></i> Powered by Logistic Regression AI
                </div>
                <h1>Smart Loan Risk <span>Analysis</span> in Seconds</h1>
                <p class="mt-4 mb-5">
                    LoanGuard uses a custom-trained machine learning model to instantly evaluate loan applications.
                    No guesswork — real AI, real decisions, real time.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <i class="bi bi-person-plus"></i> Apply for a Loan
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-secondary">
                        <i class="bi bi-box-arrow-in-right"></i> Admin Login
                    </a>
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div>
                        <div class="text-white fw-bold fs-4">80.5%</div>
                        <div style="color:rgba(255,255,255,0.5);font-size:13px">Model Accuracy</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.1)"></div>
                    <div>
                        <div class="text-white fw-bold fs-4">&lt; 1s</div>
                        <div style="color:rgba(255,255,255,0.5);font-size:13px">Risk Score Time</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.1)"></div>
                    <div>
                        <div class="text-white fw-bold fs-4">5</div>
                        <div style="color:rgba(255,255,255,0.5);font-size:13px">Risk Factors</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:42px;height:42px;background:rgba(233,69,96,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-cpu" style="color:#e94560;font-size:20px"></i>
                        </div>
                        <div>
                            <div class="fw-bold">AI Risk Assessment</div>
                            <div style="color:rgba(255,255,255,0.5);font-size:13px">Live Analysis Result</div>
                        </div>
                        <span class="ms-auto badge" style="background:rgba(0,184,148,0.2);color:#00b894">● Live</span>
                    </div>
                    @foreach([
                        ['Credit Score', 72, '#00b894'],
                        ['Income Ratio', 45, '#fdcb6e'],
                        ['Employment', 85, '#0984e3'],
                        ['Loan Amount', 60, '#e94560'],
                    ] as [$label, $pct, $color])
                    <div class="mb-3">
                        <div class="d-flex justify-content-between" style="font-size:13px">
                            <span style="color:rgba(255,255,255,0.7)">{{ $label }}</span>
                            <span style="color:{{ $color }};font-weight:600">{{ $pct }}%</span>
                        </div>
                        <div class="risk-bar">
                            <div class="risk-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-4 p-3 rounded-3" style="background:rgba(0,184,148,0.1);border:1px solid rgba(0,184,148,0.2)">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color:#00b894"></i>
                            <span class="fw-bold" style="color:#00b894">Low Risk — Recommended for Approval</span>
                        </div>
                        <div style="color:rgba(255,255,255,0.5);font-size:12px;margin-top:4px">Risk Score: 23.4% · Confidence: 91.2%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<section class="stats-bar">
    <div class="container">
        <div class="row g-3 text-center">
            @foreach([
                ['Loan Applications', '45+'],
                ['Model Accuracy', '80.5%'],
                ['Risk Factors', '5'],
                ['Decision Time', '< 1 sec'],
            ] as [$label, $value])
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="number">{{ $value }}</div>
                    <div class="label">{{ $label }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Features</div>
            <h2 class="fw-bold fs-1">Everything You Need</h2>
            <p class="text-muted mt-2">Built with Laravel, Python Flask, and scikit-learn</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['bi-cpu','#e94560','rgba(233,69,96,0.1)','Real ML Model','Custom Logistic Regression trained on financial data — not an API call. You see the algorithm, you own the intelligence.'],
                ['bi-shield-check','#0984e3','rgba(9,132,227,0.1)','Role-Based Access','Admins manage everything. Applicants see only their own data. Policy-enforced at every route.'],
                ['bi-lightning-charge','#fdcb6e','rgba(253,203,110,0.1)','Instant Scoring','Submit a form, get a risk score in under a second. Flask microservice responds in real time.'],
                ['bi-bar-chart','#00b894','rgba(0,184,148,0.1)','Smart Dashboard','Charts, stats, risk meters, and applicant rankings — all powered by live database queries.'],
                ['bi-funnel','#6c5ce7','rgba(108,92,231,0.1)','Filter & Search','Admins can filter by status, risk level, or search by applicant name and email instantly.'],
                ['bi-check2-circle','#e17055','rgba(225,112,85,0.1)','AJAX Decisions','Approve or reject applications without page reload — smooth, professional UX.'],
            ] as [$icon, $color, $bg, $title, $desc])
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:{{ $bg }}">
                        <i class="bi {{ $icon }}" style="color:{{ $color }}"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $title }}</h5>
                    <p class="text-muted mb-0" style="font-size:14px;line-height:1.6">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How it works -->
<section class="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge">Process</div>
            <h2 class="fw-bold fs-1">How It Works</h2>
        </div>
        <div class="row g-4 text-center">
            @foreach([
                ['1','bi-person-plus','Register','Create your account as an applicant in seconds'],
                ['2','bi-file-earmark-text','Apply','Fill the loan form with your financial details'],
                ['3','bi-cpu','AI Analyzes','Our ML model scores your application instantly'],
                ['4','bi-check-circle','Decision','Admin reviews and approves or rejects'],
            ] as [$num, $icon, $title, $desc])
            <div class="col-6 col-md-3">
                <div class="step-number">{{ $num }}</div>
                <i class="bi {{ $icon }} fs-2 mb-3 d-block" style="color:#e94560"></i>
                <h6 class="fw-bold">{{ $title }}</h6>
                <p class="text-muted" style="font-size:13px">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="section-badge">Get Started</div>
        <h2 class="text-white fw-bold fs-1 mt-3 mb-3">Ready to Apply?</h2>
        <p style="color:rgba(255,255,255,0.6);font-size:1.1rem;margin-bottom:40px">
            Get your AI-powered risk score in under a second.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="bi bi-person-plus"></i> Create Account
            </a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <strong class="text-white">Loan<span style="color:#e94560">Guard</span></strong> —
        AI Loan Risk Analyzer · Built with Laravel + Python Flask + scikit-learn
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>