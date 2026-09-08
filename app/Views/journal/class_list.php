<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    .report-header {
        background: linear-gradient(
            135deg,
            rgba(108, 99, 255, 0.12),
            rgba(108, 99, 255, 0.02)
        );
        border: 1px solid rgba(108, 99, 255, 0.25);
        border-radius: 16px;
        padding: 20px 24px;
    }

    .filter-card {
        background: rgba(255,255,255,.025);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 16px;
    }

    .custom-select-dark {
        background-color: rgba(0,0,0,.25);
        border: 1px solid rgba(255,255,255,.1);
        color: #e2e8f0;
        border-radius: 10px;
    }

    .custom-select-dark:focus {
        background-color: rgba(0,0,0,.4);
        border-color: #6c63ff;
        color: #fff;
        box-shadow: 0 0 0 .2rem rgba(108,99,255,.2);
    }

    .custom-select-dark option {
        background: #1e293b;
        color: #fff;
    }

    .journal-row td {
        vertical-align: middle;
    }

    .empty-state {
        border: 1.5px dashed rgba(255,255,255,.12);
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        color: rgba(255,255,255,.45);
    }

    .table.glass-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table.glass-table thead th {
        background: rgba(255,255,255,.04);
        color: rgba(255,255,255,.75);
        font-weight: 600;
        font-size: .85rem;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .table.glass-table tbody tr {
        background: rgba(255,255,255,.02);
    }
    .table.glass-table tbody tr:hover {
        background: rgba(108,99,255,.06);
    }
    .table.glass-table td {
        border-top: 1px solid rgba(255,255,255,.06);
        /*color: #e2e8f0;*/
    }
    .btn-glass-edit {
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        color: #e2e8f0;
    }
    .btn-glass-edit:hover {
        background: rgba(108,99,255,.18);
        border-color: rgba(108,99,255,.4);
        color: #fff;
    }
</style>

<div class="container-fluid px-0 py-3">

    <!-- HEADER -->
    <div class="report-header mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-journal-text text-primary fs-4 me-2"></i>
                    <h5 class="fw-bold text-light mb-0">
                        Class Journals
                    </h5>
                </div>
                <div class="text-white-50 small">
                    Read-only view of every teaching journal created for this class.
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <?php
                                    $todayStr = date('Y-m-d');
                                    $todayUrl = base_url('journal/class/' . (int)$class['id'] . '/print')
                                        . '?date_from=' . $todayStr . '&date_to=' . $todayStr;
                                ?>
                                <a href="<?= $todayUrl ?>"
                                   class="btn btn-sm btn-primary rounded-pill px-3"
                                   target="_blank"
                                   title="Print combined recap for today">
                                    <i class="bi bi-printer me-1"></i> Print Recap (Today)
                                </a>
                <?php
                    $qs = [];
                    foreach (['subject_id','teacher_id','date_from','date_to'] as $k) {
                        if (!empty($filters[$k])) $qs[$k] = $filters[$k];
                    }
                    $recapFilteredUrl = base_url('journal/class/' . (int)$class['id'] . '/print')
                        . ($qs ? '?' . http_build_query($qs) : '');
                    $hasActiveFilter = !empty($qs);
                ?>
                <a href="<?= $recapFilteredUrl ?>"
                   class="btn btn-sm <?= $hasActiveFilter ? 'btn-primary' : 'btn-outline-light' ?> rounded-pill px-3"
                   target="_blank"
                   title="Print recap using current filters">
                    <i class="bi bi-printer-fill me-1"></i> Print Recap (Filtered)
                </a>
                <a href="<?= base_url() ?>"
                   class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary border border-primary px-3 py-2">
                <i class="bi bi-door-open me-1"></i>
                <?= esc($class['class_name'] ?? '-') ?>
            </span>
            <span class="badge rounded-pill bg-secondary bg-opacity-25 text-light border border-secondary px-3 py-2">
                <i class="bi bi-mortarboard-fill me-1"></i>
                <?= esc($class['grade_name'] ?? '-') ?>
            </span>
            <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success px-3 py-2">
                <i class="bi bi-journal-bookmark-fill me-1"></i>
                <?= count($journals) ?> Journals
            </span>
        </div>
    </div>

    <!-- FILTER -->
    <div class="filter-card p-4 mb-4">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-funnel-fill text-primary me-2"></i>
            <h6 class="text-light fw-bold mb-0">Filter</h6>
        </div>

        <form method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-white-50 small">Subject</label>
                <select name="subject_id" class="form-select custom-select-dark form-select-sm">
                    <option value="">-- All Subjects --</option>
                    <?php foreach (($subjects ?? []) as $s): ?>
                        <option value="<?= esc($s['id']) ?>"
                            <?= (string)($filters['subject_id'] ?? '') === (string)$s['id'] ? 'selected' : '' ?>>
                            <?= esc($s['subject_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label text-white-50 small">Teacher</label>
                <select name="teacher_id" class="form-select custom-select-dark form-select-sm">
                    <option value="">-- All Teachers --</option>
                    <?php foreach (($teachers ?? []) as $t): ?>
                        <option value="<?= esc($t['id']) ?>"
                            <?= (string)($filters['teacher_id'] ?? '') === (string)$t['id'] ? 'selected' : '' ?>>
                            <?= esc($t['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label text-white-50 small">From</label>
                <input type="date" name="date_from"
                       value="<?= esc($filters['date_from'] ?? '') ?>"
                       class="form-control custom-select-dark form-control-sm">
            </div>

            <div class="col-md-2">
                <label class="form-label text-white-50 small">To</label>
                <input type="date" name="date_to"
                       value="<?= esc($filters['date_to'] ?? '') ?>"
                       class="form-control custom-select-dark form-control-sm">
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                    <i class="bi bi-search me-1"></i> Apply
                </button>
                <a href="<?= base_url('journal/class/' . (int)$class['id']) ?>"
                   class="btn btn-sm btn-outline-light rounded-pill px-3">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- LIST -->
    <?php if (!empty($journals)): ?>
        <div class="table-responsive" style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">
            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Unit / Sub-Unit</th>
                        <th class="text-center">JP</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($journals as $j): ?>
                    <tr class="journal-row">
                        <td class="ps-3 small">
                            <?= esc(date('d M Y', strtotime($j['date']))) ?>
                        </td>
                        <td class="small"><?= esc($j['subject_name'] ?? '-') ?></td>
                        <td class="small"><?= esc($j['teacher_name'] ?? '-') ?></td>
                        <td class="small">
                            <?php
                                $parts = [];
                                if (!empty($j['unit_name']))    $parts[] = esc($j['unit_name']);
                                if (!empty($j['subunit_name'])) $parts[] = '<span class="text-muted">›</span> ' . esc($j['subunit_name']);
                                echo $parts ? implode('<br>', $parts) : '<span class="text-muted">-</span>';
                            ?>
                        </td>
                        <td class="small text-center"><?= esc($j['periods'] ?? '-') ?></td>
                        <td class="text-end pe-3">
                            <a href="<?= base_url('journal/show/'.$j['id']) ?>"
                               class="btn btn-sm btn-glass-edit"
                               title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?= base_url('journal/print/'.$j['id']) ?>"
                               class="btn btn-sm btn-glass-edit"
                               target="_blank" title="Print">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
            <div class="fw-semibold text-white-50">
                No journals found
            </div>
            <div class="small mt-1">
                Belum ada teaching journal untuk kelas ini.
                <?php if (!empty($filters['subject_id']) || !empty($filters['teacher_id']) || !empty($filters['date_from']) || !empty($filters['date_to'])): ?>
                    Coba reset filter untuk melihat semua journal.
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>