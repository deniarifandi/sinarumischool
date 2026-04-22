<?= $this->extend('main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .address-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Number of lines to show */
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 250px;
        line-height: 1.4; /* Adjust based on your font size for better spacing */
        height: 2.8em;   /* line-height * line-clamp (1.4 * 2) */
    }
</style>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    

    /* Filter Styling */
    .filter-section {
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    /* Modern Table Styling */
    .custom-table { border-collapse: separate; border-spacing: 0 8px; }
    .custom-table thead th { border: none; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; padding: 0 1rem; }
    .custom-table tbody tr { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.2s; }
    .custom-table tbody tr:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .custom-table td { padding: 1rem; border: none; vertical-align: middle; }
    .custom-table td:first-child { border-radius: 10px 0 0 10px; }
    .custom-table td:last-child { border-radius: 0 10px 10px 0; }

    .avatar-circle {
        width: 35px; height: 35px; background: #e7f1ff; color: #0d6efd;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: 600; margin-right: 12px;
    }

    /* Custom Search Box */
    .search-container { position: relative; width: 250px; }
    .search-container i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #adb5bd; }
    .search-container input { padding-left: 35px !important; border-radius: 20px !important; }
</style>

<div class="glass-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0 text-muted">Attendance List</h4>
            <p class="text-muted small mb-0">Live presence tracking</p>
        </div>
        
        <div class="d-flex gap-2">
            <div class="search-container">
                <i class="bi bi-search"></i>
                <input type="text" id="customSearch" class="form-control form-control-sm border-0 shadow-sm" placeholder="Search by name...">
            </div>
            <button class="btn btn-light border btn-sm rounded-circle" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    <div class="filter-section p-3 mb-4">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Date</label>
                <input type="date" name="date" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Range Period</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="start" class="form-control">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-dash"></i></span>
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
                <button type="reset" id="resetBtn" class="btn btn-link btn-sm text-muted">Reset</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle custom-table" id="attendanceTable" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(function () {
    const table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true, // Internal searching enabled
        pageLength: 10,
        dom: 'rtip', // "f" is hidden because we use #customSearch
        order: [[0, 'desc']],
        ajax: {
            url: "<?= base_url('attendance/data') ?>",
            type: "POST",
            data: function (d) {
                d.date   = $('input[name=date]').val();
                d.start  = $('input[name=start]').val();
                d.end    = $('input[name=end]').val();
                d.status = $('select[name=status]').val();
            }
        },
        columns: [
            { data: 'presensidata_id', render: (data) => `<span class="text-muted small">#${data}</span>` },
            { 
                data: 'name',
                render: (data) => `
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle">${data.charAt(0)}</div>
                        <span class="fw-bold text-dark">${data}</span>
                    </div>`
            },
            {
                data: 'created_at',
                render: function (data) {
                    const d = new Date(data);
                    return `<div><span class="d-block text-dark fw-semibold">${d.toLocaleDateString('en-GB')}</span>
                            <small class="text-muted">${d.getHours()}:${String(d.getMinutes()).padStart(2, '0')}</small></div>`;
                }
            },
            {
                data: 'status',
                render: function (data) {
                    const map = { 1: ['Masuk', 'bg-success'], 2: ['Izin', 'bg-info'], 3: ['Sakit', 'bg-danger'] };
                    const s = map[data] || ['Unknown', 'bg-secondary'];
                    return `<span class="badge ${s[1]}">${s[0]}</span>`;
                }
            },
           { 
    data: 'address',
    render: function(data) {
        return `<div class="address-clamp text-muted small" title="${data || ''}">
                    ${data || '-'}
                </div>`;
    }
}
        ]
    });

    // Custom Search Implementation
    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Filter Handlers
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#resetBtn').on('click', function() {
        $('#filterForm')[0].reset();
        $('#customSearch').val('');
        table.search('').ajax.reload();
    });
});
</script>
<?= $this->endSection() ?>