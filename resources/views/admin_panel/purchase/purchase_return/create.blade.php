@extends('admin_panel.layout.app')

@section('content')
<style>
    :root {
        --pr-primary: #4f46e5;
        --pr-primary-light: #818cf8;
        --pr-bg: #f0f2f5;
        --pr-card-bg: #ffffff;
        --pr-border: rgba(0,0,0,0.04);
        --pr-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        --pr-text: #1e293b;
        --pr-text-muted: #94a3b8;
    }

    .pr-page { background: var(--pr-bg); min-height: 100vh; padding: 24px; }
    .pr-card { background: var(--pr-card-bg); border: 1px solid var(--pr-border); border-radius: 20px; box-shadow: var(--pr-shadow); overflow: hidden; }
    .pr-card-body { padding: 24px; }
    .pr-card-header {
        background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pr-card-header h4 { color: #fff; font-weight: 800; margin: 0; font-size: 18px; }

    .pr-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--pr-text-muted); margin-bottom: 4px; display: block; }
    .pr-input, .pr-select {
        background: #f8fafc !important;
        border: 2px solid transparent !important;
        border-radius: 10px !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: var(--pr-text) !important;
        transition: all 0.2s;
        height: auto !important;
        line-height: 1.4 !important;
    }
    .pr-input:focus, .pr-select:focus {
        background: #fff !important;
        border-color: var(--pr-primary) !important;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1) !important;
    }
    .pr-input-sm { padding: 5px 10px !important; font-size: 12px !important; border-radius: 8px !important; }
    .pr-input[readonly] { background: #f1f5f9 !important; color: #64748b !important; cursor: not-allowed; }

    .pr-table { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .pr-table thead th { background: #f1f5f9; color: var(--pr-text-muted); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .pr-table tbody td { padding: 6px 10px; font-size: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; color: var(--pr-text); }
    .pr-table tbody tr:last-child td { border-bottom: none; }
    .pr-table .pr-cell-note { max-width: 120px; }

    .pr-scroll { max-height: 220px; overflow-y: auto; }
    .pr-scroll::-webkit-scrollbar { width: 4px; }
    .pr-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    .pr-chk { width: 18px; height: 18px; border-radius: 4px; border: 2px solid #cbd5e1; cursor: pointer; accent-color: var(--pr-primary); }

    .pr-btn { border: none; border-radius: 10px; padding: 8px 18px; font-size: 13px; font-weight: 700; transition: all 0.25s; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .pr-btn-primary { background: var(--pr-primary); color: #fff; box-shadow: 0 4px 14px rgba(79,70,229,0.3); }
    .pr-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.35); }
    .pr-btn-danger { background: #ef4444; color: #fff; }
    .pr-btn-danger:hover { background: #dc2626; }

    .pr-summary { display: flex; gap: 16px; flex-wrap: wrap; }
    .pr-summary-item { flex: 1; min-width: 150px; }
    .pr-summary-item .pr-label { margin-bottom: 2px; }

    .pr-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .pr-badge-avail { background: #dcfce7; color: #166534; }

    .pr-muted { font-size: 11px; color: var(--pr-text-muted); font-weight: 500; }
</style>

<div class="pr-page">
    <div class="pr-card">
        <div class="pr-card-header">
            <h4><i class="bi bi-arrow-return-left me-2"></i>Purchase Return</h4>
            <span style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:600;">Invoice #{{ $purchase->invoice_no }}</span>
        </div>
        <div class="pr-card-body">
            @if ($errors->any())
            <div class="alert alert-danger" style="border-radius:12px;font-size:13px;">
                <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
            @endif
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;font-size:13px;">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('purchase.return.store') }}" method="POST">
                @csrf
                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                <input type="hidden" name="purchase_to" value="{{ $purchase->purchase_to }}">

                {{-- HEADER FIELDS --}}
                <div class="row g-2 mb-3">
                    <div class="col-xl-2 col-md-4 col-6">
                        <span class="pr-label">Purchase Date</span>
                        <input name="purchase_date" type="date" class="pr-input form-control" value="{{ $purchase->purchase_date instanceof \Carbon\Carbon ? $purchase->purchase_date->format('Y-m-d') : $purchase->purchase_date }}" readonly>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <span class="pr-label">Return Date</span>
                        <input name="return_date" type="date" class="pr-input form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-xl-3 col-md-4 col-6">
                        <span class="pr-label">Vendor</span>
                        <input type="hidden" name="vendor_id" value="{{ $purchase->vendor_id }}">
                        <input type="text" class="pr-input form-control" value="{{ $purchase->vendor->name ?? '-' }}" readonly>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <span class="pr-label">Invoice #</span>
                        <input name="purchase_order_no" type="text" class="pr-input form-control" value="{{ $purchase->invoice_no }}">
                    </div>
                    <div class="col-xl-3 col-md-4 col-6">
                        <span class="pr-label">Job / Description</span>
                        <input name="note" type="text" class="pr-input form-control" value="{{ $purchase->note }}">
                    </div>
                </div>

                {{-- PURCHASED ITEMS TABLE --}}
                <div class="mb-3">
                    <h6 style="font-weight:800;font-size:14px;color:var(--pr-text);margin-bottom:8px;"><i class="bi bi-cart-check me-1" style="color:var(--pr-primary);"></i> Purchased Items</h6>
                    <div class="pr-scroll">
                        <table class="pr-table" id="purchasedItemsTable" style="table-layout:fixed;">
                            <colgroup>
                                <col style="width:36px">
                                <col style="width:145px">
                                <col style="width:65px">
                                <col style="width:80px">
                                <col style="width:80px">
                                <col style="width:36px">
                                <col style="width:70px">
                                <col style="width:55px">
                                <col style="width:55px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Ret?</th>
                                    <th>Product</th>
                                    <th>Item Note</th>
                                    <th>Code</th>
                                    <th>Brand</th>
                                    <th>Unit</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Avail.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $item)
                                <tr data-product-id="{{ $item->product_id }}" data-variant-id="{{ $item->variant_id ?? '' }}" data-price="{{ $item->price }}" data-unit="{{ $item->unit }}" data-item-disc="{{ $item->item_discount ?? 0 }}" data-item-note="{{ $item->note ?? '' }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="pr-chk select-return-item" {{ ($item->available_qty ?? $item->qty) <= 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td style="font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $item->product->item_name ?? '-' }}">{{ $item->product->item_name ?? '-' }}</td>
                                    <td><input type="text" class="pr-input pr-input-sm form-control" value="{{ $item->note }}" readonly style="width:100%;"></td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $item->product->item_code ?? '-' }}">{{ $item->product->item_code ?? '-' }}</td>
                                    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $item->product->brand->name ?? '-' }}">{{ $item->product->brand->name ?? '-' }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->price,0) }}</td>
                                    <td class="text-end">{{ $item->qty }}</td>
                                    <td class="text-end available-qty"><span class="pr-badge pr-badge-avail">{{ $item->available_qty ?? $item->qty }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- RETURN ITEMS TABLE --}}
                <div class="mb-3">
                    <h6 style="font-weight:800;font-size:14px;color:var(--pr-text);margin-bottom:8px;"><i class="bi bi-arrow-return-left me-1" style="color:var(--pr-primary);"></i> Items Selected for Return</h6>
                    <div style="max-height:260px;overflow-y:auto;">
                        <table class="pr-table" id="returnItemsTable" style="table-layout:fixed;">
                            <colgroup>
                                <col style="width:135px">
                                <col style="width:60px">
                                <col style="width:70px">
                                <col style="width:70px">
                                <col style="width:32px">
                                <col style="width:70px">
                                <col style="width:115px">
                                <col style="width:62px">
                                <col style="width:62px">
                                <col style="width:55px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Note</th>
                                    <th>Code</th>
                                    <th>Brand</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Ret. Qty</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- SUMMARY --}}
                <div class="pr-summary mt-3 mb-3">
                    <div class="pr-summary-item">
                        <span class="pr-label">Subtotal</span>
                        <input type="text" id="summarySubtotal" name="subtotal" value="0.00" readonly class="pr-input form-control">
                    </div>
                    <div class="pr-summary-item">
                        <span class="pr-label">Discount (Overall)</span>
                        <input type="number" step="0.01" id="overallDiscount" class="pr-input form-control" name="discount" value="0">
                    </div>
                    <div class="pr-summary-item">
                        <span class="pr-label">Extra Cost</span>
                        <input type="number" step="0.01" id="extraCost" class="pr-input form-control" name="extra_cost" value="0">
                    </div>
                    <div class="pr-summary-item">
                        <span class="pr-label">Net Amount</span>
                        <input type="text" id="netAmount" name="net_amount" class="pr-input form-control fw-bold" value="0" readonly style="color:var(--pr-primary)!important;">
                    </div>
                </div>

                <button type="submit" class="pr-btn pr-btn-primary"><i class="bi bi-check2"></i> Submit Return</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        function num(v) { return isNaN(parseFloat(v)) ? 0 : parseFloat(v); }
        function pkrFromPct(pct, price) { return price ? (price * pct) / 100 : 0; }
        function pctFromPkr(pkr, price) { return price ? (pkr / price) * 100 : 0; }

        function recalcReturnRow($row) {
            const qty = num($row.find('.qty-input').val());
            const price = num($row.find('.price-input').val());
            const discPc = num($row.find('.disc-pkr').val());
            let total = (price * qty) - (discPc * qty);
            if (total < 0) total = 0;
            $row.find('.row-total').val(total.toFixed(2));
        }

        $('#purchasedItemsTable').on('change', '.select-return-item', function() {
            const $row = $(this).closest('tr');
            const productId = $row.data('product-id').toString();
            const variantId = ($row.data('variant-id') || '').toString();
            const price = num($row.data('price'));
            const unit = $row.data('unit') || '';
            const itemDisc = num($row.data('item-disc'));
            const productName = $row.find('td').eq(1).text().trim();
            const itemCode = $row.find('td').eq(3).text().trim();
            const brand = $row.find('td').eq(4).text().trim();
            const availableQty = num($row.find('.available-qty').text());

            if (this.checked) {
                if (availableQty <= 0) { this.checked = false; return; }
                if ($('#returnItemsTable tbody tr[data-product-id="' + productId + '"][data-variant-id="' + variantId + '"]').length) return;
                let returnQtyDefault = (availableQty < 1) ? availableQty : 1;
                const rowHtml = `
<tr data-product-id="${productId}" data-variant-id="${variantId}">
    <td style="font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${productName}">${productName}
        <input type="hidden" name="product_id[]" value="${productId}">
        <input type="hidden" name="variant_id[]" value="${variantId}">
    </td>
    <td>
        <input type="hidden" name="item_note[]" class="item-note-hidden" value="${$row.data('item-note') || ''}">
        <input type="text" class="pr-input pr-input-sm form-control item-note-visible" value="${$row.data('item-note') || ''}" placeholder="Note" style="width:100%;">
    </td>
    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${itemCode}">${itemCode}</td>
    <td style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${brand}">${brand}</td>
    <td>${unit}<input type="hidden" name="unit[]" value="${unit}"></td>
    <td><input type="number" step="0.01" name="price[]" class="pr-input pr-input-sm form-control price-input" value="${price}" style="width:100%;"></td>
    <td>
        <div class="d-flex gap-1 align-items-center">
            <input type="number" step="0.01" name="item_disc[]" class="pr-input pr-input-sm form-control disc-pkr" placeholder="PKR" style="width:48%;">
            <div style="width:48%;display:flex;align-items:center;">
                <input type="number" step="0.01" class="pr-input pr-input-sm form-control disc-pct" placeholder="%" style="width:65%;">
                <span style="width:35%;text-align:center;font-size:11px;">%</span>
            </div>
        </div>
    </td>
    <td>
        <input type="number" step="0.01" name="qty[]" class="pr-input pr-input-sm form-control qty-input" value="${returnQtyDefault}" min="0.01" max="${availableQty}" style="width:100%;">
        <div class="pr-muted">Max: ${availableQty}</div>
    </td>
    <td><input type="text" name="total[]" class="pr-input pr-input-sm form-control row-total" readonly style="width:100%;"></td>
    <td class="text-center"><button type="button" class="pr-btn pr-btn-danger remove-return-item" style="padding:4px 10px;font-size:11px;"><i class="bi bi-x"></i></button></td>
</tr>`;
                $('#returnItemsTable tbody').append(rowHtml);
                let displayAvail = parseFloat((availableQty - returnQtyDefault).toFixed(2));
                $row.find('.available-qty').html('<span class="pr-badge pr-badge-avail">' + displayAvail + '</span>');
                if (displayAvail <= 0) $row.find('.select-return-item').prop('disabled', true);
            } else {
                const $returnRow = $('#returnItemsTable tbody tr[data-product-id="' + productId + '"][data-variant-id="' + variantId + '"]');
                if ($returnRow.length) {
                    const prevQty = num($returnRow.find('.qty-input').val());
                    const currentAvailable = num($row.find('.available-qty').text());
                    let restored = parseFloat((currentAvailable + prevQty).toFixed(2));
                    $row.find('.available-qty').html('<span class="pr-badge pr-badge-avail">' + restored + '</span>');
                    $row.find('.select-return-item').prop('disabled', false);
                    $returnRow.remove();
                }
            }
            recalcAll();
        });

        $('#returnItemsTable').on('input', '.item-note-visible', function() {
            $(this).closest('td').find('.item-note-hidden').val($(this).val());
        });

        $('#returnItemsTable').on('click', '.remove-return-item', function() {
            const $returnRow = $(this).closest('tr');
            const productId = $returnRow.data('product-id');
            const qtyRemoved = num($returnRow.find('.qty-input').val());
            const $topRow = $('#purchasedItemsTable tbody tr[data-product-id="' + productId + '"]');
            if ($topRow.length) {
                const curAvailable = num($topRow.find('.available-qty').text());
                let restored = parseFloat((curAvailable + qtyRemoved).toFixed(2));
                $topRow.find('.available-qty').html('<span class="pr-badge pr-badge-avail">' + restored + '</span>');
                $topRow.find('.select-return-item').prop('checked', false).prop('disabled', false);
            }
            $returnRow.remove();
            recalcAll();
        });

        $('#returnItemsTable').on('input', '.disc-pkr', function() {
            const $row = $(this).closest('tr');
            if ($row.data('syncing')) return;
            const price = num($row.find('.price-input').val());
            const pkr = num($(this).val());
            const pct = pctFromPkr(pkr, price);
            $row.data('syncing', true);
            $row.find('.disc-pct').val(pct ? pct.toFixed(2) : '');
            $row.data('syncing', false);
            recalcReturnRow($row);
            recalcAll();
        });

        $('#returnItemsTable').on('input', '.disc-pct', function() {
            const $row = $(this).closest('tr');
            if ($row.data('syncing')) return;
            const price = num($row.find('.price-input').val());
            const pct = num($(this).val());
            const pkr = pkrFromPct(pct, price);
            $row.data('syncing', true);
            $row.find('.disc-pkr').val(pkr ? pkr.toFixed(2) : '');
            $row.data('syncing', false);
            recalcReturnRow($row);
            recalcAll();
        });

        $('#returnItemsTable').on('input', '.qty-input, .price-input', function() {
            recalcReturnRow($(this).closest('tr'));
            recalcAll();
        });

        function recalcAll() {
            let subtotal = 0;
            $('#returnItemsTable tbody tr').each(function() { subtotal += num($(this).find('.row-total').val()); });
            $('#summarySubtotal').val(subtotal.toFixed(2));
            const oDisc = num($('#overallDiscount').val());
            const xCost = num($('#extraCost').val());
            const net = Math.max(0, (subtotal - oDisc + xCost));
            $('#netAmount').val(net.toFixed(2));
        }

        $('#overallDiscount, #extraCost').on('input', recalcAll);
        recalcAll();
    });
</script>
@endsection