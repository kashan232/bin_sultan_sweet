@extends('admin_panel.layout.app')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

:root {
  --p-bg: #f1f4f9;
  --p-surface: #ffffff;
  --p-border: #e9edf2;
  --p-border-light: #f1f4f9;
  --p-text: #0b1a33;
  --p-text-secondary: #54657e;
  --p-text-muted: #8896ab;
  --p-primary: #1a4d8c;
  --p-primary-light: #e8f0fe;
  --p-primary-dark: #0d3b6e;
  --p-accent: #2b7fff;
  --p-accent-dark: #1a6ae8;
  --p-success: #0fae6b;
  --p-danger: #e54545;
  --p-warning: #f5a623;
  --p-radius: 14px;
  --p-radius-sm: 9px;
  --p-shadow-sm: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.05);
  --p-shadow: 0 2px 8px rgba(0,0,0,.04), 0 1px 4px rgba(0,0,0,.06);
  --p-shadow-lg: 0 8px 30px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.04);
  --p-shadow-xl: 0 20px 60px rgba(0,0,0,.10), 0 8px 24px rgba(0,0,0,.06);
  --p-font: 'Inter', -apple-system, 'Segoe UI', Roboto, sans-serif;
}

.pp * { font-family: var(--p-font); }

.pp {
  background: var(--p-bg);
  min-height: 100vh;
  padding-bottom: 3rem;
  overflow: visible;
}

.pp .container-fluid { overflow: visible; }

/* ═══════ HEADER ═══════ */
.pp-header {
  position: relative;
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--p-radius);
  padding: 1.5rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--p-shadow-xl);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}

.pp-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%),
    radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%);
  pointer-events: none;
}

.pp-header::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: .5;
  pointer-events: none;
}

.pp-header > * { position: relative; z-index: 1; }

.pp-header h2 {
  font-size: 1.35rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -.4px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .65rem;
}

.pp-header h2 i { font-size: 1.4rem; color: #60a5fa; }

.pp-header .hdr-badge {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 20px;
  padding: .25rem .9rem;
  font-size: .7rem;
  font-weight: 600;
  color: rgba(255,255,255,.65);
  letter-spacing: .4px;
  text-transform: uppercase;
}

.pp-back {
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.1);
  border-radius: var(--p-radius-sm);
  padding: .45rem 1.1rem;
  font-size: .82rem;
  font-weight: 600;
  color: rgba(255,255,255,.7);
  transition: all .2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: .45rem;
}

.pp-back:hover { background: rgba(255,255,255,.12); color: #fff; text-decoration: none; }

/* ═══════ CARDS ═══════ */
.pp-card {
  background: var(--p-surface);
  border: 1px solid var(--p-border);
  border-radius: var(--p-radius);
  box-shadow: var(--p-shadow-sm);
  margin-bottom: 1.25rem;
  transition: box-shadow .35s ease, transform .2s ease;
  overflow: visible;
}

.pp-card:hover { box-shadow: var(--p-shadow); }

.pp-card-hdr {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--p-border-light);
  font-size: .82rem;
  font-weight: 700;
  color: var(--p-text);
  letter-spacing: -.1px;
  display: flex;
  align-items: center;
  gap: .5rem;
  overflow: visible;
}

.pp-card-hdr i { font-size: 1rem; color: var(--p-accent); }

.pp-card-bd {
  padding: 1.5rem;
  overflow: visible;
}

.pp-card-bd.compact { padding: 0; }

/* ═══════ FORM ELEMENTS ═══════ */
.pp-lbl {
  font-size: .77rem;
  font-weight: 600;
  color: var(--p-text-secondary);
  margin-bottom: .35rem;
  letter-spacing: -.1px;
  display: flex;
  align-items: center;
  gap: .3rem;
}

.pp-lbl i { color: var(--p-accent); font-size: .8rem; }

.pp-fld {
  border: 1.5px solid var(--p-border);
  border-radius: var(--p-radius-sm);
  padding: .52rem .85rem;
  font-size: .88rem;
  font-weight: 500;
  color: var(--p-text);
  background: var(--p-surface);
  transition: all .25s ease;
  width: 100%;
  outline: none;
}

