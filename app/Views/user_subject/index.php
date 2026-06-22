<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php $subjectCount = count($userSubjects ?? []); ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

    <!-- Header -->
    <div class="card-header bg-dark text-white px-4 py-3 border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="bi bi-journals fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold">Subject Management</h6>
                    <span class="text-white-50 small">
                        <i class="bi bi-person-badge me-1"></i>User ID: <span class="font-monospace text-white-75"><?= esc($userId ?? 'N/A') ?></span>
                    </span>
                </div>
            </div>
            <?php if ($subjectCount > 0): ?>
                <span class="badge bg-white text-dark fw-semibold rounded-pill px-3 py-2">
                    <?= $subjectCount ?> <?= $subjectCount === 1 ? 'Subject' : 'Subjects' ?>
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-0">

        <?php if ($subjectCount === 0): ?>

            <!-- Empty state -->
            <div class="text-center py-5 px-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 68px; height: 68px;">
                    <i class="bi bi-journal-x fs-3 text-secondary"></i>
                </div>
                <p class="fw-semibold text-dark mb-1">No Subjects Assigned</p>
                <p class="text-muted small mb-0">Assign a subject to this user to get started.</p>
            </div>

        <?php else: ?>

            <!-- Subject table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase text-secondary fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                            <th class="ps-4 py-3 border-0" style="width: 3rem;">#</th>
                            <th class="py-3 border-0" style="width: 35%;">Subject</th>
                            <th class="py-3 border-0">Assigned Teachers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userSubjects as $i => $us): ?>
                            <tr>
                                <td class="ps-4 py-3 text-muted small"><?= $i + 1 ?></td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-journal-text text-primary"></i>
                                        <span class="fw-medium text-dark"><?= esc($us['subject_name']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <?php if (!empty($us['teacher'])): ?>
                                        <div class="d-flex flex-wrap gap-1">
                                            <?php foreach ($us['teacher'] as $teacher): ?>
                                                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-normal px-2 py-1">
                                                    <i class="bi bi-person me-1"></i><?= esc($teacher) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic small">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer summary -->
            <div class="px-4 py-2 bg-light border-top text-muted small">
                Showing <?= $subjectCount ?> <?= $subjectCount === 1 ? 'subject' : 'subjects' ?>
            </div>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>