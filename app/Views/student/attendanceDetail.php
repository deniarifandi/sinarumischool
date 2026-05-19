<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">
                Class: <?= esc($data[0]['class_name'] ?? '-') ?>
            </h5>
            <small class="text-muted">
                Date: <?= date('j-M-Y', strtotime($tanggal)) ?>
            </small>
        </div>

        <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
           class="btn btn-secondary">
            ← Back
        </a>
    </div>

    <?php if (empty($data)): ?>
        <p class="text-muted">No data.</p>
    <?php else: ?>

    <?php
    $statusMap = [
        1 => 'Hadir',
        2 => 'Izin',
        3 => 'Sakit',
        4 => 'Alpha'
    ];
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:60px;">No</th>
                <th>Student Name</th>
                <th style="width:150px;">Status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($data as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($row['name']) ?></td>
                <td>
                    <?php if (!empty($row['status'])): ?>
                        <?= esc($statusMap[$row['status']] ?? 'Unknown') ?>
                    <?php else: ?>
                        <span class="text-danger">Not Set</span>
                    <?php endif; ?>
                </td>
                <td><?= esc($row['absensi_keterangan'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>