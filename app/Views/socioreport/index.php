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
    $currentYear = date('Y');
    ?>

    <form action="<?= base_url('socioreport/import') ?>"
          method="post"
          enctype="multipart/form-data"
          class="mb-4">
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
                    <?php for ($y=$currentYear-3; $y<=$currentYear+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y==$currentYear?'selected':'' ?>>
                            <?php echo ($y-1)." - ".$y ?>
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

    <!-- GROUPED DATA -->
    <h6 class="mb-2">Uploaded Reports</h6>

    <?php if (empty($periods)): ?>
        <div class="text-muted">No data available.</div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,.1)">
            <table class="table glass-table mb-0 align-middle">
                <thead>
                <tr>
                    <th class="ps-3">Class</th>
                    <th>Term</th>
                    <th>Total Students</th>
                    <th class="text-end pe-3">Action</th>
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
