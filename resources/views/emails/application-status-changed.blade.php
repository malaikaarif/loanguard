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
    .status-banner { border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; text-align: center; }
    .status-approved { background: #e8f5e9; border: 2px solid #00b894; }
    .status-rejected { background: #ffebee; border: 2px solid #d63031; }
    .status-approved .status-icon { font-size: 40px; }
    .status-rejected .status-icon { font-size: 40px; }
    .status-approved .status-text { color: #00b894; font-size: 22px; font-weight: 800; }
    .status-rejected .status-text { color: #d63031; font-size: 22px; font-weight: 800; }
    .card { background: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
    .card-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f2f5; }
    .card-row:last-child { border-bottom: none; }
    .card-label { color: #6c757d; font-size: 13px; }
    .card-value { font-weight: 600; color: #1a1a2e; font-size: 13px; }
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
            There has been an update on your loan application #{{ $application->id }}.
        </p>

        <div class="status-banner status-{{ $application->status }}">
            <div class="status-icon">
                {{ $application->status === 'approved' ? '✅' : '❌' }}
            </div>
            <div class="status-text">
                Application {{ strtoupper($application->status) }}
            </div>
            <p style="margin:8px 0 0;font-size:13px;color:#6c757d">
                {{ $application->status === 'approved'
                    ? 'Congratulations! Your loan application has been approved.'
                    : 'Unfortunately, your loan application has been rejected.' }}
            </p>
        </div>

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
                <span class="card-label">AI Risk Score</span>
                <span class="card-value">{{ round($application->risk_score * 100, 1) }}%</span>
            </div>
            <div class="card-row">
                <span class="card-label">Decision Date</span>
                <span class="card-value">{{ now()->format('d M Y') }}</span>
            </div>
        </div>

        <p class="text">
            {{ $application->status === 'approved'
                ? 'Our team will contact you shortly with further details regarding your loan disbursement.'
                : 'If you have questions about this decision, please contact our support team.' }}
        </p>
    </div>
    <div class="footer">
        <p><strong>Loan<span>Guard</span></strong> — AI Loan Risk Analyzer</p>
        <p style="margin-top:4px">This is an automated email. Please do not reply.</p>
    </div>
</div>
</body>
</html>