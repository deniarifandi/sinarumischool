<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Student Management</h5>
            <small class="text-white-50">
                Division ID: <?= esc($divisionId ?? '-') ?>
            </small>
        </div>

        <?php if (!empty($user) && in_array($user['role'], ['superadmin', 'admin'])): ?>
            <a href="<?= base_url('student/create?division=' . $divisionId) ?>"
               class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Add Student
            </a>
        <?php endif; ?>
    </div>

    <form method="get" class="row g-3 mb-4">
        <input type="hidden" name="division" value="<?= esc($divisionId) ?>">

        <div class="col-md-4">
            <select name="class" class="form-select" onchange="this.form.submit()">
                <option value="">All Classes</option>

                <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>"
                        <?= ($classId == $class['id']) ? 'selected' : '' ?>>
                        <?= esc($class['class_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                Filter
            </button>

            <a href="<?= base_url('student?division=' . $divisionId) ?>"
               class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>

    <div class="table-responsive" style="border-radius:12px; overflow:hidden;">
        <table class="table align-middle mb-0" id="studentsTable">
            <thead>
                <tr>
                    <th class="ps-3">Code</th>
                    <th>Name</th>
                    <th>NIS</th>
                    <th>Gender</th>
                    <th>Birthdate</th>
                    <th>Class</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>

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
                            <div class="fw-bold text-dark">
                                <?= esc($s['student_code']) ?>
                            </div>
                        </td>

                        <td><?= esc($s['gender']) ?></td>

                        <td><?= esc($s['birthdate']) ?></td>

                        <td><?= esc($s['class_name']) ?></td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('student/edit/' . $s['id'] . '?division=' . $divisionId) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('student/delete/' . $s['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>

                                <input type="hidden"
                                       name="division_id"
                                       value="<?= esc($divisionId) ?>">

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)"
                                        onclick="return confirm('Delete this student?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?= esc(session()->getFlashdata('success')) ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= esc(session()->getFlashdata('error')) ?>'
});
</script>
<?php endif; ?>

<script>
$(function () {
    $('#studentsTable').DataTable({
        pageLength: 10,
        searching: true,
        language: {
            emptyTable: "No students found.",
            zeroRecords: "No students found.",
            paginate: {
                previous: "‹",
                next: "›"
            }
        }
    });
});
</script>

<?= $this->endSection() ?>