<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appreciation Certificate - {{ $loan->loan_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .certificate {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 8px solid #1e40af;
            padding: 40px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .great-vibes-regular {
            font-family: "Great Vibes", cursive;
            font-weight: 400;
            font-style: normal;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .certificate {
                box-shadow: none;
                border: 8px solid #1e40af;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div style="background-image: url('{{ $certificateBackgroundUrl }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 500px; padding: 40px; position: relative;">
            <div style="padding: 40px; position: relative; z-index: 1;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="font-size: 32px; font-weight: bold; color: #1e40af; margin-bottom: 5px; font-family: 'Times New Roman', serif;">CERTIFICATE OF APPRECIATION</h1>
                </div>
                
                <div style="text-align: center; margin-bottom: 30px;">
                    <p style="color: #1f2937; font-size: 16px; margin-bottom: 10px;">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
                    <h2 class="great-vibes-regular" style="font-size: 36px; color: #1e40af; margin: 10px 0;">{{ $loan->user->name }}</h2>
                    <div style="width: 350px; height: 2px; background: linear-gradient(to right, transparent, #1e40af, transparent); margin: 8px auto;"></div>
                    <p style="color: #1f2937; font-size: 16px;">In recognition of successfully completing loan repayment for <strong>Loan Number {{ $loan->loan_number }}</strong> with a total amount of <strong>TZS {{ number_format($loan->principal_amount + $loan->total_interest, 2) }}</strong>. This achievement demonstrates financial responsibility and commitment to fulfilling obligations, serving as an example to other members of FEEDTAN COMMUNITY MICROFINANCE GROUP.</p>
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding: 20px; background: rgba(255, 255, 255, 0.4); border-radius: 8px;">
                    <p style="color: #1f2937; font-size: 14px; line-height: 1.8;">
                        Loan Number: <strong>{{ $loan->loan_number }}</strong> | 
                        Loan Amount: <strong>TZS {{ number_format($loan->principal_amount, 2) }}</strong> | 
                        Completion Date: <strong>{{ $loan->updated_at->format('d F Y') }}</strong> | 
                        Status: <strong>Fully Paid</strong>
                    </p>
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid rgba(0,0,0,0.1);">
                    <p style="color: #1f2937; font-size: 14px;">This certificate serves as proof of successful loan completion and expresses our gratitude for your trust and partnership with FEEDTAN COMMUNITY MICROFINANCE GROUP.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
