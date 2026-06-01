<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #1a1a2e, #0f3460); padding: 32px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 24px; }
    .header h1 span { color: #e94560; }
    .header p { color: rgba(255,255,255,0.6); margin: 6px 0 0; font-size: 13px; }
    .alert-banner { background: rgba(233,69,96,0.08); border-left: 4px solid #e94560; padding: 16px 24px; margin: 24px 24px 0; border-radius: 0 8px 8px 0; }
    .alert-banner p { margin: 0; font-size: 14px; color: #1a1a2e; font-weight: 600; }
    .body { padding: 24px 32px 32px; }
    .greeting { font-size: 18px; font-weight: 600; color: #1a1a2e; margin-bottom: 8px; }
    .text { color: #6c757d; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
    .card { background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
    .card-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f2f5; }
    .card-row:last-child { border-bottom: none; }
    .card-label { color: #6c757d; font-size: 13px; }
    .card-value { font-weight: 600; color: #1a1a2e; font-size: 13px; }
    .risk-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .risk-low { background: #e8f5e9; color: #00b894; }
    .risk-high { background: #ffebee; color: #d63031; }
    .ai-box { background: rgba(233,69,96,0.05); border: 1px solid rgba(233,69,96,0.15); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
    .ai-score { font-size: 32px; font-weight: 900; color: {{ $application->risk_label == 'high' ? '#d63031' : '#00b894' }}; }
    .footer { background: #f8f9fa; padding: 20px 32px; text-align: center; }
    .footer p { color: #adb5bd; font-size: 12px; margin: 0; }
    .footer strong { color: #1a1a2e; }
    .footer strong span { color: #e94560; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Loan<span>Guard</span></h1>
        <p>Admin Notification — New Application Received</p>
    </div>

    <div class="alert-banner">
        <p>🔔 A new loan application requires your review.</p>
    </div>

    <div class="body">
        <div class="greeting">Hello, Admin 👋</div>
        <p class="text">
            A new loan application has been submitted and is awaiting your decision.
            The AI model has already analyzed the application and generated a risk score.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Application ID</span>
                <span class="card-value">#{{ $application->id }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Applicant Name</span>
                <span class="card-value">{{ $application->user->name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Applicant Email</span>
                <span class="card-value">{{ $application->user->email }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Loan Amount</span>
                <span class="card-value">PKR {{ number_format($application->loan_amount) }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Purpose</span>
                <span class="card-value">{{ ucfirst($application->purpose) }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Annual Income</span>
                <span class="card-value">PKR {{ number_format($application->income) }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Credit Score</span>
                <span class="card-value">{{ $application->credit_score }} / 850</span>
            </div>
            <div class="card-row">
                <span class="card-label">Employment Years</span>
                <span class="card-value">{{ $application->employment_years }} years</span>
            </div>
            <div class="card-row">
                <span class="card-label">Submitted</span>
                <span class="card-value">{{ $application->created_at->format('d M Y, h:i A') }}</span>
            </div>
        </div>

        <div class="ai-box">
            <p style="font-weight:600;color:#1a1a2e;margin-bottom:6px;margin-top:0">🤖 AI Risk Assessment</p>
            <div class="ai-score">{{ round($application->risk_score * 100, 1) }}%</div>
            <p style="margin:4px 0">Risk Level:
                <span class="risk-badge risk-{{ $application->risk_label }}">
                    {{ strtoupper($application->risk_label) }} RISK
                </span>
            </p>
            <p style="color:#6c757d;font-size:12px;margin:8px 0 0">
                Logistic Regression Model · 80.5% Accuracy
            </p>
        </div>

        <p class="text">
            Please log in to the LoanGuard admin panel to review and make a decision on this application.
        </p>
    </div>

    <div class="footer">
        <p><strong>Loan<span>Guard</span></strong> — Admin Notification System</p>
        <p style="margin-top:4px">This is an automated alert. Please do not reply.</p>
    </div>
</div>
</body>
</html>