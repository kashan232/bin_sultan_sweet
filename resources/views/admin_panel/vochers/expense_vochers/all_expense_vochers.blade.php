@extends('admin_panel.layout.app')
@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="main-content">
    <div class="container-fluid">
        <div class="card-header mt-2 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Expense Vouchers</h4>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expenseModal"><i class="bi bi-plus-lg"></i> Add Expense Voucher</button>
                <a class="btn btn-outline-primary" href="{{ route('expense-vochers') }}"><i class="bi bi-box-arrow-up-right"></i> Full Page</a>
            </div>
        </div>
        <div class="card shadow mt-4">
            <div class="card-body">
                <form action="{{ route('all-expense-vochers') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    @if(auth()->user()->hasRole('Admin'))
                    <div class="col-md-3">
                        <label class="form-label fw-bold">User / Cashier</label>
                        <select name="user_id" class="form-control">
                            <option value="all">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                        <a href="{{ route('all-expense-vochers') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mt-4 mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="productTable" class="table table-striped table-bordered align-middle nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Voucher No</th>
                                <th>Account Head</th>
                                <th>Account</th>
                                <th style="min-width:260px;">Remarks</th>
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                            <tr>
                                <td>{{ $voucher->id }}</td>
                                <td>{{ $voucher->evid }}</td>
                                <td>{{ $voucher->type_name }}</td>
                                <td>{{ $voucher->party_name }}</td>
                                <td>
                                    @php
                                    $remarks = is_array($voucher->remarks)
                                    ? $voucher->remarks
                                    : json_decode($voucher->remarks, true) ?? [];

                                    $amounts = is_array($voucher->amount)
                                    ? $voucher->amount
                                    : json_decode($voucher->amount, true) ?? [];
                                    @endphp

                                    @foreach ($remarks as $i => $remark)
                                    <div class="d-flex justify-content-between align-items-center mb-1 p-2 rounded bg-light border">
                                        <span class="text-dark fw-medium">
                                            {{ $remark }}
                                        </span>
                                        <span class="badge bg-primary">
                                            Rs {{ number_format($amounts[$i] ?? 0, 2) }}
                                        </span>
                                    </div>
                                    @endforeach
                                </td>
                                <td class="text-end fw-bold text-success">
                                    Rs {{ number_format($voucher->total_amount, 2) }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $voucher->user->name ?? 'Admin' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('expenseVoucher.print', $voucher->id) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-danger">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Expense Modal --}}
<div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 24px 48px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background:linear-gradient(135deg,#4f46e5,#818cf8);color:#fff;border-radius:16px 16px 0 0;padding:16px 20px;">
                <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Add Expense Voucher</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="expenseForm" style="padding:20px;">
                @csrf
                <input type="hidden" name="evid" value="{{ $nextRvid ?? 'EVID-001' }}">
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="font-size:12px;color:#64748b;">EVID</label>
                        <input type="text" class="form-control" value="{{ $nextRvid ?? 'EVID-001' }}" readonly style="background:#f8fafc;border-radius:10px;font-weight:600;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="font-size:12px;color:#64748b;">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius:10px;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold" style="font-size:12px;color:#64748b;">Account Head</label>
                        <select name="vendor_type" id="modalVendorType" class="form-control" required style="border-radius:10px;">
                            <option value="">Select</option>
                            @foreach($AccountHeads ?? [] as $head)
                            <option value="{{ $head->id }}">{{ $head->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="font-size:12px;color:#64748b;">Account</label>
                        <select name="vendor_id" id="modalVendorId" class="form-control" required style="border-radius:10px;">
                            <option value="">Select Head first</option>
                        </select>
                    </div>
                </div>
                <hr style="opacity:0.5;">
                <div style="font-weight:700;font-size:13px;color:#1e293b;margin-bottom:8px;">Expense Lines</div>
                <div id="modalExpenseRows">
                    <div class="row g-2 mb-2 expense-row">
                        <div class="col-md-7">
                            <input type="text" name="remarks[]" class="form-control" placeholder="Remark" required style="border-radius:10px;">
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="amount[]" class="form-control expense-amount" placeholder="Amount" required style="border-radius:10px;">
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="button" class="btn btn-outline-success btn-sm add-expense-row" style="border-radius:8px;"><i class="bi bi-plus"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-expense-row" style="border-radius:8px;"><i class="bi bi-dash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 offset-md-8">
                        <label class="form-label fw-bold" style="font-size:12px;color:#64748b;">Total Amount</label>
                        <input type="text" id="modalTotalAmount" class="form-control fw-bold" value="0.00" readonly style="color:#4f46e5;border-radius:10px;font-size:18px;">
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-end mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:10px;font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:700;padding:8px 24px;box-shadow:0 4px 14px rgba(79,70,229,0.3);">Save Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [
                [1, 'desc']
            ],
            columnDefs: [{
                targets: 0,
                orderable: false
            }],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });

        // Load accounts when head changes
        $('#modalVendorType').on('change', function() {
            var headId = $(this).val();
            if (!headId) { $('#modalVendorId').html('<option value="">Select Head first</option>'); return; }
            $.get('/get-accounts-by-head/' + headId, function(data) {
                var html = '<option value="">Select Account</option>';
                $.each(data, function(i, a) { html += '<option value="' + a.id + '">' + a.title + '</option>'; });
                $('#modalVendorId').html(html);
            });
        });

        // Add expense row
        $(document).on('click', '.add-expense-row', function() {
            var row = '<div class="row g-2 mb-2 expense-row">\
                <div class="col-md-7"><input type="text" name="remarks[]" class="form-control" placeholder="Remark" required style="border-radius:10px;"></div>\
                <div class="col-md-3"><input type="number" step="0.01" name="amount[]" class="form-control expense-amount" placeholder="Amount" required style="border-radius:10px;"></div>\
                <div class="col-md-2 d-flex gap-1">\
                    <button type="button" class="btn btn-outline-success btn-sm add-expense-row" style="border-radius:8px;"><i class="bi bi-plus"></i></button>\
                    <button type="button" class="btn btn-outline-danger btn-sm remove-expense-row" style="border-radius:8px;"><i class="bi bi-dash"></i></button>\
                </div></div>';
            $('#modalExpenseRows').append(row);
        });

        $(document).on('click', '.remove-expense-row', function() {
            if ($('.expense-row').length > 1) $(this).closest('.expense-row').remove();
            calcModalTotal();
        });

        $(document).on('input', '.expense-amount', calcModalTotal);

        function calcModalTotal() {
            var total = 0;
            $('.expense-amount').each(function() { total += parseFloat($(this).val()) || 0; });
            $('#modalTotalAmount').val(total.toFixed(2));
        }

        // AJAX submit - closes modal on success
        $('#expenseForm').on('submit', function(e) {
            e.preventDefault();
            var btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
            $.ajax({
                url: '{{ route("expense.vochers.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#expenseModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    var msg = 'Error saving voucher';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errs = Object.values(xhr.responseJSON.errors).flat();
                        msg = errs.join('<br>');
                    }
                    alert(msg);
                    btn.prop('disabled', false).text('Save Voucher');
                }
            });
        });
    });
</script>

@endsection