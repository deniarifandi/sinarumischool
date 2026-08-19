<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<h5 class="text-light mb-1">Gradebook - <?= esc($term['name']) ?></h5>
<p class="text-white-50 small">Term status: <?= $term['is_locked'] ? 'Locked' : 'Open' ?></p>

<table class="table table-sm table-bordered">
<thead>
<tr>
    <th>Academic Year</th><th>Kelas</th><th>Mapel</th><th>Status</th><th>Override</th><th>Aksi</th></tr>
</thead>
<tbody>
<?php foreach ($gradebooks as $gb): ?>
<tr>
    <td><?= esc($gb['academic_year_name']) ?></td>
    <td><?= esc($gb['class_name']) ?></td>
    <td><?= esc($gb['subject_name']) ?></td>
    <td>
        <?= $gb['is_locked'] ? '<span class="badge bg-danger">Locked</span>' : '<span class="badge bg-success">Open</span>' ?>
    </td>
    <td>
        <?= $gb['lock_override'] ? '<span class="badge bg-warning text-dark">Manual override</span>' : '<span class="text-muted small">Ikut term</span>' ?>
    </td>
    <td>
        <form method="post" action="<?= base_url('lock/gradebooks/' . $gb['id'] . '/override') ?>" class="d-inline">
            <?= csrf_field() ?>
            <?php if ($gb['is_locked']): ?>
                <button name="action" value="unlock" class="btn btn-sm btn-success">Unlock (override)</button>
            <?php else: ?>
                <button name="action" value="lock" class="btn btn-sm btn-danger">Lock (override)</button>
            <?php endif; ?>
            <?php if ($gb['lock_override']): ?>
                <button name="action" value="reset" class="btn btn-sm btn-outline-secondary">Reset ke status term</button>
            <?php endif; ?>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?= $this->endSection() ?>