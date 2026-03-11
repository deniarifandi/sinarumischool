<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($role) ? 'Edit Role' : 'Add Role' ?>
    </h5>

    <form action="<?= isset($role)
        ? base_url('roles/save/'.$role['id'])
        : base_url('roles/save') ?>"
        method="post">

        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text"
                   name="roles"
                   class="form-control"
                   required
                   value="<?= old('roles', $role['roles'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      class="form-control"
                      rows="3"><?= old('description', $role['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('roles') ?>"
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