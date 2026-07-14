<?= $this->extend('main') ?>
<?= $this->section('content') ?>



<div class="">
    <div class="glass-card">
        
        <!-- Header & Action Row -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-secondary-subtle">
            <div>
                <h4 class="mb-1 text-white">Class: <?= esc($data[0]['class_name'] ?? '-') ?></h4>
                <p class="text-white-50 small mb-0">
                    Attendance Date: <span class="text-info fw-medium"><?= date('d M Y', strtotime($tanggal)) ?></span>
                </p>
            </div>

            <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
               class="btn btn-sm btn-outline-light px-3 py-2">
                ← Back to List
            </a>
        </div>

        <!-- Roster Check -->
        <?php if (empty($data)): ?>
            <div class="text-center py-5 border border-secondary-subtle rounded-3" style="background: rgba(0,0,0,0.1)">
                <i class="bi bi-file-earmark-x display-4 text-white-50"></i>
                <p class="mt-3 text-white-50 mb-0">No attendance data found for this record.</p>
            </div>
        <?php else: ?>

            <?php
            $statusMap = [
                1 => ['label' => 'Hadir', 'class' => 'badge-hadir'],
                2 => ['label' => 'Izin',  'class' => 'badge-izin'],
                3 => ['label' => 'Sakit', 'class' => 'badge-sakit'],
                4 => ['label' => 'Alpha', 'class' => 'badge-alpha']
            ];
            ?>

            <!-- White Table Grid -->
            <div class="table-responsive">
                <table class="table table-white-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 10%">No</th>
                            <th style="width: 45%">Student Name</th>
                            <th style="width: 20%">Status</th>
                            <th style="width: 25%">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td class="text-muted small fw-medium"><?= $no++ ?></td>
                            <td>
                                <span class="fw-semibold text-dark"><?= esc($row['name']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($row['status']) && isset($statusMap[$row['status']])): ?>
                                    <span class="badge-status <?= $statusMap[$row['status']]['class'] ?>">
                                        <?= esc($statusMap[$row['status']]['label']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger text-white small">Not Set</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="<?= empty($row['absensi_keterangan']) ? 'text-muted small' : 'text-dark' ?>">
                                    <?= !empty($row['absensi_keterangan']) ? esc($row['absensi_keterangan']) : '-' ?>
                                </span>
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