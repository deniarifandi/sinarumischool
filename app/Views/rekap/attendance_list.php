<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .address-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 220px;
        line-height: 1.4;
        height: 2.8em;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .filter-section {
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    .custom-table {
        border-collapse: separate;
        border-spacing: 0 8px;
        width: 100%;
    }

    .custom-table thead th {
        border: none;
        color: #6c757d;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 0 1rem;
    }

    .custom-table tbody tr {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: 0.2s;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .custom-table td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }

    .custom-table td:first-child {
        border-radius: 10px 0 0 10px;
    }

    .custom-table td:last-child {
        border-radius: 0 10px 10px 0;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        background: #e7f1ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .search-container {
        position: relative;
        width: 250px;
    }

    .search-container i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .search-container input {
        padding-left: 35px !important;
        border-radius: 20px !important;
    }

    .dataTables_wrapper .dataTables_filter {
        display: none;
    }
</style>

<div class="glass-card">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>
            <h4 class="fw-bold mb-0 text-dark">Attendance List</h4>
            <p class="text-muted small mb-0">Live presence tracking</p>
        </div>

        <div class="d-flex gap-2">
            <div class="search-container">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    id="customSearch"
                    class="form-control form-control-sm border-0 shadow-sm"
                    placeholder="Search by name..."
                >
            </div>

            <button
                class="btn btn-light border btn-sm rounded-circle"
                onclick="table.ajax.reload()"
            >
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>

    </div>

    <div class="filter-section p-3 mb-4">

        <form id="filterForm" class="row g-3 align-items-end">

            <div class="col-md-2" style="display:none">
                <label class="form-label small fw-bold text-muted">Date</label>
                <input type="date" name="date" class="form-control form-control-sm">
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Range Period</label>

                <div class="input-group input-group-sm">
                    <input type="date" name="start" class="form-control">

                    <span class="input-group-text bg-white border-0">
                        <i class="bi bi-dash"></i>
                    </span>

                    <input type="date" name="end" class="form-control">
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Status</label>

                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1">Masuk</option>
                    <option value="2">Izin</option>
                    <option value="3">Sakit</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>

            <div class="col-md-2 text-center">
                <button
                    type="button"
                    id="resetBtn"
                    class="btn btn-link btn-sm text-muted text-decoration-none"
                >
                    Reset
                </button>
            </div>

            <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
            <i class="bi bi-plus"></i> Add Attendance
        </button>

        </form>

    </div>

    <div class="table-responsive">

        <table id="usersTable" class="custom-table display">

            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Coordinates</th>
                    <th>Address</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody></tbody>

        </table>

    </div>

</div>

<div class="modal fade" id="addAttendanceModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="<?= base_url('attendance/store') ?>">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Add Attendance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">User</label>
            <select name="guru_id" class="form-select" required>
              <option value="">Select user</option>
              <?php foreach ($allUsers as $u): ?>
                <option value="<?= $u['id'] ?>">
                  <?= esc($u['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="1">Masuk</option>
              <option value="2">Izin</option>
              <option value="3">Sakit</option>
            </select>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>

      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
let table;

$(function () {

    table = $('#usersTable').DataTable({

        processing: true,
        serverSide: true,
        pageLength: 10,

        ajax: {
            url: "<?= base_url('attendance/data') ?>",
            type: "GET",

            data: function (d) {

                d.date = $('input[name="date"]').val();
                d.startDate = $('input[name="start"]').val();
                d.endDate = $('input[name="end"]').val();
                d.status = $('select[name="status"]').val();
            }
        },

        order: [[0, 'desc']],

        columns: [

            {
                data: 'presensidata_id',

                render: function(data) {
                    return `
                        <span class="text-muted small">
                            #${data}
                        </span>
                    `;
                }
            },

            {
                data: 'name',

                render: function(data) {

                    let initial = '-';

                    if (data) {
                        initial = data.charAt(0).toUpperCase();
                    }

                    return `
                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-circle">
                                ${initial}
                            </div>

                            <span class="fw-semibold text-dark">
                                ${data ?? '-'}
                            </span>

                        </div>
                    `;
                }
            },

            {
                data: 'presensidata_tanggal',

                render: function(data) {
                    return `
                        <div class="small text-dark">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            ${data}
                        </div>
                    `;
                }
            },

            {
                data: 'status',

                render: function(data) {

                    if (data == 1) {
                        return `
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Masuk
                            </span>
                        `;
                    }

                    if (data == 2) {
                        return `
                            <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">
                                <i class="bi bi-envelope-fill me-1"></i>
                                Izin
                            </span>
                        `;
                    }

                    return `
                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            Sakit
                        </span>
                    `;
                }
            },

            {
                data: null,

                render: function(data) {

                    return `
                        <span class="text-muted small">
                            <i class="bi bi-geo-alt text-secondary"></i>
                            ${data.latitude}, ${data.longitude}
                        </span>
                    `;
                }
            },

            {
                data: 'address',

                render: function(data) {

                    return `
                        <div class="address-clamp small text-secondary" title="${data}">
                            ${data ?? '-'}
                        </div>
                    `;
                }
            },

            {
                data: 'created_at',

                render: function(data) {

                    return `
                        <span class="small text-muted">
                            ${data}
                        </span>
                    `;
                }
            }

        ],

        language: {
            processing: "Loading...",
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });

    // custom search
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

    // filter submit
    $('#filterForm').on('submit', function(e){

        e.preventDefault();

        table.draw();
    });

    // reset filters
    $('#resetBtn').on('click', function(){

        $('#filterForm')[0].reset();

        table.search('').draw();
    });

});
</script>

<?= $this->endSection() ?>