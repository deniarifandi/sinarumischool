<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Objective Management</h5>
            <small class="text-white-50">
                
                <?php $outcome_id = esc($_GET['outcome_id'] ?? '-') ?>

                <?php $division_id = esc($_GET['division_id'] ?? $_GET['divisi'] ?? '-') ?>

                Outcame ID: <?= esc($outcome_id ?? '-') ?> |
                Grade ID: <?= esc($gradeId ?? '-') ?>
            </small>
        </div>

        <a href="<?= base_url('objective/create?outcome_id='.$outcome_id) ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add objective
        </a>
    </div>

    <?php if (empty($objective)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No objectives found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Objective</th>
                        <th>Outcome</th>
                      
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($objective as $u): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($u['id']) ?>
                            </span>
                        </td>

                         <td class="text-dark-50 small">
                            <?= esc($u['objective_name']) ?>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($u['outcome_name']) ?>
                            </div>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('objective/edit/'.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('objective/delete/'.$u['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="outcome_id" value="<?= esc($outcome_id) ?>">
                               
                                <button type="submit"
                                        onclick="return confirm('Delete this objective?')"
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