<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 mb-0">Student Management</h6>
            <a href="<?= base_url('admin/students/create') ?>"
               class="btn btn-sm btn-outline-light me-3">
                + Add Student
            </a>
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Class</th>
                        <th>Birthdate</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?= esc($s['student_code']) ?></td>
                            <td><?= esc($s['name']) ?></td>
                            <td><?= esc(ucfirst($s['gender'])) ?></td>
                            <td><?= esc($s['class_name']) ?></td>
                            <td><?= esc($s['birthdate']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/students/edit/' . $s['id']) ?>"
                                   class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                                |
                                <a href="<?= base_url('admin/students/delete/' . $s['id']) ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this student?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
