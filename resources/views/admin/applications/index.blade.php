@extends('layouts.app')
@section('title', 'Manage Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Loan Applications</h4>
        <p class="text-muted mb-0">Review and manage all applicant submissions</p>
    </div>
    <span class="badge bg-primary fs-6 px-3">{{ $stats['total'] }} Total</span>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    @foreach([
        ['Pending', $stats['pending'], 'clock', '#fdcb6e', 'warning'],
        ['Approved', $stats['approved'], 'check-circle', '#00b894', 'success'],
        ['Rejected', $stats['rejected'], 'x-circle', '#d63031', 'danger'],
        ['High Risk', $stats['high_risk'], 'exclamation-triangle', '#e94560', 'danger'],
    ] as [$label, $count, $icon, $color, $variant])
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:{{ $color }}20">
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

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="risk" class="form-select">
                    <option value="">All Risk Levels</option>
                    <option value="high" {{ request('risk') == 'high' ? 'selected' : '' }}>High Risk</option>
                    <option value="low" {{ request('risk') == 'low' ? 'selected' : '' }}>Low Risk</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Applicant</th>
                        <th>Loan Amount</th>
                        <th>Purpose</th>
                        <th>Credit Score</th>
                        <th>AI Risk Score</th>
                        <th>Risk</th>
                        <th>Status</th>
                        <th class="pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    <tr id="row-{{ $app->id }}">
                        <td class="ps-4 text-muted">#{{ $app->id }}</td>
                        <td>
                            <div class="fw-semibold" style="font-size:14px">{{ $app->user->name }}</div>
                            <div class="text-muted" style="font-size:12px">{{ $app->user->email }}</div>
                        </td>
                        <td class="fw-semibold">PKR {{ number_format($app->loan_amount) }}</td>
                        <td><span class="badge bg-light text-dark text-capitalize">{{ $app->purpose }}</span></td>
                        <td>
                            <span class="fw-semibold" style="color:{{ $app->credit_score >= 670 ? '#00b894' : ($app->credit_score >= 580 ? '#fdcb6e' : '#d63031') }}">
                                {{ $app->credit_score }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="risk-meter" style="width:70px">
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
                        <td>
                            <span class="badge bg-{{ $app->statusBadge() }}-subtle text-{{ $app->statusBadge() }} text-capitalize" id="status-badge-{{ $app->id }}">
                                {{ $app->status }}
                            </span>
                        </td>
                        <td class="pe-4">
                            @if($app->status === 'pending')
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-success"
                                    onclick="updateStatus({{ $app->id }}, 'approved')"
                                    title="Approve">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                <button class="btn btn-sm btn-danger"
                                    onclick="updateStatus({{ $app->id }}, 'rejected')"
                                    title="Reject">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            @else
                            <span class="text-muted" style="font-size:12px">
                                {{ ucfirst($app->status) }}
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                            No applications found
                        </td>
                    </tr>
                    @endforelse
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

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="actionToast" class="toast align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMsg"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(id, status) {
    if (!confirm(`${status.charAt(0).toUpperCase() + status.slice(1)} this application?`)) return;

    fetch(`/admin/applications/${id}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update badge
            const badge = document.getElementById(`status-badge-${id}`);
            badge.className = `badge bg-${data.badge_class}-subtle text-${data.badge_class} text-capitalize`;
            badge.textContent = data.status;

            // Hide action buttons
            const row = document.getElementById(`row-${id}`);
            const actionCell = row.querySelector('td:last-child');
            actionCell.innerHTML = `<span class="text-muted" style="font-size:12px">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>`;

            // Show toast
            const toastEl = document.getElementById('actionToast');
            const toast = new bootstrap.Toast(toastEl);
            toastEl.className = `toast align-items-center border-0 text-bg-${data.badge_class}`;
            document.getElementById('toastMsg').textContent = data.message;
            toast.show();
        }
    });
}
</script>
@endpush