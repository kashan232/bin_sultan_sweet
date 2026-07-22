@extends('admin_panel.layout.app')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

:root {
  --sc-bg: #f1f4f9;
  --sc-surface: #ffffff;
  --sc-border: #e9edf2;
  --sc-border-lt: #f1f4f9;
  --sc-text: #0b1a33;
  --sc-text-sec: #54657e;
  --sc-text-muted: #8896ab;
  --sc-accent: #2b7fff;
  --sc-accent-drk: #1a6ae8;
  --sc-radius: 14px;
  --sc-radius-sm: 9px;
  --sc-shadow: 0 1px 2px rgba(0,0,0,.03), 0 1px 3px rgba(0,0,0,.05);
  --sc-shadow-lg: 0 8px 30px rgba(0,0,0,.07), 0 3px 12px rgba(0,0,0,.04);
  --sc-shadow-xl: 0 20px 60px rgba(0,0,0,.10), 0 8px 24px rgba(0,0,0,.06);
  --sc-font: 'Inter', -apple-system, 'Segoe UI', Roboto, sans-serif;
}

.sc-page * { font-family: var(--sc-font); }
.sc-page { background: var(--sc-bg); min-height: 100vh; padding-bottom: 2.5rem; }

/* ═══════ HEADER ═══════ */
.sc-hdr {
  position: relative;
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 50%, #1a4d8c 100%);
  border-radius: var(--sc-radius);
  padding: 1.3rem 1.8rem;
  margin-bottom: 1.5rem;
  box-shadow: var(--sc-shadow-xl);
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}
.sc-hdr::before {
  content: '';
  position: absolute;
  inset: 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 90%, rgba(43,127,255,.15) 0%, transparent 100%),
    radial-gradient(ellipse 40% 40% at 90% 10%, rgba(43,127,255,.08) 0%, transparent 100%);
  pointer-events: none;
}
.sc-hdr::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  opacity: .5;
  pointer-events: none;
}
.sc-hdr > * { position: relative; z-index: 1; }
.sc-hdr h2 {
  font-size: 1.3rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -.4px;
  margin: 0;
  display: flex;
  align-items: center;
  gap: .65rem;
}
.sc-hdr h2 i { font-size: 1.35rem; color: #60a5fa; }
.sc-hdr-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.sc-btn {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  border-radius: var(--sc-radius-sm);
  font-weight: 600;
  font-size: .78rem;
  transition: all .25s ease;
  cursor: pointer;
  text-decoration: none;
  border: none;
  padding: .45rem 1.1rem;
}
.sc-btn-primary {
  background: linear-gradient(135deg, var(--sc-accent) 0%, var(--sc-accent-drk) 100%);
  color: #fff;
}
.sc-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,127,255,.25); color: #fff; }
.sc-btn-outline {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  color: #fff;
}
.sc-btn-outline:hover { background: rgba(255,255,255,.14); color: #fff; }

/* ═══════ FILTER CARD ═══════ */
.sc-filter {
  background: var(--sc-surface);
  border: 1px solid var(--sc-border);
  border-radius: var(--sc-radius);
  box-shadow: var(--sc-shadow);
  padding: 1rem 1.25rem;
  margin-bottom: 1.25rem;
  transition: box-shadow .3s ease;
}
.sc-filter:hover { box-shadow: var(--sc-shadow-lg); }
.sc-filter .form-label {
  font-size: .72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  color: var(--sc-text-muted);
  margin-bottom: .25rem;
}
.sc-filter .form-control, .sc-filter .form-select {
  border: 1.5px solid var(--sc-border);
  border-radius: var(--sc-radius-sm);
  padding: .45rem .7rem;
  font-size: .82rem;
  font-weight: 500;
  color: var(--sc-text);
  background: var(--sc-surface);
  outline: none;
  transition: all .25s ease;
  height: auto;
}
.sc-filter .form-control:focus, .sc-filter .form-select:focus {
  border-color: var(--sc-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}

/* ═══════ SUMMARY CARDS ═══════ */
.sc-summary {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 1.25rem;
}
.sc-stat {
  flex: 1 1 140px;
  min-width: 130px;
  border-radius: var(--sc-radius);
  padding: 1rem 1.1rem;
  box-shadow: var(--sc-shadow);
  transition: all .3s ease;
  position: relative;
  overflow: hidden;
}
.sc-stat:hover { transform: translateY(-2px); box-shadow: var(--sc-shadow-lg); }
.sc-stat::after {
  content: '';
  position: absolute;
  top: -50%;
  right: -30%;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: rgba(255,255,255,.06);
  pointer-events: none;
}
.sc-stat-icon {
  font-size: 1.3rem;
  margin-bottom: .2rem;
  opacity: .85;
}
.sc-stat-label {
  font-size: .65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .4px;
  opacity: .85;
  margin-bottom: .15rem;
}
.sc-stat-value {
  font-size: 1.15rem;
  font-weight: 800;
  line-height: 1.2;
}
.sc-stat-value small { font-size: .7rem; font-weight: 600; opacity: .7; }

/* ═══════ DETAIL CARDS ═══════ */
.sc-detail {
  background: var(--sc-surface);
  border: 1px solid var(--sc-border);
  border-radius: var(--sc-radius);
  box-shadow: var(--sc-shadow);
  transition: box-shadow .3s ease;
  height: 100%;
}
.sc-detail:hover { box-shadow: var(--sc-shadow-lg); }
.sc-detail-body { padding: 1.2rem; }
.sc-detail-title {
  font-size: .95rem;
  font-weight: 700;
  color: var(--sc-text);
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: .5rem;
}
.sc-detail-title i { font-size: 1.1rem; }

/* ═══════ TABLES ═══════ */
.sc-tbl-wrap {
  overflow-x: auto;
  border: 1px solid var(--sc-border);
  border-radius: var(--sc-radius-sm);
}
.sc-tbl {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  font-size: .8rem;
}
.sc-tbl thead th {
  background: #f8fafc;
  font-size: .65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .45px;
  color: var(--sc-text-muted);
  padding: .5rem .65rem;
  border-bottom: 2px solid var(--sc-border);
  text-align: left;
  white-space: nowrap;
}
.sc-tbl thead th.text-end { text-align: right; }
.sc-tbl tbody td {
  padding: .45rem .65rem;
  border-bottom: 1px solid var(--sc-border-lt);
  vertical-align: middle;
  color: var(--sc-text-sec);
}
.sc-tbl tbody tr { transition: background .12s ease; }
.sc-tbl tbody tr:hover { background: #fafbfc; }
.sc-tbl tbody tr:last-child td { border-bottom: none; }
.sc-tbl .sc-inv { font-weight: 600; color: var(--sc-text); }
.sc-tbl .sc-amt { font-weight: 700; white-space: nowrap; }
.sc-tbl .sc-date { font-size: .76rem; color: var(--sc-text-muted); }
.sc-tbl tbody tr.sc-row-empty td {
  padding: 2rem .65rem;
  text-align: center;
  color: var(--sc-text-muted);
  font-size: .82rem;
}
.sc-tbl tbody tr.sc-row-empty i { font-size: 1.5rem; display: block; margin-bottom: .3rem; color: #d0d6e8; }

/* ═══════ LOADER ═══════ */
.sc-loader {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(11,26,51,.35);
  backdrop-filter: blur(2px);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}
.sc-loader.show { display: flex; }
.sc-loader-inner {
  background: #fff;
  border-radius: var(--sc-radius);
  padding: 2rem 2.5rem;
  text-align: center;
  box-shadow: var(--sc-shadow-xl);
}
.sc-loader-spinner {
  width: 38px;
  height: 38px;
  border: 3.5px solid var(--sc-border);
  border-top-color: var(--sc-accent);
  border-radius: 50%;
  animation: sc-spin .7s linear infinite;
  margin: 0 auto .6rem;
}
@keyframes sc-spin { to { transform: rotate(360deg); } }
.sc-loader-text { font-size: .82rem; font-weight: 600; color: var(--sc-text-sec); }

/* ═══════ ANIMATIONS ═══════ */
@keyframes sc-fade-up {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}
.sc-fade-in { animation: sc-fade-up .35s ease both; }

/* ═══════ RESPONSIVE ═══════ */
@media (max-width: 768px) {
  .sc-hdr { padding: 1rem 1.25rem; }
  .sc-hdr h2 { font-size: 1.1rem; }
  .sc-stat { flex: 1 1 120px; min-width: 110px; padding: .8rem .9rem; }
  .sc-stat-value { font-size: 1rem; }
  .sc-detail-body { padding: .9rem; }
  .sc-filter { padding: .8rem 1rem; }
}
</style>

<div class="sc-page">
  <div class="container-fluid px-3 px-md-4 py-3">
    {{-- ═══ HEADER ═══ --}}
    <div class="sc-hdr">
      <h2><i class="la la-file-text"></i> Sale Closing Report</h2>
      <div class="sc-hdr-actions">
        <button type="button" id="btnPrint" class="sc-btn sc-btn-outline">
          <i class="la la-print"></i> Print
        </button>
      </div>
    </div>

    {{-- ═══ FILTERS ═══ --}}
    <div class="sc-filter">
      <form id="ClosingFilterForm" class="row g-2 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Start Date & Time</label>
          <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ date('Y-m-d\T00:00') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">End Date & Time</label>
          <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ date('Y-m-d\T23:59') }}">
        </div>
        @if(auth()->user()->hasRole('Admin'))
        <div class="col-md-2">
          <label class="form-label">Cashier</label>
          <select name="user_id" id="user_id" class="form-select">
            <option value="all">All Users</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
        @else
          <input type="hidden" id="user_id" value="{{ auth()->id() }}">
        @endif
        <div class="col-md-2">
          <label class="form-label">&nbsp;</label>
          <button type="button" id="btnSearch" class="sc-btn sc-btn-primary w-100">
            <i class="la la-search"></i> Search
          </button>
        </div>
      </form>
    </div>

    {{-- ═══ SUMMARY CARDS ═══ --}}
    <div class="sc-summary" id="summaryContainer">
      <div class="sc-stat" style="background:linear-gradient(135deg,#059669,#10b981);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-shopping-cart"></i></div>
        <div class="sc-stat-label">Total Sales (+)</div>
        <div class="sc-stat-value" id="summarySale">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#0284c7,#38bdf8);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-money"></i></div>
        <div class="sc-stat-label">Cash (+)</div>
        <div class="sc-stat-value" id="summaryCash">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-credit-card"></i></div>
        <div class="sc-stat-label">Card (+)</div>
        <div class="sc-stat-value" id="summaryCard">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#dc2626,#f87171);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-percent"></i></div>
        <div class="sc-stat-label">Discount (-)</div>
        <div class="sc-stat-value" id="summaryDiscount">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#ea580c,#fb923c);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-money-bill-wave"></i></div>
        <div class="sc-stat-label">Expenses (-)</div>
        <div class="sc-stat-value" id="summaryExpense">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#0b1a33,#1e40af);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-balance-scale"></i></div>
        <div class="sc-stat-label">Net Balance</div>
        <div class="sc-stat-value" id="summaryNet">Rs 0</div>
      </div>
      <div class="sc-stat" style="background:linear-gradient(135deg,#4b5563,#9ca3af);color:#fff;">
        <div class="sc-stat-icon"><i class="la la-drawer"></i></div>
        <div class="sc-stat-label">Cash in Drawer</div>
        <div class="sc-stat-value" id="summaryDrawer">Rs 0</div>
      </div>
    </div>

    {{-- ═══ DETAIL TABLES ═══ --}}
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="sc-detail">
          <div class="sc-detail-body">
            <div class="sc-detail-title"><i class="la la-shopping-cart" style="color:#059669;"></i> Sales Detailed</div>
            <div class="sc-tbl-wrap">
              <table class="sc-tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody id="saleBody">
                  <tr class="sc-row-empty"><td colspan="3"><i class="la la-inbox"></i>No sales found</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="sc-detail">
          <div class="sc-detail-body">
            <div class="sc-detail-title"><i class="la la-money-bill-wave" style="color:#ea580c;"></i> Expenses Detailed</div>
            <div class="sc-tbl-wrap">
              <table class="sc-tbl">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>EVID</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody id="expenseBody">
                  <tr class="sc-row-empty"><td colspan="3"><i class="la la-inbox"></i>No expenses found</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══ LOADER OVERLAY ═══ --}}
  <div class="sc-loader" id="scLoader">
    <div class="sc-loader-inner">
      <div class="sc-loader-spinner"></div>
      <div class="sc-loader-text">Loading report…</div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
  fetchData();

  // ─── Search ───
  $('#btnSearch').on('click', fetchData);
  $('#start_date, #end_date, #user_id').on('change', fetchData);

  // ─── Keyboard shortcut: Enter ───
  $('#ClosingFilterForm').on('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); fetchData(); }
  });

  // ─── Print ───
  $('#btnPrint').on('click', function() {
    let start = $('#start_date').val();
    let end = $('#end_date').val();
    let user = $('#user_id').val();
    window.open("{{ route('report.sale_closing.print') }}?start_date=" + start + "&end_date=" + end + "&user_id=" + user, '_blank');
  });

  // ─── Fetch ───
  function fetchData() {
    let start = $('#start_date').val();
    let end = $('#end_date').val();
    let user = $('#user_id').val();

    $('#scLoader').addClass('show');

    $.ajax({
      url: "{{ route('report.sale_closing.fetch') }}",
      type: "GET",
      data: { start_date: start, end_date: end, user_id: user },
      success: function(res) {
        $('#scLoader').removeClass('show');

        // Summary cards with animation
        animateValue('summarySale', res.total_sale);
        animateValue('summaryCash', res.total_cash);
        animateValue('summaryCard', res.total_card);
        animateValue('summaryExpense', res.total_expense);
        $('#summaryDiscount').text('Rs ' + (res.total_discount || 0).toLocaleString());

        let netAmount = res.net_amount || 0;
        let netEl = $('#summaryNet');
        netEl.text('Rs ' + netAmount.toLocaleString());
        netEl.closest('.sc-stat').css('background', netAmount < 0
          ? 'linear-gradient(135deg,#dc2626,#f87171)'
          : 'linear-gradient(135deg,#0b1a33,#1e40af)');

        let drawerAmount = res.total_cash - res.total_expense;
        animateValue('summaryDrawer', drawerAmount);

        // Sales table
        let saleHtml = '';
        if (res.sales && res.sales.length) {
          res.sales.forEach(function(s, idx) {
            let d = new Date(s.created_at);
            let dateStr = d.getDate() + '-' + (d.getMonth()+1) + '-' + d.getFullYear();
            let timeStr = d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
            saleHtml += '<tr class="sc-fade-in" style="animation-delay:' + (idx * 0.03) + 's">'
              + '<td class="sc-date">' + dateStr + ' <span style="font-size:.7rem;color:#b0b8c8">' + timeStr + '</span></td>'
              + '<td class="sc-inv">' + (s.invoice_no || '—') + '</td>'
              + '<td class="sc-amt text-end">' + parseFloat(s.total_net).toLocaleString() + '</td>'
              + '</tr>';
          });
        } else {
          saleHtml = '<tr class="sc-row-empty"><td colspan="3"><i class="la la-inbox"></i>No sales found</td></tr>';
        }
        $('#saleBody').html(saleHtml);

        // Expenses table
        let expHtml = '';
        if (res.expenses && res.expenses.length) {
          res.expenses.forEach(function(e, idx) {
            expHtml += '<tr class="sc-fade-in" style="animation-delay:' + (idx * 0.03) + 's">'
              + '<td class="sc-date">' + (e.date || '—') + '</td>'
              + '<td class="sc-inv">' + (e.evid || '—') + '</td>'
              + '<td class="sc-amt text-end">' + parseFloat(e.total_amount).toLocaleString() + '</td>'
              + '</tr>';
          });
        } else {
          expHtml = '<tr class="sc-row-empty"><td colspan="3"><i class="la la-inbox"></i>No expenses found</td></tr>';
        }
        $('#expenseBody').html(expHtml);
      },
      error: function(xhr) {
        $('#scLoader').removeClass('show');
        let msg = 'Error fetching data';
        try { msg = JSON.parse(xhr.responseText).message || msg; } catch(e) {}
        Swal.fire({ icon: 'error', title: 'Failed', text: msg, timer: 3000, showConfirmButton: false });
      }
    });
  }

  // ─── Animate number counting ───
  function animateValue(id, target) {
    let el = $('#' + id);
    let current = parseFloat(el.text().replace(/Rs\s*|,/g, '')) || 0;
    target = parseFloat(target) || 0;
    if (current === target) { el.text('Rs ' + target.toLocaleString()); return; }

    let duration = 400, startTime = null;
    let startVal = current;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      let progress = Math.min((timestamp - startTime) / duration, 1);
      let eased = 1 - Math.pow(1 - progress, 3);
      let currentVal = startVal + (target - startVal) * eased;
      el.text('Rs ' + Math.round(currentVal).toLocaleString());
      if (progress < 1) requestAnimationFrame(step);
      else el.text('Rs ' + target.toLocaleString());
    }
    requestAnimationFrame(step);
  }
});
</script>
@endsection
