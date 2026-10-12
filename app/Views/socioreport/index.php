<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
.glass-table thead th{
    background:rgb(243,244,246);
    color:#3b82f6;
    font-size:.7rem;
    text-transform:uppercase;
    white-space:nowrap;
}
.glass-table tbody td{
    background:#fff;
    font-size:.75rem;
    white-space:nowrap;
}
</style>

<div class="glass-card">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">Socio Emotional Report</h5>
            <small class="text-white-50">Per Class · Monthly</small>
        </div>
    </div>

    <!-- IMPORT FORM -->
    <?php
    $months = [
        1=>'1',2=>'2',3=>'3',4=>'4'
    ];
    $currentYear = (int) date('Y');
    // Tahun ajaran dimulai pada bulan Juli: Oktober 2026 berada di AY 2026/2027.
    $academicYearStart = ((int) date('n') >= 7) ? $currentYear : $currentYear - 1;
    ?>

    <div class="border rounded-3 p-3 mb-4 bg-light text-dark">
        <div class="fw-semibold text-secondary mb-1">Upload New Report</div>
        <div class="small text-muted mb-3">Gunakan formulir ini untuk mengunggah data laporan siswa dalam format CSV.</div>

        <form action="<?= base_url('socioreport/import') ?>"
              method="post"
              enctype="multipart/form-data">
            <?= csrf_field() ?>

        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Class</label>
                <select name="class_id" class="form-select form-select-sm" required>
                    <option value="">Select class</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>">
                            <?= esc($c['class_name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small">TERM</label>
                <select name="month" class="form-select form-select-sm" required>
                    <option value="">TERM</option>
                    <?php foreach ($months as $k=>$m): ?>
                        <option value="<?= $k ?>"><?= $m ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small">Year</label>
                <select name="year" class="form-select form-select-sm" required>
                    <?php for ($y=$academicYearStart-3; $y<=$academicYearStart+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y==$academicYearStart?'selected':'' ?>>
                            <?php echo $y." - ".($y + 1) ?>
                        </option>
                    <?php endfor ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">CSV File</label>
                <input type="file"
                       name="csv_file"
                       accept=".csv"
                       class="form-control form-control-sm"
                       required>
            </div>

            <div class="col-auto">
                <button class="btn btn-primary btn-sm rounded-pill px-4">
                    <i class="bi bi-upload me-1"></i> Import
                </button>
            </div>
        </div>
        </form>
    </div>

    <!-- GROUPED DATA -->
    <h6 class="mb-2">Uploaded Reports</h6>

    <?php if (empty($periods)): ?>
        <div class="text-muted">No data available.</div>
    <?php else: ?>
        <form id="report-filter-form" method="get" action="<?= base_url('socioreport') ?>">
            <input type="hidden" name="divisi" value="<?= esc($_GET['divisi'] ?? '') ?>">
        </form>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,.1)">
            <table class="table glass-table mb-0 align-middle">
                <thead>
                <tr>
                    <th class="ps-3">Class</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th>Total Students</th>
                    <th class="text-end pe-3">Action</th>
                </tr>
                <tr>
                    <th class="ps-3">
                        <select form="report-filter-form" name="filter_class" class="form-select form-select-sm">
                            <option value="0">All Classes</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($filterClass ?? 0) == $c['id'] ? 'selected' : '' ?>>
                                    <?= esc($c['class_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </th>
                    <th>
                        <select form="report-filter-form" name="filter_month" class="form-select form-select-sm">
                            <option value="0">All Terms</option>
                            <?php foreach ($months as $k => $m): ?>
                                <option value="<?= $k ?>" <?= ($filterMonth ?? 0) == $k ? 'selected' : '' ?>><?= $m ?></option>
                            <?php endforeach ?>
                        </select>
                    </th>
                    <th>
                        <select form="report-filter-form" name="filter_year" class="form-select form-select-sm">
                            <option value="0">All Years</option>
                            <?php foreach ($periods as $p): $periodYear = (int) date('Y', strtotime($p['period'])); ?>
                                <option value="<?= $periodYear ?>" <?= ($filterYear ?? 0) == $periodYear ? 'selected' : '' ?>>
                                    <?= $periodYear . ' - ' . ($periodYear + 1) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </th>
                    <th></th>
                    <th class="text-end pe-3">
                        <button form="report-filter-form" class="btn btn-primary btn-sm">Filter</button>
                        <a href="<?= base_url('socioreport?divisi=' . urlencode($_GET['divisi'] ?? '')) ?>" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($periods as $p): ?>
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <?= esc($p['class_name']) ?>
                        </td>
                        <td>
                            <?= date('m', strtotime($p['period'])) ?>
                        </td>
                        <td>
                            <?php $academicYear = (int) date('Y', strtotime($p['period'])); ?>
                            <?= $academicYear . ' - ' . ($academicYear + 1) ?>
                        </td>
                        <td><?= $p['total'] ?></td>
                        <td class="text-end pe-3">
                            <a href="<?= base_url('socioreport/print/'.$p['class_id'].'/'.$p['period']) ?>"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer"></i>
                            </a>

                            <form action="<?= base_url('socioreport/delete/'.$p['class_id'].'/'.$p['period']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        onclick="return confirm('Delete this report?')"
                                        class="btn btn-sm btn-outline-danger ms-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
