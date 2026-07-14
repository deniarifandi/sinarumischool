<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    /* Consistent dark glassmorphic layout for the main card container */
    .glass-card {
     
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    
    /* Dedicated Styling for the Crisp White Table */
    .table-white-custom {
        background-color: #ffffff !important;
        color: #212529 !important; /* Dark text */
        border-radius: 8px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    .table-white-custom th {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 2px solid #dee2e6 !important;
    }
    .table-white-custom td {
        background-color: #ffffff !important;
        color: #212529 !important;
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef !important;
    }
    .table-white-custom tbody tr:hover td {
        background-color: #f8f9fa !important;
    }
</style>

<div class="">
    <div class="glass-card mx-auto">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pb-3 mb-4 border-bottom border-secondary-subtle gap-3">
            <div>
                <h4 class="mb-1 text-white">Attendance History</h4>
                <p class="text-white-50 small mb-0">View or add daily attendance sessions.</p>
                
                <?php if (!empty($academicYear)): ?>
                    <div class="mt-2">
                        <span class="badge bg-dark border border-primary text-primary px-2 py-1.5 small fw-normal">
                            Academic Year: <?= esc($academicYear) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Header Action Controls -->
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('student/attendance/recap/'.$class_id) ?>"
                   class="btn btn-sm btn-outline-light px-3 py-2">
                    Monthly Recap
                </a>
                <a href="<?= base_url('student/attendance/create/'.$class_id) ?>"
                   class="btn btn-sm btn-primary px-3 py-2 shadow-sm fw-medium">
                    + Add Attendance
                </a>
            </div>
        </div>

        <!-- Check data availability -->
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
                                    <span class="fw-semibold text-dark">
                                        <?= esc($d['class_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark fw-medium">
                                        <?= date('d M Y', strtotime($d['tanggal'])) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="<?= base_url('student/attendance/detail/'.$class_id.'/'.$d['tanggal']) ?>"
                                           class="btn btn-xs btn-outline-primary px-2 py-1 small" style="font-size: 0.8rem;">
                                            View
                                        </a>
                                        <a href="<?= base_url('student/attendance/edit/'.$class_id.'/'.$d['tanggal']) ?>"
                                           class="btn btn-xs btn-outline-secondary text-dark px-2 py-1 small" style="font-size: 0.8rem; background-color: #f1f3f5;">
                                            Edit
                                        </a>
                                    </div>
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