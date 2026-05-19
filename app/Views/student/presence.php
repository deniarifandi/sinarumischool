<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Attendance</h5>
            <small class="text-white-50">
                Class ID: <?= esc($class_id ?? '-') ?>
            </small>
        </div>
    </div>

    <?php if (empty($students)): ?>
        <div class="text-center py-5">
            <i class="bi bi-people display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No students found.</p>
        </div>
    <?php else: ?>

    <form method="post" action="<?= base_url($isEdit ? 'attendance/update' : 'attendance/simpan') ?>">
    <input type="hidden" name="class_id" value="<?= $class_id ?>">
    <input type="date" name="tanggal" value="<?= $tanggal ?>">

    <table border="1" cellpadding="8">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Status</th>
            <th>Note</th>
        </tr>

        <?php foreach ($data as $i => $row): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td>
                <?= esc($row['name']) ?>
                <input type="hidden" name="student_id[]" value="<?= $row['murid_id'] ?>">
            </td>
            <td>
                <select name="status[]">
                    <option value="">--</option>
                    <option value="Hadir" <?= $row['status']=='Hadir'?'selected':'' ?>>Hadir</option>
                    <option value="Izin" <?= $row['status']=='Izin'?'selected':'' ?>>Izin</option>
                    <option value="Sakit" <?= $row['status']=='Sakit'?'selected':'' ?>>Sakit</option>
                    <option value="Alpha" <?= $row['status']=='Alpha'?'selected':'' ?>>Alpha</option>
                </select>
            </td>
            <td>
                <input type="text" name="keterangan[]" value="<?= esc($row['absensi_keterangan']) ?>">
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button type="submit">
        <?= $isEdit ? 'Update' : 'Save' ?>
    </button>
</form>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
</script>
<?= $this->endSection() ?>