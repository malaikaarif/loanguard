@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Welcome back, {{ auth()->user()->name }} 👋</h4>
    <p class="text-muted">Here's your loan application overview.</p>
</div>

<div class="row g-3 mb-4">
    @php
        $total    = auth()->user()->loanApplications()->count();
        $pending  = auth()->user()->loanApplications()->where('status','pending')->count();
        $approved = auth()->user()->loanApplications()->where('status','approved')->count();
        $rejected = auth()->user()->loanApplications()->where('status','rejected')->count();
    @endphp

    @foreach([
        ['Total Applied', $total,    'file-earmark-text', '#0984e3', '#e3f2fd'],
        ['Pending',       $pending,  'clock',             '#fdcb6e', '#fffde7'],
        ['Approved',      $approved, 'check-circle',      '#00b894', '#e8f5e9'],
        ['Rejected',      $rejected, 'x-circle',          '#d63031', '#ffebee'],
    ] as [$label, $count, $icon, $color, $bg])
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:{{ $bg }}">
                <i class="bi bi-{{ $icon }}" style="color:{{ $color }}"></i>
            </div>
            <div>
                <div class="fw-bold fs-4">{{ $count }}</div>
                <div class="text-muted" style="font-size:13px">{{ $label }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Applications</h6>
                <a href="{{ route('applications.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>New Application
                </a>
            </div>
            <div class="card-body p-0">
                @php $recent = auth()->user()->loanApplications()->latest()->take(5)->get(); @endphp
                @if($recent->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                    No applications yet
                </div>
                @else
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Amount</th>
                                <th>Risk Score</th>
                                <th>Status</th>
                                <th class="pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent as $app)
                            <tr>
                                <td class="ps-4 text-muted">#{{ $app->id }}</td>
                                <td class="fw-semibold">PKR {{ number_format($app->loan_amount) }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="risk-meter" style="width:60px">
                                            <div class="risk-meter-fill"
                                                style="width:{{ $app->risk_score * 100 }}%;background:{{ $app->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                                            </div>
                                        </div>
                                        <small class="fw-bold">{{ round($app->risk_score * 100, 1) }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $app->statusBadge() }}-subtle text-{{ $app->statusBadge() }} text-capitalize">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="pe-4 text-muted" style="font-size:13px">
                                    {{ $app->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('applications.create') }}" class="btn btn-primary py-3">
                        <i class="bi bi-cpu me-2"></i>Apply for New Loan
                    </a>
                    <a href="{{ route('applications.index') }}" class="btn btn-light py-3">
                        <i class="bi bi-list-ul me-2"></i>View All Applications
                    </a>
                </div>
                <hr>
                <div class="p-3 rounded-3" style="background:rgba(233,69,96,0.05);border:1px solid rgba(233,69,96,0.15)">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-cpu" style="color:#e94560"></i>
                        <span class="fw-semibold" style="font-size:13px">AI Powered</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size:12px">
                        Your applications are scored by our Logistic Regression model with 80.5% accuracy.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection