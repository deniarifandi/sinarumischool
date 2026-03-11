<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Role Management</h5>
            <small class="text-white-50">
                Manage system user roles
            </small>
        </div>

        <a href="<?= base_url('roles/create') ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Role
        </a>
    </div>

    <?php if (empty($roles)): ?>
        <div class="text-center py-5">
            <i class="bi bi-shield-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No roles found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Role</th>
                        <th>Description</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($roles as $r): ?>
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($r['roles']) ?>
                            </span>
                        </td>

                        <td>
                            <span class="text-dark-50 small">
                                <?= esc($r['description'] ?: '-') ?>
                            </span>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('roles/edit/'.$r['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('roles/delete/'.$r['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        onclick="return confirm('Delete this role?')"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>