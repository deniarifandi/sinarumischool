<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php $j = $journal; ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Teaching Journal</h5>
            <small class="text-white-50">
                <?= esc(date('l, d F Y', strtotime($j['date']))) ?>
            </small>
        </div>
        <div>
            <a href="<?= base_url('journal') ?>" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <a href="<?= base_url('journal/print/'.$j['id']) ?>" target="_blank"
               class="btn btn-outline-primary btn-sm me-2">
                <i class="bi bi-printer"></i> Print
            </a>
            <a href="<?= base_url('journal/edit/'.$j['id']) ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
        </div>
    </div>

    <table class="table glass-table">
        <tbody>
            <tr>
                <th width="20%">Date</th>
                <td><?= esc(date('d M Y', strtotime($j['date']))) ?></td>
            </tr>
            <tr>
                <th>Teacher</th>
                <td><?= esc($j['teacher_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Subject</th>
                <td><?= esc($j['subject_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Class</th>
                <td>
                    <?= esc(($j['grade_name'] ?? '') . ' - ' . ($j['class_name'] ?? '-')) ?>
                </td>
            </tr>
            <?php if (!empty($j['unit_name'])): ?>
            <tr>
                <th>Unit</th>
                <td><?= esc($j['unit_name']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($j['subunit_name'])): ?>
            <tr>
                <th>Sub-Unit</th>
                <td><?= esc($j['subunit_name']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($j['periods'])): ?>
            <tr>
                <th>JP (Jam Pelajaran)</th>
                <td><?= esc($j['periods']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($j['activities'])): ?>
            <tr>
                <th>Activities</th>
                <td style="white-space:pre-line"><?= esc($j['activities']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($j['notes'])): ?>
            <tr>
                <th>Notes / Refleksi</th>
                <td style="white-space:pre-line"><?= esc($j['notes']) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>Created</th>
                <td class="small text-white-50">
                    <?= esc($j['created_at'] ?? '-') ?>
                    <?php if (!empty($j['updated_at']) && $j['updated_at'] !== $j['created_at']): ?>
                        <br>Updated: <?= esc($j['updated_at']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
