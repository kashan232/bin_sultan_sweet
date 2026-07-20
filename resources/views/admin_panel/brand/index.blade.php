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
  font-size: .85rem;
}

.pc-tbl thead th {
  background: #f8fafc;
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .6px;
  color: var(--pc-text-muted);
  padding: .65rem .85rem;
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

.pc-tbl .pc-id { color: var(--pc-text-muted); font-weight: 600; width: 80px; }
.pc-tbl .pc-name { font-weight: 600; color: var(--pc-text); }

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
  padding: .3rem .75rem;
  font-size: .75rem;
  font-weight: 600;
  transition: all .2s ease;
  text-decoration: none;
  cursor: pointer;
  border: 1.5px solid transparent;
}

.pc-act-edit { background: #eef2ff; border-color: #dde4f7; color: #3b5bb3; }
.pc-act-edit:hover { background: #dde4f7; color: #2a4a9e; }

.pc-act-del { background: #fef2f2; border-color: #f5d0d0; color: #991b1b; }
.pc-act-del:hover { background: #fde8e8; color: #7f1d1d; }

/* ═══════ EMPTY ═══════ */
.pc-empty {
  text-align: center;
  padding: 2.5rem .85rem;
  color: var(--pc-text-muted);
}

.pc-empty i { font-size: 2rem; color: #ced8e6; display: block; margin-bottom: .5rem; }
.pc-empty span { font-size: .9rem; font-weight: 500; }

/* ═══════ MODAL ═══════ */
#brandModal .modal-content {
  border: none;
  border-radius: var(--pc-radius);
  box-shadow: var(--pc-shadow-xl);
}

#brandModal .modal-header {
  background: linear-gradient(135deg, #0b1a33 0%, #162d50 100%);
  color: #fff;
  border-radius: var(--pc-radius) var(--pc-radius) 0 0;
  border: none;
  padding: 1.1rem 1.5rem;
}

#brandModal .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .6; transition: opacity .2s; }
#brandModal .modal-header .btn-close:hover { opacity: 1; }
#brandModal .modal-header h5 { font-weight: 700; font-size: 1rem; }

#brandModal .modal-body { padding: 1.5rem; }

#brandModal .modal-footer {
  border-top: 1px solid var(--pc-border-lt);
  padding: 1rem 1.5rem;
}

.pc-lbl {
  font-size: .8rem;
  font-weight: 600;
  color: var(--pc-text-sec);
  margin-bottom: .35rem;
  display: flex;
  align-items: center;
  gap: .3rem;
}

.pc-lbl i { color: var(--pc-accent); font-size: .82rem; }

.pc-fld {
  border: 1.5px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
  padding: .52rem .85rem;
  font-size: .88rem;
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

.pc-btn-s {
  background: transparent;
  border: 1.5px solid var(--pc-border);
  border-radius: var(--pc-radius-sm);
  padding: .45rem 1.2rem;
  font-weight: 600;
  font-size: .83rem;
  color: var(--pc-text-sec);
  transition: all .2s ease;
  cursor: pointer;
}

.pc-btn-s:hover { border-color: #c8d0dd; color: var(--pc-text); }

.pc-btn-p {
  background: linear-gradient(135deg, var(--pc-accent) 0%, var(--pc-accent-drk) 100%);
  border: none;
  border-radius: var(--pc-radius-sm);
  padding: .45rem 1.5rem;
  font-weight: 600;
  font-size: .83rem;
  color: #fff;
  transition: all .3s ease;
  cursor: pointer;
}

.pc-btn-p:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(43,127,255,.25);
  color: #fff;
}

@media (max-width: 768px) {
  .pc-hdr { padding: 1.1rem 1.25rem; }
  .pc-hdr h2 { font-size: 1.1rem; }
  .pc-card-body { padding: 1rem; }
}
</style>

@section('content')
<div class="pc-page">
  <div class="container-fluid px-3 px-md-4 py-3">

    {{-- ═══ HEADER ═══ --}}
    <div class="pc-hdr">
      <div class="d-flex align-items-center gap-3">
        <h2><i class="bi bi-tags"></i>Brands</h2>
        <span class="hdr-badge d-none d-sm-inline">{{ count($Brand) }} Records</span>
      </div>
      <button type="button" class="pc-btn pc-btn-primary mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#brandModal" id="reset">
        <i class="bi bi-plus-circle"></i>Add Brand
      </button>
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
          <table id="brandTable" class="pc-tbl">
            <thead>
              <tr>
                <th style="width:80px;">ID</th>
                <th>Name</th>
                <th style="width:180px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($Brand as $company)
              <tr>
                <td class="pc-id id">{{ $company->id }}</td>
                <td class="pc-name name">{{ $company->name }}</td>
                <td>
                  <div class="pc-actions">
                    <button class="pc-act pc-act-edit edit-btn"
                      data-url="{{ route('store.Brand') }}">
                      <i class="bi bi-pencil"></i>Edit
                    </button>
                    <button class="pc-act pc-act-del delete-btn"
                      data-url="{{ route('delete.Brand', $company->id) }}"
                      data-msg="Are you sure you want to delete this brand?"
                      data-method="get"
                      onclick="logoutAndDeleteFunction(this)">
                      <i class="bi bi-trash3"></i>Delete
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="pc-empty">
                  <i class="bi bi-inbox"></i>
                  <span>No brands found</span>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ═══ MODAL ═══ --}}
    <div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="brandModalLabel"><i class="bi bi-tag me-1"></i> Brand</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form class="myform" action="{{ route('store.Brand') }}" method="POST">
            @csrf
            <input type="hidden" name="edit_id" id="id" />
            <div class="modal-body">
              <div class="mb-2">
                <label class="pc-lbl"><i class="bi bi-type-bold"></i>Title</label>
                <input type="text" name="name" class="pc-fld" id="name" placeholder="Enter brand name" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="pc-btn-s" data-bs-dismiss="modal">Cancel</button>
              <input type="submit" class="pc-btn-p save-btn" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).on('submit', '.myform', function(e) {
    e.preventDefault();
    var formdata = new FormData(this);
    url = $(this).attr('action');
    method = $(this).attr('method');
    $(this).find(':submit').attr('disabled', true);
    myAjax(url, formdata, method);
  });

  $(document).on('click', '.edit-btn', function() {
    var tr = $(this).closest("tr");
    var id = tr.find(".id").text();
    var name = tr.find(".name").text();
    $('#id').val(id);
    $('#name').val(name);
    $("#brandModal").modal("show");
  });

  $(document).ready(function() {
    $('#brandTable').DataTable({
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50, 100],
      order: [[0, 'desc']],
      language: {
        search: "Search Brand:",
        lengthMenu: "Show _MENU_ entries",
        emptyTable: "No brands found"
      }
    });
  });
</script>
@endsection
