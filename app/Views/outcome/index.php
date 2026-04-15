<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Outcome Management</h5>
            <small class="text-white-50">
                <?php $subject_id = esc($_GET['subject_id'] ?? '-') ?>
                <?php $division_id = esc($_GET['division_id'] ?? $_GET['divisi'] ?? '-') ?>
                Subject ID: <?= esc($subject_id ?? '-') ?> |
                Grade ID: <?= esc($gradeId ?? '-') ?>
            </small>
        </div>

        <a href="<?= base_url('outcome/create?subject_id='.$subject_id) ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add outcome
        </a>
    </div>

    <?php if (empty($outcome)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No outcomes found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Objective</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($outcome as $u): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($u['id']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($u['outcome_name']) ?>
                            </div>
                        </td>

                        <td class="text-dark-50 small">
                            <?= esc($u['subject_name']) ?>
                        </td>

                         <td class="text-dark-50 small">
                            <a href="<?= base_url('objective?outcome_id='.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('outcome/edit/'.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('outcome/delete/'.$u['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="subject_id" value="<?= esc($subject_id) ?>">
                               
                                <button type="submit"
                                        onclick="return confirm('Delete this outcome?')"
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