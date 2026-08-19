<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<h5 class="text-light mb-3">Kelola Tahun Ajaran</h5>

<button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
    <i class="bi bi-plus-lg"></i> Tambah Tahun Ajaran
</button>

<?php
$grouped = [];
foreach ($academicYears as $ay) {
    $grouped[$ay['division_name']][] = $ay;
}
?>

<?php foreach ($grouped as $divisionName => $items): ?>
<h6 class="text-white-50 mt-4"><?= esc($divisionName) ?></h6>
<table class="table table-sm table-bordered">
<thead><tr><th>Nama</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach ($items as $ay): ?>
<tr>
    <td><?= esc($ay['name']) ?></td>
    <td><?= esc($ay['start_date']) ?></td>
    <td><?= esc($ay['end_date']) ?></td>
    <td>
        <?= $ay['is_active']
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Tidak aktif</span>' ?>
    </td>
    <td>
        <a href="<?= base_url('academic-year/' . $ay['id']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
        <?php if (!$ay['is_active']): ?>
        <form method="post" action="<?= base_url('academic-year/' . $ay['id'] . '/set-active') ?>" class="d-inline"
              onsubmit="return confirm('Aktifkan tahun ajaran ini? Tahun ajaran lain di divisi yang sama akan otomatis nonaktif.')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-outline-success">Aktifkan</button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endforeach; ?>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('academic-year/create') ?>" class="modal-content">
        <?= csrf_field() ?>
        <div class="modal-header"><h6 class="modal-title">Tambah Tahun Ajaran</h6></div>
        <div class="modal-body">
            <div class="mb-2">
                <label class="form-label small">Divisi</label>
                <select name="division_id" class="form-select form-select-sm" required>
                    <option value="">-- pilih divisi --</option>
                    <?php foreach ($divisions as $d): ?>
                        <option value="<?= esc($d['id']) ?>"><?= esc($d['division_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-2">
                <label class="form-label small">Nama</label>
                <input type="text" name="name" class="form-control form-control-sm" placeholder="cth: 2026/2027" required>
            </div>
            <div class="mb-2">
                <label class="form-label small">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control form-control-sm" required>
            </div>
            <div class="mb-2">
                <label class="form-label small">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control form-control-sm" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
        </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>