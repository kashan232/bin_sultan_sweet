@extends('admin_panel.layout.app')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

:root {
  --pc-bg: #f1f4f9;
  --pc-surface: #ffffff;
  --pc-border: #e9edf2;
  --pc-border-lt: #f1f4f9;
  --pc-text: #0b1a33;
  --pc-text-sec: #54657e;
  --pc-text-muted: #8896ab;
  --pc-accent: #2b7fff;
  --pc-accent-drk: #1a6ae8;
  --pc-success: #0fae6b;
  --pc-danger: #e54545;
  --pc-warning: #f5a623;
  --pc-radius: 14px;
  --pc-radius-sm: 9px;
  --pc-shadow: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.05);
  --pc-shadow-lg: 0 8px 30px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.04);
  --pc-shadow-xl: 0 20px 60px rgba(0,0,0,.10), 0 8px 24px rgba(0,0,0,.06);
  --pc-font: 'Inter', -apple-system, 'Segoe UI', Roboto, sans-serif;
}

.pc-page * { font-family: var(--pc-font); box-sizing: border-box; }

.pc-page {
  background: var(--pc-bg);
  min-height: 100vh;
  padding-bottom: 3rem;
}

/* ═══════ HEADER ═══════ */
.pc-hdr {
  position: relative;
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--pc-radius);
  padding: 1.4rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--pc-shadow-xl);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
  gap: .75rem;
}

.pc-hdr::before {
  content: '';
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%),
    radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%);
  pointer-events: none;
}
.pc-hdr::after {
  content: '';
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: .5;
  pointer-events: none;
}
.pc-hdr > * { position: relative; z-index: 1; }

.pc-hdr h2 {
  font-size: 1.35rem; font-weight: 800; color: #fff;
  letter-spacing: -.4px; margin: 0;
  display: flex; align-items: center; gap: .65rem;
}
.pc-hdr h2 i { font-size: 1.4rem; color: #60a5fa; }

.hdr-badge {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 20px;
  padding: .25rem .9rem;
  font-size: .7rem; font-weight: 600;
  color: rgba(255,255,255,.65);
  letter-spacing: .4px;
  text-transform: uppercase;
}

/* ═══════ BUTTONS ═══════ */
.pc-btn {
  display: inline-flex; align-items: center; gap: .45rem;
  border-radius: var(--pc-radius-sm);
  font-weight: 600; font-size: .82rem;
  transition: all .25s ease;
  cursor: pointer; text-decoration: none; border: none;
  padding: .5rem 1.25rem;
  white-space: nowrap;
}
.pc-btn-primary {
  background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%);
  color: #fff;
}
.pc-btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(43,127,255,.3);
  color: #fff;
}
.pc-btn-outline {
  background: rgba(255,255,255,.08);
  border: 1.5px solid rgba(255,255,255,.18);
  color: rgba(255,255,255,.85);
}
.pc-btn-outline:hover { background: rgba(255,255,255,.14); color: #fff; }

/* ═══════ FILTER CARD ═══════ */
.filter-card {
  background: var(--pc-surface);
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius);
  box-shadow: var(--pc-shadow);
  padding: 1.25rem 1.5rem;
  margin-bottom: 1.25rem;
}
.filter-card .filter-title {
  font-size: .72rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .6px; color: var(--pc-text-muted);
  margin-bottom: .75rem;
  display: flex; align-items: center; gap: .4rem;
}
.filter-group {
  display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end;
}
.filter-item { display: flex; flex-direction: column; gap: .3rem; }
.filter-item label {
  font-size: .74rem; font-weight: 600; color: var(--pc-text-sec);
}
.filter-item input[type="date"] {
  border: 1.5px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
  padding: .48rem .85rem;
  font-size: .84rem; font-weight: 500;
  color: var(--pc-text);
  background: #f8fafc;
  outline: none;
  transition: all .25s ease;
  min-width: 145px;
}
.filter-item input[type="date"]:focus {
  border-color: var(--pc-accent);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}
