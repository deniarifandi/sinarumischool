<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($division);
$action = $isEdit
    ? base_url('division/save/'.$division['id'])
    : base_url('division/save');

$title = $isEdit ? 'Edit Division' : 'Add Division';
?>

<div class="glass-card" style="max-width:500px">
    <h5 class="mb-4"><?= $title ?></h5>

    <?php if (session('error')): ?>
        <div class="alert alert-danger bg-transparent border border-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>

        <!-- Division Name -->
        <div class="mb-4">
            <label class="form-label text-white-50">Division Name</label>
            <input type="text"
                   name="division_name"
                   value="<?= old('division_name', $division['division_name'] ?? '') ?>"
                   class="form-control bg-white text-dark border-secondary"
                   required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= base_url('division') ?>"
               class="btn btn-outline-light rounded-pill">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary rounded-pill px-4">
                <?= $isEdit ? 'Update Division' : 'Save Division' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
