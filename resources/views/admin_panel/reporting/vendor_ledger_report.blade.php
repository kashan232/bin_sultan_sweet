@extends('admin_panel.layout.app')

@section('content')
<style>
    :root {
        --vl-primary: #4f46e5;
        --vl-primary-light: #818cf8;
        --vl-bg: #f0f2f5;
        --vl-card-bg: #ffffff;
        --vl-border: rgba(0,0,0,0.04);
        --vl-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        --vl-text: #1e293b;
        --vl-text-muted: #94a3b8;
    }

    .vl-page { background: var(--vl-bg); min-height: 100vh; padding: 24px; }
    .vl-card { background: var(--vl-card-bg); border: 1px solid var(--vl-border); border-radius: 20px; box-shadow: var(--vl-shadow); overflow: hidden; }
    .vl-card-body { padding: 24px; }
    .vl-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--vl-text-muted); margin-bottom: 4px; display: block; }
    .vl-input, .vl-select {
        background: #f8fafc !important; border: 2px solid transparent !important; border-radius: 10px !important;
        padding: 8px 14px !important; font-size: 13px !important; font-weight: 600 !important;
        color: var(--vl-text) !important; transition: all 0.2s; height: auto !important;
    }
    .vl-input:focus, .vl-select:focus { background: #fff !important; border-color: var(--vl-primary) !important; box-shadow: 0 0 0 3px rgba(79,70,229,0.1) !important; }
    .vl-btn {
        border: none; border-radius: 10px; padding: 9px 20px; font-size: 13px; font-weight: 700;
        transition: all 0.25s; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
    }
    .vl-btn-primary { background: var(--vl-primary); color: #fff; box-shadow: 0 4px 14px rgba(79,70,229,0.3); }
    .vl-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.35); }
    .vl-btn-danger { background: #dc2626; color: #fff; }
    .vl-btn-danger:hover { background: #b91c1c; }
    .vl-btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(220,38,38,0.3); }

    .vl-ledger-box { border: none; border-radius: 16px; padding: 0; background: transparent; }
    .vl-ledger-title { font-size: 20px; font-weight: 900; color: var(--vl-text); margin-bottom: 16px; letter-spacing: 0.5px; }
    .vl-ledger-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; margin-bottom: 16px;
        font-size: 13px; font-weight: 600; color: var(--vl-text);
        display: flex; justify-content: space-between; align-items: center;
    }

    .vl-table { width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .vl-table thead th {
        background: #f1f5f9; color: var(--vl-text-muted); font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0; text-align: center;
    }
    .vl-table tbody td { padding: 8px 12px; font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; text-align: center; color: var(--vl-text); }
    .vl-table tbody tr:last-child td { border-bottom: none; }
    .vl-table tbody tr:hover td { background: #f8fafc; }
    .vl-table .text-left { text-align: left !important; }

    .vl-badge-positive { color: #059669; font-weight: 800; }
    .vl-badge-negative { color: #dc2626; font-weight: 800; }
    .vl-badge-neutral { color: var(--vl-primary); font-weight: 800; }

    .vl-total-row td { background: #f1f5f9; font-weight: 800; border-top: 2px solid #e2e8f0 !important; font-size: 14px; }
    .vl-opening td { background: #fafbfc; font-weight: 600; color: var(--vl-text-muted); }
</style>

<div class="vl-page">
    <div class="row g-3">
        <div class="col-12">
            <div class="vl-card">
                <div class="vl-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 style="font-weight:900;color:var(--vl-text);margin:0;font-size:20px;"><i class="bi bi-journal-text me-2" style="color:var(--vl-primary);"></i>Vendor Ledger</h4>
                            <p style="font-size:13px;color:var(--vl-text-muted);margin:2px 0 0;">View ledger by date range</p>
                        </div>
                    </div>

                    <form id="ledgerForm" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <span class="vl-label">Vendor</span>
                            <select name="Vendor_id" id="Vendor_id" class="vl-select form-control" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <span class="vl-label">Start Date</span>
                            <input type="date" name="start_date" id="start_date" class="vl-input form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <span class="vl-label">End Date</span>
                            <input type="date" name="end_date" id="end_date" class="vl-input form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" id="btnSearch" class="vl-btn vl-btn-primary flex-grow-1"><i class="bi bi-search"></i> Search</button>
                            <button id="exportPdfBtn" class="vl-btn vl-btn-danger" onclick="exportPDF()"><i class="bi bi-filetype-pdf"></i> PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="vl-card">
                <div class="vl-card-body">
                    <div id="loader" style="display:none;text-align:center;padding:40px;">
                        <div class="spinner-border" style="color:var(--vl-primary);width:2.5rem;height:2.5rem;" role="status"></div>
                        <p style="margin-top:10px;color:var(--vl-text-muted);font-weight:600;">Loading ledger...</p>
                    </div>
                    <div id="ledgerBox" style="display:none;">
                        <div class="vl-ledger-box" id="ledgerPdfArea">
                            <div class="vl-ledger-title">VENDOR LEDGER</div>
                            <div id="ledgerHeader" class="vl-ledger-header"></div>
                            <div class="table-responsive">
                                <table class="vl-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Inv / Ref</th>
                                            <th>Description</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ledgerBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#Vendor_id').select2({ placeholder: "Select Vendor", allowClear: true, width: '100%' });

    function formatDate(dateStr) {
        if (!dateStr) return '';
        var d = new Date(dateStr + 'T00:00:00');
        return String(d.getDate()).padStart(2,'0') + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + d.getFullYear();
    }

    $(document).on('click', '#btnSearch', function() {
        var cid = $("#Vendor_id").val();
        var start = $("#start_date").val();
        var end = $("#end_date").val();
        if (!cid || !start || !end) { alert("Please select all fields"); return; }

        $("#loader").show();
        $("#ledgerBox").hide();

        $.get("{{ route('report.vendor.ledger.fetch') }}", { vendor_id: cid, start_date: start, end_date: end }, function(res) {
            $("#loader").hide();
            $("#ledgerBox").show();
            $("#ledgerHeader").html('<span><strong>Vendor:</strong> ' + res.vendor.name + '</span><span><strong>Duration:</strong> ' + formatDate(start) + ' to ' + formatDate(end) + '</span>');

            var totalDebit = 0, totalCredit = 0, lastBalance = parseFloat(res.opening_balance) || 0;
            var html = '<tr class="vl-opening"><td>N/A</td><td>-</td><td>-</td><td>-</td><td class="text-left">Opening Balance</td><td class="vl-badge-neutral">Rs. ' + lastBalance.toFixed(2) + '</td></tr>';

            res.transactions.forEach(function(t) {
                var debit = 0, credit = 0;
                if (t.type === 'purchase') debit = parseFloat(t.amount) || 0;
                else if (t.type === 'purchase_return') credit = parseFloat(t.amount) || 0;
                else if (t.type === 'vendor_payment') credit = parseFloat(t.amount) || 0;
                else { debit = parseFloat(t.debit) || 0; credit = parseFloat(t.credit) || 0; }
                totalDebit += debit; totalCredit += credit;
                lastBalance = lastBalance + debit - credit;
                var invRef = t.invoice ?? '-';
                if (t.reference) invRef += ' (' + t.reference + ')';
                var balClass = lastBalance > 0 ? 'vl-badge-positive' : (lastBalance < 0 ? 'vl-badge-negative' : 'vl-badge-neutral');
                html += '<tr><td>' + formatDate(t.date.split(" ")[0]) + '</td><td>' + invRef + '</td><td class="text-left">' + t.description + '</td><td>' + (debit > 0 ? 'Rs. ' + debit.toFixed(2) : '-') + '</td><td>' + (credit > 0 ? 'Rs. ' + credit.toFixed(2) : '-') + '</td><td class="' + balClass + '">Rs. ' + lastBalance.toFixed(2) + '</td></tr>';
            });

            html += '<tr class="vl-total-row"><td colspan="3" class="text-left">Totals:</td><td>Rs. ' + totalDebit.toFixed(2) + '</td><td>Rs. ' + totalCredit.toFixed(2) + '</td><td class="' + (lastBalance > 0 ? 'vl-badge-positive' : (lastBalance < 0 ? 'vl-badge-negative' : 'vl-badge-neutral')) + '">Rs. ' + lastBalance.toFixed(2) + '</td></tr>';
            $("#ledgerBody").html(html);
        });
    });

    window.exportPDF = function() {
        var cid = $("#Vendor_id").val();
        var start = $("#start_date").val();
        var end = $("#end_date").val();
        if (!cid || !start || !end) { alert("Please generate ledger first"); return; }
        window.open("{{ route('report.vendor.ledger.pdf') }}?vendor_id=" + cid + "&start_date=" + start + "&end_date=" + end, "_blank");
    };
});
</script>
@endsection