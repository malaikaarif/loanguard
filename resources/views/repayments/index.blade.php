@extends('layouts.app')
@section('title', 'Repayments Overview')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">Repayments Overview</h4>
    <p class="text-muted">All active loans and their repayment status</p>
</div>

@forelse($loans as $loan)
@php
    $paid = $loan->repayments->where('status','paid')->count();
    $total = $loan->repayments->count();
    $percent = $total > 0 ? round($paid / $total * 100) : 0;
@endphp
<div class="card mb-3" style="border-radius:16px">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="fw-bold">{{ $loan->user->name }} <span class="text-muted small">({{ $loan->user->email }})</span></div>
                <div class="text-muted small">Loan #{{ $loan->id }} · PKR {{ number_format($loan->loan_amount) }} · {{ ucfirst($loan->purpose) }}</div>
            </div>
            <a href="{{ route('repayments.show', $loan->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
        </div>
        <div class="d-flex justify-content-between mb-1 small">
            <span>{{ $paid }}/{{ $total }} installments paid</span>
            <span>{{ $percent }}%</span>
        </div>
        <div class="progress" style="height:8px;border-radius:4px">
            <div class="progress-bar" style="width:{{ $percent }}%;background:#e94560;border-radius:4px"></div>
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="bi bi-cash-stack fs-1 d-block mb-3"></i>
    No active loans yet.
</div>
@endforelse
@endsection