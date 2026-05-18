<?= $this->extend('main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    .address-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Number of lines to show */
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 220px;
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
    .custom-table { border-collapse: separate; border-spacing: 0 8px; width: 100%; }
    .custom-table thead th { border: none; color: #6c757d; font-size: 0.75rem; text-transform: uppercase; padding: 0 1rem; }
    .custom-table tbody tr { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.2s; }
    .custom-table tbody tr:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .custom-table td { padding: 1rem; border: none; vertical-align: middle; }
    .custom-table td:first-child { border-radius: 10px 0 0 10px; }
    .custom-table td:last-child { border-radius: 0 10px 10px 0; }

    .avatar-circle {
        width: 32px; height: 32px; background: #e7f1ff; color: #0d6efd;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; font-weight: 600; font-size: 0.85rem;
    }

    /* Custom Search Box */
    .search-container { position: relative; width: 250px; }
    .search-container i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #adb5bd; }
    .search-container input { padding-left: 35px !important; border-radius: 20px !important; }
    
    /* Tweaks to clean up DataTables default styling override */
    .dataTables_wrapper .dataTables_filter { display: none; } /* Using your custom search bar */
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
                <input type="text" id="customSearch" class="form-control form-control-sm border-0 shadow-sm" placeholder="Search by name...">
            </div>
            <button class="btn btn-light border btn-sm rounded-circle" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>

    <div class="filter-section p-3 mb-4">
        <form id="filterForm" method="GET" class="row g-3 align-items-end">
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
                <button type="reset" id="resetBtn" class="btn btn-link btn-sm text-muted text-decoration-none">Reset</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <!-- Changed class from table table-bordered to custom-table -->
        <table id="usersTable" class="custom-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Coordinates</th>
                    <th>Address</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $row): ?>
                    <tr>
                        <td class="text-muted small">#<?= esc($row['presensidata_id']) ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle">
                                    <?= strtoupper(substr(esc($row['name']), 0, 1)) ?>
                                </div>
                                <span class="fw-semibold text-dark"><?= esc($row['name']) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark"><i class="bi bi-calendar3 me-1 text-muted"></i> <?= esc($row['presensidata_tanggal']) ?></div>
                        </td>
                        <td>
                            <?php if ($row['status'] == 1): ?>
                                <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Masuk</span>
                            <?php elseif ($row['status'] == 2): ?>
                                <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5 rounded-pill"><i class="bi bi-envelope-fill me-1"></i> Izin</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i> Sakit</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-geo-alt text-secondary"></i> <?= esc($row['latitude']) ?>, <?= esc($row['longitude']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="address-clamp small text-secondary" title="<?= esc($row['address']) ?>">
                                <?= esc($row['address']) ?>
                            </div>
                        </td>
                        <td>
                            <span class="small text-muted"><?= esc($row['created_at']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(function () {
    var table = $('#usersTable').DataTable({
        pageLength: 10,
        dom: 'rtip', // Hides default search box structure
        language: {
            paginate: {
                previous: '<i class="bi bi-chevron-left"></i>',
                next: '<i class="bi bi-chevron-right"></i>'
            }
        }
    });

    // Connects your custom search input to the Datatable logic
    $('#customSearch').on('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>
<?= $this->endSection() ?>