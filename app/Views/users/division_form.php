<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card" style="max-width:520px">
    <h5 class="mb-4">
        Edit User Divisions<br>
        <small class="text-white-50"><?= esc($user['name']) ?></small>
    </h5>

    <form method="post" action="<?= base_url('users/division/'.$user['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-4">
            <?php foreach ($divisions as $d): ?>
                <div class="form-check mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           name="divisi[]"
                           value="<?= $d['id'] ?>"
                           <?= in_array($d['id'], $userDivisions) ? 'checked' : '' ?>>
                    <label class="form-check-label">
                        <?= esc($d['division_name']) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= base_url('users') ?>" class="btn btn-outline-light rounded-pill">
                Cancel
            </a>
            <button class="btn btn-primary rounded-pill px-4">
                Save Divisions
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
