<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill - {{ $table_name }}</title>
    <style>
        @media print { @page { margin: 0; } body { margin: 0; padding: 5px; } .no-print { display: none; } }
        body {
            font-family: 'Arial', sans-serif;
            width: 80mm;
            margin: 0 auto;
            color: #000;
            font-weight: 700;
            font-size: 13px;
        }
        .header { text-align: center; margin-bottom: 8px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 11px; font-weight: 400; }
        .table-name { text-align: center; font-size: 15px; font-weight: 900; margin: 5px 0; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 3px; font-size: 12px; }
        .row.bold { font-weight: 900; font-size: 14px; }
        .col-name { flex: 1; text-align: left; }
        .col-qty { width: 40px; text-align: center; }
        .col-price { width: 50px; text-align: right; }
        .col-total { width: 55px; text-align: right; }
        .item-row { display: flex; margin-bottom: 2px; font-size: 11px; font-weight: 600; }
        .item-row .col-name { font-weight: 600; }
        .item-row .col-total { font-weight: 700; }
        .sub-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 3px; }
        .sub-row span:last-child { font-weight: 700; }
        .net-box { border: 2px solid #000; padding: 8px; text-align: center; font-size: 16px; margin-top: 8px; }
        .net-box .label { font-size: 11px; font-weight: 600; }
        .net-box .amount { font-size: 18px; font-weight: 900; }
        .footer { text-align: center; margin-top: 12px; font-size: 10px; font-weight: 400; }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center;padding:10px;">
        <button onclick="window.print()" style="padding:10px 20px;cursor:pointer;background:#000;color:#fff;border:none;border-radius:5px;">Print Bill</button>
        <button onclick="window.close()" style="padding:10px 20px;cursor:pointer;background:#666;color:#fff;border:none;border-radius:5px;margin-left:5px;">Close</button>
    </div>

    <div class="header">
        <h2>Bin Sultan Sweets</h2>
        <p>Table Bill / Order Summary</p>
    </div>

    <div class="table-name">🍽️ {{ $table_name }}</div>

    <div class="divider"></div>

    <div class="row bold" style="border-bottom:1px solid #000;padding-bottom:3px;">
        <span class="col-name">Item</span>
        <span class="col-qty">Qty</span>
        <span class="col-price">Price</span>
        <span class="col-total">Total</span>
    </div>

    @foreach($items as $item)
    <div class="item-row">
        <span class="col-name">{{ $item['name'] }}</span>
        <span class="col-qty">{{ number_format($item['qty'], 2) }}</span>
        <span class="col-price">{{ number_format($item['price'], 0) }}</span>
        <span class="col-total">{{ number_format($item['total'], 0) }}</span>
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="sub-row">
        <span>Subtotal</span>
        <span>Rs {{ number_format($subtotal, 0) }}</span>
    </div>

    @if($extra_discount > 0)
    <div class="sub-row">
        <span>Extra Discount</span>
        <span>-Rs {{ number_format($extra_discount, 0) }}</span>
    </div>
    @endif

    <div class="sub-row">
        <span>Total Discount</span>
        <span>-Rs {{ number_format($total_discount, 0) }}</span>
    </div>

    <div class="net-box">
        <div class="label">NET TOTAL</div>
        <div class="amount">Rs {{ number_format($net_total, 0) }}</div>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>*** Bin Sultan Sweets ***</p>
        <p style="font-size:10px;margin:2px 0;">Bill Date: {{ $sale->created_at->format('d-M-Y h:i A') }}</p>
        <p style="font-size:10px;margin:2px 0;">Order stays running — Pay later to complete</p>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 300);
        };
    </script>
</body>
</html>
