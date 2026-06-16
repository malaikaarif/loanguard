@extends('layouts.app')
@section('title', 'Give Feedback')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Share Your Feedback</h4>
            <p class="text-muted">Help us improve LoanGuard with your honest review.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-white p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(233,69,96,0.1)">
                        <i class="bi bi-star-fill" style="color:#e94560"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Rate Your Experience</h6>
                        <small class="text-muted">Your feedback helps us serve you better</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf

                    {{-- Star Rating --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Rating <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star fs-2 star-btn"
                                   data-value="{{ $i }}"
                                   style="cursor:pointer; color:#dee2e6; transition:color 0.2s"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', $myFeedback->rating ?? '') }}">
                        <div id="ratingLabel" class="mt-2" style="font-size:13px;font-weight:600"></div>
                        @error('rating')
                            <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Comment --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Your Comment <span class="text-danger">*</span></label>
                        <textarea name="comment" rows="4"
                            class="form-control @error('comment') is-invalid @enderror"
                            placeholder="Tell us about your experience with LoanGuard..."
                            style="border-radius:12px; resize:none">{{ old('comment', $myFeedback->comment ?? '') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary px-5 py-2">
                        <i class="bi bi-send me-2"></i>Submit Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
const colors = ['', '#d63031', '#e17055', '#fdcb6e', '#00b894', '#0984e3'];
const stars   = document.querySelectorAll('.star-btn');
const input   = document.getElementById('ratingInput');
const label   = document.getElementById('ratingLabel');

function setStars(val) {
    stars.forEach((s, i) => {
        s.classList.toggle('bi-star-fill', i < val);
        s.classList.toggle('bi-star',      i >= val);
        s.style.color = i < val ? '#f39c12' : '#dee2e6';
    });
    input.value = val;
    label.innerHTML = val ? `<span style="color:${colors[val]}">${labels[val]}</span>` : '';
}

// Set existing rating if editing
const existing = parseInt(input.value);
if (existing) setStars(existing);

stars.forEach((s, i) => {
    s.addEventListener('click',      () => setStars(i + 1));
    s.addEventListener('mouseover',  () => setStars(i + 1));
    s.addEventListener('mouseleave', () => setStars(parseInt(input.value) || 0));
});
</script>
@endpush