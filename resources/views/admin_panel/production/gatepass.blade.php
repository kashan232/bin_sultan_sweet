<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Production Gatepass - {{ $entry->entry_no }}</title>
<style>
    *, *::before, *::after { box-sizing: border-box; }
    
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none !important; }
        @page { size: 80mm auto; margin: 2mm; }
    }
    
    body {
        font-family: 'Arial', sans-serif;
        font-size: 11px;
        color: #000 !important;
        background: #fff;
        margin: 0;
        padding: 0;
    }
    
    .receipt {
        width: 100%;
        max-width: 240px;
        margin: 0 auto;
        padding: 6px 4px;
    }
    
    .center { text-align: center; }
    .bold { font-weight: 900 !important; }
    
    .line { border-top: 1px dashed #000; margin: 4px 0; }
    .dbl-line { border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin: 4px 0; }
    
    .brand { font-size: 14px; font-weight: 900; margin-bottom: 1px; text-transform: uppercase; letter-spacing: 0.3px; }
    .address { font-size: 9px; font-weight: 900; line-height: 1.2; }
    .title { font-size: 11px; font-weight: 900; margin: 6px 0; text-transform: uppercase; letter-spacing: 0.5px; }
    
    table { width: 100%; border-collapse: collapse; margin: 3px 0; }
    th { text-align: left; font-size: 10px; font-weight: 900; border-bottom: 1.5px solid #000; padding: 3px 1px; }
    td { font-size: 10px; font-weight: 900; padding: 3px 1px; vertical-align: top; border-bottom: 1px dotted #000; }
    
    table tbody tr:last-child td { border-bottom: none; }
    
    .r { text-align: right; }
    .info-row { display: flex; justify-content: space-between; font-size: 10px; margin: 2px 0; font-weight: 900; }
    
    .sig-area { display: flex; justify-content: space-between; margin-top: 30px; font-size: 9px; font-weight: 900; padding: 0 3px; }
    .sig-box { text-align: center; width: 30%; border-top: 1px dashed #000; padding-top: 3px; }
    
    .print-btn {
        display: block; margin: 10px auto; padding: 8px 20px;
        background: #000; color: #fff; border: none; border-radius: 5px;
        font-size: 13px; font-weight: 900; cursor: pointer; text-transform: uppercase;
    }
    
    .item-name { font-size: 10px; font-weight: 900; line-height: 1.1; margin-bottom: 1px; }
    .item-code { font-size: 9px; font-weight: 900; }
</style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">🖨️ Print</button>

<div class="receipt">
    <div class="center">
        <div class="brand">Bin Sultan</div>
        <div class="address" style="font-size:15px; margin-bottom: 3px;">Sweets & Bakers</div>
        <div class="address">Latifabad no 6 Near Shadman</div>
        <div class="address">Hall Hyderabad</div>
        <div class="address">Ph: 022 2786661</div>
    </div>

    <div class="dbl-line" style="margin-top:10px;"></div>
    <div class="center title">Production Gatepass</div>
    <div class="dbl-line" style="margin-bottom:10px;"></div>

    <div class="info-row"><span>Batch:</span><span>{{ $entry->entry_no }}</span></div>
    <div class="info-row"><span>Date:</span><span>{{ \Carbon\Carbon::parse($entry->production_date)->format('d-M-Y') }}</span></div>
    <div class="info-row"><span>Source:</span><span style="text-transform:uppercase">{{ $entry->source }}</span></div>
    <div class="info-row"><span>By:</span><span style="text-transform:uppercase">{{ $entry->user_name ?? 'SYSTEM' }}</span></div>

    <div class="line" style="margin-top:8px;"></div>

    <table>
        <thead>
            <tr>
                <th style="width:10%;">#</th>
                <th style="width:65%;">Item Name</th>
                <th style="width:25%;" class="r">Qty</th>
            </tr>
        </thead>
        <tbody>
            @php $sno = 0; @endphp
            @foreach($items as $item)
            @php
                $sno++;
                $isKg = ($item->unit_type === 'kg');
                
                if ($isKg) {
                    $entered = (float) $item->qty_entered;
                    $kg = floor($entered);
                    $gm = round(($entered - $kg) * 1000);
                    if ($kg > 0 && $gm > 0) {
                        $qtyDisplay = $kg . 'kg ' . $gm . 'g';
                    } elseif ($kg > 0) {
                        $qtyDisplay = $kg . 'kg';
                    } else {
                        $qtyDisplay = $gm . 'g';
                    }
                } else {
                    $qtyDisplay = number_format((float)$item->qty_entered, 0);
                }
            @endphp
            <tr>
                <td>{{ $sno }}</td>
                <td>
                    <div class="item-name">
                        {{ $item->item_name }} 
                        @if($item->size_label || $item->variant_name)
                            ({{ $item->size_label ?: $item->variant_name }})
                        @endif
                    </div>
                    <div class="item-code">[{{ $item->item_code }}]</div>
                </td>
                <td class="r" style="font-size:16px; font-weight:900;">{{ $qtyDisplay }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>
    <div class="info-row" style="font-size:15px;"><span>Total Items:</span><span>{{ $sno }}</span></div>

    @if($entry->notes)
    <div class="line"></div>
    <div class="bold" style="font-size:13px; line-height:1.3;">Note: {{ $entry->notes }}</div>
    @endif

    <div class="line" style="margin-bottom:10px;"></div>
    <div class="center bold" style="font-size:12px;">Printed: {{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</div>

    <div class="sig-area">
        <div class="sig-box">PREPARED</div>
        <div class="sig-box">CHECKED</div>
        <div class="sig-box">RECEIVED</div>
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
