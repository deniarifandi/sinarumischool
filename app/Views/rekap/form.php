<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($rekap);
$action = $isEdit ? base_url('rekap/save/'.$rekap['id']) : base_url('rekap/save');
$title  = $isEdit ? 'Edit Rekap' : 'Add Rekap';

$inputClass  = "form-control form-control-sm bg-white text-dark border-secondary";
$selectClass = "form-select form-select-sm bg-white text-dark border-secondary";
?>

<div class="glass-card mx-auto">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>
    </div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger py-2 small">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Division ID</label>
                <select name="division_id" class="<?= $selectClass ?>" required>

<option value="">- Select Division -</option>

<?php foreach ($divisions as $d): ?>
<option value="<?= $d['id'] ?>"
    <?= old('division_id', $rekap['division_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
    <?= esc($d['division_name']) ?>
</option>
<?php endforeach ?>

</select>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">User Group</label>
                <input type="text"
                       name="user_group"
                       value="<?= old('user_group', $rekap['user_group'] ?? '') ?>"
                       class="<?= $inputClass ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Group Sort</label>
                <input type="number"
                       name="group_sort"
                       value="<?= old('group_sort', $rekap['group_sort'] ?? '') ?>"
                       class="<?= $inputClass ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label small text-white-50 mb-1">User Role</label>
                <input type="text"
                       name="user_role"
                       value="<?= old('user_role', $rekap['user_role'] ?? '') ?>"
                       class="<?= $inputClass ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label small text-white-50 mb-1">Role Sort</label>
                <input type="number"
                       name="role_sort"
                       value="<?= old('role_sort', $rekap['role_sort'] ?? '') ?>"
                       class="<?= $inputClass ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label small text-white-50 mb-1">User</label>

                <select name="user_id" class="<?= $selectClass ?>">
                    <option value="">- Select User -</option>

                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"
                            <?= old('user_id', $rekap['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                            <?= esc($u['name']) ?> (@<?= esc($u['username']) ?>)
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Status</label>
                <select name="nullified" class="<?= $selectClass ?>">
                    <option value="0" <?= old('nullified', $rekap['nullified'] ?? 0) == 0 ? 'selected' : '' ?>>Active</option>
                    <option value="1" <?= old('nullified', $rekap['nullified'] ?? 0) == 1 ? 'selected' : '' ?>>Nullified</option>
                </select>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="<?= base_url('rekap') ?>"
               class="btn btn-sm btn-outline-light px-3">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-sm btn-primary px-4">
                <?= $isEdit ? 'Update' : 'Save' ?> Rekap
            </button>
        </div>

    </form>

</div>

<?= $this->endSection() ?>