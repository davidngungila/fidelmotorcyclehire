<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Completion Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Georgia', serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .certificate {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 8px solid #10b981;
            padding: 40px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid #10b981;
            margin: 8px;
            pointer-events: none;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #10b981;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 16px;
        }
        .certificate-number {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            margin-bottom: 15px;
        }
        .details {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .details-label {
            font-weight: bold;
            color: #666;
        }
        .details-value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #10b981;
        }
        .footer p {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .certificate {
                box-shadow: none;
                border: 8px solid #10b981;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <h1>Certificate of Completion</h1>
            <p>This certifies that the loan has been fully repaid</p>
        </div>

        <div class="certificate-number">
            Certificate No: {{ $certificate->certificate_number }}
        </div>

        <div class="content">
            <p>This is to certify that the loan referenced below has been successfully completed and fully repaid according to the terms agreed upon between the member and the financial institution.</p>
        </div>

        <div class="details">
            <div class="details-row">
                <span class="details-label">Loan Number:</span>
                <span class="details-value">{{ $certificate->loan->loan_number }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Loan Amount:</span>
                <span class="details-value">TSh {{ number_format($certificate->loan->principal_amount, 2) }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Purpose:</span>
                <span class="details-value">{{ ucfirst($certificate->loan->purpose) }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Disbursement Date:</span>
                <span class="details-value">{{ $certificate->loan->disbursement_date ? $certificate->loan->disbursement_date->format('F d, Y') : 'N/A' }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Completion Date:</span>
                <span class="details-value">{{ $certificate->completion_date->format('F d, Y') }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Member Name:</span>
                <span class="details-value">{{ $certificate->loan->member->name ?? 'N/A' }}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Member Number:</span>
                <span class="details-value">{{ $certificate->loan->member_number }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <span class="badge">✓ LOAN SUCCESSFULLY COMPLETED</span>
        </div>

        @if($certificate->notes)
        <div class="content" style="margin-top: 30px;">
            <p><strong>Additional Notes:</strong></p>
            <p>{{ $certificate->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Issued by: {{ config('app.name') }}</p>
            <p>Date of Issue: {{ $certificate->completion_date->format('F d, Y') }}</p>
            <p style="margin-top: 20px; font-size: 12px;">This certificate is valid as proof of loan completion</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
