<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">Assign Subject to User</h5>

    <form action="<?= base_url('user-subject/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">User</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= esc($u['id']) ?>"
                        <?= old('user_id') == $u['id'] ? 'selected' : '' ?>>
                        <?= esc($u['name'] ?? $u['username'] ?? $u['id']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select" required>
                <option value="">-- Select Subject --</option>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= esc($s['id']) ?>"
                        <?= old('subject_id') == $s['id'] ? 'selected' : '' ?>>
                        <?= esc($s['subject_name'] ?? $s['id']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('user-subject') ?>"
               class="btn btn-outline-secondary me-2">
                Back
            </a>

            <button type="submit" class="btn btn-primary">
                Save
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>