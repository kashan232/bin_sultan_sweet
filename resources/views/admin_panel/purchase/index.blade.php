@extends('admin_panel.layout.app')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

:root {
  --pi-bg: #f1f4f9;
  --pi-surface: #ffffff;
  --pi-border: #e9edf2;
  --pi-border-lt: #f1f4f9;
  --pi-text: #0b1a33;
  --pi-text-sec: #54657e;
  --pi-text-muted: #8896ab;
  --pi-accent: #2b7fff;
  --pi-accent-drk: #1a6ae8;
  --pi-success: #0fae6b;
  --pi-danger: #e54545;
  --pi-warning: #f5a623;
  --pi-radius: 14px;
  --pi-radius-sm: 9px;
  --pi-shadow: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.05);
  --pi-shadow-lg: 0 8px 30px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.04);
  --pi-shadow-xl: 0 20px 60px rgba(0,0,0,.10), 0 8px 24px rgba(0,0,0,.06);
  --pi-font: 'Inter', -apple-system, 'Segoe UI', Roboto, sans-serif;
}

.pi-page * { font-family: var(--pi-font); }

.pi-page {
  background: var(--pi-bg);
  min-height: 100vh;
  padding-bottom: 2.5rem;
}

.pi-page .container-fluid { overflow: visible; }

/* ═══════ HEADER ═══════ */
.pi-hdr {
  position: relative;
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--pi-radius);
  padding: 1.4rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--pi-shadow-xl);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}

.pi-hdr::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%),
    radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%);
  pointer-events: none;
}

.pi-hdr::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: .5;
  pointer-events: none;
}

.pi-hdr > * { position: relative; z-index: 1; }

.pi-hdr h2 {
  font-size: 1.4rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -.4px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .65rem;
}

.pi-hdr h2 i { font-size: 1.45rem; color: #60a5fa; }

.pi-hdr .hdr-badge {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: 20px;
  padding: .25rem .9rem;
  font-size: .7rem;
  font-weight: 600;
  color: rgba(255,255,255,.65);
  letter-spacing: .4px;
  text-transform: uppercase;
}

.pi-hdr-actions { display: flex; gap: .6rem; flex-wrap: wrap; }

.pi-btn {
  display: inline-flex;
  align-items: center;
  gap: .45rem;
  border-radius: var(--pi-radius-sm);
  font-weight: 600;
  font-size: .82rem;
  transition: all .25s ease;
  cursor: pointer;
  text-decoration: none;
}

.pi-btn-primary {
  background: linear-gradient(135deg, var(--pi-accent) 0%, var(--pi-accent-drk) 100%);
  border: none; color: #fff;
  padding: .5rem 1.35rem;
}

.pi-btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(43,127,255,.25);
  color: #fff; text-decoration: none;
}

.pi-btn-ghost {
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  color: rgba(255,255,255,.7);
  padding: .5rem 1.2rem;
}

.pi-btn-ghost:hover {
  background: rgba(255,255,255,.12);
  color: #fff; text-decoration: none;
}

.pi-btn-sm {
  padding: .35rem .9rem;
  font-size: .78rem;
}

/* ═══════ CARD ═══════ */
.pi-card {
  background: var(--pi-surface);
  border: 1px solid var(--pi-border);
  border-radius: var(--pi-radius);
  box-shadow: var(--pi-shadow);
  margin-bottom: 1.25rem;
  transition: box-shadow .3s ease;
}

.pi-card:hover { box-shadow: var(--pi-shadow-lg); }

.pi-card-body { padding: 1.5rem; }

/* ═══════ FILTER ═══════ */
.pi-filter {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  gap: 1rem;
}

.pi-filter-group {
  display: flex;
  flex-direction: column;
  gap: .3rem;
}

.pi-filter-group label {
  font-size: .75rem;
  font-weight: 600;
  color: var(--pi-text-sec);
  text-transform: uppercase;
  letter-spacing: .4px;
}

.pi-filter-group input {
  border: 1.5px solid var(--pi-border);
  border-radius: var(--pi-radius-sm);
  padding: .45rem .8rem;
  font-size: .85rem;
  font-weight: 500;
  color: var(--pi-text);
  background: var(--pi-surface);
  transition: all .2s ease;
  outline: none;
  min-width: 170px;
}

