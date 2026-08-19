<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<h5 class="text-light mb-3">Kelola Kunci Nilai per Term</h5>

<table class="table table-sm table-bordered">
<thead>
<tr>
    <th>Division</th><th>Tahun Ajaran</th><th>Semester</th><th>Term</th><th>Status</th><th>Aksi</th>
</tr>
</thead>
<tbody>
<?php foreach ($terms as $t): ?>
<tr>
    <td><?= esc($t['division_name']) ?></td>
    <td><?= esc($t['academic_year_name']) ?></td>
    <td><?= esc($t['semester_name']) ?></td>
    <td><?= esc($t['name']) ?></td>
    <td>
        <?php if ($t['is_locked']): ?>
            <span class="badge bg-danger"><i class="bi bi-lock-fill"></i> Locked</span>
        <?php else: ?>
            <span class="badge bg-success"><i class="bi bi-unlock-fill"></i> Open</span>
        <?php endif; ?>
    </td>
    <td>
        <a href="<?= base_url('lock/terms/' . $t['id']) ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
        <?php if ($t['is_locked']): ?>
            <form method="post" action="<?= base_url('lock/terms/' . $t['id'] . '/unlock') ?>" class="d-inline">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-success">Unlock</button>
            </form>
        <?php else: ?>
            <form method="post" action="<?= base_url('lock/terms/' . $t['id'] . '/lock') ?>" class="d-inline"
                  onsubmit="return confirm('Kunci semua gradebook di term ini?')">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-danger">Lock</button>
            </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?= $this->endSection() ?>