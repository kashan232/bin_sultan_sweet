@extends('admin_panel.layout.app')
@section('content')
<div class="main-content">
<div class="main-content-inner">
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-3 mt-2">
    <h2 class="page-title m-0">📦 New Stock Adjustment</h2>
    <a href="{{ route('stock-adjustment.index') }}" class="btn btn-danger">← Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $e)<p class="mb-0">{{ $e }}</p>@endforeach
</div>
@endif

<div class="card">
<div class="card-body">
<form action="{{ route('stock-adjustment.store') }}" method="POST" id="adjForm">
    @csrf

    {{-- Header Info --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="fw-bold">Date <span class="text-danger">*</span></label>
            <input type="date" name="adjustment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="fw-bold">Type <span class="text-danger">*</span></label>
            <select name="type" class="form-select" id="adjType" required>
                <option value="">-- Select --</option>
                <option value="increase">➕ Increase (Add Stock)</option>
                <option value="decrease">➖ Decrease (Remove Stock)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="fw-bold">Reason <span class="text-danger">*</span></label>
            <select name="reason" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Wastage">Wastage / Kharab Maal</option>
                <option value="Mix Sale Adjustment">Mix Sale Adjustment</option>
                <option value="Physical Count Correction">Physical Count Correction</option>
                <option value="Damaged/Expired">Damaged / Expired</option>
                <option value="Bonus/Free">Bonus / Free Stock Added</option>
                <option value="Opening Stock">Opening Stock</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="fw-bold">Internal Notes</label>
            <input type="text" name="notes" class="form-control" placeholder="Optional...">
        </div>
    </div>

    {{-- Type Alert --}}
    <div id="typeAlert" class="alert d-none mb-3"></div>

    {{-- Items Table --}}
    <div class="table-responsive">
        <table class="table table-bordered" id="itemsTable">
            <thead class="table-dark">
                <tr>
                    <th width="38%">Product</th>
                    <th>Unit</th>
                    <th>Qty (KG/Pcs)</th>
                    <th>Item Note</th>
                    <th width="60px">Del</th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control select2-prod" required>
                            <option value="">Search product...</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}"
                                data-unit="{{ $p->unit_type === 'kg' ? 'KG' : ($p->unit->name ?? 'Pc') }}"
                                data-iskg="{{ $p->unit_type === 'kg' ? '1' : '0' }}">
                                {{ $p->item_code }} - {{ $p->item_name }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" class="form-control unit-disp" readonly></td>
                    <td>
                        <input type="number" name="qty[]" class="form-control" step="0.001" min="0.001" required>
                        <small class="text-muted conv-disp"></small>
                    </td>
                    <td><input type="text" name="item_note[]" class="form-control" placeholder="e.g. qty wrong reason"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <button type="button" id="addRow" class="btn btn-success mt-1 mb-3"><i class="fas fa-plus"></i> Add Row</button>
    <hr>
    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-save"></i> Save Adjustment</button>

</form>
</div>
</div>

</div>
</div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // Init first row select2
    initSelect2($('#itemsBody tr:first'));

    // Add row
    $('#addRow').click(function () {
        let first = $('#itemsBody tr:first');
        let newRow = first.clone(false);
        newRow.find('input').val('');
        newRow.find('.unit-disp').val('');
        newRow.find('.conv-disp').text('');
        newRow.find('.select2-container').remove();
        newRow.find('select').val('');
        $('#itemsBody').append(newRow);
        initSelect2(newRow);
        newRow.find('select').trigger('change');
        newRow.find('.remove-row').click(function () {
            if ($('#itemsBody tr').length > 1) $(this).closest('tr').remove();
        });
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        if ($('#itemsBody tr').length > 1) $(this).closest('tr').remove();
    });

    // Product change
    $(document).on('change', '.select2-prod', function () {
        let opt = $(this).find(':selected');
        let row = $(this).closest('tr');
        row.find('.unit-disp').val(opt.data('unit') || '');
        updateConv(row);
    });

    // Qty change
    $(document).on('input', 'input[name="qty[]"]', function () {
        updateConv($(this).closest('tr'));
    });

    function updateConv(row) {
        let isKg = row.find('.select2-prod option:selected').data('iskg') == '1';
        let qty = parseFloat(row.find('input[name="qty[]"]').val()) || 0;
        if (isKg && qty > 0) {
            row.find('.conv-disp').text('= ' + (qty * 1000).toLocaleString() + ' grams in stock');
        } else {
            row.find('.conv-disp').text('');
        }
    }

    // Type alert
    $('#adjType').change(function () {
        let t = $(this).val();
        let al = $('#typeAlert');
        if (t === 'increase') {
            al.removeClass('d-none alert-danger').addClass('alert-success').html('<strong>➕ Increase:</strong> Stock will be <b>ADDED</b>. Use for: Opening Stock, Bonus, Correction (undercount).');
        } else if (t === 'decrease') {
            al.removeClass('d-none alert-success').addClass('alert-danger').html('<strong>➖ Decrease:</strong> Stock will be <b>REMOVED</b>. Use for: Wastage, Mix Sale, Damaged, Correction (overcount).');
        } else {
            al.addClass('d-none');
        }
    });

    function initSelect2(row) {
        row.find('.select2-prod').select2({ width: '100%', placeholder: 'Search product...' });
    }
});
</script>
@endsection
