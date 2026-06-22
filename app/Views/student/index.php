<?= $this->extend('main') ?>
<?= $this->section('content') ?>


<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Student Management</h5>
            <small class="text-white-50">
                Division ID: <?= esc($divisionId ?? '-') ?>
            </small>
        </div>
        <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
        <a href="<?= base_url('student/create?division='.$divisionId) ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Student
        </a>
        <?php endif ?>
    </div>

    <form method="get" class="row g-3 mb-4">
    <input type="hidden" name="division" value="<?= $divisionId ?>">

    <div class="col-md-4">
        <select name="class" class="form-select" onchange="this.form.submit()">
            <option value="">All Classes</option>

            <?php foreach ($classes as $class): ?>
                <option value="<?= $class['id'] ?>"
                    <?= ($classId == $class['id']) ? 'selected' : '' ?>>
                    <?= esc($class['class_name']) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-primary">
            Filter
        </button>

        <a href="<?= base_url('student?division='.$divisionId) ?>"
           class="btn btn-secondary">
            Reset
        </a>
    </div>
</form>

   



            <table class="table align-middle mb-0" id="studentsTable">
                <thead>
                    <tr>
                        <th class="ps-3">Code</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Birthdate</th>
                        <th>Class</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>

                 <?php if (empty($students)): ?>
       
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;">

                <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($s['id']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($s['name']) ?>
                            </div>
                        </td>

                        <td>
                            <span class="text-dark">
                                <?= esc($s['gender']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="text-dark-50 small">
                                <?= esc($s['birthdate']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="text-dark">
                                <?= esc($s['class_name']) ?>
                            </span>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('student/edit/'.$s['id'].'?division='.$divisionId) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('student/delete/'.$s['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="division_id" value="<?= $divisionId ?>">
                                <button type="submit"
                                        onclick="return confirm('Delete this student?')"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
$(function () {
    $('#studentsTable').DataTable({
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
