<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white py-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="<?= base_url('sla/create?division=' . $division_id) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline ms-1">Add New</span>
        </a>
        <h6 class="mb-0 text-primary fw-bold">
            <i class="bi bi-person-exclamation me-1"></i> Late Arrival Slips
            <?php if (!empty($division)): ?>
                <span class="text-muted fw-normal ms-1">
                    - <?= esc($division['name'] ?? $division['division_name'] ?? '') ?>
                </span>
            <?php endif; ?>
        </h6>
        
    </div>

    <!-- BODY -->
    <div class="card-body p-2 p-sm-3">

        <!-- ALERTS -->
        <?php foreach (['success', 'error'] as $msgType): ?>
            <?php if (session()->getFlashdata($msgType)): ?>
                <div class="alert alert-<?= $msgType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show p-2 px-3 shadow-sm text-sm mb-3">
                    <i class="bi bi-<?= $msgType === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-1"></i>
                    <?= esc(session()->getFlashdata($msgType)) ?>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- FILTERS -->
        <form action="<?= base_url('sla') ?>" method="GET" class="mb-3 bg-light p-2 rounded border">
            <input type="hidden" name="division" value="<?= esc($division_id) ?>">
            <div class="row g-2 align-items-center">
                <!-- Month -->
                <div class="col-6 col-md-2">
                    <select name="month" class="form-select form-select-sm">
                        <option value="">All Months</option>
                        <?php 
                        $months = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                        foreach ($months as $num => $name): ?>
                            <option value="<?= $num ?>" <?= request()->getGet('month') == $num ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Year -->
                <div class="col-6 col-md-2">
                    <select name="year" class="form-select form-select-sm">
                        <option value="">All Years</option>
                        <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= request()->getGet('year') == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <!-- Class -->
                <div class="col-12 col-md-3">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= esc($class['id']) ?>" <?= request()->getGet('class_id') == $class['id'] ? 'selected' : '' ?>>
                                    <?= esc($class['class_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <!-- Buttons -->
                <div class="col-12 col-md-5 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                    <a href="<?= base_url('sla?division=' . $division_id) ?>" class="btn btn-sm btn-light border px-2" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                    <div class="dropdown flex-fill">
                        <button type="button" class="btn btn-sm btn-outline-success w-100 dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download"></i> Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="font-size: 0.875rem;">
                            <li><button type="submit" name="export" value="excel" class="dropdown-item py-2"><i class="bi bi-file-earmark-excel text-success me-2"></i> Excel</button></li>
                            <li><button type="submit" name="export" value="resume" class="dropdown-item py-2"><i class="bi bi-file-earmark-text text-info me-2"></i> Resume</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>

        <!-- TABLE (Scrollable on Mobile) -->
        <div class="table-responsive">
            <!-- text-nowrap mencegah teks turun ke bawah dan memaksa tabel agar bisa di-scroll horizontal di HP -->
            <table class="table table-sm table-hover table-bordered align-middle text-nowrap mb-0" style="font-size: 0.875rem;" id="slaTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Student</th>
                        <th>Arrival Time</th>
                        <th>Problem</th>
                        <th>Reason</th>
                        <th class="text-center">Point</th>
                        <th>Officer</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($slas)): ?>
                        <?php foreach ($slas as $index => $sla): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-semibold text-primary">
                                        <?= !empty($sla['student_name']) ? esc($sla['student_name']) : esc($sla['student_id']) ?>
                                    </div>
                                    <?php if (!empty($sla['student_name'])): ?>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <?= esc($sla['student_code']) ?> &bull; <?= esc($sla['class_name'] ?? '-') ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= !empty($sla['arrivaltime']) ? date('d M Y, H:i', strtotime($sla['arrivaltime'])) : '-' ?>
                                </td>
                                <td><?= esc($sla['problem']) ?></td>
                                <td><?= !empty($sla['reason']) ? esc($sla['reason']) : '<span class="text-muted">-</span>' ?></td>
                                <td class="text-center">
                                    <?php if (isset($sla['reduction']) && $sla['reduction'] !== ''): ?>
                                        <span class="badge bg-danger"><?= esc($sla['reduction']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= !empty($sla['teacher_name']) ? esc($sla['teacher_name']) : '<span class="text-muted">N/A</span>' ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="<?= base_url('sla/edit/' . $sla['id']) ?>" class="btn btn-warning btn-sm px-2 py-0" title="Edit">
                                            <i class="bi bi-pencil" style="font-size: 0.75rem;"></i>
                                        </a>
                                        <form action="<?= base_url('sla/delete/' . $sla['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this late arrival slip?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm px-2 py-0" title="Delete">
                                                <i class="bi bi-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                <span>No late arrival slips found.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>        
    </div>
</div>


<?= $this->endSection() ?>