@extends('admin_panel.layout.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
:root {
  --pc-bg: #f1f4f9; --pc-surface: #ffffff; --pc-border: #e9edf2; --pc-border-lt: #f1f4f9;
  --pc-text: #0b1a33; --pc-text-sec: #54657e; --pc-text-muted: #8896ab;
  --pc-accent: #2b7fff; --pc-accent-drk: #1a6ae8; --pc-success: #0fae6b; --pc-danger: #e54545;
  --pc-radius: 14px; --pc-radius-sm: 9px;
  --pc-shadow: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.05);
  --pc-shadow-lg: 0 8px 30px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.04);
  --pc-shadow-xl: 0 20px 60px rgba(0,0,0,.10), 0 8px 24px rgba(0,0,0,.06);
  --pc-font: 'Inter', -apple-system, 'Segoe UI', Roboto, sans-serif;
}
.pc-page * { font-family: var(--pc-font); }
.pc-page { background: var(--pc-bg); min-height: 100vh; padding-bottom: 2.5rem; }

.pc-hdr {
  position: relative; background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--pc-radius); padding: 1.3rem 2rem; margin-bottom: 1.5rem;
  box-shadow: var(--pc-shadow-xl); display: flex; flex-wrap: wrap; align-items: center;
  justify-content: space-between; overflow: hidden;
}
.pc-hdr::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%), radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%); pointer-events: none; }
.pc-hdr::after { content: ''; position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); opacity: .5; pointer-events: none; }
.pc-hdr > * { position: relative; z-index: 1; }
.pc-hdr h2 { font-size: 1.35rem; font-weight: 800; color: #fff; letter-spacing: -.4px; margin: 0; display: flex; align-items: center; gap: .65rem; }
.pc-hdr h2 i { font-size: 1.4rem; color: #60a5fa; }
.pc-hdr .hdr-bal { font-size: .82rem; font-weight: 700; padding: .3rem 1rem; border-radius: 20px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1); }

.pc-btn {
  display: inline-flex; align-items: center; gap: .4rem; border-radius: var(--pc-radius-sm);
  font-weight: 600; font-size: .78rem; transition: all .25s ease; cursor: pointer;
  text-decoration: none; border: none; padding: .4rem 1rem;
}
.pc-btn-primary { background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%); color: #fff; }
.pc-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,127,255,.25); color: #fff; }
.pc-btn-ghost { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); color: #fff; }
.pc-btn-ghost:hover { background: rgba(255,255,255,.14); color: #fff; }
.pc-btn-sm { font-size: .72rem; padding: .35rem .85rem; }

.pc-card { background: var(--pc-surface); border: 1px solid var(--pc-border); border-radius: var(--pc-radius); box-shadow: var(--pc-shadow); transition: box-shadow .3s ease; }
.pc-card:hover { box-shadow: var(--pc-shadow-lg); }
.pc-card-body { padding: 1.5rem; }

.pc-tbl-wrap { overflow-x: auto; border: 1px solid var(--pc-border); border-radius: var(--pc-radius-sm); }
.pc-tbl { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .83rem; }
.pc-tbl thead th { background: #f8fafc; font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--pc-text-muted); padding: .55rem .7rem; border-bottom: 2px solid var(--pc-border); text-align: left; white-space: nowrap; }
.pc-tbl tbody td { padding: .5rem .7rem; border-bottom: 1px solid var(--pc-border-lt); vertical-align: middle; }
.pc-tbl tbody tr { transition: background .12s ease; }
.pc-tbl tbody tr:hover { background: #fafbfc; }
.pc-tbl tbody tr:last-child td { border-bottom: none; }

.pc-avatar { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .8rem; background: #eef2ff; color: #3b5bb3; flex-shrink: 0; }
.pc-name { font-weight: 700; color: var(--pc-text); }
.pc-phone { color: var(--pc-text-sec); }
.pc-bal { font-weight: 700; }
.pc-bal.pos { color: var(--pc-success); }
.pc-bal.neg { color: var(--pc-danger); }
.pc-addr { color: var(--pc-text-muted); font-size: .78rem; }

.pc-act { display: inline-flex; align-items: center; gap: 3px; border-radius: 5px; padding: .3rem .65rem; font-size: .72rem; font-weight: 600; transition: all .2s ease; text-decoration: none; border: 1.5px solid transparent; }
.pc-act-edit { background: #eef2ff; border-color: #dde4f7; color: #3b5bb3; }
.pc-act-edit:hover { background: #dde4f7; color: #2a4a9e; }
.pc-act-del { background: #fef2f2; border-color: #f5d0d0; color: #991b1b; }
.pc-act-del:hover { background: #fde8e8; color: #7f1d1d; }

.pc-empty { text-align: center; padding: 2.5rem .85rem; color: var(--pc-text-muted); }
.pc-empty i { font-size: 2rem; color: #ced8e6; display: block; margin-bottom: .5rem; }
.pc-empty span { font-size: .9rem; font-weight: 500; }

/* MODAL */
#vendorModal .modal-content { border: none; border-radius: var(--pc-radius); box-shadow: var(--pc-shadow-xl); }
#vendorModal .modal-header { background: linear-gradient(135deg, #0b1a33 0%, #162d50 100%); color: #fff; border-radius: var(--pc-radius) var(--pc-radius) 0 0; border: none; padding: 1rem 1.5rem; }
#vendorModal .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .6; }
#vendorModal .modal-header .btn-close:hover { opacity: 1; }
#vendorModal .modal-header h5 { font-weight: 700; font-size: 1rem; }
#vendorModal .modal-body { padding: 1.5rem; }
#vendorModal .modal-footer { border-top: 1px solid var(--pc-border-lt); padding: 1rem 1.5rem; }
.pc-lbl { font-size: .78rem; font-weight: 600; color: var(--pc-text-sec); margin-bottom: .3rem; }
.pc-fld { border: 1.5px solid var(--pc-border); border-radius: var(--pc-radius-sm); padding: .45rem .75rem; font-size: .84rem; font-weight: 500; color: var(--pc-text); background: var(--pc-surface); transition: all .25s ease; width: 100%; outline: none; }
.pc-fld:focus { border-color: var(--pc-accent); box-shadow: 0 0 0 3px rgba(43,127,255,.1); }
.pc-btn-s { background: transparent; border: 1.5px solid var(--pc-border); border-radius: var(--pc-radius-sm); padding: .4rem 1.2rem; font-weight: 600; font-size: .82rem; color: var(--pc-text-sec); transition: all .2s ease; cursor: pointer; }
.pc-btn-s:hover { border-color: #c8d0dd; color: var(--pc-text); }
.pc-btn-p { background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%); border: none; border-radius: var(--pc-radius-sm); padding: .4rem 1.5rem; font-weight: 600; font-size: .82rem; color: #fff; transition: all .3s ease; cursor: pointer; }
.pc-btn-p:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,127,255,.25); color: #fff; }

@media (max-width: 768px) { .pc-hdr { padding: 1rem 1.25rem; flex-direction: column; align-items: stretch; gap: .5rem; } .pc-hdr h2 { font-size: 1.1rem; } .pc-card-body { padding: 1rem; } }
</style>

<div class="pc-page">
<div class="container-fluid px-3 px-md-4 py-3">

  <div class="pc-hdr">
    <div class="d-flex align-items-center gap-3">
      <h2><i class="bi bi-people"></i>Vendors</h2>
      @php $totalColor = $totalClosingBalance < 0 ? 'style=color:#f87171' : 'style=color:#4ade80'; @endphp
      <span class="hdr-bal" {!! $totalColor !!}><i class="bi bi-wallet2"></i> Balance: Rs {{ number_format($totalClosingBalance, 2) }}</span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ url('vendors-ledger') }}" class="pc-btn pc-btn-ghost pc-btn-sm"><i class="bi bi-journal-text"></i>Ledger</a>
      <a href="{{ route('vendor.payments') }}" class="pc-btn pc-btn-ghost pc-btn-sm"><i class="bi bi-cash-coin"></i>Payments</a>
      <a href="{{ url('vendor/bilties') }}" class="pc-btn pc-btn-ghost pc-btn-sm"><i class="bi bi-truck"></i>Bilty</a>
      <button class="pc-btn pc-btn-primary pc-btn-sm" data-bs-toggle="modal" data-bs-target="#vendorModal" onclick="clearVendor()">
        <i class="bi bi-plus-circle"></i>Add Vendor
      </button>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-3" style="border:none;border-radius:var(--pc-radius-sm);font-size:.86rem;padding:.75rem 1rem;">
    <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="pc-card">
    <div class="pc-card-body">
      <div class="pc-tbl-wrap">
        <table id="vendorTable" class="pc-tbl" style="width:100%">
          <thead>
            <tr>
              <th style="width:45px;">S.No</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Opening Balance</th>
              <th>Closing Balance</th>
              <th>Address</th>
              <th style="width:120px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vendors as $key => $v)
            <tr>
              <td style="color:var(--pc-text-muted);font-weight:600;">{{ $key + 1 }}</td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="pc-avatar">{{ strtoupper(substr($v->name, 0, 1)) }}</div>
                  <span class="pc-name">{{ $v->name }}</span>
                </div>
              </td>
              <td class="pc-phone">{{ $v->phone }}</td>
              <td style="color:var(--pc-text-muted);">{{ number_format((float)$v->opening_balance, 2) }}</td>
              @php
                $balance = (float)($v->ledger->closing_balance ?? 0);
                $color = $balance < 0 ? 'neg' : 'pos';
              @endphp
              <td class="pc-bal {{ $color }}">{{ number_format($balance, 2) }}</td>
              <td class="pc-addr">{{ Str::limit($v->address, 30) }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="pc-act pc-act-edit btn-edit-vendor"
                    data-id="{{ $v->id }}" data-name="{{ $v->name }}"
                    data-phone="{{ $v->phone }}" data-opening="{{ $v->opening_balance }}"
                    data-address="{{ $v->address }}" title="Edit">
                    <i class="bi bi-pencil"></i>Edit
                  </button>
                  <a href="{{ url('vendor/delete/'.$v->id) }}" class="pc-act pc-act-del" onclick="return confirm('Delete this vendor?')" title="Delete">
                    <i class="bi bi-trash3"></i>Del
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- MODAL --}}
  <div class="modal fade" id="vendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-person me-1"></i> <span id="vendorModalLabel">Add Vendor</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ url('vendor/store') }}" method="POST">
          @csrf
          <input type="hidden" id="vendor_id" name="id">
          <div class="modal-body">
            <div class="mb-3">
              <label class="pc-lbl">Name</label>
              <input class="pc-fld" name="name" id="vname" placeholder="Enter vendor name" required>
            </div>
            <div class="mb-3">
              <label class="pc-lbl">Opening Balance</label>
              <input type="number" step="any" class="pc-fld" name="opening_balance" id="opening_balance" placeholder="0.00">
            </div>
            <div class="mb-3">
              <label class="pc-lbl">Phone</label>
              <input class="pc-fld" name="phone" id="vphone" placeholder="Enter phone number">
            </div>
            <div class="mb-3">
              <label class="pc-lbl">Address</label>
              <textarea class="pc-fld" name="address" id="vaddress" placeholder="Enter address" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="pc-btn-s" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="pc-btn-p">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('#vendorTable').DataTable({
      responsive: true, pageLength: 25, lengthMenu: [10, 25, 50, 100],
      order: [],
      language: { search: "", searchPlaceholder: "Search vendor..." }
    });

    window.clearVendor = function() {
      $('#vendor_id').val(''); $('#vname').val('');
      $('#opening_balance').val('').prop('readonly', false);
      $('#vphone').val(''); $('#vaddress').val('');
      $('#vendorModalLabel').text('Add Vendor');
    };

    $(document).on('click', '.btn-edit-vendor', function() {
      $('#vendor_id').val($(this).data('id'));
      $('#vname').val($(this).data('name'));
      $('#vphone').val($(this).data('phone'));
      $('#opening_balance').val($(this).data('opening')).prop('readonly', false);
      $('#vaddress').val($(this).data('address'));
      $('#vendorModalLabel').text('Edit Vendor');
      new bootstrap.Modal(document.getElementById('vendorModal')).show();
    });
  });
</script>
@endsection
