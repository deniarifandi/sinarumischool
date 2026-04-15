<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
    $unit_id    = esc($unit_id ?? ($subunit['unit_id'] ??$_GET['unit_id'] ?? ''));
    $isEdit     = isset($subunit) && !empty($subunit['id']);
?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= $isEdit ? 'Edit Sub-Unit' : 'Add Sub-Unit' ?>
    </h5>

    <form action="<?= $isEdit
        ? base_url('subunit/update/' . $subunit['id'])
        : base_url('subunit/store') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="subunit_id"
               value="<?= esc($subunit['id'] ?? '') ?>">

        <input type="hidden"
               name="unit_id"
               value="<?= $unit_id ?>">

        <div class="mb-3">
            <label class="form-label">Sub-Unit Name</label>
            <input type="text"
                   name="subunit_name"
                   class="form-control"
                   required
                   value="<?= old('subunit_name', $subunit['subunit_name'] ?? '') ?>">
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('subunit?unit_id=' . ($unit_id ?? $subunit['unit_id'] ?? '')) ?>"
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