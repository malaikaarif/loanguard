<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LoanGuard - @yield('title', 'AI Loan Risk Analyzer')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1a1a2e;
            --secondary: #16213e;
            --accent: #0f3460;
            --gold: #e94560;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #d63031;
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand span { color: var(--gold); }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);
            width: 260px; position: fixed; top: 0; left: 0; z-index: 100;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75); border-radius: 10px;
            margin: 2px 12px; padding: 10px 16px; transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(233,69,96,0.2); color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link i { width: 22px; }
        .sidebar-brand {
            padding: 24px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e9ecef;
            padding: 12px 28px; position: sticky; top: 0; z-index: 99;
        }
        .card { border: none; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header { border-radius: 16px 16px 0 0 !important; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .stat-card {
            border-radius: 16px; padding: 24px;
            background: #fff; border: none;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 22px;
        }
        .badge { font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 20px; }
        .btn { border-radius: 10px; font-weight: 500; }
        .btn-primary { background: var(--gold); border-color: var(--gold); }
        .btn-primary:hover { background: #c0392b; border-color: #c0392b; }
        .form-control, .form-select {
            border-radius: 10px; border: 1.5px solid #e9ecef;
            padding: 10px 14px; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--gold); box-shadow: 0 0 0 3px rgba(233,69,96,0.1);
        }
        .table th { font-size: 12px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px; color: #6c757d; border-bottom: 2px solid #f0f2f5; }
        .table td { vertical-align: middle; border-bottom: 1px solid #f8f9fa; }
        .risk-meter {
            height: 8px; border-radius: 4px; background: #f0f2f5; overflow: hidden;
        }
        .risk-meter-fill { height: 100%; border-radius: 4px; transition: width 1s ease; }
        .alert { border-radius: 12px; border: none; }
        .page-link { border-radius: 8px !important; margin: 0 2px; border: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-260px); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/dashboard') }}" class="text-decoration-none">
                <h5 class="text-white mb-0 fw-bold">
                    <i class="bi bi-shield-check me-2" style="color:#e94560"></i>Loan<span style="color:#e94560">Guard</span>
                </h5>
                <small class="text-white-50">AI Risk Analyzer</small>
            </a>
        </div>
        <div class="mt-3">
            <div class="px-3 mb-2">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size:10px;letter-spacing:1px">
                    {{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'My Account' }}
                </small>
            </div>
            <ul class="nav flex-column">
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text me-2"></i> All Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.feedbacks') }}" class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}">
                            <i class="bi bi-chat-square-text me-2"></i> Feedbacks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.repayments') }}" class="nav-link {{ request()->routeIs('admin.repayments') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack me-2"></i> Repayments
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('applicant.dashboard') }}" class="nav-link {{ request()->routeIs('applicant.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('applications.create') }}" class="nav-link {{ request()->routeIs('applications.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle me-2"></i> Apply for Loan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('applications.index') }}" class="nav-link {{ request()->routeIs('applications.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ul me-2"></i> My Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('feedback.create') }}" class="nav-link {{ request()->routeIs('feedback.create') ? 'active' : '' }}">
                            <i class="bi bi-star me-2"></i> Give Feedback
                        </a>
                    </li>
                    @php
                        $approvedLoan = auth()->user()->loanApplications()->where('status','approved')->latest()->first();
                    @endphp
                    @if($approvedLoan)
                    <li class="nav-item">
                        <a href="{{ route('repayments.show', $approvedLoan->id) }}" class="nav-link {{ request()->routeIs('repayments.show') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check me-2"></i> My Repayments
                        </a>
                    </li>
                    @endif
                @endif
            </ul>
            <div class="px-3 mt-4 mb-2">
                <small class="text-white-50 text-uppercase fw-bold" style="font-size:10px;letter-spacing:1px">Account</small>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 w-100 text-start" style="background:none">
                            <i class="bi bi-box-arrow-left me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="position-absolute bottom-0 w-100 p-3" style="border-top:1px solid rgba(255,255,255,0.1)">
            <div class="d-flex align-items-center gap-2">
                <div style="width:36px;height:36px;background:rgba(233,69,96,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-person-fill text-white" style="font-size:16px"></i>
                </div>
                <div>
                    <div class="text-white fw-semibold" style="font-size:13px">{{ auth()->user()->name }}</div>
                    <div class="text-white-50" style="font-size:11px">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="topbar d-flex align-items-center justify-content-between">
            <button class="btn btn-sm btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:14px">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d M Y') }}
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark">
                    <i class="bi bi-person me-1"></i>{{ auth()->user()->name }}
                </span>
            </div>
        </div>
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>