.filter-btn-apply {
  background: linear-gradient(135deg, var(--pc-accent), var(--pc-accent-drk));
  color: #fff; border: none;
  border-radius: var(--pc-radius-sm);
  padding: .5rem 1.25rem;
  font-size: .82rem; font-weight: 700;
  cursor: pointer;
  transition: all .25s ease;
  display: inline-flex; align-items: center; gap: .4rem;
}
.filter-btn-apply:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(43,127,255,.3); }
.filter-btn-reset {
  background: #f1f5f9; color: var(--pc-text-sec);
  border: 1.5px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
  padding: .48rem 1rem;
  font-size: .82rem; font-weight: 600;
  cursor: pointer;
  transition: all .2s ease;
  text-decoration: none;
  display: inline-flex; align-items: center; gap: .4rem;
}
.filter-btn-reset:hover { background: #e2e8f0; color: var(--pc-text); }

/* Active filter badge */
.active-filter-strip {
  background: linear-gradient(90deg, #eef2ff 0%, #f0f9ff 100%);
  border: 1px solid #dde4f7;
  border-radius: var(--pc-radius-sm);
  padding: .5rem 1rem;
  font-size: .8rem; color: #3b5bb3; font-weight: 600;
  display: flex; align-items: center; gap: .5rem;
  margin-bottom: 1rem;
}

/* ═══════ STAT ROW ═══════ */
.stat-mini-row {
  display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem;
}
.stat-mini {
  flex: 1; min-width: 155px;
  background: var(--pc-surface);
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius);
  padding: .9rem 1.2rem;
  box-shadow: var(--pc-shadow);
  position: relative; overflow: hidden;
}
.stat-mini::before {
  content: '';
  position: absolute; top: 0; left: 0;
  width: 4px; height: 100%;
  border-radius: 0 4px 4px 0;
}
.stat-mini.sm-blue::before { background: linear-gradient(180deg,#2b7fff,#60a5fa); }
.stat-mini.sm-green::before { background: linear-gradient(180deg,#0fae6b,#34d399); }
.stat-mini.sm-purple::before { background: linear-gradient(180deg,#7c3aed,#a78bfa); }
.stat-mini.sm-orange::before { background: linear-gradient(180deg,#f59e0b,#fbbf24); }
.stat-mini .sm-label {
  font-size: .68rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .5px; color: var(--pc-text-muted); margin-bottom: .2rem;
}
.stat-mini .sm-val {
  font-size: 1.35rem; font-weight: 800; color: var(--pc-text); line-height: 1.2;
}
.stat-mini .sm-sub { font-size: .72rem; color: var(--pc-text-muted); margin-top: .15rem; }

/* ═══════ CARD ═══════ */
.pc-card {
  background: var(--pc-surface);
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius);
  box-shadow: var(--pc-shadow);
}
.pc-card-header {
  padding: 1rem 1.4rem;
  border-bottom: 1px solid var(--pc-border-lt);
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem;
}
.pc-card-header .ch-title {
  font-size: .82rem; font-weight: 700; color: var(--pc-text-sec);
  text-transform: uppercase; letter-spacing: .5px;
  display: flex; align-items: center; gap: .4rem;
}
.pc-card-body { padding: 1.25rem; }

/* ═══════ TABLE ═══════ */
.pc-tbl-wrap {
  overflow-x: auto;
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
}
.pc-tbl {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: .84rem;
}
.pc-tbl thead th {
  background: #f8fafc;
  font-size: .67rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .6px;
  color: var(--pc-text-muted);
  padding: .65rem .9rem;
  border-bottom: 2px solid var(--pc-border);
  text-align: left; white-space: nowrap;
}
.pc-tbl tbody td {
  padding: .6rem .9rem;
  border-bottom: 1px solid var(--pc-border-lt);
  vertical-align: middle;
}
.pc-tbl tbody tr { transition: background .12s ease; }
.pc-tbl tbody tr:hover { background: #fafbfc; }
.pc-tbl tbody tr:last-child td { border-bottom: none; }

.pc-batch { font-weight: 700; color: var(--pc-text); white-space: nowrap; font-size: .86rem; }
.pc-date { color: var(--pc-text-sec); white-space: nowrap; font-weight: 500; }

.pc-badge {
  display: inline-flex; align-items: center; gap: .3rem;
  border-radius: 6px; padding: .18rem .65rem;
  font-size: .72rem; font-weight: 700;
  text-transform: uppercase; letter-spacing: .3px; white-space: nowrap;
}
.pc-badge-kitchen { background: #eef2ff; color: #3b5bb3; }
.pc-badge-warehouse { background: #f0fdf4; color: #0f7a47; }

.pc-items { line-height: 1.45; }
.pc-items .items-text { font-size: .82rem; color: var(--pc-text); }
.pc-items .items-meta { font-size: .72rem; color: var(--pc-text-muted); font-weight: 500; margin-top: 2px; display: flex; align-items: center; gap: .3rem; }

.retail-val {
  font-weight: 700; color: #0fae6b;
  font-size: .9rem; white-space: nowrap;
}
.retail-val .rv-label { font-size: .67rem; color: var(--pc-text-muted); font-weight: 500; display: block; text-transform: uppercase; letter-spacing: .4px; }

.pc-notes { color: var(--pc-text-sec); max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.pc-user { color: var(--pc-text-sec); font-weight: 500; font-size: .8rem; }

.pc-actions { display: flex; gap: 5px; white-space: nowrap; }
.pc-act {
  display: inline-flex; align-items: center; justify-content: center; gap: 4px;
  border-radius: 6px; padding: .35rem .8rem;
  font-size: .74rem; font-weight: 600;
  transition: all .2s ease; text-decoration: none;
  cursor: pointer; border: 1.5px solid transparent;
}
.pc-act-edit { background: #eef2ff; border-color: #dde4f7; color: #3b5bb3; }
.pc-act-edit:hover { background: #dde4f7; color: #2a4a9e; }
.pc-act-print { background: #f8fafc; border-color: var(--pc-border); color: var(--pc-text-sec); }
.pc-act-print:hover { background: #e9edf2; color: var(--pc-text); }

.pc-empty { text-align: center; padding: 3rem .85rem; color: var(--pc-text-muted); }
.pc-empty i { font-size: 2.5rem; color: #ced8e6; display: block; margin-bottom: .65rem; }
.pc-empty span { font-size: .9rem; font-weight: 500; }

@media (max-width: 768px) {
  .pc-hdr { padding: 1.1rem 1.25rem; }
  .pc-hdr h2 { font-size: 1.1rem; }
  .pc-card-body { padding: 1rem; }
  .pc-tbl tbody td { padding: .45rem .6rem; }
  .stat-mini-row { gap: .5rem; }
  .stat-mini { min-width: 130px; }
}
</style>

@section('content')
<div class="pc-page">
  <div class="container-fluid px-3 px-md-4 py-3">

    {{-- ═══ HEADER ═══ --}}
    <div class="pc-hdr">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <h2><i class="bi bi-gear-wide-connected"></i>Production History</h2>
        <span class="hdr-badge d-none d-sm-inline">{{ count($entries) }} Records</span>
        @if($dateFrom || $dateTo)
          <span class="hdr-badge" style="background:rgba(251,191,36,.15);border-color:rgba(251,191,36,.3);color:#fbbf24;">
            <i class="bi bi-funnel-fill"></i> Filtered
          </span>
        @endif
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('production.create') }}" class="pc-btn pc-btn-primary">
          <i class="bi bi-plus-circle"></i>New Production
        </a>
        <a href="{{ url()->previous() }}" class="pc-btn pc-btn-outline">
          <i class="bi bi-arrow-left"></i>Back
        </a>
      </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" style="border:none;border-radius:var(--pc-radius-sm);font-size:.86rem;padding:.75rem 1rem;">
      <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ═══ DATE FILTER CARD ═══ --}}
    <div class="filter-card">
      <div class="filter-title">
        <i class="bi bi-funnel"></i> Filter by Date Range
      </div>
      <form method="GET" action="{{ route('production.index') }}">
        <div class="filter-group">
          <div class="filter-item">
            <label>From Date</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}">
          </div>
          <div class="filter-item">
            <label>To Date</label>
            <input type="date" name="date_to" value="{{ $dateTo }}">
          </div>
          <div class="filter-item" style="flex-direction:row; gap:.5rem; align-items:flex-end;">
            <button type="submit" class="filter-btn-apply">
              <i class="bi bi-search"></i> Apply
            </button>
            <a href="{{ route('production.index') }}" class="filter-btn-reset">
              <i class="bi bi-x-circle"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>

    @if($dateFrom || $dateTo)
    <div class="active-filter-strip">
      <i class="bi bi-info-circle"></i>
      Showing results:
      @if($dateFrom) from <strong>{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</strong> @endif
      @if($dateTo) to <strong>{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</strong> @endif
      — {{ count($entries) }} {{ count($entries) === 1 ? 'entry' : 'entries' }} found.
    </div>
    @endif

    {{-- ═══ MINI STATS ═══ --}}
    @php
      $totalItems  = $entries->sum('items_count');
      $totalRetail = $entries->sum('retail_value');
      $kitchenCnt  = $entries->where('source','kitchen')->count();
      $warehCnt    = $entries->where('source','warehouse')->count();
    @endphp
    <div class="stat-mini-row">
      <div class="stat-mini sm-blue">
        <div class="sm-label">Total Batches</div>
        <div class="sm-val">{{ count($entries) }}</div>
        <div class="sm-sub">Production runs</div>
      </div>
      <div class="stat-mini sm-green">
        <div class="sm-label">Total Items</div>
        <div class="sm-val">{{ number_format($totalItems) }}</div>
        <div class="sm-sub">Across all batches</div>
      </div>
      <div class="stat-mini sm-orange">
        <div class="sm-label">Sources</div>
        <div class="sm-val">{{ $kitchenCnt }}<span style="font-size:.9rem;color:var(--pc-text-muted);font-weight:600;"> / {{ $warehCnt }}</span></div>
        <div class="sm-sub">Kitchen / Warehouse</div>
      </div>
    </div>

    {{-- ═══ TABLE CARD ═══ --}}
    <div class="pc-card">
      <div class="pc-card-header">
        <div class="ch-title">
          <i class="bi bi-table" style="color:var(--pc-accent);"></i>
          Production Entries
        </div>
        <span style="font-size:.78rem;color:var(--pc-text-muted);">
          Sorted by production date (newest first)
        </span>
      </div>
      <div class="pc-card-body" style="padding:0;">
        <div class="pc-tbl-wrap">
          <table id="productionTable" class="pc-tbl">
            <thead>
              <tr>
                <th>#</th>
                <th>Batch No.</th>
                <th>Prod. Date</th>
                <th>Source</th>
                <th>Items Produced</th>
                <th>Retail Value</th>
                <th>Notes</th>
                <th>Created By</th>
                <th style="width:155px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($entries as $i => $e)
              <tr>
                <td style="color:var(--pc-text-muted);font-weight:600;font-size:.8rem;">{{ $i + 1 }}</td>
                <td><span class="pc-batch">{{ $e->entry_no }}</span></td>
                <td>
                  <span class="pc-date">{{ \Carbon\Carbon::parse($e->production_date)->format('d M Y') }}</span>
                  <div style="font-size:.7rem;color:var(--pc-text-muted);margin-top:1px;">
                    {{ \Carbon\Carbon::parse($e->created_at)->format('h:i A') }}
                  </div>
                </td>
                <td>
                  <span class="pc-badge {{ $e->source === 'kitchen' ? 'pc-badge-kitchen' : 'pc-badge-warehouse' }}">
                    <i class="bi bi-{{ $e->source === 'kitchen' ? 'house-door' : 'building' }}"></i>
                    {{ ucfirst($e->source) }}
                  </span>
                </td>
                <td>
                  <div class="pc-items">
                    <div class="items-text" title="{{ $e->product_details }}">
                      {{ \Illuminate\Support\Str::limit($e->product_details, 55) }}
                    </div>
                    <div class="items-meta">
                      <i class="bi bi-box-seam"></i>
                      {{ $e->items_count }} {{ $e->items_count == 1 ? 'item' : 'items' }}
                    </div>
                  </div>
                </td>
                <td>
                  <div class="retail-val">
                    <span class="rv-label">Retail Value</span>
                    Rs {{ number_format($e->retail_value, 0) }}
                  </div>
                </td>
                <td class="pc-notes" title="{{ $e->notes }}">{{ $e->notes ?? '—' }}</td>
                <td><span class="pc-user">{{ $e->user_name ?? 'System' }}</span></td>
                <td>
                  <div class="pc-actions">
                    <a href="{{ route('production.edit', $e->id) }}" class="pc-act pc-act-edit">
                      <i class="bi bi-pencil"></i>Edit
                    </a>
                    <a href="{{ route('production.gatepass', $e->id) }}" class="pc-act pc-act-print" target="_blank">
                      <i class="bi bi-printer"></i>Gatepass
                    </a>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="pc-empty">
                  <i class="bi bi-inbox"></i>
                  <span>No production entries found{{ ($dateFrom || $dateTo) ? ' for selected date range' : '' }}.</span>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Only init DataTable when no server-side filter is applied (dates are cleared)
    // so date filter works via form GET and DataTable handles search/sort
    if ($('#productionTable tbody tr').length > 0 && !$('#productionTable tbody .pc-empty').length) {
      $('#productionTable').DataTable({
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [], // keep server-side order
        language: {
          search: "Search:",
          lengthMenu: "Show _MENU_ entries",
          emptyTable: "No production entries found",
          info: "Showing _START_ to _END_ of _TOTAL_ entries",
        },
        columnDefs: [
          { orderable: false, targets: [4, 8] } // Items & Actions not sortable
        ]
      });
    }
  });
</script>
@endsection
