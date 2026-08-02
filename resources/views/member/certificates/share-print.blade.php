<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Certificate - {{ $certificate->certificate_number }}</title>
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
    @php
        $settings = \Illuminate\Support\Facades\Cache::get('share_settings', []);
        $certificateBackgroundPath = $settings['certificate_background'] ?? '';
        $certificateBackgroundUrl = $certificateBackgroundPath ? asset('storage/' . $certificateBackgroundPath) : '';
        $memberName = $certificate->sharePurchase->member->name ?? 'N/A';
        $shareProduct = $certificate->sharePurchase->shareProduct->name ?? 'N/A';
        $totalValue = $certificate->number_of_shares * $certificate->share_value_per_share;
    @endphp

    <div class="certificate">
        <div style="background-image: url('{{ $certificateBackgroundUrl }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 500px; padding: 40px; position: relative;">
            <div style="padding: 40px; position: relative; z-index: 1;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h1 style="font-size: 32px; font-weight: bold; color: #1e40af; margin-bottom: 5px; font-family: 'Times New Roman', serif;">CERTIFICATE OF OWNERSHIP</h1>
                </div>
                
                <div style="text-align: center; margin-bottom: 30px;">
                    <p style="color: #1f2937; font-size: 16px; margin-bottom: 10px;">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
                    <h2 class="great-vibes-regular" style="font-size: 36px; color: #1e40af; margin: 10px 0;">{{ $memberName }}</h2>
                    <div style="width: 350px; height: 2px; background: linear-gradient(to right, transparent, #1e40af, transparent); margin: 8px auto;"></div>
                    <p style="color: #1f2937; font-size: 16px;">This certifies that {{ $memberName }} is the registered owner of <strong>{{ $certificate->number_of_shares }} {{ $shareProduct }}</strong> in FEEDTAN COMMUNITY MICROFINANCE GROUP with a total value of <strong>TZS {{ number_format($totalValue, 2) }}</strong>. The shares are issued in accordance with the organization's Constitution, Share Policy, and applicable regulations, granting the shareholder all rights and responsibilities of ownership.</p>
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding: 20px; background: rgba(255, 255, 255, 0.4); border-radius: 8px;">
                    <p style="color: #1f2937; font-size: 14px; line-height: 1.8;">
                        Certificate No: <strong>{{ $certificate->certificate_number }}</strong> | 
                        Status: <strong>{{ $certificate->is_active ? 'Active' : 'Inactive' }}</strong> | 
                        Share Product: <strong>{{ $shareProduct }}</strong> | 
                        Issue Date: <strong>{{ $certificate->issue_date->format('d F Y') }}</strong> | 
                        Ownership Status: <strong>Fully Paid</strong> | 
                        Expiry: <strong>N/A (Permanent)</strong>
                    </p>
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid rgba(0,0,0,0.1);">
                    <p style="color: #1f2937; font-size: 14px;">This certificate serves as official proof of share ownership and remains valid according to the organization's governing policies.</p>
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
