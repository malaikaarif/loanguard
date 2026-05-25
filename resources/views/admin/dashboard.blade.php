@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
@php
    use App\Models\LoanApplication;
    use App\Models\User;

    $totalApps    = LoanApplication::count();
    $pending      = LoanApplication::where('status','pending')->count();
    $approved     = LoanApplication::where('status','approved')->count();
    $rejected     = LoanApplication::where('status','rejected')->count();
    $highRisk     = LoanApplication::where('risk_label','high')->count();
    $lowRisk      = LoanApplication::where('risk_label','low')->count();
    $totalUsers   = User::where('role','applicant')->count();
    $avgRisk      = LoanApplication::avg('risk_score');
    $recentApps   = LoanApplication::with('user')->latest()->take(6)->get();

    $purposeData  = LoanApplication::selectRaw('purpose, count(*) as total')
                    ->groupBy('purpose')->pluck('total','purpose');
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Admin Dashboard</h4>
        <p class="text-muted mb-0">LoanGuard AI Risk Analyzer — Overview</p>
    </div>
    <a href="{{ route('admin.applications.index') }}" class="btn btn-primary">
        <i class="bi bi-file-earmark-text me-2"></i>Manage Applications
    </a>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    @foreach([
        ['Total Applications', $totalApps,  'file-earmark-text', '#0984e3', '#e3f2fd'],
        ['Pending Review',     $pending,    'clock-history',     '#fdcb6e', '#fffde7'],
        ['Approved',           $approved,   'check-circle-fill', '#00b894', '#e8f5e9'],
        ['Rejected',           $rejected,   'x-circle-fill',     '#d63031', '#ffebee'],
        ['High Risk',          $highRisk,   'exclamation-triangle-fill', '#e94560', '#fce4ec'],
        ['Total Applicants',   $totalUsers, 'people-fill',       '#6c5ce7', '#ede7f6'],
    ] as [$label, $count, $icon, $color, $bg])
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card d-flex flex-column align-items-start gap-2 h-100">
            <div class="stat-icon" style="background:{{ $bg }}">
                <i class="bi bi-{{ $icon }}" style="color:{{ $color }}"></i>
            </div>
            <div>
                <div class="fw-bold fs-3">{{ $count }}</div>
                <div class="text-muted" style="font-size:12px">{{ $label }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Donut: Status breakdown -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white p-4">
                <h6 class="fw-bold mb-0">Application Status</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" width="260" height="260"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut: Risk breakdown -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white p-4">
                <h6 class="fw-bold mb-0">Risk Distribution</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="riskChart" width="260" height="260"></canvas>
            </div>
        </div>
    </div>

    <!-- Bar: Purpose breakdown -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white p-4">
                <h6 class="fw-bold mb-0">Loan by Purpose</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="purposeChart" width="260" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Average Risk + Recent -->
<div class="row g-4">
    <!-- Average risk score -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-cpu display-4 mb-3" style="color:#e94560"></i>
                <h6 class="fw-bold text-muted mb-3">Average AI Risk Score</h6>
                <div class="display-4 fw-bold mb-2"
                    style="color:{{ $avgRisk > 0.5 ? '#d63031' : '#00b894' }}">
                    {{ round($avgRisk * 100, 1) }}%
                </div>
                <div class="risk-meter mb-3">
                    <div class="risk-meter-fill"
                        style="width:{{ $avgRisk * 100 }}%;
                        background:{{ $avgRisk > 0.5 ? '#d63031' : '#00b894' }}">
                    </div>
                </div>
                <small class="text-muted">Logistic Regression Model · 80.5% Accuracy</small>
            </div>
        </div>
    </div>

    <!-- Recent applications -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Applications</h6>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Applicant</th>
                                <th>Amount</th>
                                <th>AI Score</th>
                                <th>Risk</th>
                                <th class="pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentApps as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold" style="font-size:13px">{{ $app->user->name }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ $app->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="fw-semibold">PKR {{ number_format($app->loan_amount) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="risk-meter" style="width:50px">
                                            <div class="risk-meter-fill"
                                                style="width:{{ $app->risk_score * 100 }}%;background:{{ $app->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                                            </div>
                                        </div>
                                        <small class="fw-bold">{{ round($app->risk_score * 100, 1) }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $app->riskBadge() }}-subtle text-{{ $app->riskBadge() }} text-capitalize">
                                        {{ $app->risk_label }}
                                    </span>
                                </td>
                                <td class="pe-4">
                                    <span class="badge bg-{{ $app->statusBadge() }}-subtle text-{{ $app->statusBadge() }} text-capitalize">
                                        {{ $app->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [{{ $pending }}, {{ $approved }}, {{ $rejected }}],
            backgroundColor: ['#fdcb6e', '#00b894', '#d63031'],
            borderWidth: 0,
        }]
    },
    options: {
        cutout: '70%',
        plugins: { legend: { position: 'bottom' } }
    }
});

// Risk Chart
new Chart(document.getElementById('riskChart'), {
    type: 'doughnut',
    data: {
        labels: ['High Risk', 'Low Risk'],
        datasets: [{
            data: [{{ $highRisk }}, {{ $lowRisk }}],
            backgroundColor: ['#e94560', '#00b894'],
            borderWidth: 0,
        }]
    },
    options: {
        cutout: '70%',
        plugins: { legend: { position: 'bottom' } }
    }
});

// Purpose Chart
new Chart(document.getElementById('purposeChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($purposeData->keys()->map(fn($k) => ucfirst($k))->toArray()) !!},
        datasets: [{
            label: 'Applications',
            data: {!! json_encode($purposeData->values()->toArray()) !!},
            backgroundColor: ['#0984e3','#e94560','#00b894','#fdcb6e','#6c5ce7'],
            borderRadius: 8,
            borderWidth: 0,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, grid: { color: '#f0f2f5' } }, x: { grid: { display: false } } }
    }
});
</script>
@endpush