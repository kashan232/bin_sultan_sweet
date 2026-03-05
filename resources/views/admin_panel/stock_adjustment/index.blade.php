@extends('admin_panel.layout.app')
@section('content')
<div class="main-content">
<div class="main-content-inner">
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-3 mt-2">
    <h2 class="page-title m-0">📦 Stock Adjustments</h2>
    <div>
        <a href="{{ route('stock-adjustment.report') }}" class="btn btn-outline-secondary me-2"><i class="fas fa-chart-bar"></i> Report</a>
        <a href="{{ route('stock-adjustment.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Adjustment</a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- FILTERS --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-bold mb-1">From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <label class="form-label fw-bold mb-1">To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <label class="form-label fw-bold mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="increase" {{ request('type')=='increase' ? 'selected':'' }}>➕ Increase</option>
                    <option value="decrease" {{ request('type')=='decrease' ? 'selected':'' }}>➖ Decrease</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                <a href="{{ route('stock-adjustment.index') }}" class="btn btn-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Ref #</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Items</th>
                        <th>By</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adj)
                    <tr>
                        <td><strong>{{ $adj->ref_no }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($adj->adjustment_date)->format('d-M-Y') }}</td>
                        <td>
                            @if($adj->type === 'increase')
                                <span class="badge bg-success">➕ Increase</span>
                            @else
                                <span class="badge bg-danger">➖ Decrease</span>
                            @endif
                        </td>
                        <td>{{ $adj->reason }}</td>
                        <td>
                            @php $cnt = \App\Models\StockAdjustmentItem::where('adjustment_id', $adj->id)->count(); @endphp
                            <span class="badge bg-secondary">{{ $cnt }} items</span>
                        </td>
                        <td>{{ optional($adj->user)->name ?? 'System' }}</td>
                        <td>{{ Str::limit($adj->notes, 40) }}</td>
                        <td>
                            <a href="{{ route('stock-adjustment.show', $adj->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No adjustments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2">{{ $adjustments->links() }}</div>
    </div>
</div>

</div>
</div>
</div>
@endsection
