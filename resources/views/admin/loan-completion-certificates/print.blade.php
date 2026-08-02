<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Completion Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
        }
        body {
            font-family: 'Times New Roman', serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .certificate {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 10px solid #1e3a5f;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .certificate-header {
            text-align: center;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .certificate-header h1 {
            color: #1e3a5f;
            font-size: 32px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-header h2 {
            color: #2c5282;
            font-size: 18px;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        .certificate-body {
            text-align: center;
            margin: 40px 0;
        }
        .certificate-body h3 {
            color: #1e3a5f;
            font-size: 24px;
            margin-bottom: 30px;
        }
        .certificate-body p {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            margin: 15px 0;
        }
        .certificate-body .highlight {
            font-weight: bold;
            color: #1e3a5f;
            font-size: 18px;
        }
        .certificate-details {
            background: #f8f9fa;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid #dee2e6;
        }
        .certificate-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .certificate-details td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .certificate-details td:first-child {
            font-weight: bold;
            color: #1e3a5f;
            width: 40%;
        }
        .certificate-footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #1e3a5f;
        }
        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
        .seal {
            width: 100px;
            height: 100px;
            border: 3px solid #c53030;
            border-radius: 50%;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #c53030;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1e3a5f; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            <i class="fa-solid fa-print"></i> Print Certificate
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="certificate">
        <div class="certificate-header">
            <h1>Certificate of Completion</h1>
            <h2>Loan Repayment Certificate</h2>
        </div>

        <div class="certificate-body">
            <h3>This is to certify that</h3>
            <p class="highlight">{{ $certificate->user->name }}</p>
            <p>Member Number: <span class="highlight">{{ $certificate->user->member_number }}</span></p>
            <p>has successfully completed the repayment of loan</p>
            <p class="highlight">{{ $certificate->loan->loan_number }}</p>
        </div>

        <div class="certificate-details">
            <table>
                <tr>
                    <td>Certificate Number:</td>
                    <td>{{ $certificate->certificate_number }}</td>
                </tr>
                <tr>
                    <td>Completion Date:</td>
                    <td>{{ $certificate->completion_date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td>Original Loan Amount:</td>
                    <td>TSh {{ number_format($certificate->original_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Amount Paid:</td>
                    <td>TSh {{ number_format($certificate->total_paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Interest Paid:</td>
                    <td>TSh {{ number_format($certificate->total_interest_paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Issue Date:</td>
                    <td>{{ $certificate->issue_date->format('F d, Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="certificate-body">
            <p>This certificate confirms that all obligations under the loan agreement have been fully satisfied and the loan has been completely repaid.</p>
        </div>

        <div class="seal">
            OFFICIAL<br>SEAL
        </div>

        <div class="certificate-footer">
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line">
                        <strong>{{ $certificate->issued_by }}</strong><br>
                        <small>Issuing Officer</small>
                    </div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">
                        <strong>{{ $certificate->user->name }}</strong><br>
                        <small>Member</small>
                    </div>
                </div>
            </div>
            <p style="margin-top: 30px; font-size: 14px; color: #666;">
                This certificate is issued on {{ $certificate->issue_date->format('F d, Y') }} and is valid as proof of loan completion.
            </p>
        </div>
    </div>
</body>
</html>