.pi-filter-group input:focus {
  border-color: var(--pi-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}

.pi-filter-actions {
  display: flex;
  gap: .5rem;
  align-items: flex-end;
  padding-bottom: 1px;
}

.pi-fbtn {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  border-radius: var(--pi-radius-sm);
  font-weight: 600;
  font-size: .82rem;
  padding: .45rem 1.1rem;
  transition: all .2s ease;
  cursor: pointer;
  text-decoration: none;
  border: none;
}

.pi-fbtn-primary {
  background: var(--pi-accent);
  color: #fff;
}

.pi-fbtn-primary:hover { background: var(--pi-accent-drk); color: #fff; }

.pi-fbtn-ghost {
  background: transparent;
  border: 1.5px solid var(--pi-border);
  color: var(--pi-text-sec);
}

.pi-fbtn-ghost:hover { border-color: #c8d0dd; color: var(--pi-text); }

/* ═══════ ALERT BANNER ═══════ */
.pi-alert {
  background: #eef2ff;
  border: 1px solid #dde4f7;
  border-radius: var(--pi-radius-sm);
  padding: .7rem 1rem;
  font-size: .85rem;
  color: var(--pi-text);
  display: flex;
  align-items: center;
  gap: .5rem;
  margin-bottom: 1rem;
}

.pi-alert i { color: var(--pi-accent); }

/* ═══════ TABLE ═══════ */
.pi-tbl-wrap {
  overflow-x: auto;
  border: 1px solid var(--pi-border);
  border-radius: var(--pi-radius-sm);
}

.pi-tbl {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: .84rem;
}

.pi-tbl thead th {
  background: #f8fafc;
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--pi-text-muted);
  padding: .65rem .75rem;
  border-bottom: 2px solid var(--pi-border);
  white-space: nowrap;
  text-align: center;
}

.pi-tbl thead th:first-child { text-align: center; }

.pi-tbl tbody td {
  padding: .5rem .75rem;
  border-bottom: 1px solid var(--pi-border-lt);
  vertical-align: middle;
  text-align: center;
}

.pi-tbl tbody tr { transition: background .12s ease; }

.pi-tbl tbody tr:hover { background: #fafbfc; }

.pi-tbl tbody tr:last-child td { border-bottom: none; }

/* returned row */
.pi-tbl tbody tr.pi-returned {
  background: #fef2f2 !important;
}

.pi-tbl tbody tr.pi-returned td {
  color: #991b1b;
}

.pi-tbl tbody tr.pi-returned:hover {
  background: #fde8e8 !important;
}

/* product/qty columns */
.pi-prod-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
  text-align: left;
}

.pi-prod-list .pi-prod-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: .82rem;
  line-height: 1.4;
}

.pi-prod-list .pi-prod-item .pi-prod-qty {
  background: var(--pi-border-lt);
  border-radius: 4px;
  padding: 0 6px;
  font-weight: 700;
  font-size: .75rem;
  color: var(--pi-text-sec);
  white-space: nowrap;
  flex-shrink: 0;
}

/* badges */
.pi-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  border-radius: 5px;
  padding: 2px 9px;
  font-size: .72rem;
  font-weight: 600;
}

.pi-badge-inward {
  background: #dbeafe;
  color: #1a4d8c;
}

.pi-badge-shop {
  background: #e8f5e9;
  color: #1b6b3a;
}

.pi-badge-wh {
  background: #fff3e0;
  color: #9a6d1b;
}

/* ═══════ AMOUNT STYLES ═══════ */
.pi-amount {
  font-weight: 700;
  font-variant-numeric: tabular-nums;
}

.pi-amount-net {
  color: var(--pi-accent);
  font-size: .9rem;
}

.pi-amount-paid {
  color: var(--pi-success);
}

.pi-amount-due {
  color: var(--pi-danger);
}

/* action buttons */
.pi-actions {
  display: flex;
  gap: 4px;
  justify-content: center;
}

.pi-act {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 3px;
  border-radius: 6px;
  padding: .3rem .65rem;
  font-size: .75rem;
  font-weight: 600;
  transition: all .2s ease;
  text-decoration: none;
  cursor: pointer;
  border: 1.5px solid transparent;
}

.pi-act-info {
  background: #eef2ff;
  border-color: #dde4f7;
  color: #3b5bb3;
}

