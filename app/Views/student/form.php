<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($student);
$action = $isEdit ? base_url('student/save/'.$student['id']) : base_url('student/save');
$title  = $isEdit ? 'Edit Student' : 'Add Student';
// Common class string to keep the code clean
$inputClass = "form-control form-control-sm bg-white text-dark border-secondary";
$selectClass = "form-select form-select-sm bg-white text-dark border-secondary";
?>

<div class="glass-card mx-auto" style="">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>
        <span class="badge bg-info text-dark">Division ID: <?= esc($divisionId) ?></span>
    </div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger py-2 small">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="division_id" value="<?= esc($divisionId) ?>">

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Student Code</label>
                <input type="text" name="student_code" value="<?= old('student_code', $student['student_code'] ?? '') ?>" class="<?= $inputClass ?>">
            </div>
            <div class="col-md-8">
                <label class="form-label small text-white-50 mb-1">Full Name</label>
                <input type="text" name="name" value="<?= old('name', $student['name'] ?? '') ?>" class="<?= $inputClass ?>" required>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Gender</label>
                <select name="gender" class="<?= $selectClass ?>">
                    <option value="">- Select -</option>
                    <option value="L" <?= old('gender', $student['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Male</option>
                    <option value="P" <?= old('gender', $student['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Birthdate</label>
                <input type="date" name="birthdate" value="<?= old('birthdate', $student['birthdate'] ?? '') ?>" class="<?= $inputClass ?>">
            </div>
            
            <!-- Class Dropdown -->
            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Class</label>
                <select name="class_id"
                        class="<?= $inputClass ?>"
                        required>
                    <option value="">- Select Class -</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= old('class_id', $student['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= esc($c['grade_name'].' - '.$c['class_name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>


            <div class="col-12">
                <label class="form-label small text-white-50 mb-1">Religion</label>
                <input type="text" name="murid_agama" value="<?= old('murid_agama', $student['murid_agama'] ?? '') ?>" class="<?= $inputClass ?>">
            </div>

            <div class="col-12">
                <label class="form-label small text-white-50 mb-1">Address</label>
                <textarea name="address" rows="2" class="<?= $inputClass ?>"><?= old('address', $student['address'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="<?= base_url('student?division='.$divisionId) ?>" class="btn btn-sm btn-outline-light px-3">
                Cancel
            </a>
            <button type="submit" class="btn btn-sm btn-primary px-4">
                <?= $isEdit ? 'Update' : 'Save' ?> Student
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>