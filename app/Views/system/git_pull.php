<?= $this->extend('main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h4 class="mb-3"><i class="bi bi-cloud-download me-2"></i>Pull Source dari Git</h4>
        <?php if ($result !== null): ?>
            <div class="alert <?= $result['success'] ? 'alert-success' : 'alert-danger' ?>">
                <?= $result['success'] ? 'Proses git pull selesai.' : 'Proses git pull gagal.' ?>
            </div>
            <pre class="bg-dark text-light rounded p-3" style="white-space: pre-wrap;"><?= esc($result['output']) ?></pre>
        <?php endif; ?>
        <p class="text-muted">Folder project: <code><?= esc($projectPath) ?></code></p>
        <p>Pastikan tidak ada perubahan lokal yang belum disimpan sebelum menjalankan pull.</p>
        <form method="post" onsubmit="return confirm('Jalankan git pull sekarang?');">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-download me-1"></i> Jalankan Git Pull</button>
            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
