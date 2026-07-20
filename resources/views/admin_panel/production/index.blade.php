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

.pc-page * { font-family: var(--pc-font); }

.pc-page {
  background: var(--pc-bg);
  min-height: 100vh;
  padding-bottom: 2.5rem;
}

/* ═══════ HEADER ═══════ */
.pc-hdr {
  position: relative;
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--pc-radius);
  padding: 1.3rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--pc-shadow-xl);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}

.pc-hdr::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%),
    radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%);
  pointer-events: none;
}

.pc-hdr::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: .5;
  pointer-events: none;
}

.pc-hdr > * { position: relative; z-index: 1; }

.pc-hdr h2 {
  font-size: 1.35rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -.4px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .65rem;
}

.pc-hdr h2 i { font-size: 1.4rem; color: #60a5fa; }

.pc-hdr .hdr-badge {
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

.pc-btn {
  display: inline-flex;
  align-items: center;
  gap: .45rem;
  border-radius: var(--pc-radius-sm);
  font-weight: 600;
  font-size: .82rem;
  transition: all .25s ease;
  cursor: pointer;
  text-decoration: none;
  border: none;
}

.pc-btn-primary {
  background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%);
  color: #fff;
  padding: .5rem 1.35rem;
}

.pc-btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(43,127,255,.25);
  color: #fff;
}

/* ═══════ CARD ═══════ */
.pc-card {
  background: var(--pc-surface);
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius);
  box-shadow: var(--pc-shadow);
  transition: box-shadow .3s ease;
}

.pc-card:hover { box-shadow: var(--pc-shadow-lg); }

.pc-card-body { padding: 1.5rem; }

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
  font-size: .68rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--pc-text-muted);
  padding: .6rem .85rem;
  border-bottom: 2px solid var(--pc-border);
  text-align: left;
}

.pc-tbl tbody td {
  padding: .55rem .85rem;
  border-bottom: 1px solid var(--pc-border-lt);
  vertical-align: middle;
}

.pc-tbl tbody tr { transition: background .12s ease; }
.pc-tbl tbody tr:hover { background: #fafbfc; }
.pc-tbl tbody tr:last-child td { border-bottom: none; }

.pc-tbl .pc-batch {
  font-weight: 700;
  color: var(--pc-text);
  font-size: .88rem;
  white-space: nowrap;
}

.pc-tbl .pc-date {
  color: var(--pc-text-sec);
  white-space: nowrap;
}

.pc-badge {
  display: inline-flex;
  align-items: center;
  gap: .3rem;
  border-radius: 6px;
  padding: .18rem .65rem;
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .3px;
  white-space: nowrap;
}

.pc-badge-kitchen {
  background: #eef2ff;
  color: #3b5bb3;
}

.pc-badge-warehouse {
  background: #f0fdf4;
  color: #0f7a47;
}

.pc-tbl .pc-items {
  line-height: 1.4;
}

.pc-tbl .pc-items small {
  color: var(--pc-text-muted);
  font-size: .72rem;
  font-weight: 500;
}

.pc-tbl .pc-notes {
  color: var(--pc-text-sec);
  max-width: 140px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.pc-tbl .pc-user {
  color: var(--pc-text-sec);
  font-weight: 500;
  font-size: .8rem;
}

.pc-tbl .pc-actions {
  display: flex;
  gap: 5px;
  white-space: nowrap;
}

.pc-act {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  border-radius: 6px;
  padding: .35rem .8rem;
  font-size: .74rem;
  font-weight: 600;
  transition: all .2s ease;
  text-decoration: none;
  cursor: pointer;
  border: 1.5px solid transparent;
}

.pc-act-edit {
  background: #eef2ff;
  border-color: #dde4f7;
  color: #3b5bb3;
}

.pc-act-edit:hover { background: #dde4f7; color: #2a4a9e; }

.pc-act-print {
  background: #f8fafc;
  border-color: var(--pc-border);
  color: var(--pc-text-sec);
}

.pc-act-print:hover { background: #e9edf2; color: var(--pc-text); }

/* ═══════ EMPTY ═══════ */
.pc-empty {
  text-align: center;
  padding: 2.5rem .85rem;
  color: var(--pc-text-muted);
}

.pc-empty i { font-size: 2rem; color: #ced8e6; display: block; margin-bottom: .5rem; }
.pc-empty span { font-size: .9rem; font-weight: 500; }

/* ═══════ RESPONSIVE ═══════ */
@media (max-width: 768px) {
  .pc-hdr { padding: 1.1rem 1.25rem; }
  .pc-hdr h2 { font-size: 1.1rem; }
  .pc-card-body { padding: 1rem; }
  .pc-tbl tbody td { padding: .45rem .6rem; }
}
</style>

@section('content')
<div class="pc-page">
  <div class="container-fluid px-3 px-md-4 py-3">

    {{-- ═══ HEADER ═══ --}}
    <div class="pc-hdr">
      <div class="d-flex align-items-center gap-3">
        <h2><i class="bi bi-gear-wide-connected"></i>Production History</h2>
        <span class="hdr-badge d-none d-sm-inline">{{ count($entries) }} Records</span>
      </div>
      <a href="{{ route('production.create') }}" class="pc-btn pc-btn-primary mt-2 mt-md-0">
        <i class="bi bi-plus-circle"></i>New Production
      </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" style="border:none;border-radius:var(--pc-radius-sm);font-size:.86rem;padding:.75rem 1rem;">
      <strong><i class="bi bi-check-circle me-1"></i></strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ═══ TABLE CARD ═══ --}}
    <div class="pc-card">
      <div class="pc-card-body">
        <div class="pc-tbl-wrap">
          <table id="productionTable" class="pc-tbl">
            <thead>
              <tr>
                <th>Batch #</th>
                <th>Date</th>
                <th>Source</th>
                <th>Items</th>
                <th>Notes</th>
                <th>Created By</th>
                <th style="width:170px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($entries as $e)
              <tr>
                <td><span class="pc-batch">{{ $e->entry_no }}</span></td>
                <td><span class="pc-date">{{ \Carbon\Carbon::parse($e->production_date)->format('d-M-Y') }}</span></td>
                <td>
                  <span class="pc-badge {{ $e->source === 'kitchen' ? 'pc-badge-kitchen' : 'pc-badge-warehouse' }}">
                    <i class="bi bi-{{ $e->source === 'kitchen' ? 'house-door' : 'building' }}"></i>
                    {{ $e->source }}
                  </span>
                </td>
                <td>
                  <div class="pc-items" title="{{ $e->product_details }}">
                    {{ \Illuminate\Support\Str::limit($e->product_details, 50) }}
                    <br>
                    <small><i class="bi bi-box-seam"></i> {{ $e->items_count }} items</small>
                  </div>
                </td>
                <td class="pc-notes">{{ $e->notes ?? '-' }}</td>
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
                <td colspan="7" class="pc-empty">
                  <i class="bi bi-inbox"></i>
                  <span>No production entries found.</span>
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
    $('#productionTable').DataTable({
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50, 100],
      order: [[1, 'desc']],
      language: {
        search: "Search Production:",
        lengthMenu: "Show _MENU_ entries",
        emptyTable: "No production entries found"
      }
    });
  });
</script>
@endsection
