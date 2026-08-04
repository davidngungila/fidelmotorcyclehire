<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Statement - {{ $loanNumber }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .header h1 { font-size: 24px; margin: 0; color: #333; }
        .header p { font-size: 12px; color: #666; margin: 5px 0; }
        .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
        .info-item { padding: 10px; background: #f5f5f5; border-radius: 4px; }
        .info-item label { display: block; font-weight: bold; font-size: 10px; color: #666; margin-bottom: 5px; }
        .info-item span { font-size: 14px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f5f5f5; padding: 10px; text-align: left; font-weight: bold; border-bottom: 2px solid #333; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .amount { text-align: right; }
        .credit { color: green; }
        .debit { color: red; }
        .summary { margin-top: 30px; padding: 20px; background: #f5f5f5; border-radius: 4px; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .summary-item { text-align: center; }
        .summary-item label { display: block; font-size: 10px; color: #666; margin-bottom: 5px; }
        .summary-item span { font-size: 18px; font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Motorcycle Hire Purchase</h1>
        <p>LOAN ACCOUNT STATEMENT</p>
        <p>Statement Date: {{ date('Y-m-d') }}</p>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <label>Account</label>
            <span>{{ $loanNumber }}</span>
        </div>
        <div class="info-item">
            <label>Product</label>
            <span>{{ $loan->purpose }}</span>
        </div>
        <div class="info-item">
            <label>Status</label>
            <span>{{ ucfirst($loan->status) }}</span>
        </div>
        <div class="info-item">
            <label>Member</label>
            <span>{{ $member['name'] }}</span>
        </div>
        <div class="info-item">
            <label>Member Number</label>
            <span>{{ $member['member_number'] }}</span>
        </div>
        <div class="info-item">
            <label>Phone</label>
            <span>{{ $member['phone'] }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Description</th>
                <th class="amount">Debit</th>
                <th class="amount">Credit</th>
                <th class="amount">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loanStatement as $item)
            <tr>
                <td>{{ $item['date'] }}</td>
                <td>{{ $item['type'] }}</td>
                <td>{{ $item['reference'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td class="amount debit">{{ (float)$item['debit'] > 0 ? number_format($item['debit'], 2) . ' TSh' : '-' }}</td>
                <td class="amount credit">{{ (float)$item['credit'] > 0 ? number_format($item['credit'], 2) . ' TSh' : '-' }}</td>
                <td class="amount">{{ number_format($item['balance'], 2) . ' TSh' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <label>Total Paid</label>
                <span>{{ number_format($paidAmount, 2) }} TSh</span>
            </div>
            <div class="summary-item">
                <label>Outstanding</label>
                <span>{{ number_format($outstanding, 2) }} TSh</span>
            </div>
            <div class="summary-item">
                <label>Total Loan</label>
                <span>{{ number_format($loanAmount, 2) }} TSh</span>
            </div>
        </div>
    </div>
</body>
</html>
