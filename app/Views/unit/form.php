<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($unit) ? 'Edit Unit' : 'Add Unit' ?>
    </h5>

    <form action="<?= isset($unit)
        ? base_url('unit/update/'.$unit['id'])
        : base_url('unit/create') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="subject_id"
               value="<?= old('subject_id', $unit['subject_id'] ?? $subjectId ?? '') ?>">

        <input type="hidden"
               name="grade_id"
               value="<?= old('grade_id', $unit['grade_id'] ?? $gradeId ?? '') ?>">

        <div class="mb-3">
            <label class="form-label">Unit Name</label>
            <input type="text"
                   name="name"
                   class="form-control"
                   required
                   value="<?= old('name', $unit['name'] ?? '') ?>">
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('unit?subject='.($subjectId ?? $unit['subject_id'] ?? '').'&grade='.($gradeId ?? $unit['grade_id'] ?? '')) ?>"
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