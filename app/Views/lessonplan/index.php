<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Lesson Plan Management</h5>
            <small class="text-white-50">
                Total Data: <?= isset($lessonplans) ? count($lessonplans) : 0 ?>
            </small>
        </div>

        <a href="<?= base_url('lessonplan/create') ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Lesson Plan
        </a>
    </div>

    <?php if (empty($lessonplans)): ?>
        <div class="text-center py-5">
            <i class="bi bi-journal-text display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No lesson plans found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive" style="border-radius:12px;overflow:hidden;">
            <table class="table align-middle mb-0" id="lessonplanTable">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Class</th>
                        <th>Unit</th>
                        <th>Subunit</th>
                        <th>Semester</th>
                        <th>Bulan</th>
                        <th>DPL</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lessonplans as $lp): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($lp['id']) ?>
                            </span>
                        </td>

                       <td><?= esc($lp['class_name']) ?></td>
                        <td><?= esc($lp['unit_name']) ?></td>
                        <td><?= esc($lp['subunit_name']) ?></td>
                        <td><?= esc($lp['semester']) ?></td>
                        <td><?= esc($lp['bulan']) ?></td>
                        <td><?= esc($lp['dpl']) ?></td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('lessonplan/edit/'.$lp['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                           <form action="<?= base_url('lessonplan/'.$lp['id']) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit"
                                    onclick="return confirm('Delete this lesson plan?')"
                                    class="btn btn-sm btn-outline-danger ms-1">
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


<?= $this->section('script') ?>
<script>
$(function () {
    $('#lessonplanTable').DataTable({
        pageLength: 10,
        searching: true,
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