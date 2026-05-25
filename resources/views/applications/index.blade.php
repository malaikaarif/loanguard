@extends('layouts.app')
@section('title', 'My Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">My Loan Applications</h4>
        <p class="text-muted mb-0">Track the status of your submissions</p>
    </div>
    <a href="{{ route('applications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>New Application
    </a>
</div>

@if($applications->isEmpty())
<div class="card text-center py-5">
    <div class="card-body">
        <i class="bi bi-file-earmark-x display-1 text-muted opacity-25"></i>
        <h5 class="mt-3 text-muted">No applications yet</h5>
        <p class="text-muted">Submit your first loan application to get started.</p>
        <a href="{{ route('applications.create') }}" class="btn btn-primary mt-2">
            <i class="bi bi-plus-lg me-2"></i>Apply Now
        </a>
    </div>
</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Loan Amount</th>
                        <th>Purpose</th>
                        <th>Risk Score</th>
                        <th>Risk Level</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted">#{{ $app->id }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold">PKR {{ number_format($app->loan_amount) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark text-capitalize">{{ $app->purpose }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="min-width:100px">
                                <div class="risk-meter flex-grow-1">
                                    <div class="risk-meter-fill"
                                        style="width:{{ $app->risk_score * 100 }}%;background:{{ $app->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                                    </div>
                                </div>
                                <span style="font-size:12px;font-weight:600;min-width:36px">
                                    {{ round($app->risk_score * 100, 1) }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $app->riskBadge() }}-subtle text-{{ $app->riskBadge() }} text-capitalize">
                                <i class="bi bi-circle-fill me-1" style="font-size:7px"></i>
                                {{ $app->risk_label ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $app->statusBadge() }}-subtle text-{{ $app->statusBadge() }} text-capitalize">
                                {{ $app->status }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:13px">
                                {{ $app->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-1">
                                <a href="{{ route('applications.show', $app) }}"
                                    class="btn btn-sm btn-light" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($app->status === 'pending')
                                <form method="POST" action="{{ route('applications.destroy', $app) }}"
                                    onsubmit="return confirm('Withdraw this application?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Withdraw">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($applications->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3">
        <small class="text-muted">Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }}</small>
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endif
@endsection