<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">User Subject Management</h5>
            <small class="text-white-50">
                User ID: <?= esc($userId ?? '-') ?>
            </small>
        </div>
    </div>

    <?php if (empty($userSubjects)): ?>
        <div class="text-center py-5">
            <i class="bi bi-link-45deg display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No subject assigned.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0">
               <thead>
                    <tr>
                        <th class="ps-3">Subject</th>
                        <th>Teachers</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($userSubjects as $us): ?>
    <tr>
        <td class="ps-3">
            <span class="badge bg-primary bg-opacity-25 text-primary">
                <?= esc($us['subject_name']) ?>
            </span>
        </td>

        <td class="text-dark-50 small">
            <?php foreach ($us['teacher'] as $teacher): ?>
                <div><?= esc($teacher) ?></div>
                <?php endforeach; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
                </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>