@extends('layouts.app')
@section('title', 'Application #' . $application->id)

@section('content')
<div class="mb-4">
    <a href="{{ route('applications.index') }}" class="text-muted text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i>Back to Applications
    </a>
     <a href="{{ route('applications.pdf', $application) }}" class="btn btn-primary">
        <i class="bi bi-file-pdf me-2"></i>Download PDF
    </a>
</div>

<div class="row g-4">
    <!-- Left: Details -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Application #{{ $application->id }}</h5>
                    <small class="text-muted">Submitted {{ $application->created_at->diffForHumans() }}</small>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $application->riskBadge() }}-subtle text-{{ $application->riskBadge() }} fs-6">
                        {{ ucfirst($application->risk_label) }} Risk
                    </span>
                    <span class="badge bg-{{ $application->statusBadge() }}-subtle text-{{ $application->statusBadge() }} fs-6">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach([
                        ['Loan Amount', 'PKR ' . number_format($application->loan_amount), 'cash-stack', '#e94560'],
                        ['Annual Income', 'PKR ' . number_format($application->income), 'wallet2', '#0984e3'],
                        ['Age', $application->age . ' years', 'person', '#6c5ce7'],
                        ['Credit Score', $application->credit_score, 'star-half', '#fdcb6e'],
                        ['Employment', $application->employment_years . ' years', 'briefcase', '#00b894'],
                        ['Purpose', ucfirst($application->purpose), 'tag', '#e17055'],
                    ] as [$label, $value, $icon, $color])
                    <div class="col-md-4">
                        <div class="p-3 rounded-3" style="background:#f8f9fa">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-{{ $icon }}" style="color:{{ $color }}"></i>
                                <small class="text-muted fw-semibold">{{ $label }}</small>
                            </div>
                            <div class="fw-bold">{{ $value }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Right: AI Score -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-cpu display-4" style="color:#e94560"></i>
                </div>
                <h6 class="fw-bold text-muted mb-3">AI Risk Score</h6>
                <div class="display-4 fw-bold mb-2"
                    style="color:{{ $application->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                    {{ round($application->risk_score * 100, 1) }}%
                </div>
                <div class="risk-meter mb-3">
                    <div class="risk-meter-fill"
                        style="width:{{ $application->risk_score * 100 }}%;
                        background:{{ $application->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                    </div>
                </div>
                <span class="badge fs-6 bg-{{ $application->riskBadge() }}-subtle text-{{ $application->riskBadge() }} px-3 py-2">
                    {{ ucfirst($application->risk_label) }} Risk Profile
                </span>
                <hr>
                <div class="text-start">
                    <small class="text-muted fw-semibold d-block mb-2">DECISION</small>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-circle-fill fs-6 text-{{ $application->statusBadge() }}"></i>
                        <span class="fw-bold text-capitalize">{{ $application->status }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection