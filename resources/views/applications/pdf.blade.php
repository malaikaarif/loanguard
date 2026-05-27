<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
       body { font-family: DejaVu Sans, sans-serif; color: #1a1a2e; font-size: 11px; }

        .header {
    background: #1a1a2e;
    color: white;
    padding: 16px 28px;
    margin-bottom: 0;
}
       .header-brand { font-size: 18px; font-weight: bold; }
        .header-brand span { color: #e94560; }
        .header-sub { color: rgba(255,255,255,0.6); font-size: 10px; margin-top: 2px; }
        .status-bar {
    padding: 8px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
        .status-approved { background: #e8f5e9; border-left: 4px solid #00b894; }
        .status-rejected { background: #ffebee; border-left: 4px solid #d63031; }
        .status-pending  { background: #fffde7; border-left: 4px solid #fdcb6e; }

     .body { padding: 14px 28px; }

        .section-title {
    font-size: 9px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c757d;
    margin-bottom: 6px;
    padding-bottom: 4px;
    border-bottom: 1px solid #f0f2f5;
}
        .grid { width: 100%; margin-bottom: 10px; }
        .grid td { padding: 4px 8px; width: 50%; vertical-align: top; }
.grid .label { color: #6c757d; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
.grid .value { font-weight: bold; font-size: 12px; margin-top: 2px; }


       .ai-box {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 10px 16px;
    margin-bottom: 10px;
    border: 1px solid #f0f2f5;
}

        .risk-high { color: #d63031; }
        .risk-low  { color: #00b894; }

        .risk-bar-bg {
            background: #e9ecef;
            height: 10px;
            border-radius: 5px;
            margin: 8px 0;
        }
        .risk-bar-fill {
            height: 10px;
            border-radius: 5px;
        }

       .footer {
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px solid #f0f2f5;
    text-align: center;
    color: #adb5bd;
    font-size: 9px;
}

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success { background: #e8f5e9; color: #00b894; }
        .badge-danger  { background: #ffebee; color: #d63031; }
        .badge-warning { background: #fffde7; color: #e17055; }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div style="display:flex; justify-content:space-between; align-items:center">
        <div>
            <div class="header-brand">Loan<span>Guard</span></div>
            <div class="header-sub">AI Loan Risk Analyzer</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:18px;font-weight:bold">Application #{{ $application->id }}</div>
            <div style="color:rgba(255,255,255,0.6);font-size:11px">{{ $application->created_at->format('d M Y, h:i A') }}</div>
        </div>
    </div>
</div>

<!-- Status Bar -->
<div class="status-bar status-{{ $application->status }}">
    <div>
        <strong>Status:</strong>
        <span class="badge badge-{{ $application->statusBadge() }}">
            {{ strtoupper($application->status) }}
        </span>
    </div>
    <div>
        <strong>Risk Level:</strong>
        <span class="badge badge-{{ $application->riskBadge() }}">
            {{ strtoupper($application->risk_label ?? 'N/A') }} RISK
        </span>
    </div>
    <div style="color:#6c757d;font-size:12px">
        Issued: {{ now()->format('d M Y') }}
    </div>
</div>

<div class="body">

    <!-- Applicant Info -->
    <div class="section-title">Applicant Information</div>
    <table class="grid">
        <tr>
            <td>
                <div class="label">Full Name</div>
                <div class="value">{{ $application->user->name }}</div>
            </td>
            <td>
                <div class="label">Email Address</div>
                <div class="value">{{ $application->user->email }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Application Date</div>
                <div class="value">{{ $application->created_at->format('d M Y') }}</div>
            </td>
            <td>
                <div class="label">Application ID</div>
                <div class="value">#{{ $application->id }}</div>
            </td>
        </tr>
    </table>

    <!-- Loan Details -->
    <div class="section-title">Loan Details</div>
    <table class="grid">
        <tr>
            <td>
                <div class="label">Loan Amount</div>
                <div class="value">PKR {{ number_format($application->loan_amount) }}</div>
            </td>
            <td>
                <div class="label">Loan Purpose</div>
                <div class="value">{{ ucfirst($application->purpose) }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Annual Income</div>
                <div class="value">PKR {{ number_format($application->income) }}</div>
            </td>
            <td>
                <div class="label">Loan-to-Income Ratio</div>
                <div class="value">{{ round($application->loan_amount / max($application->income, 1), 2) }}x</div>
            </td>
        </tr>
    </table>

    <!-- Personal Financial Info -->
    <div class="section-title">Financial Profile</div>
    <table class="grid">
        <tr>
            <td>
                <div class="label">Age</div>
                <div class="value">{{ $application->age }} years</div>
            </td>
            <td>
                <div class="label">Credit Score</div>
                <div class="value" style="color:{{ $application->credit_score >= 670 ? '#00b894' : ($application->credit_score >= 580 ? '#e17055' : '#d63031') }}">
                    {{ $application->credit_score }} / 850
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Employment Years</div>
                <div class="value">{{ $application->employment_years }} years</div>
            </td>
            <td>
                <div class="label">Employment Status</div>
                <div class="value">{{ $application->employment_years > 0 ? 'Employed' : 'Unemployed' }}</div>
            </td>
        </tr>
    </table>

    <!-- AI Risk Assessment -->
    <div class="section-title">AI Risk Assessment</div>
    <div class="ai-box">
        <table width="100%">
            <tr>
                <td width="60%">
                    <div style="font-size:12px;color:#6c757d;margin-bottom:4px">Risk Score (Logistic Regression Model)</div>
                    <div class="risk-bar-bg">
                        <div class="risk-bar-fill"
                            style="width:{{ $application->risk_score * 100 }}%;background:{{ $application->risk_label == 'high' ? '#d63031' : '#00b894' }}">
                        </div>
                    </div>
                    <div style="font-size:11px;color:#adb5bd">Model Accuracy: 80.5% · Algorithm: Logistic Regression</div>
                </td>
                <td width="40%" style="text-align:center">
                    <div style="font-size:36px;font-weight:900" class="risk-{{ $application->risk_label }}">
                        {{ round($application->risk_score * 100, 1) }}%
                    </div>
                    <div class="badge badge-{{ $application->riskBadge() }}">
                        {{ strtoupper($application->risk_label ?? 'N/A') }} RISK
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Decision -->
    <div class="section-title">Decision</div>
    <table class="grid">
        <tr>
            <td>
                <div class="label">Final Status</div>
                <div class="value" style="font-size:18px">
                    <span class="badge badge-{{ $application->statusBadge() }}">
                        {{ strtoupper($application->status) }}
                    </span>
                </div>
            </td>
            <td>
                <div class="label">Processed By</div>
                <div class="value">LoanGuard AI System</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <strong>LoanGuard</strong> — AI Loan Risk Analyzer &nbsp;|&nbsp;
        Generated on {{ now()->format('d M Y, h:i A') }} &nbsp;|&nbsp;
        This document is system-generated and serves as an official application record.
    </div>

</div>
</body>
</html>