.pp-fld:focus {
  border-color: var(--p-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}

.pp-fld-lg { padding: .68rem 1.1rem; font-size: .95rem; }

.pp-fld::placeholder { color: var(--p-text-muted); font-weight: 400; }

select.pp-fld { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%238896ab' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }

/* ═══════ SEARCH ═══════ */
.pp-srch {
  position: relative;
  z-index: 1050;
}

.pp-srch .srch-icon {
  position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
  color: var(--p-text-muted); font-size: 1rem; pointer-events: none; z-index: 2;
}

.pp-srch .srch-spin {
  position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
  color: var(--p-accent); z-index: 2; display: none;
}

.pp-srch input { padding-left: 2.5rem !important; }

#ppResults {
  position: absolute; top: calc(100% + 6px); left: 0; right: 0;
  z-index: 99999; background: var(--p-surface);
  border: 1px solid var(--p-border);
  border-radius: var(--p-radius-sm);
  max-height: 400px; overflow-y: auto;
  box-shadow: var(--p-shadow-xl);
}

.pp-res-item {
  padding: 13px 18px; cursor: pointer;
  border-bottom: 1px solid var(--p-border-light);
  transition: all .12s ease; display: flex; flex-direction: column; gap: 3px;
}

.pp-res-item:last-child { border-bottom: none; }

.pp-res-item:hover, .pp-res-item.active {
  background: var(--p-primary-light);
  padding-left: 22px;
}

.pp-res-item .ri-nm {
  font-size: .9rem; font-weight: 700; color: var(--p-text); line-height: 1.3;
}

.pp-res-item .ri-meta {
  display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
}

.pp-res-item .ri-cd {
  font-size: .73rem; color: var(--p-text-muted); font-weight: 500;
  display: inline-flex; align-items: center; gap: 4px;
}

.pp-res-item .ri-pr {
  font-size: .77rem; font-weight: 700; color: var(--p-accent);
  display: inline-flex; align-items: center; gap: 4px;
}

.pp-res-item .ri-sz {
  font-size: .7rem; font-weight: 600; color: var(--p-accent);
  background: var(--p-primary-light); padding: 1px 8px; border-radius: 4px;
}

.pp-res-empty {
  padding: 28px 18px; text-align: center;
}

.pp-res-empty i { font-size: 1.7rem; color: var(--p-text-muted); margin-bottom: 6px; display: block; }
.pp-res-empty span { color: var(--p-text-muted); font-size: .86rem; font-weight: 500; }

/* ═══════ TABLE ═══════ */
.pp-tbl-wrap { overflow-x: auto; }

.pp-tbl {
  width: 100%; border-collapse: separate; border-spacing: 0;
}

.pp-tbl thead th {
  background: #f8fafc;
  font-size: .71rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .6px; color: var(--p-text-muted);
  padding: .7rem .9rem; border-bottom: 2px solid var(--p-border);
  white-space: nowrap;
}

.pp-tbl tbody td {
  padding: .5rem .9rem; border-bottom: 1px solid var(--p-border-light);
  vertical-align: middle; font-size: .86rem; color: var(--p-text);
}

.pp-tbl tbody tr { transition: background .15s ease; }
.pp-tbl tbody tr:hover { background: #fafbfc; }
.pp-tbl tbody tr:last-child td { border-bottom: none; }

.pp-tbl .t-inp {
  border: 1.5px solid var(--p-border);
  border-radius: 6px; padding: .3rem .5rem; font-size: .84rem;
  font-weight: 600; color: var(--p-text); background: var(--p-surface);
  transition: all .2s ease; width: auto;
}

.pp-tbl .t-inp:focus {
  border-color: var(--p-accent);
  box-shadow: 0 0 0 2px rgba(43,127,255,.1);
  outline: none;
}

.pp-tbl .t-inp.qty { text-align: center; font-weight: 600; }

.pp-tbl .t-total {
  font-weight: 700; color: var(--p-text);
}

.pp-tbl .t-del {
  border: 1.5px solid #f1e0e0; border-radius: 6px;
  background: transparent; color: var(--p-danger);
  padding: .22rem .55rem; font-size: .84rem;
  transition: all .2s ease; cursor: pointer; opacity: .5;
}

.pp-tbl tr:hover .t-del { opacity: 1; }

.pp-tbl .t-del:hover { background: #fdf2f2; border-color: #f5c8c8; }

.pp-tbl .t-var {
  display: inline-flex; align-items: center; gap: 3px;
  background: linear-gradient(135deg, #eef2ff 0%, #f4f7ff 100%);
  color: #3b5bb3; border-radius: 4px;
  padding: 2px 9px; font-size: .72rem; font-weight: 600; margin-left: 4px;
}

.pp-tbl .t-empty td { padding: 2.8rem .9rem !important; text-align: center; }
.pp-tbl .t-empty i { font-size: 2rem; color: #ced8e6; display: block; margin-bottom: .5rem; }
.pp-tbl .t-empty span { font-size: .9rem; font-weight: 500; color: var(--p-text-muted); }

/* ═══════ COUNT BADGE ═══════ */
.pp-cnt {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--p-primary-light); color: var(--p-primary);
  border-radius: 20px; padding: .25rem .85rem;
  font-size: .75rem; font-weight: 700;
}

/* ═══════ SUMMARY ═══════ */
.pp-sum {
  background: #fafbfc;
  border: 1.5px solid var(--p-border);
  border-radius: var(--p-radius-sm);
  padding: 1.25rem 1.5rem;
}

.pp-sum .sm-lbl {
  font-size: .71rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .6px; color: var(--p-text-muted); margin-bottom: .25rem;
}

.pp-sum .sm-val {
  font-size: 1.15rem; font-weight: 700; color: var(--p-text);
}

.pp-sum .sm-val.net {
  font-size: 1.6rem; color: var(--p-accent);
}

.pp-sum .sm-inp {
  border: 1.5px solid var(--p-border); border-radius: var(--p-radius-sm);
  padding: .45rem .75rem; font-size: .88rem; font-weight: 600;
  color: var(--p-text); background: var(--p-surface);
  transition: all .2s ease; width: 100%; outline: none;
}

.pp-sum .sm-inp:focus {
  border-color: var(--p-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}

/* ═══════ BUTTONS ═══════ */
.pp-btn {
  background: linear-gradient(135deg, var(--p-accent) 0%, var(--p-accent-dark) 100%);
  border: none; border-radius: var(--p-radius-sm);
  padding: .58rem 2.2rem; font-weight: 700; font-size: .88rem;
  color: #fff; transition: all .3s ease; cursor: pointer;
  display: inline-flex; align-items: center; gap: .5rem;
  position: relative; overflow: hidden;
}

.pp-btn::after {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,.08) 0%, transparent 60%);
  pointer-events: none;
}

.pp-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(43,127,255,.28);
  color: #fff;
}

.pp-btn:active { transform: translateY(0); }
.pp-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; box-shadow: none; }

.pp-btn-s {
  background: transparent; border: 1.5px solid var(--p-border);
  border-radius: var(--p-radius-sm); padding: .45rem 1.2rem;
  font-weight: 600; font-size: .83rem; color: var(--p-text-secondary);
  transition: all .2s ease; cursor: pointer;
  display: inline-flex; align-items: center; gap: .4rem;
}

.pp-btn-s:hover { border-color: #c8d0dd; color: var(--p-text); }

/* ═══════ RADIO ═══════ */
.pp-rd {
  display: flex; gap: 1.5rem; margin-top: .35rem;
}

.pp-rd .rd-opt {
  display: inline-flex; align-items: center; gap: .45rem; cursor: pointer;
}

.pp-rd .rd-opt input[type="radio"] {
  appearance: none; -webkit-appearance: none;
  width: 1.05rem; height: 1.05rem; margin: 0; flex-shrink: 0;
  border: 2px solid #ced8e6; border-radius: 50%;
  background: #fff; cursor: pointer;
  transition: all .15s ease;
  position: relative;
}

.pp-rd .rd-opt input[type="radio"]:checked {
  border-color: var(--p-accent);
  background: var(--p-accent);
  box-shadow: inset 0 0 0 3px #fff, 0 0 0 1px var(--p-accent);
}

.pp-rd .rd-opt input[type="radio"]:focus-visible {
  outline: 2px solid var(--p-accent); outline-offset: 2px;
}

.pp-rd .rd-opt label {
  font-size: .86rem; font-weight: 500; color: var(--p-text-secondary);
  cursor: pointer; line-height: 1.3; user-select: none;
}

/* ═══════ MODAL ═══════ */
#vModal .modal-content {
  border: none; border-radius: var(--p-radius);
  box-shadow: var(--p-shadow-xl); overflow: visible;
}

#vModal .modal-header {
  background: linear-gradient(135deg, var(--p-primary) 0%, var(--p-primary-dark) 100%);
  color: #fff; border-radius: var(--p-radius) var(--p-radius) 0 0;
  padding: 1.25rem 1.5rem; border: none;
}

