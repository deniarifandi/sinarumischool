<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($outcome) ? 'Edit Outcome' : 'Add Outcome' ?>
            <?php $subjectId = esc( $subject_id ?? $_GET['subject_id'] ?? '-') ?>
    </h5>

    <form action="<?= isset($outcome)
        ? base_url('outcome/update/'.$outcome['id'])
        : base_url('outcome/store') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="subject_id"
               value="<?php echo $subjectId ?>">

    <?php 
$selectedGrade = old('grade_id') 
    ?? ($outcome['grade_id'] ?? ($_GET['grade'] ?? ''));
?>

        <div class="mb-3">
            <label class="form-label">Outcome Name</label>
            <input type="text"
                   name="outcome_name"
                   class="form-control"
                   required
                   value="<?= old('outcome_name', $outcome['outcome_name'] ?? '') ?>">
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('outcome?subject_id='.($subjectId ?? $outcome['subject_id'] ?? '')) ?>"
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