<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
.glass-table {
    color: #fff;
    border-color: rgba(255, 255, 255, 0.1);
}
.glass-table thead th {
    background: rgb(243, 244, 246);
    color: #3b82f6;
    border-bottom: 2px solid rgba(59, 130, 246, 0.3);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
}
.glass-table tbody tr td {
    background: rgba(255, 255, 255, 1);
}
.btn-glass-edit {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}
.btn-glass-edit:hover {
    background: #3b82f6;
    color: #fff;
}
</style>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Class Management</h5>
            <small class="text-white-50">Division ID: <?= esc($divisiId) ?></small>
        </div>

        <a href="<?= base_url('class/create?divisi='.$divisiId) ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Class
        </a>
    </div>

    <?php if (empty($classes)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No classes found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0" id="classTable">
                <thead>
                    <tr>
                        <th class="ps-3">Grade</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th class="text-end pe-3">Actions</th>
                        <th class="text-end pe-3">Manage</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($classes as $c): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($c['grade_name']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($c['class_name']) ?>
                            </div>
                        </td>

                        <td>
                            <span class="text-dark-50 small">
                                <?= esc($c['description'] ?: '-') ?>
                            </span>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('class/edit/'.$c['id'].'?divisi='.$divisiId) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('class/delete/'.$c['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="divisi" value="<?= $divisiId ?>">
                                <button type="submit"
                                        onclick="return confirm('Delete this class?')"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('student?class='.$c['id']) ?>"
                               class="btn btn-sm btn-outline-primary">
                                Manage
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
$(function () {
    $('#classTable').DataTable({
        pageLength: 10,
        language: {
            paginate: {
                previous: '‹',
                next: '›'
            }
        }
    });
});
</script>

<?= $this->endSection() ?>