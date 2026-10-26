<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php foreach (['success', 'error'] as $type): ?>
    <?php if (session($type)): ?>
        <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>" role="alert"><?= esc(session($type)) ?></div>
    <?php endif; ?>
<?php endforeach; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">User Terhapus</h6>
        <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary btn-sm">Kembali ke User Directory</a>
    </div>
    <div class="card-body">
        <p class="text-muted">Pulihkan user agar kembali muncul di User Directory dengan data dan relasi yang masih tersimpan.</p>
        <?php if (empty($users)): ?>
            <div class="alert alert-info mb-0">Tidak ada user terhapus.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="deletedUsersTable">
                    <thead>
                        <tr><th>ID</th><th>Nama</th><th>Username</th><th>Role</th><th>Dihapus pada</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['role']) ?></td>
                                <td><?= esc($user['deleted_at']) ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('users/restore/' . $user['id']) ?>" onsubmit="return confirm('Pulihkan user ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-sm">Pulihkan</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(function () {
    $('#deletedUsersTable').DataTable({
        pageLength: 15,
        order: [[4, 'desc']],
        columnDefs: [{ targets: 5, orderable: false, searchable: false }]
    });
});
</script>
<?= $this->endSection() ?>
