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

/* ══════════════ HEADER ══════════════ */
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
  padding: .5rem 1.35rem;
}

.pc-btn-ghost {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  color: #fff;
}

.pc-btn-ghost:hover { background: rgba(255,255,255,.14); color: #fff; }

/* ══════════════ CARD ══════════════ */
.pc-card {
  background: var(--pc-surface);
  border: 1px solid var(--pc-border);
  border-radius: var(--pc-radius);
  box-shadow: var(--pc-shadow);
  transition: box-shadow .3s ease;
}

.pc-card:hover { box-shadow: var(--pc-shadow-lg); }

.pc-card-hd {
  padding: 1.1rem 1.5rem;
  border-bottom: 1px solid var(--pc-border-lt);
  font-size: .9rem;
  font-weight: 700;
  color: var(--pc-text);
  display: flex;
  align-items: center;
  gap: .5rem;
}

.pc-card-hd i { color: var(--pc-accent); font-size: 1rem; }

.pc-card-body { padding: 1.5rem; }

/* ══════════════ FORM FIELDS ══════════════ */
.pc-lbl {
  font-size: .78rem;
  font-weight: 600;
  color: var(--pc-text-sec);
  margin-bottom: .35rem;
  display: flex;
  align-items: center;
  gap: .3rem;
}

.pc-lbl i { color: var(--pc-accent); font-size: .8rem; }

.pc-fld {
  border: 1.5px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
  padding: .5rem .85rem;
  font-size: .85rem;
  font-weight: 500;
  color: var(--pc-text);
  background: var(--pc-surface);
  transition: all .25s ease;
  width: 100%;
  outline: none;
}

.pc-fld:focus {
  border-color: var(--pc-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.1);
}

.pc-fld::placeholder { color: var(--pc-text-muted); font-weight: 400; }

.pc-fld[readonly] {
  background: #f8fafc;
  color: var(--pc-text-sec);
  cursor: default;
}

select.pc-fld {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238896ab' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right .75rem center;
  padding-right: 2.2rem;
}

/* ══════════════ TABLE ══════════════ */
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

.pc-tbl tbody tr:last-child td { border-bottom: none; }

.pc-tbl tbody tr { transition: background .12s ease; }
.pc-tbl tbody tr:hover { background: #fafbfc; }

.pc-tbl .pc-tbl-fld {
  border: 1.5px solid var(--pc-border);
  border-radius: 6px;
  padding: .38rem .65rem;
  font-size: .82rem;
  font-weight: 500;
  color: var(--pc-text);
  background: var(--pc-surface);
  transition: all .25s ease;
  outline: none;
  width: 100%;
}

.pc-tbl .pc-tbl-fld:focus {
  border-color: var(--pc-accent);
  box-shadow: 0 0 0 3px rgba(43,127,255,.08);
}

.pc-tbl .pc-tbl-fld[readonly] {
  background: #f8fafc;
  color: var(--pc-text-sec);
}

.pc-tbl .pc-conv {
  font-size: .73rem;
  font-weight: 500;
  color: var(--pc-text-muted);
  display: block;
  margin-top: 2px;
}

/* ══════════════ ACTIONS ══════════════ */
.pc-act-remove {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: 1.5px solid var(--pc-border);
  background: transparent;
  color: var(--pc-danger);
  transition: all .2s ease;
  cursor: pointer;
  font-size: .9rem;
}

.pc-act-remove:hover {
  background: #fef2f2;
  border-color: #f5d0d0;
}

.pc-act-add {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  border-radius: var(--pc-radius-sm);
  padding: .5rem 1.3rem;
  font-weight: 600;
  font-size: .82rem;
  background: transparent;
  border: 1.5px dashed var(--pc-border);
  color: var(--pc-text-sec);
  transition: all .2s ease;
  cursor: pointer;
}

.pc-act-add:hover {
  border-color: var(--pc-accent);
  color: var(--pc-accent);
  background: #f5f9ff;
}

.pc-act-submit {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  border-radius: var(--pc-radius-sm);
  padding: .6rem 2rem;
  font-weight: 700;
  font-size: .88rem;
  background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%);
  color: #fff;
  border: none;
  transition: all .3s ease;
  cursor: pointer;
}

.pc-act-submit:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(43,127,255,.25);
  color: #fff;
}

