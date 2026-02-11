<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($subject) ? 'Edit Subject' : 'Add Subject' ?>
    </h5>

    <form action="<?= isset($subject)
        ? base_url('subject/save/'.$subject['id'])
        : base_url('subject/save') ?>"
        method="post">

        <?= csrf_field() ?>
        <input type="hidden" name="division_id" value="<?= esc($divisionId) ?>">

        <div class="mb-3">
            <label class="form-label">Subject Code</label>
            <input type="text"
                   name="subject_code"
                   class="form-control"
                   value="<?= old('subject_code', $subject['subject_code'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text"
                   name="subject_name"
                   class="form-control"
                   required
                   value="<?= old('subject_name', $subject['subject_name'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      class="form-control"
                      rows="3"><?= old('description', $subject['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('subject?division='.$divisionId) ?>"
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
