<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Closing Report - {{ $dateLabel }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; }

@media print {
  body { margin: 0; padding: 0; }
  .no-print { display: none !important; }
  @page { size: 80mm auto; margin: 0; }
}

body {
  font-family: 'Arial', sans-serif;
  font-size: 12px;
  color: #000 !important;
  background: #fff;
  margin: 0;
  padding: 0;
}

.receipt {
  width: 100%;
  max-width: 290px;
  margin: 0 auto;
  padding: 10px 8px;
}

.center { text-align: center; }
.bold { font-weight: 900 !important; }
.line { border-top: 1.5px dashed #000; margin: 5px 0; }
.dbl-line { border-top: 2px solid #000; border-bottom: 2px solid #000; height: 3px; margin: 5px 0; }

.brand { font-size: 18px; font-weight: 900; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
.address { font-size: 11px; font-weight: 900; line-height: 1.3; }
.title { font-size: 14px; font-weight: 900; margin: 8px 0; text-transform: uppercase; letter-spacing: 1px; }

table { width: 100%; border-collapse: collapse; margin: 5px 0; }
th {
  text-align: left; font-size: 11px; font-weight: 900;
  border-bottom: 2px solid #000; padding: 4px 2px;
}
td {
  font-size: 11px; font-weight: 900;
  padding: 4px 2px; vertical-align: top;
  border-bottom: 1px dotted #000;
}
table tbody tr:last-child td { border-bottom: none; }
.r { text-align: right; }

.info-row { display: flex; justify-content: space-between; font-size: 11px; margin: 3px 0; font-weight: 900; }

.print-btn {
  display: block; margin: 15px auto; padding: 12px 30px;
  background: #000; color: #fff; border: none; border-radius: 6px;
  font-size: 16px; font-weight: 900; cursor: pointer; text-transform: uppercase;
}

.item-name { font-size: 11px; font-weight: 900; line-height: 1.2; }
.item-code { font-size: 10px; font-weight: 900; color: #333; }
</style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">
  <span style="font-size:18px;margin-right:4px;">🖨️</span> Print Closing
</button>

<div class="receipt">
  <div class="center">
    <div class="brand">Bin Sultan</div>
    <div class="address" style="font-size:14px;margin-bottom:2px;">Sweets & Bakers</div>
    <div class="address">Latifabad no 6 Near Shadman Hall Hyderabad</div>
    <div class="address">Ph: 022 2786661</div>
  </div>

  <div class="dbl-line" style="margin-top:8px;"></div>
  <div class="center title">Daily Closing Report</div>
  <div class="dbl-line" style="margin-bottom:8px;"></div>

  <div class="info-row"><span>Date:</span><span>{{ $dateLabel }}</span></div>
  <div class="info-row"><span>Shift:</span><span>{{ $timeLabel }}</span></div>
  <div class="info-row"><span>Printed:</span><span>{{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</span></div>

  <div class="line"></div>

  <table>
    <thead>
      <tr>
        <th style="width:38%;">Item</th>
        <th style="width:22%;" class="r">Purch + Prod</th>
        <th style="width:20%;" class="r">Sold</th>
        <th style="width:20%;" class="r">Available</th>
      </tr>
    </thead>
    <tbody>
      @php $tp=0; $ts=0; $ta=0; @endphp
      @forelse($rows as $r)
      @php
        $purchProd = ((float)($r->purchased ?? 0)) + ((float)($r->produced ?? 0));
        $sold      = (float)($r->sold ?? 0);
        $avail     = (float)($r->balance ?? 0);
        $isKg      = !empty($r->is_kg);
        $itemName  = $r->item_name ?? '';
        $itemCode  = $r->item_code ?? '';
        $tp += $purchProd; $ts += $sold; $ta += $avail;
      @endphp
      <tr>
        <td>
          <div class="item-name">{{ $itemName }}</div>
          <div class="item-code">[{{ $itemCode }}]</div>
        </td>
        <td class="r">{{ number_format($purchProd, $isKg ? 0 : 0) }}{{ $isKg ? 'g' : '' }}</td>
        <td class="r">{{ number_format($sold, $isKg ? 0 : 0) }}{{ $isKg ? 'g' : '' }}</td>
        <td class="r">{{ number_format($avail, $isKg ? 0 : 0) }}{{ $isKg ? 'g' : '' }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="center" style="padding:10px 0;">No data for this period</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="line"></div>
  <div style="display:flex;justify-content:space-between;font-weight:900;font-size:12px;padding:3px 2px;">
    <span>TOTALS</span>
    <span>{{ number_format($tp) }}</span>
    <span>{{ number_format($ts) }}</span>
    <span>{{ number_format($ta) }}</span>
  </div>
  <div class="line" style="margin-bottom:8px;"></div>

  <div class="center bold" style="font-size:11px;margin-top:6px;">
    <div>Total Items: {{ $total }}</div>
    <div style="margin-top:4px;">— End of Report —</div>
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
