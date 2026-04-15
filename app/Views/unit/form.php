<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($unit) ? 'Edit Unit' : 'Add Unit' ?>
            <?php $subjectId = esc( $subject_id ?? $_GET['subject_id'] ?? '-') ?>
    </h5>

    <form action="<?= isset($unit)
        ? base_url('unit/update/'.$unit['id'])
        : base_url('unit/store') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="subject_id"
               value="<?php echo $subjectId ?>">

    <?php 
$selectedGrade = old('grade_id') 
    ?? ($unit['grade_id'] ?? ($_GET['grade'] ?? ''));
?>

<div class="mb-3">
    <label class="form-label">Grade</label>
    <select name="grade_id" class="form-control" required>
        <option value="">-- Select Grade --</option>
        <?php foreach ($grades as $grade): ?>
            <option value="<?= $grade['id'] ?>"
                <?= $selectedGrade == $grade['id'] ? 'selected' : '' ?>>
                <?= $grade['grade_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
        <div class="mb-3">
            <label class="form-label">Unit Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   required
                   value="<?= old('name', $unit['name'] ?? '') ?>">
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('unit?subject_id='.($subjectId ?? $unit['subject_id'] ?? '')) ?>"
               class="btn btn-outline-secondary me-2">
                Back
            </a>

            <button type="submit"
                    class="btn btn-primary">
                Save
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>