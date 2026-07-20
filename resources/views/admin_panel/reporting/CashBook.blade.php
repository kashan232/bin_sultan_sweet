@extends('admin_panel.layout.app')
@section('content')
<style>
    .cashbook-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        overflow: hidden;
        margin: 20px 0;
        border: none;
    }
    .cashbook-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        color: #fff;
        padding: 18px 24px;
    }
    .cashbook-body { padding: 24px; }

    .summary-card {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid #e9ecef;
        height: 100%;
    }
    .summary-card .summary-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 4px;
    }
    .summary-card .summary-value {
        font-size: 24px;
        font-weight: 900;
        color: #1a1a2e;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }
    .summary-card .summary-sub {
        font-size: 13px;
        color: #6c757d;
        margin-top: 2px;
    }
    .summary-card.bg-gradient-cash { background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); }
    .summary-card.bg-gradient-card { background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%); }
    .summary-card.bg-gradient-change { background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); }
    .summary-card.bg-gradient-sale { background: linear-gradient(135deg, #e8daef 0%, #d2b4de 100%); }

    .summary-card.bg-gradient-recovery { background: linear-gradient(135deg, #d5f4e6 0%, #b7e4c7 100%); }
    .summary-card.bg-gradient-vendor { background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%); }
    .summary-card.bg-gradient-expense { background: linear-gradient(135deg, #ffe0b2 0%, #ffcc80 100%); }

    .balance-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 12px;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #fff;
        margin-bottom: 24px;
    }
    .balance-hero .balance-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
    }
    .balance-hero .balance-amount {
        font-size: 32px;
        font-weight: 900;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }
    .balance-hero .balance-amount.positive { color: #4ade80; }
    .balance-hero .balance-amount.negative { color: #f87171; }

    .cash-table {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .cash-table thead th {
        background: #1a1a2e;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 2px solid #0f3460;
    }
    .cash-table tbody td {
        padding: 10px 14px;
        vertical-align: middle;
        font-size: 14px;
        border-bottom: 1px solid #eee;
    }
    .cash-table tbody tr:last-child td { border-bottom: none; }
    .cash-table .sep-col {
        width: 4px;
        background: #1a1a2e;
        padding: 0 !important;
        border: none !important;
    }
    .entry-title { font-weight: 700; color: #1a1a2e; }
    .entry-ref { font-size: 12px; color: #6c757d; display: block; margin-top: 2px; }
    .entry-amount { font-weight: 800; text-align: right; font-size: 15px; }
    .entry-amount.credit { color: #dc3545; }

    .total-row td {
        background: #f1f3f5;
        font-weight: 800;
        font-size: 15px;
        border-top: 2px solid #1a1a2e !important;
        padding: 12px 14px;
    }
    .grand-row td {
        background: #1a1a2e;
        color: #fff;
        font-weight: 800;
        font-size: 15px;
        padding: 12px 14px;
    }

    .method-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }
    .method-badge.cash { background: #d4edda; color: #155724; }
    .method-badge.card { background: #cce5ff; color: #004085; }
    .method-badge.account { background: #fff3cd; color: #856404; }
    .method-badge.credit { background: #f8d7da; color: #721c24; }
    .method-badge.bank { background: #d1ecf1; color: #0c5460; }

    .section-divider {
        position: relative;
        text-align: center;
        margin: 20px 0;
    }
    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, #1a1a2e, transparent);
    }
    .section-divider span {
        position: relative;
        background: #fff;
        padding: 0 16px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 14px;
        color: #1a1a2e;
        letter-spacing: 1px;
    }

    .method-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 8px;
    }

    @media (max-width: 768px) {
        .cashbook-body { padding: 14px; }
        .balance-hero { flex-direction: column; gap: 12px; text-align: center; }
        .balance-hero .balance-amount { font-size: 24px; }
        .summary-card .summary-value { font-size: 18px; }
        .method-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container-fluid">
            <div class="cashbook-card">
                <div class="cashbook-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="m-0 fw-bold"><i class="fa fa-book me-2"></i>DAILY CASH BOOK</h4>
                        <form method="GET" action="{{ route('cashbook') }}" id="dateFilterForm" class="d-flex gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-white-50 mb-0" style="font-size:13px;">From:</label>
                                <input type="date" name="start_date" class="form-control form-control-sm border-0" style="background:rgba(255,255,255,0.15);color:#fff;"
                                       value="{{ $startDate ?? now()->subDays(30)->format('Y-m-d') }}"
                                       onchange="document.getElementById('dateFilterForm').submit()">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-white-50 mb-0" style="font-size:13px;">View:</label>
                                <input type="date" name="date" class="form-control form-control-sm border-0" style="background:rgba(255,255,255,0.15);color:#fff;"
                                       value="{{ $selectedDate ?? date('Y-m-d') }}"
                                       onchange="document.getElementById('dateFilterForm').submit()">
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="text-white-50 mb-0" style="font-size:13px;">Time:</label>
                                <input type="time" name="start_time" class="form-control form-control-sm border-0" style="background:rgba(255,255,255,0.15);color:#fff;max-width:120px;"
                                       value="{{ $startTime ?? '00:00' }}"
                                       onchange="document.getElementById('dateFilterForm').submit()">
                                <span class="text-white-50">-</span>
                                <input type="time" name="end_time" class="form-control form-control-sm border-0" style="background:rgba(255,255,255,0.15);color:#fff;max-width:120px;"
                                       value="{{ $endTime ?? '23:59' }}"
                                       onchange="document.getElementById('dateFilterForm').submit()">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="cashbook-body">
                    {{-- BALANCE HERO --}}
                    <div class="balance-hero">
                        <div class="text-center">
                            <div class="balance-label">Opening Balance</div>
                            <div class="balance-amount {{ $openingBalance >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($openingBalance, 0) }}
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="balance-label">Closing Balance</div>
                            <div class="balance-amount {{ $closingBalance >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($closingBalance, 0) }}
                            </div>
                        </div>
                    </div>

                    {{-- SALES BREAKDOWN --}}
                    <div class="section-divider"><span>Sales Breakdown</span></div>
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="summary-card bg-gradient-cash">
                                <div class="summary-label">Cash Sales</div>
                                <div class="summary-value">{{ number_format($totalSaleCash, 0) }}</div>
                                <div class="summary-sub">{{ $saleCount }} invoice(s)</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-card bg-gradient-card">
                                <div class="summary-label">Card Sales</div>
                                <div class="summary-value">{{ number_format($totalSaleCard, 0) }}</div>
                                <div class="summary-sub">via card terminal</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-card bg-gradient-change">
                                <div class="summary-label">Change Returned</div>
                                <div class="summary-value">{{ number_format($totalChange, 0) }}</div>
                                <div class="summary-sub">deducted from cash</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="summary-card bg-gradient-sale">
                                <div class="summary-label">Net Sales</div>
                                <div class="summary-value">{{ number_format($totalSaleNet, 0) }}</div>
                                <div class="summary-sub">total_net = cash + card - change</div>
                            </div>
                        </div>
                    </div>

                    {{-- RECOVERY & PAYMENT METHODS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="summary-card bg-gradient-recovery" style="height:auto;">
                                <div class="summary-label">Customer Recoveries</div>
                                <div class="summary-value mb-2">{{ number_format($totalRecoveries, 0) }}</div>
                                @if(count($recoveryByMethod))
                                    <div class="method-grid">
                                        @foreach($recoveryByMethod as $method => $amt)
                                            <div>
                                                <span class="method-badge {{ strtolower($method) }}">{{ $method }}</span>
                                                <span style="font-weight:700;font-size:14px;float:right;">{{ number_format($amt, 0) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="summary-sub">No recoveries today</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-card bg-gradient-vendor" style="height:auto;">
                                <div class="summary-label">Vendor Payments</div>
                                <div class="summary-value mb-2">{{ number_format($totalVendorPayments, 0) }}</div>
                                @if(count($vendorPayByMethod))
                                    <div class="method-grid">
                                        @foreach($vendorPayByMethod as $method => $amt)
                                            <div>
                                                <span class="method-badge {{ strtolower($method) }}">{{ $method }}</span>
                                                <span style="font-weight:700;font-size:14px;float:right;">{{ number_format($amt, 0) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="summary-sub">No vendor payments today</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- TRANSACTION TABLE --}}
                    <div class="section-divider"><span>Transactions</span></div>
                    <div class="table-responsive">
                        <table class="cash-table">
                            <thead>
                                <tr>
                                    <th width="38%">Receipts</th>
                                    <th width="12%">Amount</th>
                                    <th class="sep-col"></th>
                                    <th width="38%">Payments</th>
                                    <th width="12%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < $maxRows; $i++)
                                    <tr>
                                        <td>
                                            @if(isset($receipts[$i]))
                                                <span class="entry-title">{{ $receipts[$i]['title'] }}</span>
                                                <span class="entry-ref">{{ $receipts[$i]['ref'] }}</span>
                                            @endif
                                        </td>
                                        <td class="entry-amount">{{ isset($receipts[$i]) ? number_format($receipts[$i]['amount'],0) : '' }}</td>
                                        <td class="sep-col"></td>
                                        <td>
                                            @if(isset($payments[$i]))
                                                <span class="entry-title">{{ $payments[$i]['title'] }}</span>
                                                <span class="entry-ref">{{ $payments[$i]['ref'] }}</span>
                                            @endif
                                        </td>
                                        <td class="entry-amount credit">{{ isset($payments[$i]) ? number_format($payments[$i]['amount'],0) : '' }}</td>
                                    </tr>
                                @endfor

                                {{-- Totals --}}
                                <tr class="total-row">
                                    <td>Total Receipts</td>
                                    <td class="text-end">{{ number_format($totalReceipts, 0) }}</td>
                                    <td class="sep-col"></td>
                                    <td>Total Payments</td>
                                    <td class="text-end">{{ number_format($totalPayments, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection