@extends('layouts.app')
@section('title', 'Apply for Loan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Apply for a Loan</h4>
            <p class="text-muted">Our AI model will instantly calculate your risk score.</p>
        </div>

        <div class="card">
            <div class="card-header bg-white p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(233,69,96,0.1)">
                        <i class="bi bi-robot" style="color:#e94560"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">AI-Powered Risk Assessment</h6>
                        <small class="text-muted">Logistic Regression Model · Instant Results</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('applications.store') }}" id="loanForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Loan Amount -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Amount (PKR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-3 bg-light border-end-0">PKR</span>
                                <input type="number" name="loan_amount" value="{{ old('loan_amount') }}"
                                    class="form-control border-start-0 @error('loan_amount') is-invalid @enderror"
                                    placeholder="500,000" min="1000" max="10000000">
                                @error('loan_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Monthly Income -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Annual Income (PKR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-3 bg-light border-end-0">PKR</span>
                                <input type="number" name="income" value="{{ old('income') }}"
                                    class="form-control border-start-0 @error('income') is-invalid @enderror"
                                    placeholder="600,000" min="1000">
                                @error('income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" value="{{ old('age') }}"
                                class="form-control @error('age') is-invalid @enderror"
                                placeholder="25" min="18" max="70">
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Credit Score -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">
                                Credit Score <span class="text-danger">*</span>
                                <i class="bi bi-info-circle text-muted ms-1" data-bs-toggle="tooltip"
                                    title="300 = Very Poor, 850 = Exceptional"></i>
                            </label>
                            <input type="number" name="credit_score" value="{{ old('credit_score') }}"
                                class="form-control @error('credit_score') is-invalid @enderror"
                                placeholder="650" min="300" max="850" id="creditScoreInput">
                            @error('credit_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="creditLabel" class="mt-1" style="font-size:12px"></div>
                        </div>

                        <!-- Employment Years -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Employment Years <span class="text-danger">*</span></label>
                            <input type="number" name="employment_years" value="{{ old('employment_years') }}"
                                class="form-control @error('employment_years') is-invalid @enderror"
                                placeholder="3" min="0" max="50">
                            @error('employment_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Purpose -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Loan Purpose <span class="text-danger">*</span></label>
                            <div class="row g-2" id="purposeOptions">
                                @foreach(['home' => ['bi-house','Home'], 'car' => ['bi-car-front','Vehicle'], 'education' => ['bi-mortarboard','Education'], 'business' => ['bi-briefcase','Business'], 'personal' => ['bi-person','Personal']] as $val => $opt)
                                <div class="col-6 col-md">
                                    <input type="radio" class="btn-check" name="purpose" id="purpose_{{ $val }}"
                                        value="{{ $val }}" {{ old('purpose') == $val ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-3 gap-1"
                                        for="purpose_{{ $val }}" style="border-radius:12px;font-size:13px">
                                        <i class="bi {{ $opt[0] }} fs-4"></i>
                                        {{ $opt[1] }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('purpose')
                                <div class="text-danger mt-1" style="font-size:13px">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- AI Notice -->
                    <div class="alert mt-4 mb-0 d-flex gap-3 align-items-start"
                        style="background:rgba(233,69,96,0.05);border:1px solid rgba(233,69,96,0.2);border-radius:12px">
                        <i class="bi bi-cpu fs-4" style="color:#e94560;flex-shrink:0"></i>
                        <div>
                            <div class="fw-semibold" style="font-size:14px">AI Risk Analysis</div>
                            <div class="text-muted" style="font-size:13px">
                                Your application will be scored by our Logistic Regression model trained on financial data.
                                The score considers credit history, income-to-loan ratio, employment stability, and age.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2" id="submitBtn">
                            <span id="btnDefault">
                                <i class="bi bi-cpu me-2"></i>Submit & Analyze Risk
                            </span>
                            <span id="btnLoading" style="display:none;">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Analyzing Risk with AI...
                            </span>
                        </button>
                        <a href="{{ route('applications.index') }}" class="btn btn-light px-4 py-2" id="cancelBtn">Cancel</a>
                    </div>

                    <!-- Loading overlay -->
                    <div id="loadingOverlay" style="display:none; margin-top:16px;">
                        <div class="d-flex align-items-center gap-3 p-3"
                            style="background:rgba(233,69,96,0.05);border:1px solid rgba(233,69,96,0.2);border-radius:12px">
                            <div class="spinner-border text-danger spinner-border-sm" role="status"></div>
                            <div>
                                <div class="fw-semibold" style="font-size:13px;color:#e94560">Processing your application...</div>
                                <div class="text-muted" style="font-size:12px">AI model is calculating your risk score. Please wait.</div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Credit score live label
document.getElementById('creditScoreInput').addEventListener('input', function() {
    const v = parseInt(this.value);
    const el = document.getElementById('creditLabel');
    if (!v) { el.innerHTML = ''; return; }
    let label, color;
    if (v < 580)      { label = 'Poor'; color = '#d63031'; }
    else if (v < 670) { label = 'Fair'; color = '#e17055'; }
    else if (v < 740) { label = 'Good'; color = '#fdcb6e'; }
    else if (v < 800) { label = 'Very Good'; color = '#00b894'; }
    else              { label = 'Exceptional'; color = '#0984e3'; }
    el.innerHTML = `<span style="color:${color};font-weight:600"><i class="bi bi-circle-fill me-1" style="font-size:8px"></i>${label}</span>`;
});

// Loading state on submit
document.getElementById('loanForm').addEventListener('submit', function() {
    // Show loading state on button
    document.getElementById('btnDefault').style.display = 'none';
    document.getElementById('btnLoading').style.display = 'inline';
    document.getElementById('submitBtn').disabled = true;

    // Hide cancel button
    document.getElementById('cancelBtn').style.display = 'none';

    // Show loading overlay below button
    document.getElementById('loadingOverlay').style.display = 'block';
});

// Tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});
</script>
@endpush