/* ══════════════ SELECT2 OVERRIDES ══════════════ */
.pc-page .select2-container--default .select2-selection--single {
  border: 1.5px solid var(--pc-border) !important;
  border-radius: 6px !important;
  height: 38px !important;
  padding: .38rem .65rem !important;
}

.pc-page .select2-container--default .select2-selection--single .select2-selection__rendered {
  color: var(--pc-text) !important;
  font-size: .84rem !important;
  font-weight: 500 !important;
  line-height: normal !important;
  padding: 0 !important;
}

.pc-page .select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 36px !important;
}

.pc-page .select2-container--default .select2-search--dropdown .select2-search__field {
  border: 1.5px solid var(--pc-border) !important;
  border-radius: 6px !important;
  font-size: .84rem !important;
}

.pc-page .select2-dropdown {
  border: 1.5px solid var(--pc-border) !important;
  border-radius: 8px !important;
  box-shadow: var(--pc-shadow-lg) !important;
}

/* ══════════════ RESPONSIVE ══════════════ */
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
      <h2><i class="bi bi-gear-wide-connected"></i>Own Production Entry</h2>
      <a href="{{ route('production.index') }}" class="pc-btn pc-btn-ghost">
        <i class="bi bi-arrow-left"></i>Back
      </a>
    </div>

    {{-- ═══ MAIN CARD ═══ --}}
    <div class="pc-card">
      <div class="pc-card-hd"><i class="bi bi-clipboard-data"></i>Production Batch Details</div>
      <div class="pc-card-body">
        <form action="{{ route('production.store') }}" method="POST">
          @csrf

          {{-- ROW 1: Date / Entry# / Source --}}
          <div class="row g-3 mb-4">
            <div class="col-md-3">
              <label class="pc-lbl"><i class="bi bi-calendar-event"></i>Production Date</label>
              <input type="date" name="production_date" value="{{ date('Y-m-d') }}" class="pc-fld" required>
            </div>
            <div class="col-md-3">
              <label class="pc-lbl"><i class="bi bi-hash"></i>Batch / Entry #</label>
              <input type="text" name="entry_no" value="PROD-{{ date('Ymd-His') }}" class="pc-fld" readonly>
            </div>
            <div class="col-md-3">
              <label class="pc-lbl"><i class="bi bi-geo-alt"></i>Source</label>
              <select name="source" class="pc-fld">
                <option value="kitchen">Main Kitchen</option>
                <option value="warehouse">Warehouse</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="pc-lbl"><i class="bi bi-card-text"></i>Notes</label>
              <input type="text" name="notes" class="pc-fld" placeholder="Optional notes for this batch...">
            </div>
          </div>

          {{-- ═══ ITEMS TABLE ═══ --}}
          <div class="pc-tbl-wrap mb-3">
            <table class="pc-tbl">
              <thead>
                <tr>
                  <th style="width:28%;">Product</th>
                  <th style="width:12%;">Code</th>
                  <th style="width:8%;">Unit</th>
                  <th style="width:16%;">Entered Qty (KG/Pc)</th>
                  <th style="width:18%;">Note</th>
                  <th style="width:70px;">Action</th>
                </tr>
              </thead>
              <tbody id="productionItems">
                <tr>
                  <td>
                    <select name="product_id[]" class="pc-tbl-fld select2 product-select" required style="width:100%;">
                      <option value="">Search Product...</option>
                      @foreach($products as $p)
                      <option value="{{ $p->id }}"
                        data-code="{{ $p->item_code }}"
                        data-unit="{{ $p->unit_type === 'kg' ? 'KG' : ($p->unit->name ?? 'Pc') }}"
                        data-is-gram="{{ $p->unit_type === 'kg' || str_contains(strtolower($p->item_name), 'gram') || str_contains(strtolower($p->unit->name ?? ''), 'gram') ? '1' : '0' }}">
                        {{ $p->item_code }} - {{ $p->item_name }}
                      </option>
                      @endforeach
                    </select>
                    <div class="variant-container mt-1" style="display:none;">
                      <select name="variant_id[]" class="pc-tbl-fld variant-select" style="width:100%;">
                        <option value="">Select Size (Optional)</option>
                      </select>
                    </div>
                  </td>
                  <td><input type="text" class="pc-tbl-fld code-display" readonly></td>
                  <td><input type="text" class="pc-tbl-fld unit-display" readonly></td>
                  <td>
                    <input type="number" step="0.001" name="qty[]" class="pc-tbl-fld qty-input" required min="0.001">
                    <small class="pc-conv conversion-display"></small>
                  </td>
                  <td><input type="text" name="item_note[]" class="pc-tbl-fld"></td>
                  <td>
                    <button type="button" class="pc-act-remove remove-row" title="Remove row">
                      <i class="bi bi-x-lg"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          {{-- ═══ BOTTOM BAR ═══ --}}
          <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
            <button type="button" class="pc-act-add" id="addRow">
              <i class="bi bi-plus-circle"></i>Add More
            </button>
            <div class="ms-auto">
              <button type="submit" class="pc-act-submit">
                <i class="bi bi-floppy"></i>Save Entry
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  $('.select2').select2({ width: '100%', placeholder: 'Select product...' });

  $(document).on('change', '.product-select', function() {
    let opt = $(this).find(':selected');
    let row = $(this).closest('tr');
    row.find('.code-display').val(opt.data('code'));
    row.find('.unit-display').val(opt.data('unit'));
    updateConversion(row);

    let productId = $(this).val();
    let variantSelect = row.find('.variant-select');
    let variantContainer = row.find('.variant-container');
    let isGram = opt.data('is-gram') == '1';

    if (productId && !isGram) {
      variantContainer.show();
      variantSelect.html('<option value="">Loading sizes...</option>');
      $.ajax({
        url: '/pos/product-variants/' + productId,
        type: 'GET',
        success: function(res) {
          variantSelect.html('<option value="">Select Size (Optional)</option>');
          if (res.variants && res.variants.length > 0) {
            res.variants.forEach(function(v) {
              variantSelect.append('<option value="' + v.id + '">' + v.size_label + '</option>');
            });
          } else {
            variantContainer.hide();
            variantSelect.html('<option value="">No Sizes</option>');
          }
        },
        error: function() {
          variantSelect.html('<option value="">Select Size (Optional)</option>');
        }
      });
    } else {
      variantContainer.hide();
      variantSelect.html('<option value="">Select Size (Optional)</option>');
    }
  });

  $(document).on('input', '.qty-input', function() {
    updateConversion($(this).closest('tr'));
  });

  function updateConversion(row) {
    let qty = parseFloat(row.find('.qty-input').val()) || 0;
    let isGram = row.find('.product-select option:selected').data('is-gram') == '1';
    if (isGram && qty > 0) {
      let grams = qty * 1000;
      row.find('.conversion-display').text('(' + grams.toLocaleString() + ' grams to stock)');
    } else {
      row.find('.conversion-display').text('');
    }
  }

  $('#addRow').click(function() {
    let newRow = $('#productionItems tr:first').clone();
    newRow.find('input').val('');
    newRow.find('.conversion-display').text('');
    newRow.find('.variant-container').hide();
    newRow.find('.variant-select').html('<option value="">Select Size (Optional)</option>');
    newRow.find('.select2-container').remove();
    $('#productionItems').append(newRow);
    newRow.find('.select2').select2({ width: '100%' });
  });

  $(document).on('click', '.remove-row', function() {
    if ($('#productionItems tr').length > 1) $(this).closest('tr').remove();
  });
});
</script>
@endsection
