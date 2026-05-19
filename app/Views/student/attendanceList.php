<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Attendance Dates</h5>

        <!-- ADD BUTTON -->
        <a href="<?= base_url('student/attendance/create/'.$class_id) ?>"
           class="btn btn-primary">
            + Add Attendance
        </a>
    </div>

    <?php if (empty($dates)): ?>
        <p class="text-muted">No data.</p>
    <?php else: ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Class</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($dates as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($d['class_name']) ?></td>
                <td><?= date('j-M-Y', strtotime($d['tanggal'])) ?></td>
                <td>
                    <a href="<?= base_url('student/attendance/detail/'.$class_id.'/'.$d['tanggal']) ?>"
                       class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>