#vModal .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .6; transition: opacity .2s; }
#vModal .modal-header .btn-close:hover { opacity: 1; }
#vModal .modal-body { padding: 1.5rem; }
#vModal .modal-footer { border-top: 1px solid var(--p-border-light); padding: 1rem 1.5rem; }

.vbtn {
  border: 1.5px solid var(--p-accent); background: var(--p-surface);
  color: var(--p-accent); border-radius: var(--p-radius-sm);
  padding: 7px 22px; font-weight: 600; font-size: .84rem;
  transition: all .2s ease; cursor: pointer;
}

.vbtn:hover { background: var(--p-primary-light); transform: translateY(-1px); }

.vbtn.sel {
  background: linear-gradient(135deg, var(--p-accent) 0%, var(--p-accent-dark) 100%);
  color: #fff; border-color: var(--p-accent);
  box-shadow: 0 4px 14px rgba(43,127,255,.22);
}

.nvbtn {
  border: 1.5px solid var(--p-success); background: var(--p-surface);
  color: var(--p-success); border-radius: var(--p-radius-sm);
  padding: 7px 22px; font-weight: 600; font-size: .84rem; transition: all .2s ease;
}

.nvbtn.sel {
  background: linear-gradient(135deg, var(--p-success) 0%, #0c9a5e 100%);
  color: #fff; box-shadow: 0 4px 14px rgba(15,174,107,.22);
}

/* ═══════ MISC ═══════ */
@keyframes ppFd {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

.pp-tbl tbody tr.pp-row-new { animation: ppFd .25s ease; }

@keyframes ppPl {
  0%, 100% { box-shadow: 0 0 0 0 rgba(43,127,255,.25); }
  50% { box-shadow: 0 0 0 7px rgba(43,127,255,0); }
}

.pp-pls { animation: ppPl .9s ease infinite; }

.pp-alrt {
  border: none; border-radius: var(--p-radius-sm);
  font-size: .86rem; padding: .75rem 1rem;
}

.pp-tag {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--p-primary-light); color: var(--p-primary);
  border-radius: 4px; padding: 1px 8px; font-size: .72rem; font-weight: 600;
}

/* ═══════ SELECT2 ═══════ */
.select2-container--default .select2-selection--single {
  height: 42px !important;
  border: 1.5px solid var(--p-border) !important;
  border-radius: var(--p-radius-sm) !important;
  padding: .2rem 0;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 38px !important; color: var(--p-text) !important; font-size: .88rem;
}

.select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; }

