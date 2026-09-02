<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
    $subjectName = $subject['subject_name'] ?? 'Subject';
    $createUrl   = base_url('journal/create?subject_id=' . (int)($subject['id'] ?? 0));
?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?= base_url('/') ?>" class="text-white-50 text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
            <h5 class="mb-0 mt-1">
                <i class="bi bi-bookmark-fill text-danger me-2"></i>
                Teaching Journal — <?= esc($subjectName) ?>
            </h5>
            <small class="text-white-50">
                Catatan mengajar harian untuk mata pelajaran ini
            </small>
        </div>

        <a href="<?= $createUrl ?>" class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Journal
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form method="get" class="mb-3 d-flex flex-wrap gap-2 align-items-end">
        <input type="hidden" name="subject_id" value="<?= (int)($subject['id'] ?? 0) ?>">

        <?php if ($isAdmin && !empty($teachers)): ?>
            <div>
                <label class="form-label small mb-1">Teacher</label>
                <select name="teacher_id" class="form-select form-select-sm">
                    <option value="">All teachers</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            <?= (string)($filters['teacher_id'] ?? '') === (string)$t['id'] ? 'selected' : '' ?>>
                            <?= esc($t['name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        <?php endif; ?>

        <div>
            <label class="form-label small mb-1">Class</label>
            <select name="class_id" class="form-select form-select-sm">
                <option value="">All classes</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= (string)($filters['class_id'] ?? '') === (string)$c['id'] ? 'selected' : '' ?>>
                        <?= esc($c['grade_name'] ?? '') ?> - <?= esc($c['class_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label class="form-label small mb-1">From</label>
            <input type="date" name="date_from" class="form-control form-control-sm"
                   value="<?= esc($filters['date_from'] ?? '') ?>">
        </div>

        <div>
            <label class="form-label small mb-1">To</label>
            <input type="date" name="date_to" class="form-control form-control-sm"
                   value="<?= esc($filters['date_to'] ?? '') ?>">
        </div>

        <div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="<?= base_url('journal?subject_id=' . (int)($subject['id'] ?? 0)) ?>"
               class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>

    <?php if (empty($journals)): ?>
        <div class="text-center py-5">
            <i class="bi bi-journal-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">Belum ada teaching journal untuk subject ini.</p>
            <a href="<?= $createUrl ?>" class="btn btn-primary btn-sm rounded-pill px-3 mt-2">
                <i class="bi bi-plus-lg me-1"></i> Buat journal pertama
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive" style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">
            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Date</th>
                        <th>Teacher</th>
                        <th>Class</th>
                        <th>JP</th>
                        <th>Unit / Sub-Unit</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($journals as $j): ?>
                    <tr>
                        <td class="ps-3 small">
                            <?= esc(date('d M Y', strtotime($j['date']))) ?>
                        </td>
                        <td class="small"><?= esc($j['teacher_name'] ?? '-') ?></td>
                        <td class="small"><?= esc($j['class_name'] ?? '-') ?></td>
                        <td class="small"><?= esc($j['periods'] ?? '-') ?></td>
                        <td class="small">
                            <?php
                                $parts = [];
                                if (!empty($j['unit_name']))    $parts[] = esc($j['unit_name']);
                                if (!empty($j['subunit_name'])) $parts[] = '<span class="text-muted">›</span> ' . esc($j['subunit_name']);
                                echo $parts ? implode('<br>', $parts) : '<span class="text-muted">-</span>';
                            ?>
                        </td>
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
                            <a href="<?= base_url('journal/edit/'.$j['id']) ?>"
                               class="btn btn-sm btn-glass-edit"
                               title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="<?= base_url('journal/delete/'.$j['id']) ?>"
                                  method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        onclick="return confirm('Delete this journal?')"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)">
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
