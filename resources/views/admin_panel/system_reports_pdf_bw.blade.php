<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Reports (B&amp;W)</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #000; margin: 0; padding: 20px; }
        .header { text-align: center; padding-bottom: 12px; border-bottom: 2px solid #000; margin-bottom: 18px; }
        .header h1 { font-size: 20px; color: #000; margin: 0 0 4px; }
        .header p { font-size: 11px; color: #555; margin: 0; }
        .section { margin-bottom: 18px; }
        .section h2 { font-size: 13px; color: #000; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin: 0 0 8px; }
        .stats { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
        .stat-box { flex: 1; min-width: 90px; border: 1px solid #ccc; padding: 8px 10px; background: #fff; }
        .stat-box .label { font-size: 8px; text-transform: uppercase; color: #555; letter-spacing: 0.5px; }
        .stat-box .value { font-size: 15px; font-weight: 900; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table th { background: #000; color: #fff; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        table td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 10px; color: #000; }
        table tr:nth-child(even) td { background: #f2f2f2; }
        .text-right { text-align: right; }
        .footer { text-align: center; font-size: 9px; color: #555; margin-top: 25px; padding-top: 8px; border-top: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="header">
        <h1>System Reports</h1>
        <p>{{ $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->format('M Y') . ' — ' . \Carbon\Carbon::parse($endDate)->format('M Y') : 'All Time Overview' }} | {{ now()->format('d-M-Y h:i A') }}</p>
    </div>

    <div class="section">
        <h2>Master Data</h2>
        <div class="stats">
            <div class="stat-box"><div class="label">Categories</div><div class="value">{{ $categoryCount }}</div></div>
            <div class="stat-box"><div class="label">Subcategories</div><div class="value">{{ $subcategoryCount }}</div></div>
            <div class="stat-box"><div class="label">Products</div><div class="value">{{ $productCount }}</div></div>
            <div class="stat-box"><div class="label">Customers</div><div class="value">{{ $customerscount }}</div></div>
        </div>
    </div>

    <div class="section">
        <h2>Financial Summary</h2>
        <div class="stats">
            <div class="stat-box"><div class="label">Purchases</div><div class="value">Rs {{ number_format($totalPurchases, 0) }}</div></div>
            <div class="stat-box"><div class="label">Pur. Returns</div><div class="value">Rs {{ number_format($totalPurchaseReturns, 0) }}</div></div>
            <div class="stat-box"><div class="label">Sales</div><div class="value">Rs {{ number_format($totalSales, 0) }}</div></div>
            <div class="stat-box"><div class="label">Sal. Returns</div><div class="value">Rs {{ number_format($totalSalesReturns, 0) }}</div></div>
        </div>
    </div>

    @if(count($labels))
    <div class="section">
        <h2>Sales &amp; Purchase Breakdown</h2>
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th class="text-right">Sales (Rs)</th>
                    <th class="text-right">Purchases (Rs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labels as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">{{ number_format($salesData[$i] ?? 0, 0) }}</td>
                    <td class="text-right">{{ number_format($purchaseData[$i] ?? 0, 0) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight:900;">
                    <td>Total</td>
                    <td class="text-right">{{ number_format(array_sum($salesData), 0) }}</td>
                    <td class="text-right">{{ number_format(array_sum($purchaseData), 0) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <h2>Category Wise Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Products</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryProductChart['categories'] as $i => $cat)
                <tr>
                    <td>{{ $cat }}</td>
                    <td class="text-right">{{ $categoryProductChart['series'][0]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(count($lowStockChart['categories']))
    <div class="section">
        <h2>Low Stock Alerts</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Stock</th>
                    <th class="text-right">Alert Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockChart['categories'] as $i => $name)
                <tr>
                    <td>{{ $name }}</td>
                    <td class="text-right">{{ $lowStockChart['series'][0]['data'][$i] ?? 0 }}</td>
                    <td class="text-right">{{ $lowStockChart['series'][1]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(count($categorySubChart['categories']))
    <div class="section">
        <h2>Category — Subcategories &amp; Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Subcategories</th>
                    <th class="text-right">Products</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorySubChart['categories'] as $i => $cat)
                <tr>
                    <td>{{ $cat }}</td>
                    <td class="text-right">{{ $categorySubChart['series'][0]['data'][$i] ?? 0 }}</td>
                    <td class="text-right">{{ $categorySubChart['series'][1]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(count($expenseChartData))
    <div class="section">
        <h2>Expense Distribution</h2>
        @foreach($expenseChartData as $head)
        <p style="font-weight:bold;margin:8px 0 4px;">{{ $head['head_name'] }}</p>
        <table>
            <thead>
                <tr>
                    <th>Account</th>
                    <th class="text-right">Amount (Rs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($head['categories'] as $j => $acct)
                <tr>
                    <td>{{ $acct }}</td>
                    <td class="text-right">{{ number_format($head['series'][0]['data'][$j] ?? 0, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    @endif

    <div class="footer">Bin Sultan Sweet — System Reports (B&amp;W)</div>
</body>
</html>