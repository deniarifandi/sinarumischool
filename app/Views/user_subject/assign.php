<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Assign Subjects</h5>
            <small class="text-white-50">
                User: <?= esc($user['name'] ?? $user['username'] ?? $user['id']) ?>
            </small>
        </div>
    </div>

    <form action="<?= base_url('user-subject/store') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">

        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3" style="width:60px;">#</th>
                        <th>Subject Name</th>
                        <th>Division</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($subjects as $s): ?>
                    <tr>
                        <td class="ps-3">
                            <input type="checkbox"
                                   name="subject_ids[]"
                                   value="<?= esc($s['id']) ?>"
                                   <?= in_array($s['id'], $assignedSubjectIds ?? []) ? 'checked' : '' ?>>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($s['subject_name'] ?? $s['name'] ?? $s['id']) ?>
                            </div>
                        </td>
                          <td>
                            <div class="fw-bold text-dark">
                                <?= esc($s['division_name'] ?? $s['division_name'] ?? $s['id']) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="<?= base_url('users') ?>"
               class="btn btn-outline-secondary me-2">
                Back
            </a>

            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>