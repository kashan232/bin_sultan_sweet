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

.pc-btn {
  display: inline-flex; align-items: center; gap: .4rem; border-radius: var(--pc-radius-sm);
  font-weight: 600; font-size: .8rem; transition: all .25s ease; cursor: pointer;
  text-decoration: none; border: none; padding: .45rem 1.15rem;
}
.pc-btn-primary { background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%); color: #fff; }
.pc-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,127,255,.25); color: #fff; }
.pc-btn-ghost { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); color: #fff; }
.pc-btn-ghost:hover { background: rgba(255,255,255,.14); color: #fff; }
.pc-btn-sm { font-size: .74rem; padding: .35rem .9rem; }
.pc-btn-outline { background: transparent; border: 1.5px solid var(--pc-border); color: var(--pc-text-sec); }
.pc-btn-outline:hover { border-color: var(--pc-accent); color: var(--pc-accent); }

.pc-card { background: var(--pc-surface); border: 1px solid var(--pc-border); border-radius: var(--pc-radius); box-shadow: var(--pc-shadow); transition: box-shadow .3s ease; }
.pc-card:hover { box-shadow: var(--pc-shadow-lg); }
.pc-card-body { padding: 1.5rem; }

.pc-filter {
  display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;
  padding: 14px 18px; background: var(--pc-surface); border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius-sm); box-shadow: var(--pc-shadow); margin-bottom: 16px;
}
.pc-filter .fg { display: flex; flex-direction: column; gap: 3px; }
.pc-filter label { font-size: .68rem; font-weight: 700; color: var(--pc-text-sec); text-transform: uppercase; letter-spacing: .4px; }
.pc-filter .pc-fld { border: 1.5px solid var(--pc-border); border-radius: 7px; padding: .38rem .7rem; font-size: .82rem; font-weight: 500; color: var(--pc-text); outline: none; transition: all .2s ease; background: var(--pc-surface); }
.pc-filter .pc-fld:focus { border-color: var(--pc-accent); box-shadow: 0 0 0 3px rgba(43,127,255,.1); }
select.pc-fld { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 24 24' fill='none' stroke='%238896ab' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .6rem center; padding-right: 1.8rem; }

.pc-tbl-wrap { overflow-x: auto; border: 1px solid var(--pc-border); border-radius: var(--pc-radius-sm); }
.pc-tbl { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .82rem; }
.pc-tbl thead th { background: #f8fafc; font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--pc-text-muted); padding: .55rem .7rem; border-bottom: 2px solid var(--pc-border); text-align: left; white-space: nowrap; }
.pc-tbl tbody td { padding: .5rem .7rem; border-bottom: 1px solid var(--pc-border-lt); vertical-align: middle; }
.pc-tbl tbody tr { transition: background .12s ease; }
.pc-tbl tbody tr:hover { background: #fafbfc; }
.pc-tbl tbody tr:last-child td { border-bottom: none; }
.pc-tbl .pc-ref { font-weight: 700; color: var(--pc-text); white-space: nowrap; }
.pc-tbl .pc-date { color: var(--pc-text-sec); white-space: nowrap; }
.pc-badge { display: inline-flex; align-items: center; gap: 3px; border-radius: 5px; padding: .15rem .55rem; font-size: .7rem; font-weight: 700; text-transform: uppercase; }
.pc-badge-inc { background: #f0fdf4; color: #0f7a47; }
.pc-badge-dec { background: #fef2f2; color: #991b1b; }
.pc-tbl .pc-items { line-height: 1.3; }
.pc-tbl .pc-items > div { margin-bottom: 2px; font-size: .78rem; }
.pc-tbl .pc-reason { color: var(--pc-text-sec); font-size: .78rem; }
.pc-tbl .pc-user { color: var(--pc-text-sec); font-size: .78rem; font-weight: 500; }
.pc-act { display: inline-flex; align-items: center; gap: 3px; border-radius: 5px; padding: .3rem .7rem; font-size: .72rem; font-weight: 600; transition: all .2s ease; text-decoration: none; border: 1.5px solid transparent; }
.pc-act-view { background: #f0f4fe; border-color: #dde4f7; color: #3b5bb3; }
.pc-act-view:hover { background: #dde4f7; color: #2a4a9e; }

.pc-empty { text-align: center; padding: 2.5rem .85rem; color: var(--pc-text-muted); }
.pc-empty i { font-size: 2rem; color: #ced8e6; display: block; margin-bottom: .5rem; }
.pc-empty span { font-size: .9rem; font-weight: 500; }

.pc-pagi { margin-top: 1rem; }
.pc-pagi nav span, .pc-pagi nav a { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 .5rem; margin: 0 2px; border: 1.5px solid var(--pc-border); border-radius: 6px; font-size: .78rem; font-weight: 600; color: var(--pc-text-sec); text-decoration: none; }

@media (max-width: 768px) { .pc-hdr { padding: 1rem 1.25rem; flex-direction: column; align-items: stretch; gap: .5rem; } .pc-hdr h2 { font-size: 1.1rem; } .pc-card-body { padding: 1rem; } }
</style>

<div class="pc-page">
<div class="container-fluid px-3 px-md-4 py-3">

  <div class="pc-hdr">
    <div class="d-flex align-items-center gap-3">
      <h2><i class="bi bi-arrow-left-right"></i>Stock Adjustments</h2>
      <span class="hdr-badge d-none d-sm-inline">{{ $adjustments->total() }} Records</span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('stock-adjustment.report') }}" class="pc-btn pc-btn-ghost pc-btn-sm"><i class="bi bi-bar-chart"></i>Report</a>
      <a href="{{ route('stock-adjustment.create') }}" class="pc-btn pc-btn-primary pc-btn-sm"><i class="bi bi-plus-circle"></i>New Adjustment</a>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mb-3" style="border:none;border-radius:var(--pc-radius-sm);font-size:.86rem;padding:.75rem 1rem;">
    <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  {{-- FILTERS --}}
  <form method="GET" class="pc-filter">
    <div class="fg"><label>From</label><input type="date" name="from_date" value="{{ request('from_date') }}" class="pc-fld"></div>
    <div class="fg"><label>To</label><input type="date" name="to_date" value="{{ request('to_date') }}" class="pc-fld"></div>
    <div class="fg"><label>Type</label>
      <select name="type" class="pc-fld">
        <option value="">All</option>
        <option value="increase" {{ request('type')=='increase' ? 'selected':'' }}>Increase</option>
        <option value="decrease" {{ request('type')=='decrease' ? 'selected':'' }}>Decrease</option>
      </select>
    </div>
    <button type="submit" class="pc-btn pc-btn-primary pc-btn-sm"><i class="bi bi-funnel"></i>Filter</button>
    <a href="{{ route('stock-adjustment.index') }}" class="pc-btn pc-btn-outline pc-btn-sm"><i class="bi bi-x-circle"></i>Reset</a>
  </form>

  {{-- TABLE --}}
  <div class="pc-card">
    <div class="pc-card-body">
      <div class="pc-tbl-wrap">
        <table class="pc-tbl">
          <thead>
            <tr>
              <th>Ref #</th><th>Date</th><th>Type</th><th>Reason</th>
              <th>Items</th><th>By</th><th>Notes</th><th style="width:90px;">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($adjustments as $adj)
            <tr>
              <td><span class="pc-ref">{{ $adj->ref_no }}</span></td>
              <td><span class="pc-date">{{ \Carbon\Carbon::parse($adj->adjustment_date)->format('d-M-Y') }}</span></td>
              <td>
                <span class="pc-badge {{ $adj->type === 'increase' ? 'pc-badge-inc' : 'pc-badge-dec' }}">
                  <i class="bi bi-{{ $adj->type === 'increase' ? 'plus' : 'dash' }}"></i>
                  {{ ucfirst($adj->type) }}
                </span>
              </td>
              <td class="pc-reason">{{ $adj->reason }}</td>
              <td>
                <div class="pc-items">
                  @foreach($adj->items as $item)
                  @php
                    $isKg = optional($item->product)->unit_type === 'kg';
                    $qty = (float)$item->qty;
                    if ($isKg) { $kg = floor($qty); $gm = round(($qty - $kg) * 1000); $qtyFmt = ($kg > 0 ? $kg.'kg ' : '') . ($gm > 0 ? $gm.'g' : ($kg > 0 ? '' : '0g')); }
                    else { $qtyFmt = number_format($qty, 0) . ' ' . $item->unit; }
                  @endphp
                  <div><strong>{{ optional($item->product)->item_name }}</strong> : <span style="background:#f5f5f5;border-radius:4px;padding:1px 5px;font-weight:700;">{{ $qtyFmt }}</span>@if($item->variant) <span style="color:var(--pc-text-muted);font-size:.7rem;">({{ $item->variant->size_label ?: $item->variant->variant_name }})</span> @endif</div>
                  @endforeach
                </div>
              </td>
              <td><span class="pc-user">{{ optional($adj->user)->name ?? 'System' }}</span></td>
              <td style="color:var(--pc-text-muted);font-size:.78rem;">{{ Str::limit($adj->notes, 35) }}</td>
              <td>
                <a href="{{ route('stock-adjustment.show', $adj->id) }}" class="pc-act pc-act-view"><i class="bi bi-eye"></i>View</a>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="pc-empty"><i class="bi bi-inbox"></i><span>No adjustments found.</span></td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="pc-pagi mt-3">{{ $adjustments->links() }}</div>
    </div>
  </div>

</div>
</div>
@endsection
