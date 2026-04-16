<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Unit Management</h5>
            <small class="text-white-50">
                <?php $subjectId = esc($_GET['subject_id'] ?? '-') ?>
                <?php $division_id = esc($_GET['division_id'] ?? $_GET['divisi'] ?? '-') ?>
                Subject ID: <?= esc($subjectId ?? '-') ?> |
                Grade ID: <?= esc($gradeId ?? '-') ?>
            </small>
        </div>



        <a href="<?= base_url('unit/create?subject_id='.$subjectId) ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Unit
        </a>
    </div>

      <form method="get" class="mb-3">
    <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">

    <select name="grade_id" class="form-select w-auto d-inline">
        <option value="">All Grades</option>
        <?php foreach ($grades as $g): ?>
            <option value="<?= $g['id'] ?>"
                <?= ($gradeId == $g['id']) ? 'selected' : '' ?>>
                <?= esc($g['grade_name']) ?>
            </option>
        <?php endforeach ?>
    </select>

    <button type="submit" class="btn btn-primary ms-2">Filter</button>
</form>

    <?php if (empty($units)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No units found.</p>
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
                        <th>Grade</th>
                        <th>Subunit</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($units as $u): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($u['id']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($u['name']) ?>
                            </div>
                        </td>

                        <td class="text-dark-50 small">
                            <?= esc($u['subject_name']) ?>
                        </td>

                        <td class="text-dark-50 small">
                            <?= esc($u['grade_name']) ?>
                        </td>

                        <td class="text-dark-50 small">
                            <a href="<?= base_url('subunit?unit_id='.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('unit/edit/'.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('unit/delete/'.$u['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">
                               
                                <button type="submit"
                                        onclick="return confirm('Delete this unit?')"
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