.pi-act-info:hover { background: #dde4f7; color: #2a4a9e; text-decoration: none; }

.pi-act-danger {
  background: #fef2f2;
  border-color: #f5d0d0;
  color: #991b1b;
}

.pi-act-danger:hover { background: #fde8e8; color: #7f1d1d; text-decoration: none; }

/* ═══════ RESPONSIVE ═══════ */
@media (max-width: 768px) {
  .pi-hdr { padding: 1.1rem 1.25rem; }
  .pi-hdr h2 { font-size: 1.1rem; }
  .pi-card-body { padding: 1rem; }
  .pi-filter { flex-direction: column; align-items: stretch; }
  .pi-filter-group input { min-width: auto; }
}
</style>

@section('content')
<div class="pi-page">
  <div class="container-fluid px-3 px-md-4 py-3">

    {{-- ═══ HEADER ═══ --}}
    <div class="pi-hdr">
      <div class="d-flex align-items-center gap-3">
        <h2><i class="bi bi-cart-check"></i>Purchase List</h2>
        <span class="hdr-badge d-none d-sm-inline">{{ count($Purchase) }} Records</span>
      </div>
      <div class="pi-hdr-actions mt-2 mt-md-0">
        <a href="{{ route('add_purchase') }}" class="pi-btn pi-btn-primary">
          <i class="bi bi-plus-circle"></i>New Purchase
        </a>
        <a href="{{ url('/purchase/return') }}" class="pi-btn pi-btn-ghost">
          <i class="bi bi-arrow-repeat"></i>Returns
        </a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" style="border:none;border-radius:var(--pi-radius-sm);font-size:.86rem;padding:.75rem 1rem;">
      <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ═══ FILTER + TABLE CARD ═══ --}}
    <div class="pi-card">
      <div class="pi-card-body">

        {{-- FILTER --}}
        <form action="{{ route('Purchase.home') }}" method="GET" class="pi-filter mb-3">
          <div class="pi-filter-group">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ $start_date ?? '' }}">
          </div>
          <div class="pi-filter-group">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ $end_date ?? '' }}">
          </div>
          <div class="pi-filter-actions">
            <button type="submit" class="pi-fbtn pi-fbtn-primary"><i class="bi bi-funnel"></i>Filter</button>
            <a href="{{ route('Purchase.home') }}" class="pi-fbtn pi-fbtn-ghost"><i class="bi bi-arrow-clockwise"></i>Reset</a>
          </div>
        </form>

        @if(request('start_date') && request('end_date'))
        <div class="pi-alert">
          <i class="bi bi-info-circle"></i>
          Showing purchases from <strong>{{ request('start_date') }}</strong> to <strong>{{ request('end_date') }}</strong>.
        </div>
        @endif

        {{-- TABLE --}}
        <div class="pi-tbl-wrap">
          <table id="pi-table" class="pi-tbl">
            <thead>
              <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Branch</th>
                <th>Type</th>
                <th>Location</th>
                <th>Vendor</th>
                <th>Products</th>
                <th>Qty</th>
                <th>Note</th>
                <th>Subtotal</th>
                <th>Disc</th>
                <th>Extras</th>
                <th>Net</th>
                <th>Paid</th>
                <th>Due</th>
                <th>Date</th>
                <th style="min-width:105px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($Purchase as $purchase)
              <tr @if($purchase->return) class="pi-returned" @endif>
                <td class="fw-semibold" style="color:var(--pi-text-muted);">{{ $purchase->id }}</td>

                <td>
                  @if($purchase instanceof \App\Models\InwardGatepass)
                  <span class="fw-semibold">{{ $purchase->invoice_no }}</span>
                  <span class="pi-badge pi-badge-inward ms-1"><i class="bi bi-box-arrow-in-right"></i>Inward</span>
                  @else
                  <span class="fw-semibold">{{ $purchase->invoice_no }}</span>
                  @endif
                </td>

                <td>{{ $purchase->branch->name ?? 'N/A' }}</td>

                <td>
                  @php
                  $type = $purchase instanceof \App\Models\InwardGatepass
                    ? $purchase->receive_type
                    : ($purchase->purchase_to ?? 'shop');
                  @endphp
                  <span class="pi-badge {{ $type === 'warehouse' ? 'pi-badge-wh' : 'pi-badge-shop' }}">
                    <i class="bi {{ $type === 'warehouse' ? 'bi-building' : 'bi-shop' }}"></i>
                    {{ ucfirst($type) }}
                  </span>
                </td>

                <td>
                  @if($purchase instanceof \App\Models\InwardGatepass)
                    {{ $purchase->warehouse->warehouse_name ?? 'Shop' }}
                  @else
                    {{ ($purchase->purchase_to ?? 'shop') === 'warehouse'
                      ? ($purchase->warehouse->warehouse_name ?? '-')
                      : 'Shop' }}
                  @endif
                </td>

                <td class="fw-semibold">{{ $purchase->vendor->name ?? 'N/A' }}</td>

                {{-- Products stacked with qty badges --}}
                <td class="text-start">
                  <div class="pi-prod-list">
                    @forelse ($purchase->items as $item)
                    <div class="pi-prod-item">
                      <span class="pi-prod-qty">{{ $item->qty ?? 0 }}</span>
                      <span>{{ $item->product->item_name ?? 'N/A' }}</span>
                    </div>
                    @empty
                    <span style="color:var(--pi-text-muted);">—</span>
                    @endforelse
                  </div>
                </td>

                <td class="fw-semibold" style="color:var(--pi-text-sec);">
                  {{ $purchase->items->sum('qty') }}
                </td>

                <td style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--pi-text-muted);">
                  {{ $purchase->note ?? '—' }}
                </td>

                <td class="pi-amount">{{ number_format($purchase->subtotal ?? 0, 2) }}</td>
                <td class="pi-amount">{{ number_format($purchase->discount ?? 0, 2) }}</td>
                <td class="pi-amount">{{ number_format($purchase->extra_cost ?? 0, 2) }}</td>

                <td class="pi-amount pi-amount-net">{{ number_format($purchase->net_amount ?? 0, 2) }}</td>

                <td class="pi-amount pi-amount-paid">{{ number_format($purchase->paid_amount ?? 0, 2) }}</td>

                <td class="pi-amount pi-amount-due">{{ number_format($purchase->due_amount ?? 0, 2) }}</td>

                <td style="font-size:.82rem;color:var(--pi-text-sec);white-space:nowrap;">
                  {{ \Carbon\Carbon::parse(
                    $purchase instanceof \App\Models\InwardGatepass
                      ? $purchase->gatepass_date
                      : $purchase->purchase_date
                  )->format('d-m-Y') }}
                </td>

                <td>
                  <div class="pi-actions">
                    @if($purchase instanceof \App\Models\InwardGatepass)
                    <a href="{{ route('InwardGatepass.inv', $purchase->id) }}" class="pi-act pi-act-info">
                      <i class="bi bi-file-text"></i>Invoice
                    </a>
                    @else
                    <a href="{{ route('purchase.return.show', $purchase->id) }}" class="pi-act pi-act-danger">
                      <i class="bi bi-arrow-return-left"></i>Return
                    </a>
                    <a href="{{ route('purchase.invoice', $purchase->id) }}" class="pi-act pi-act-info">
                      <i class="bi bi-file-text"></i>Invoice
                    </a>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="17" class="text-center py-5" style="color:var(--pi-text-muted);">
                  <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#ced8e6;"></i>
                  <span style="font-weight:500;">No purchases found</span>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>

    {{-- ═══ MODAL (preserved from original) ═══ --}}
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border:none;border-radius:var(--pi-radius);box-shadow:var(--pi-shadow-xl);">
          <div class="modal-header" style="background:linear-gradient(135deg,#0b1a33,#162d50);color:#fff;border-radius:var(--pi-radius) var(--pi-radius) 0 0;border:none;">
            <h5 class="modal-title fw-bold" id="purchaseModalLabel">Add Purchase</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form class="myform" action="{{ route('store.Purchase') }}" method="POST">
              @csrf
              <input type="hidden" name="edit_id" id="id" />
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Invoice No</label>
                  <input type="text" name="invoice_no" class="form-control" id="invoice_no" required style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Supplier</label>
                  <input type="text" name="supplier" class="form-control" id="supplier" required style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Purchase Date</label>
                  <input type="date" name="purchase_date" class="form-control" id="purchase_date" required style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Warehouse</label>
                  <input type="text" name="warehouse_id" class="form-control" id="warehouse_id" required style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Item Category</label>
                  <input type="text" name="item_category" class="form-control" id="item_category" style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Item Name</label>
                  <input type="text" name="item_name" class="form-control" id="item_name" style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold" style="font-size:.82rem;color:var(--pi-text-sec);">Quantity</label>
                  <input type="number" name="quantity" class="form-control" id="quantity" style="border-radius:var(--pi-radius-sm);border:1.5px solid var(--pi-border);">
                </div>
              </div>
              <div class="modal-footer px-0 pb-0 border-0">
                <button type="button" class="btn px-3" style="border:1.5px solid var(--pi-border);border-radius:var(--pi-radius-sm);font-weight:600;color:var(--pi-text-sec);" data-bs-dismiss="modal">Close</button>
                <input type="submit" class="btn px-4" value="Save" style="background:linear-gradient(135deg,var(--pi-accent),var(--pi-accent-drk));border:none;border-radius:var(--pi-radius-sm);font-weight:600;color:#fff;">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).on('submit', '.myform', function(e) {
    e.preventDefault();
    var fd = new FormData(this);
    url = $(this).attr('action');
    method = $(this).attr('method');
    $(this).find(':submit').attr('disabled', true);
    myAjax(url, fd, method);
  });

  $(document).on('click', '.edit-btn', function() {
    var tr = $(this).closest("tr");
    $('#id').val(tr.find(".id").text());
    $('#invoice_no').val(tr.find(".invoice_no").text());
    $('#supplier').val(tr.find(".supplier").text());
    $('#purchase_date').val(tr.find(".purchase_date").text());
    $('#warehouse_id').val(tr.find(".warehouse_id").text());
    $("#purchaseModal").modal("show");
  });

  $(document).ready(function() {
    $('#pi-table').DataTable({
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50, 100],
      ordering: false,
      language: {
        search: "Search Purchase:",
        lengthMenu: "Show _MENU_ entries",
        emptyTable: "No purchases found"
      }
    });
  });
</script>
@endsection
