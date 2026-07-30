@extends('admin_panel.layout.app')
@section('content')

<!-- DataTable CSS & Icons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Premium Styling */
    .page-title-box {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 24px 30px;
        color: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 24px;
    }
    .premium-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 25px;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 22px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
        border-color: #cbd5e1;
    }
    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 15px;
    }
    .stat-title {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    .stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
    }
    
    /* Table Enhancements */
    .table-premium {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100% !important;
    }
    .table-premium thead th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 14px 10px;
    }
    .table-premium tbody tr {
        transition: all 0.2s ease;
    }
    .table-premium tbody tr:hover {
        background-color: #f8fafc !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .table-premium td {
        padding: 14px 10px !important;
        font-size: 14px;
        color: #334155;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    
    /* Custom Badges */
    .badge-invoice {
        background-color: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-product {
        background-color: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-block;
        margin: 2px;
        white-space: nowrap;
    }
    .badge-returned {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fee2e2;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Button Customizer */
    .btn-action-receipt {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action-receipt:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        color: #ffffff;
        transform: translateY(-1px);
    }
    .btn-back-header {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-back-header:hover {
        background-color: #ef4444;
        border-color: #ef4444;
        color: #ffffff;
    }
    
    /* DataTables Overrides */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0284c7 !important;
        color: white !important;
        border-color: #0284c7 !important;
    }
    .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 6px 12px;
        outline: none;
    }
    .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 6px;
    }
</style>

@php
    $totalTransactions = $salesReturns->count();
    $totalQtyReturned = $salesReturns->sum('total_items');
    $totalRefundAmount = $salesReturns->sum('total_net');

    // Pre-fetch all products linked to these codes to solve variant-only rendering issues
    $allCodes = [];
    foreach($salesReturns as $return) {
        $codes = explode(',', $return->product_code ?? '');
        foreach($codes as $code) {
            $trimmed = trim($code);
            if ($trimmed !== '') {
                $allCodes[] = $trimmed;
            }
        }
    }
    $allCodes = array_unique($allCodes);
    $productsMap = \App\Models\Product::whereIn('item_code', $allCodes)->get()->keyBy('item_code');
@endphp

<div class="container-fluid py-4">
    
    <!-- Modern Header Box -->
    <div class="page-title-box d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div>
            <h4 class="mb-1 fw-bold d-flex align-items-center">
                <i class="fa-solid fa-rotate-left text-danger me-3"></i> Sale Returns Management
            </h4>
            <p class="mb-0 text-white-50 small">Track, manage, and print sales returns history log securely.</p>
        </div>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-back-header">
                <i class="fa-solid fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Card 1 -->
        <div class="col-12 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon-wrapper" style="background-color: #fef2f2; color: #dc2626;">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-title">Total Returns</div>
                <div class="stat-value">{{ number_format($totalTransactions) }}</div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-12 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon-wrapper" style="background-color: #f0fdf4; color: #16a34a;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="stat-title">Qty Returned</div>
                <div class="stat-value">{{ number_format($totalQtyReturned) }}</div>
            </div>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="premium-card mb-4" style="padding: 20px;">
        <form method="GET" action="{{ route('sale.returns.index') }}" class="row align-items-end g-3">
            <div class="col-12 col-md-4">
                <label class="form-label fw-bold text-slate-700"><i class="fa-solid fa-calendar-day text-primary me-2"></i>From Date</label>
                <input type="date" name="from_date" value="{{ $from_date ?? '' }}" class="form-control" style="border-radius: 10px; border: 1px solid #cbd5e1; padding: 8px 12px; outline: none; background-color: #f8fafc;">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-bold text-slate-700"><i class="fa-solid fa-calendar-day text-primary me-2"></i>To Date</label>
                <input type="date" name="to_date" value="{{ $to_date ?? '' }}" class="form-control" style="border-radius: 10px; border: 1px solid #cbd5e1; padding: 8px 12px; outline: none; background-color: #f8fafc;">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold text-white d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px; padding: 10px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="{{ route('sale.returns.index') }}" class="btn btn-outline-secondary w-100 fw-semibold d-flex align-items-center justify-content-center gap-2" style="border-radius: 10px; padding: 10px; border: 1px solid #cbd5e1; color: #475569; background-color: #ffffff;">
                    <i class="fa-solid fa-rotate-left"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Main Table Card -->
    <div class="premium-card">
        @if($salesReturns->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3 text-muted" style="font-size: 48px;">
                    <i class="fa-regular fa-folder-open"></i>
                </div>
                <h5 class="fw-semibold text-slate-700">No Sale Returns Found</h5>
                <p class="text-muted small">No return transactions recorded matching the selected filter criteria.</p>
            </div>
        @else
            <div class="table-responsive">
                <table id="returns-table" class="table table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="min-width: 130px;">Invoice</th>
                            <th>Returned Items</th>
                            <th>Customer Name</th>
                            <th class="text-center">Total Items</th>
                            <th class="text-end">Total Net</th>
                            <th>Return Note</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesReturns as $return)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge-invoice">
                                    <i class="fa-solid fa-hashtag text-primary"></i>
                                    {{ $return->sale->invoice_no ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $products = explode(',', $return->product ?? '');
                                    $codes = explode(',', $return->product_code ?? '');
                                @endphp
                                @if(!empty($products) && trim($return->product) !== '')
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($products as $index => $p)
                                            @php
                                                $code = trim($codes[$index] ?? '');
                                                $productModel = $productsMap[$code] ?? null;
                                                $displayName = trim($p);
                                                if ($productModel) {
                                                    // Check if displayName is just the variant name (and differs from product item_name)
                                                    if (strcasecmp(trim($productModel->item_name), $displayName) !== 0) {
                                                        $displayName = $productModel->item_name . ' (' . $displayName . ')';
                                                    }
                                                }
                                            @endphp
                                            <span class="badge-product">
                                                <i class="fa-solid fa-cube text-slate-400 me-1"></i>
                                                {{ $displayName }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-slate-800">
                                <i class="fa-solid fa-user text-muted opacity-75 me-2"></i>
                                {{ $return->sale->customer_relation->customer_name ?? 'N/A' }}
                            </td>
                            <td class="text-center fw-semibold">{{ $return->total_items }}</td>
                            <td class="text-end fw-bold text-slate-900">Rs. {{ number_format($return->total_net, 2) }}</td>
                            <td>
                                <span class="text-muted small" title="{{ $return->return_note }}">
                                    {{ Str::limit($return->return_note ?? '–', 30) }}
                                </span>
                            </td>
                            <td class="text-center text-muted">
                                <i class="fa-regular fa-calendar me-1"></i>
                                {{ $return->created_at->format('d-m-Y') }}
                            </td>
                            <td class="text-center">
                                <span class="badge-returned">
                                    <i class="fa-solid fa-circle-check"></i> Returned
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('saleReturn.invoice', $return->id) }}" target="_blank" class="btn-action-receipt">
                                    <i class="fa-solid fa-print"></i> Receipt
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- jQuery and DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#returns-table').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[ 0, "asc" ]],
            "language": {
                "search": "<i class='fa-solid fa-magnifying-glass text-muted me-1'></i> Search:",
                "searchPlaceholder": "Search returns...",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>

@endsection