<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendor Ledger</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #000; margin: 0; padding: 15px; }
        .header { text-align: center; padding-bottom: 10px; border-bottom: 2px solid #000; margin-bottom: 14px; }
        .header h1 { font-size: 18px; margin: 0 0 2px; }
        .header p { font-size: 11px; color: #555; margin: 0; }
        .info { display: flex; justify-content: space-between; font-size: 11px; font-weight: 600; margin-bottom: 12px; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th { background: #000; color: #fff; padding: 6px 8px; text-align: center; font-size: 9px; text-transform: uppercase; }
        table td { padding: 5px 8px; border-bottom: 1px solid #ddd; text-align: center; font-size: 10px; }
        table tr:nth-child(even) td { background: #f5f5f5; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .opening td { background: #f0f0f0 !important; font-weight: 700; }
        .totals td { background: #e0e0e0 !important; font-weight: 900; border-top: 2px solid #000; }
        .pos { color: #059669; font-weight: 700; }
        .neg { color: #dc2626; font-weight: 700; }
        .zero { color: #4f46e5; font-weight: 700; }
        .footer { text-align: center; font-size: 9px; color: #666; margin-top: 20px; padding-top: 8px; border-top: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="header">
        <h1>VENDOR LEDGER</h1>
        <p>Generated: {{ now()->format('d-M-Y h:i A') }}</p>
    </div>

    <div class="info">
        <span><strong>Vendor:</strong> {{ $vendor->name }}</span>
        <span><strong>Period:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('d-M-Y') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Inv / Ref</th>
                <th>Description</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDebit = 0;
                $totalCredit = 0;
                $balance = $opening_balance;
            @endphp
            <tr class="opening">
                <td>N/A</td>
                <td>-</td>
                <td class="text-left">Opening Balance</td>
                <td>-</td>
                <td>-</td>
                <td class="zero">Rs. {{ number_format($balance, 2) }}</td>
            </tr>

            @foreach($transactions as $t)
                @php
                    $debit = (float)($t->debit ?? 0);
                    $credit = (float)($t->credit ?? 0);
                    $totalDebit += $debit;
                    $totalCredit += $credit;
                    $balance = $balance + $debit - $credit;
                    $invRef = $t->invoice ?? '-';
                    if (!empty($t->reference)) $invRef .= ' (' . $t->reference . ')';
                    $balClass = $balance > 0 ? 'pos' : ($balance < 0 ? 'neg' : 'zero');
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}</td>
                    <td>{{ $invRef }}</td>
                    <td class="text-left">{{ $t->description }}</td>
                    <td>{{ $debit > 0 ? 'Rs. '.number_format($debit, 2) : '-' }}</td>
                    <td>{{ $credit > 0 ? 'Rs. '.number_format($credit, 2) : '-' }}</td>
                    <td class="{{ $balClass }}">Rs. {{ number_format($balance, 2) }}</td>
                </tr>
            @endforeach

            <tr class="totals">
                <td colspan="3" class="text-left">Totals:</td>
                <td>Rs. {{ number_format($totalDebit, 2) }}</td>
                <td>Rs. {{ number_format($totalCredit, 2) }}</td>
                <td class="{{ $balance > 0 ? 'pos' : ($balance < 0 ? 'neg' : 'zero') }}">Rs. {{ number_format($balance, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Bin Sultan Sweet — Vendor Ledger Report
    </div>
</body>
</html>