@extends('layouts.app')
@section('title', 'Repayment Schedule')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Repayment Schedule</h4>
    <p class="text-muted">Loan #{{ $loanApplication->id }} — PKR {{ number_format($loanApplication->loan_amount) }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="border-radius:12px">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
@endif

{{-- Loan Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="fw-bold fs-4" style="color:#e94560">PKR {{ number_format($loanApplication->loan_amount) }}</div>
            <div class="text-muted small">Loan Amount</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="fw-bold fs-4" style="color:#00b894">PKR {{ number_format($repayments->sum('amount')) }}</div>
            <div class="text-muted small">Total Payable (with 10% interest)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="fw-bold fs-4" style="color:#0984e3">PKR {{ number_format($repayments->where('status','paid')->sum('amount')) }}</div>
            <div class="text-muted small">Amount Paid</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <div class="fw-bold fs-4" style="color:#fdcb6e">PKR {{ number_format($repayments->where('status','pending')->sum('amount')) }}</div>
            <div class="text-muted small">Remaining</div>
        </div>
    </div>
</div>

{{-- Progress Bar --}}
@php
    $paid = $repayments->where('status','paid')->count();
    $total = $repayments->count();
    $percent = $total > 0 ? round($paid / $total * 100) : 0;
@endphp
<div class="card mb-4 p-3">
    <div class="d-flex justify-content-between mb-1">
        <span class="fw-semibold">Repayment Progress</span>
        <span>{{ $paid }}/{{ $total }} installments paid</span>
    </div>
    <div class="progress" style="height:12px;border-radius:6px">
        <div class="progress-bar" style="width:{{ $percent }}%;background:#e94560;border-radius:6px"></div>
    </div>
</div>

{{-- Installments Table --}}
<div class="card mb-4">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead style="background:#f8f9fa">
                <tr>
                    <th class="p-3">#</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repayments as $repayment)
                <tr>
                    <td class="p-3">{{ $repayment->installment_number }}</td>
                    <td>PKR {{ number_format($repayment->amount) }}</td>
                    <td>{{ $repayment->due_date->format('d M Y') }}</td>
                    <td>{{ $repayment->paid_date ? $repayment->paid_date->format('d M Y') : '—' }}</td>
                    <td>
                        @if($repayment->status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($repayment->status === 'overdue')
                            <span class="badge bg-danger">Overdue</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($repayment->status === 'pending')
                            <form method="POST" action="{{ route('repayments.pay', [$loanApplication->id, $repayment->id]) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Pay Now</button>
                            </form>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Loan History --}}
<div class="mb-4">
    <h5 class="fw-bold mb-3">Loan History</h5>
    @forelse($history as $h)
    <div class="d-flex gap-3 mb-3">
        <div style="width:12px;height:12px;background:#e94560;border-radius:50%;margin-top:5px;flex-shrink:0"></div>
        <div>
            <div class="fw-semibold">{{ ucfirst($h->old_status ?? 'Submitted') }} → {{ ucfirst($h->new_status) }}</div>
            <div class="text-muted small">{{ $h->note }} · {{ $h->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @empty
    <p class="text-muted">No history yet.</p>
    @endforelse
</div>
@endsection