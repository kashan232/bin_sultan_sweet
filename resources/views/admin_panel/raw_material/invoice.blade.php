@extends('admin_panel.layout.app')

@section('content')
<style>
    @media print {
        body * { visibility: hidden; }
        #invoice-print, #invoice-print * { visibility: visible; }
        #invoice-print { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
    .invoice-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 2.5rem;
        max-width: 850px;
        margin: 2rem auto;
        font-family: 'Inter', sans-serif;
    }
    .invoice-header {
        border-bottom: 2px solid #e9edf2;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .invoice-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0b1a33;
    }
    .invoice-badge {
        background: #eef2ff;
        color: #3b5bb3;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.35rem 0.8rem;
        border-radius: 6px;
    }
    .tbl-inv {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
    }
    .tbl-inv th {
        background: #f8fafc;
        color: #54657e;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #e9edf2;
    }
    .tbl-inv td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f4f9;
        font-size: 0.88rem;
        color: #0b1a33;
    }
    .total-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1.25rem;
        margin-top: 1.5rem;
        border: 1px solid #e9edf2;
    }
</style>

<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 max-w-850 mx-auto no-print" style="max-width: 850px;">
        <a href="{{ route('raw_materials.index', ['tab' => 'purchases']) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Purchases
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print me-1"></i> Print Invoice
        </button>
    </div>

    <div class="invoice-card" id="invoice-print">
        <div class="invoice-header d-flex justify-content-between align-items-start">
            <div>
                <h3 class="invoice-title mb-1">Bin Sultan Sweets</h3>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Raw Material Purchase Invoice</p>
            </div>
            <div class="text-end">
                <span class="invoice-badge mb-2 d-inline-block">{{ $purchase->purchase_no }}</span>
                <p class="text-muted mb-0" style="font-size: 0.82rem;">Date: <strong>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-M-Y') }}</strong></p>
            </div>
        </div>

        <div class="row mb-4" style="font-size: 0.88rem;">
            <div class="col-6">
                <strong class="text-muted d-block text-uppercase mb-1" style="font-size: 0.72rem;">Vendor Details:</strong>
                <h6 class="fw-bold mb-1" style="color: #0b1a33;">{{ $purchase->vendor->name ?? 'N/A' }}</h6>
                <p class="mb-0 text-muted">{{ $purchase->vendor->phone ?? 'Phone N/A' }}</p>
                <p class="mb-0 text-muted">{{ $purchase->vendor->address ?? 'Address N/A' }}</p>
            </div>
            <div class="col-6 text-end">
                <strong class="text-muted d-block text-uppercase mb-1" style="font-size: 0.72rem;">Payment Status:</strong>
                @if($purchase->payment_status === 'paid')
                    <span class="badge bg-success" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">PAID</span>
                @elseif($purchase->payment_status === 'partial')
                    <span class="badge bg-warning text-dark" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">PARTIAL</span>
                @else
                    <span class="badge bg-danger" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">UNPAID</span>
                @endif
            </div>
        </div>

        <table class="tbl-inv">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Raw Material Item</th>
                    <th class="text-center">Unit</th>
                    <th class="text-end">Quantity</th>
                    <th class="text-end">Unit Rate (Rs)</th>
                    <th class="text-end">Line Total (Rs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $item->rawMaterial->name ?? 'Deleted Item' }}</strong></td>
                    <td class="text-center">{{ $item->unit ?? 'KG' }}</td>
                    <td class="text-end">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-end fw-bold">{{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row justify-content-end">
            <div class="col-md-6 col-lg-5">
                <div class="total-box">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 0.85rem;">
                        <span class="text-muted">Subtotal:</span>
                        <strong style="color: #0b1a33;">Rs {{ number_format($purchase->subtotal, 2) }}</strong>
                    </div>
                    @if($purchase->discount > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger" style="font-size: 0.85rem;">
                        <span>Discount:</span>
                        <strong>- Rs {{ number_format($purchase->discount, 2) }}</strong>
                    </div>
                    @endif
                    @if($purchase->extra_cost > 0)
                    <div class="d-flex justify-content-between mb-2 text-primary" style="font-size: 0.85rem;">
                        <span>Extra Cost / Freight:</span>
                        <strong>+ Rs {{ number_format($purchase->extra_cost, 2) }}</strong>
                    </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 1rem;">
                        <strong style="color: #0b1a33;">Net Total:</strong>
                        <strong class="text-primary" style="font-size: 1.1rem;">Rs {{ number_format($purchase->net_amount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1" style="font-size: 0.85rem;">
                        <span class="text-success">Paid Amount:</span>
                        <strong class="text-success">Rs {{ number_format($purchase->paid_amount, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size: 0.85rem;">
                        <span class="text-danger">Balance Due:</span>
                        <strong class="text-danger">Rs {{ number_format($purchase->due_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @if($purchase->note)
        <div class="mt-4 pt-3 border-top">
            <strong class="text-muted d-block text-uppercase mb-1" style="font-size: 0.72rem;">Notes:</strong>
            <p class="text-secondary mb-0" style="font-size: 0.85rem;">{{ $purchase->note }}</p>
        </div>
        @endif

        <div class="text-center mt-5 pt-4 text-muted border-top" style="font-size: 0.78rem;">
            Thank you for doing business with Bin Sultan Sweets.
        </div>
    </div>
</div>
@endsection
