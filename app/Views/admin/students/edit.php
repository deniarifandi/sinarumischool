<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white text-capitalize ps-3 mb-0">
                Edit Student
            </h6>
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/students/update/' . $student['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <!-- Student Code -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Student Code</label>
                    <input
                        type="text"
                        name="student_code"
                        class="form-control"
                        value="<?= old('student_code', $student['student_code']) ?>"
                        required
                    >
                </div>

                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?= old('name', $student['name']) ?>"
                        required
                    >
                </div>

                <!-- Gender -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="L" <?= old('gender', $student['gender']) === 'L' ? 'selected' : '' ?>>
                            Male
                        </option>
                        <option value="P" <?= old('gender', $student['gender']) === 'P' ? 'selected' : '' ?>>
                            Female
                        </option>
                    </select>
                </div>

                <!-- Birthdate -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Birthdate</label>
                    <input
                        type="date"
                        name="birthdate"
                        class="form-control"
                        value="<?= old('birthdate', $student['birthdate']) ?>"
                        required
                    >
                </div>

                <!-- Class -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Class</label>
                    <select name="class_id" class="form-select" required>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"
                                <?= old('class_id', $student['class_id']) == $c['id'] ? 'selected' : '' ?>>
                                <?= esc($c['class_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Address -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Address</label>
                    <textarea
                        name="address"
                        class="form-control"
                        rows="3"
                    ><?= old('address', $student['address']) ?></textarea>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/students') ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Student
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