.select2-dropdown {
  border: 1.5px solid var(--p-border) !important;
  border-radius: var(--p-radius-sm) !important;
  box-shadow: var(--p-shadow);
}

/* ═══════ RESPONSIVE ═══════ */
@media (max-width: 768px) {
  .pp-header { padding: 1.1rem 1.25rem; }
  .pp-header h2 { font-size: 1.05rem; }
  .pp-card-bd { padding: 1rem; }
  .pp .container-fluid { padding-left: .75rem !important; padding-right: .75rem !important; }
}
</style>

@section('content')
<div class="pp">
  <div class="container-fluid px-3 px-md-4 py-3">

    {{-- ═══ HEADER ═══ --}}
    <div class="pp-header">
      <div class="d-flex align-items-center gap-3">
        <h2><i class="bi bi-cart-plus"></i>Create Purchase</h2>
        <span class="hdr-badge d-none d-sm-inline">New Entry</span>
      </div>
      <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
        <a href="{{ route('Purchase.home') }}" class="pp-back"><i class="bi bi-arrow-left"></i>Back to Purchases</a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show pp-alrt mb-4">
      <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('store.Purchase') }}" method="POST" id="frm">
      @csrf

      {{-- ═══ DETAILS + SEARCH (merged) ═══ --}}
      <div class="pp-card">
        <div class="pp-card-hdr"><i class="bi bi-info-circle"></i>Purchase Information</div>
        <div class="pp-card-bd">

          {{-- Row 1: Date | Vendor | Inv # | Type | Note --}}
          <div class="row g-2 g-md-3 align-items-end">
            <div class="col-md-2">
              <label class="pp-lbl"><i class="bi bi-calendar3"></i>Date</label>
              <input type="date" name="purchase_date" class="pp-fld" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
              <label class="pp-lbl"><i class="bi bi-building"></i>Vendor</label>
              <select name="vendor_id" class="pp-fld select2">
                <option disabled selected>Select Vendor</option>
                @foreach($Vendor as $v)
                <option value="{{ $v->id }}">{{ $v->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-2">
              <label class="pp-lbl"><i class="bi bi-receipt"></i>Inv #</label>
              <input type="text" name="purchase_order_no" class="pp-fld" placeholder="e.g. INV-001">
            </div>
            <div class="col-md-2">
              <label class="pp-lbl"><i class="bi bi-arrow-left-right"></i>Type</label>
              <div class="pp-rd">
                <label class="rd-opt">
                  <input type="radio" class="purchaseType" name="purchase_to" value="shop" checked>
                  <span>Shop</span>
                </label>
                <label class="rd-opt">
                  <input type="radio" class="purchaseType" name="purchase_to" value="warehouse">
                  <span>Warehouse</span>
                </label>
              </div>
            </div>
            <div class="col-md-3">
              <label class="pp-lbl"><i class="bi bi-chat-text"></i>Note</label>
              <input type="text" name="note" class="pp-fld" placeholder="Optional reference note">
            </div>
          </div>

          {{-- Warehouse (conditional) --}}
          <div class="row mt-2 d-none" id="whBox">
            <div class="col-md-3">
              <label class="pp-lbl"><i class="bi bi-building2"></i>Warehouse</label>
              <select name="warehouse_id" class="pp-fld">
                <option disabled selected>Select Warehouse</option>
                @foreach($Warehouse as $w)
                <option value="{{ $w->id }}">{{ $w->warehouse_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Row 2: Search --}}
          <div class="row mt-3">
            <div class="col-12">
              <div class="pp-srch">
                <i class="bi bi-search srch-icon"></i>
                <div class="srch-spin" id="srchSpin"><div class="spinner-border spinner-border-sm" role="status"></div></div>
                <input type="text" id="srchInp" class="pp-fld" placeholder="Search products by name or code..." autocomplete="off">
                <div id="ppResults"></div>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- ═══ ITEMS ═══ --}}
      <div class="pp-card">
        <div class="pp-card-hdr d-flex flex-wrap justify-content-between align-items-center">
          <span><i class="bi bi-table"></i>Purchase Items</span>
          <span class="pp-cnt"><i class="bi bi-box-seam"></i> <span id="icnt">0 items</span></span>
        </div>
        <div class="pp-card-bd compact">
          <div class="pp-tbl-wrap">
            <table class="pp-tbl">
              <thead>
                <tr class="text-center">
                  <th style="width:36px;">#</th>
                  <th style="text-align:left;">Product</th>
                  <th style="width:100px;">Variant</th>
                  <th style="width:110px;">Unit Price</th>
                  <th style="width:80px;">Qty</th>
                  <th style="width:100px;">Line Total</th>
                  <th style="width:44px;"></th>
                </tr>
              </thead>
              <tbody id="tbd">
                <tr class="t-empty">
                  <td colspan="7"><i class="bi bi-inbox"></i><span>No items yet — search above to add</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div id="hidInputs"></div>

      {{-- ═══ SUMMARY ═══ --}}
      <div class="pp-card">
        <div class="pp-card-hdr"><i class="bi bi-calculator"></i>Summary</div>
        <div class="pp-card-bd">
          <div class="pp-sum">
            <div class="row g-3 align-items-end">
              <div class="col-md-3">
                <div class="sm-lbl">Subtotal</div>
                <div class="sm-val" id="subDsp">0.00</div>
              </div>
              <div class="col-md-3">
                <div class="sm-lbl">Discount</div>
                <input type="number" step="0.01" name="discount" id="discFld" class="sm-inp" value="0" placeholder="0.00">
              </div>
              <div class="col-md-3">
                <div class="sm-lbl">Extra Cost</div>
                <input type="number" step="0.01" name="extra_cost" id="extFld" class="sm-inp" value="0" placeholder="0.00">
              </div>
              <div class="col-md-3">
                <div class="sm-lbl">Net Amount</div>
                <div class="sm-val net" id="netDsp">0.00</div>
                <input type="hidden" name="net_amount" id="netHid" value="0.00">
              </div>
            </div>
            <div class="mt-4 text-end">
              <button type="button" id="svBtn" class="pp-btn px-5">
                <i class="bi bi-check-circle"></i>
                <span id="svTxt">Save Purchase</span>
                <div class="spinner-border spinner-border-sm d-none" id="svSpin" role="status"></div>
              </button>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

{{-- ═══ MODAL ═══ --}}
<div class="modal fade" id="vModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-0" id="vTitle" style="font-weight:700;">Product</h5>
          <small id="vSub" class="opacity-75" style="font-size:.8rem;"></small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="pp-lbl mb-2">Size / Variant</label>
          <div id="vBtns" class="d-flex flex-wrap gap-2"></div>
        </div>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <label class="pp-lbl mb-2">Purchase Price (Rs)</label>
            <input type="number" step="0.01" id="mPrice" class="pp-fld pp-fld-lg" placeholder="0.00">
          </div>
          <div class="col-6">
            <label class="pp-lbl mb-2">Quantity</label>
            <input type="number" step="0.001" min="0.001" id="mQty" class="pp-fld pp-fld-lg" placeholder="0" value="1">
          </div>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3" style="background:var(--p-primary-light);border-radius:var(--p-radius-sm);">
          <span style="font-weight:600;font-size:.86rem;color:var(--p-text-secondary);">Item Total:</span>
          <span id="mTot" style="font-weight:800;font-size:1.35rem;color:var(--p-accent);">0.00</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="pp-btn-s" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="addBtn" class="pp-btn px-4"><i class="bi bi-plus-circle"></i>Add Item</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {

  $('.select2').select2({ width: '100%', placeholder: 'Select...', allowClear: true });

  function toast(m) {
    Swal.fire({ text: m, icon: 'success', toast: true, position: 'bottom-end', showConfirmButton: false, timer: 1800, timerProgressBar: true });
  }

  $('.purchaseType').on('change', function() {
    if ($(this).val() === 'warehouse') $('#whBox').removeClass('d-none').hide().slideDown(180);
    else $('#whBox').slideUp(180, function() { $(this).addClass('d-none'); });
  });

  let items = [];
  let curProd = null;
  let curVar = null;
  let srchT = null;
  let srchRes = [];
  let actIdx = -1;

  // ─── SEARCH ───
  $('#srchInp').on('input', function() {
    let q = $(this).val().trim();
    clearTimeout(srchT); actIdx = -1;
    if (q.length < 1) { $('#ppResults').empty(); $('#srchSpin').hide(); return; }

    srchT = setTimeout(function() {
      $('#srchSpin').show();
      $('#ppResults').html('<div class="pp-res-empty"><div class="spinner-border spinner-border-sm mb-2" role="status" style="width:1.2rem;height:1.2rem;"></div><span>Searching...</span></div>');

      $.get("{{ route('search-product-name') }}", { q }, function(res) {
        $('#srchSpin').hide(); srchRes = res; renderRes(res);
      }).fail(function() {
        $('#srchSpin').hide();
        $('#ppResults').html('<div class="pp-res-empty" style="color:#e54545;"><i class="bi bi-exclamation-triangle"></i><span>Search failed</span></div>');
      });
    }, 250);
  });

  $('#srchInp').on('keydown', function(e) {
    const el = $('#ppResults .pp-res-item[data-idx]');
    if (!el.length) return;
    if (e.key === 'ArrowDown') { e.preventDefault(); actIdx = Math.min(actIdx + 1, el.length - 1); el.removeClass('active').eq(actIdx).addClass('active'); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); actIdx = Math.max(actIdx - 1, 0); el.removeClass('active').eq(actIdx).addClass('active'); }
    else if (e.key === 'Enter') { e.preventDefault(); if (actIdx >= 0) el.eq(actIdx).trigger('click'); }
  });

  function esc(s) { if (!s) return ''; var d = document.createElement('div'); d.appendChild(document.createTextNode(s)); return d.innerHTML; }

  function renderRes(r) {
    if (!r.length) { $('#ppResults').html('<div class="pp-res-empty"><i class="bi bi-search"></i><span>No products found</span></div>'); return; }
    let seen = {}, h = '', i = 0;
    r.forEach(function(p) {
      if (seen[p.id]) return; seen[p.id] = true;
      let gram = p.unit_type === 'kg' || (p.item_name && p.item_name.toLowerCase().includes('gram')) || (p.unit && p.unit.name && p.unit.name.toLowerCase().includes('gram'));
      let sz = (p.variant_id && !gram) ? '<span class="ri-sz"><i class="bi bi-grid-3x3-gap"></i> Sizes</span>' : '';
      h += `<div class="pp-res-item" data-idx="${i}" data-pid="${p.id}" data-gram="${gram ? 1 : 0}" id="psr-${i}">
        <strong class="ri-nm">${esc(p.item_name.split(' (')[0])}</strong>
        <div class="ri-meta">
          <span class="ri-cd"><i class="bi bi-upc-scan"></i> ${esc(p.item_code || '-')}</span>
          <span class="ri-pr">Rs ${p.wholesale_price || p.price || 0}</span>
          ${sz}
        </div>
      </div>`;
      i++;
    });
    $('#ppResults').html(h);
  }

  $(document).on('click', '.pp-res-item[data-pid]', function() {
    let pid = $(this).data('pid'), gram = $(this).data('gram') == 1;
    $('#ppResults').empty(); $('#srchInp').blur();
    $.get("{{ route('pos.product.variants', ':id') }}".replace(':id', pid), function(res) {
      if (gram) res.variants = [];
      openModal(res);
    });
  });

  $(document).on('click', function(e) {
    if (!$(e.target).closest('.pp-srch').length) { $('#ppResults').empty(); actIdx = -1; }
  });

  // ─── MODAL ───
  function openModal(p) {
    curProd = p; curVar = null;
    $('#vTitle').text(p.item_name); $('#vSub').text('Code: ' + (p.item_code || '-'));
    $('#mPrice').val(''); $('#mQty').val(1); $('#mTot').text('0.00');

    let b = '';
    if (p.variants && p.variants.length > 0) {
      p.variants.forEach(function(v) {
        let wp = v.wholesale_price || v.price || 0;
        b += `<button class="vbtn" data-vid="${v.id}" data-sl="${v.size_label || v.name}" data-wp="${wp}" data-pr="${v.price || 0}">${v.size_label || v.name}</button>`;
      });
    } else {
      b = `<button class="nvbtn sel" data-vid="" data-sl=""><i class="bi bi-dash-circle me-1"></i>Default</button>`;
      curVar = { id: null, label: '', price: 0 };
    }

    $('#vBtns').html(b);
    if (p.variants && p.variants.length === 1) $('#vBtns .vbtn:first').trigger('click');
    $('#vModal').modal('show');
    setTimeout(function() { $('#mPrice').focus().select(); }, 350);
  }

  $(document).on('click', '.vbtn', function() {
    $('.vbtn').removeClass('sel'); $(this).addClass('sel');
    let pr = parseFloat($(this).data('wp')) || parseFloat($(this).data('pr')) || 0;
    curVar = { id: $(this).data('vid'), label: $(this).data('sl'), price: pr };
    $('#mPrice').val(pr > 0 ? pr : ''); calcM(); $('#mQty').focus().select();
  });

  $(document).on('click', '.nvbtn', function() {
    curVar = { id: null, label: '', price: parseFloat($('#mPrice').val()) || 0 }; calcM();
  });

  $('#mPrice, #mQty').on('input', calcM);
  function calcM() { let p = parseFloat($('#mPrice').val()) || 0, q = parseFloat($('#mQty').val()) || 0; $('#mTot').text((p * q).toFixed(2)); }

  // ─── ADD ───
  $('#addBtn').on('click', function() {
    if (!curProd) return;
    let pr = parseFloat($('#mPrice').val()), qt = parseFloat($('#mQty').val());

    if (!curVar && curProd.variants && curProd.variants.length > 0) { Swal.fire({ icon: 'warning', title: 'Size Required', text: 'Select a variant first', timer: 1600, showConfirmButton: false, toast: true, position: 'top-end' }); return; }
    if (!pr || pr <= 0) { Swal.fire({ icon: 'warning', title: 'Price Required', text: 'Enter a valid price', timer: 1600, showConfirmButton: false, toast: true, position: 'top-end' }); $('#mPrice').focus(); return; }
    if (!qt || qt <= 0) { Swal.fire({ icon: 'warning', title: 'Quantity Required', text: 'Enter a valid quantity', timer: 1600, showConfirmButton: false, toast: true, position: 'top-end' }); $('#mQty').focus(); return; }

    let pid = curProd.product_id, vid = curVar ? curVar.id : null, sl = curVar ? curVar.label : '';
    let ex = items.findIndex(function(it) { return it.pid == pid && it.vid == vid; });
    let nw = false;

    if (ex >= 0) {
      items[ex].qty += qt; items[ex].price = pr; items[ex].lt = items[ex].price * items[ex].qty;
    } else {
      nw = true;
      items.push({ pid, vid, nm: curProd.item_name, sl, price: pr, qty: qt, lt: pr * qt });
      ex = items.length - 1;
    }

    renderT(nw ? ex : -1);
    toast(nw ? 'Item added' : 'Quantity updated');
    $('#mPrice').val(''); $('#mQty').val(1); $('#mTot').text('0.00'); $('#mPrice').focus().select();
  });

  function renderT(ni) {
    if (!items.length) {
      $('#tbd').html('<tr class="t-empty"><td colspan="7"><i class="bi bi-inbox"></i><span>No items yet</span></td></tr>');
      $('#icnt').text('0 items'); updH(); updS(); return;
    }

    let h = '';
    items.forEach(function(it, i) {
      let sz = it.sl ? `<span class="t-var"><i class="bi bi-tag"></i> ${it.sl}</span>` : '';
      h += `<tr class="${i === ni ? 'pp-row-new' : ''}">
        <td class="text-center fw-semibold" style="color:#94a3b8;">${i+1}</td>
        <td><span style="font-weight:600;color:var(--p-text);">${it.nm}</span>${sz}</td>
        <td class="text-center">${it.sl || '<span style="color:#b8c5d4;">—</span>'}</td>
        <td><input type="number" step="0.01" class="t-inp" data-i="${i}" value="${it.price}" style="width:100px;"></td>
        <td class="text-center"><input type="number" step="0.001" min="0.001" class="t-inp qty" data-i="${i}" value="${it.qty}" style="width:68px;"></td>
        <td class="text-end t-total">${it.lt.toFixed(2)}</td>
        <td class="text-center"><button class="t-del" data-i="${i}"><i class="bi bi-trash3"></i></button></td>
      </tr>`;
    });

    $('#tbd').html(h);
    $('#icnt').text(items.length + ' item' + (items.length !== 1 ? 's' : ''));
    updH(); updS();
  }

  $(document).on('input', '.t-inp', function() {
    let i = $(this).data('i'), q = $(this).hasClass('qty');
    let v = parseFloat($(this).val()) || 0;
    if (q) items[i].qty = v; else items[i].price = v;
    items[i].lt = items[i].price * items[i].qty;
    $('.t-total').eq(i).text(items[i].lt.toFixed(2));
    updH(); updS();
  });

  $(document).on('click', '.t-del', function() {
    let i = $(this).data('i');
    if (i === undefined) return;
    Swal.fire({
      title: 'Remove?', text: items[i].nm + (items[i].sl ? ' (' + items[i].sl + ')' : ''),
      icon: 'question', showCancelButton: true, confirmButtonText: 'Remove', cancelButtonText: 'Cancel',
      confirmButtonColor: '#e54545', cancelButtonColor: '#64748b', reverseButtons: true
    }).then(function(r) { if (r.isConfirmed) { items.splice(i, 1); renderT(); toast('Item removed'); } });
  });

  function updH() {
    let h = '';
    items.forEach(function(it) {
      h += `<input type="hidden" name="product_id[]" value="${it.pid}">`;
      h += `<input type="hidden" name="variant_id[]" value="${it.vid || ''}">`;
      h += `<input type="hidden" name="qty[]" value="${it.qty}">`;
      h += `<input type="hidden" name="price[]" value="${it.price}">`;
      h += `<input type="hidden" name="unit[]" value="${it.sl || 'Pc'}">`;
      h += `<input type="hidden" name="item_note[]" value="">`;
    });
    $('#hidInputs').html(h);
  }

  let af = null;
  function updS() {
    if (af) cancelAnimationFrame(af);
    af = requestAnimationFrame(function() {
      let sub = items.reduce(function(s, it) { return s + it.lt; }, 0);
      let d = parseFloat($('#discFld').val()) || 0, e = parseFloat($('#extFld').val()) || 0, n = sub - d + e;
      $('#subDsp').text(sub.toFixed(2)); $('#netDsp').text(n.toFixed(2)); $('#netHid').val(n.toFixed(2));
    });
  }

  $('#discFld, #extFld').on('input', updS);

  // ─── SAVE ───
  $('#svBtn').on('click', function() {
    if (!items.length) { Swal.fire({ icon: 'warning', title: 'Empty', text: 'Add at least one product', timer: 1800, showConfirmButton: false }); return; }
    let pt = $('input[name="purchase_to"]:checked').val();
    if (!pt) { Swal.fire({ icon: 'warning', title: 'Type', text: 'Select Shop or Warehouse', timer: 1800, showConfirmButton: false }); return; }
    if (pt === 'warehouse' && !$('select[name="warehouse_id"]').val()) { Swal.fire({ icon: 'warning', title: 'Warehouse', text: 'Select a warehouse', timer: 1800, showConfirmButton: false }); return; }

    const b = $('#svBtn'), s = $('#svSpin'), t = $('#svTxt');
    b.prop('disabled', true).addClass('pp-pls'); s.removeClass('d-none'); t.text('Saving...');
    setTimeout(function() { $('#frm').submit(); }, 300);
  });

  @if(session('success'))
  Swal.fire({ icon: 'success', title: 'Done!', text: @json(session('success')), confirmButtonColor: '#0fae6b' });
  @endif

  @if($errors->any())
  Swal.fire({ icon: 'error', title: 'Error', html: @json(implode('<br>', $errors->all())), confirmButtonColor: '#e54545' });
  @endif
});
</script>
@endsection
