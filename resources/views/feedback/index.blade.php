@extends('layouts.app')
@section('title', 'User Feedbacks')

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div>
        <h4 class="fw-bold mb-1">User Feedbacks</h4>
        <p class="text-muted mb-0">All feedback from applicants — latest on top</p>
    </div>
    <span class="badge bg-primary fs-6">{{ $feedbacks->count() }} Total</span>
</div>

{{-- Average Rating Card --}}
@if($feedbacks->count() > 0)
<div class="card mb-4" style="border-radius:16px">
    <div class="card-body p-4">
        <div class="row text-center">
            <div class="col-md-3">
                <div style="font-size:48px;font-weight:800;color:#e94560">
                    {{ number_format($feedbacks->avg('rating'), 1) }}
                </div>
                <div class="text-muted">Average Rating</div>
                <div class="mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($feedbacks->avg('rating')) ? '-fill' : '' }}"
                           style="color:#f39c12"></i>
                    @endfor
                </div>
            </div>
            @foreach([5,4,3,2,1] as $star)
            <div class="col-md d-flex align-items-center gap-2">
                <span style="font-size:13px;width:20px">{{ $star }}</span>
                <i class="bi bi-star-fill" style="color:#f39c12;font-size:12px"></i>
                <div class="progress flex-grow-1" style="height:8px;border-radius:4px">
                    <div class="progress-bar" style="width:{{ $feedbacks->count() ? ($feedbacks->where('rating',$star)->count() / $feedbacks->count() * 100) : 0 }}%;background:#e94560;border-radius:4px"></div>
                </div>
                <span style="font-size:13px;width:20px">{{ $feedbacks->where('rating', $star)->count() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Feedback Cards --}}
@forelse($feedbacks as $feedback)
<div class="card mb-3" style="border-radius:16px">
    <div class="card-body p-4">
        <div class="d-flex align-items-start justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(233,69,96,0.1)">
                    <i class="bi bi-person" style="color:#e94560"></i>
                </div>
                <div>
                    <div class="fw-bold">{{ $feedback->user->name }}</div>
                    <div class="text-muted" style="font-size:12px">{{ $feedback->user->email }}</div>
                </div>
            </div>
            <div class="text-end">
                <div>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $feedback->rating ? '-fill' : '' }}"
                           style="color:#f39c12"></i>
                    @endfor
                </div>
                <div class="text-muted" style="font-size:12px">{{ $feedback->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <div class="mt-3 p-3" style="background:#f8f9fa;border-radius:10px;font-size:14px">
            "{{ $feedback->comment }}"
        </div>
    </div>
</div>
@empty
<div class="text-center py-5 text-muted">
    <i class="bi bi-chat-square-text fs-1 d-block mb-3"></i>
    No feedbacks yet.
</div>
@endforelse
@endsection