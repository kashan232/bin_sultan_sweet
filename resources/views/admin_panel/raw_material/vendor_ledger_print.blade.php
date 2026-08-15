<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vendor Ledger - {{ $selectedVendor->name ?? 'All Vendors' }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; }

@media print {
  body { margin: 0; padding: 0; }
  .no-print { display: none !important; }
  @page { size: 80mm auto; margin: 0; }
}

body {
  font-family: 'Arial', sans-serif;
  font-size: 10px;
  color: #000 !important;
  background: #fff;
  margin: 0;
  padding: 0;
}

.receipt {
  width: 100%;
  max-width: 260px;
  margin: 0 auto;
  padding: 6px 6px;
}

.center { text-align: center; }
.bold { font-weight: 700 !important; }
.line { border-top: 1px dashed #000; margin: 4px 0; }
.dbl-line { border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin: 4px 0; }

.brand { font-size: 15px; font-weight: 800; margin-bottom: 1px; text-transform: uppercase; letter-spacing: 0.3px; }
.address { font-size: 9.5px; font-weight: 700; line-height: 1.2; }
.title { font-size: 11px; font-weight: 800; margin: 4px 0; text-transform: uppercase; letter-spacing: 0.5px; }

table { width: 100%; border-collapse: collapse; margin: 4px 0; table-layout: fixed; }
th {
  text-align: left; font-size: 8.5px; font-weight: 800;
  border-bottom: 1.5px solid #000; padding: 3px 1px; text-transform: uppercase;
}
td {
  font-size: 8.5px; font-weight: 700;
  padding: 3px 1px; vertical-align: top;
  border-bottom: 1px dotted #000; word-wrap: break-word;
}
table tbody tr:last-child td { border-bottom: none; }
.r { text-align: right; }

.info-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; font-weight: 700; }

.print-btn {
  display: block; margin: 10px auto; padding: 8px 20px;
  background: #000; color: #fff; border: none; border-radius: 4px;
  font-size: 12px; font-weight: 700; cursor: pointer; text-transform: uppercase;
}
</style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">Print Vendor Ledger</button>

<div class="receipt">
  <div class="center">
    <div class="brand">Bin Sultan</div>
    <div class="address" style="font-size:11px;margin-bottom:1px;">Sweets & Bakers</div>
    <div class="address">Latifabad no 6 Near Shadman Hall Hyderabad</div>
    <div class="address">Ph: 022 2786661</div>
  </div>

  <div class="dbl-line" style="margin-top:5px;"></div>
  <div class="center title">VENDOR LEDGER STATEMENT</div>
  <div class="dbl-line" style="margin-bottom:5px;"></div>

  @if($selectedVendor)
    <div class="info-row"><span>Vendor:</span><span class="bold">{{ $selectedVendor->name }}</span></div>
    <div class="info-row"><span>Phone:</span><span>{{ $selectedVendor->phone ?? 'N/A' }}</span></div>
  @else
    <div class="info-row"><span>Vendor:</span><span class="bold">All Vendors Summary</span></div>
  @endif
  <div class="info-row"><span>Date:</span><span>{{ date('d-M-Y h:i A') }}</span></div>
  @if($dateFrom || $dateTo)
    <div class="info-row"><span>Period:</span><span>{{ $dateFrom ?? 'Start' }} to {{ $dateTo ?? 'End' }}</span></div>
  @endif

  <div class="line"></div>

  <table>
    <thead>
      <tr>
        <th style="width:24%;">Date/Ref</th>
        <th style="width:26%;" class="r">Credit (+)</th>
        <th style="width:25%;" class="r">Debit (-)</th>
        <th style="width:25%;" class="r">Balance</th>
      </tr>
    </thead>
    <tbody>
      @php
        $totCredit = 0;
        $totDebit = 0;
        $finalBalance = 0;
      @endphp
      @forelse($ledgers as $leg)
      @php
        $totCredit += (float)$leg->credit;
        $totDebit += (float)$leg->debit;
        $finalBalance = $leg->running_balance;
      @endphp
      <tr>
        <td colspan="4" style="padding-top:3px; border-bottom:none; font-weight:800;">
          {{ \Carbon\Carbon::parse($leg->date)->format('d-M-Y') }} - {{ $leg->description }}
        </td>
      </tr>
      <tr style="border-bottom:1px dotted #000;">
        <td><span style="font-size:8px;">{{ $leg->reference_no ?? '-' }}</span></td>
        <td class="r" style="color:#000;">{{ $leg->credit > 0 ? number_format($leg->credit, 0) : '-' }}</td>
        <td class="r" style="color:#000;">{{ $leg->debit > 0 ? number_format($leg->debit, 0) : '-' }}</td>
        <td class="r bold" style="color:#000;">{{ number_format($leg->running_balance, 0) }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="center" style="padding:8px 0;">No ledger transactions found</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="line"></div>
  
  <div class="info-row"><span>TOTAL CREDIT (BILLS):</span><span>Rs {{ number_format($totCredit, 2) }}</span></div>
  <div class="info-row"><span>TOTAL DEBIT (PAID):</span><span>Rs {{ number_format($totDebit, 2) }}</span></div>
  
  <div class="line"></div>
  <div class="info-row bold" style="font-size:10px;">
    <span>CLOSING PAYABLE BALANCE:</span>
    <span>Rs {{ number_format($selectedVendor ? $selectedVendor->closing_balance : $finalBalance, 2) }}</span>
  </div>

  <div class="line" style="margin-bottom:5px;"></div>

  <div class="center bold" style="font-size:9px;margin-top:4px;">
    <div>Total Entries: {{ count($ledgers) }}</div>
    <div style="margin-top:3px;">— End of Vendor Ledger —</div>
  </div>
</div>

<script>
  window.onload = function() {
    if (!window.matchMedia('print').matches) {
      window.print();
    }
  };
</script>
</body>
</html>
