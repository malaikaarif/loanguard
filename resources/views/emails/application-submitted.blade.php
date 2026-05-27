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
    .body { padding: 32px; }
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
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; background: #fffde7; color: #e17055; }
    .ai-box { background: rgba(233,69,96,0.05); border: 1px solid rgba(233,69,96,0.15); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
    .ai-box p { margin: 0; font-size: 13px; color: #6c757d; }
    .ai-score { font-size: 28px; font-weight: 900; color: {{ $application->risk_label == 'high' ? '#d63031' : '#00b894' }}; }
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
        <p>AI Loan Risk Analyzer</p>
    </div>
    <div class="body">
        <div class="greeting">Hello, {{ $application->user->name }} 👋</div>
        <p class="text">
            Your loan application has been successfully submitted. Our AI model has analyzed your application and generated a risk score instantly.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Application ID</span>
                <span class="card-value">#{{ $application->id }}</span>
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
                <span class="card-label">Status</span>
                <span class="card-value"><span class="status-badge">PENDING</span></span>
            </div>
        </div>

        <div class="ai-box">
            <p style="font-weight:600;color:#1a1a2e;margin-bottom:6px">🤖 AI Risk Assessment</p>
            <div class="ai-score">{{ round($application->risk_score * 100, 1) }}%</div>
            <p>Risk Level:
                <span class="risk-badge risk-{{ $application->risk_label }}">
                    {{ strtoupper($application->risk_label) }} RISK
                </span>
            </p>
            <p style="margin-top:8px">Analyzed by Logistic Regression Model · 80.5% Accuracy</p>
        </div>

        <p class="text">
            Your application is now under review. You will receive another email once a decision has been made. You can also track your application status by logging into your LoanGuard account.
        </p>
    </div>
    <div class="footer">
        <p><strong>Loan<span>Guard</span></strong> — AI Loan Risk Analyzer</p>
        <p style="margin-top:4px">This is an automated email. Please do not reply.</p>
    </div>
</div>
</body>
</html>