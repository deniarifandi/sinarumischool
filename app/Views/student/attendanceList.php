<?= $this->extend('main') ?>
<?= $this->section('content') ?>



<div class="">
    <div class="glass-card">
        
        <!-- Header & Action Row -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-secondary-subtle">
            <div>
                <h4 class="mb-1 text-white">Attendance History</h4>
                <p class="text-white-50 small mb-0">View or add daily attendance sessions.</p>
            </div>

            <a href="<?= base_url('student/attendance/create/'.$class_id) ?>"
               class="btn btn-sm btn-primary px-3 py-2 shadow-sm d-flex align-items-center gap-1">
                <span>+ Add Attendance</span>
            </a>
        </div>

        <!-- Roster Check -->
        <?php if (empty($dates)): ?>
            <div class="text-center py-5 border border-secondary-subtle rounded-3" style="background: rgba(0,0,0,0.1)">
                <i class="bi bi-calendar-x display-4 text-white-50"></i>
                <p class="mt-3 text-white-50 mb-0">No attendance logs found for this class yet.</p>
            </div>
        <?php else: ?>

            <!-- White Table Grid -->
            <div class="table-responsive">
                <table class="table table-white-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 10%">No</th>
                            <th style="width: 40%">Class Name</th>
                            <th style="width: 30%">Date</th>
                            <th style="width: 20%" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($dates as $d): ?>
                        <tr>
                            <td class="text-muted small fw-medium"><?= $no++ ?></td>
                            <td>
                                <span class="fw-semibold text-dark"><?= esc($d['class_name']) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-dark fw-medium">
                                        <?= date('d M Y', strtotime($d['tanggal'])) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('student/attendance/detail/'.$class_id.'/'.$d['tanggal']) ?>"
                                   class="btn btn-xs btn-outline-primary px-3 py-1 font-monospace small" style="font-size: 0.8rem;">
                                    View Details
                                </a>
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