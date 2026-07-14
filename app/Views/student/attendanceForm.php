<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="">
    <div class="glass-card">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-secondary-subtle">
            <div>
                <h4 class="mb-1 text-white">Attendance Form</h4>
                <p class="text-white-50 small mb-0">Record or adjust student presence records.</p>
            </div>
            <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
               class="btn btn-sm btn-outline-light px-3 py-2">
                ← Back to List
            </a>
        </div>

        <!-- Form container -->
        <form method="post" action="<?= base_url('student/attendance/save') ?>" autocomplete="off">
            <?= csrf_field() ?>
            <input type="hidden" name="class_id" value="<?= esc($class_id) ?>">

            <!-- Date Selector Row -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label small text-white-50 mb-1 fw-medium">Attendance Date</label>
                    <input type="date"
                           name="tanggal"
                           class="form-control form-control-sm text-dark border-secondary px-3 py-2"
                           value="<?= esc($tanggal) ?>"
                           required>
                </div>
            </div>

            <!-- Empty State Check -->
            <?php if (empty($data)): ?>
                <div class="text-center py-5 border border-secondary-subtle rounded-3" style="background: rgba(0,0,0,0.1)">
                    <i class="bi bi-people display-4 text-white-50"></i>
                    <p class="mt-3 text-white-50 mb-0">No active student roster found for this class.</p>
                </div>
            <?php else: ?>

            <!-- White Table Grid -->
            <div class="table-responsive">
                <table class="table table-white-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 32%">Student Name</th>
                            <th style="width: 35%" class="text-center">Status</th>
                            <th style="width: 25%">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data as $row): ?>
                        <?php $id = $row['murid_id']; ?>
                        <tr>
                            <td class="text-muted small"><?= $no++ ?></td>
                            <td>
                                <span class="fw-semibold text-dark"><?= esc($row['name']) ?></span>
                                <input type="hidden" name="student_id[]" value="<?= esc($id) ?>">
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 btn-group-toggle" role="group">
                                    <!-- Hadir Toggle -->
                                    <input type="radio" 
                                           class="btn-check btn-check-hadir" 
                                           name="status[<?= $id ?>]" 
                                           id="status_h_<?= $id ?>" 
                                           value="1" checked>
                                    <label class="btn btn-sm rounded" for="status_h_<?= $id ?>">Hadir</label>

                                    <!-- Izin Toggle -->
                                    <input type="radio" 
                                           class="btn-check btn-check-hadir" 
                                           name="status[<?= $id ?>]" 
                                           id="status_i_<?= $id ?>" 
                                           value="2">
                                    <label class="btn btn-sm rounded" for="status_i_<?= $id ?>">Izin</label>

                                    <!-- Sakit Toggle -->
                                    <input type="radio" 
                                           class="btn-check btn-check-hadir" 
                                           name="status[<?= $id ?>]" 
                                           id="status_s_<?= $id ?>" 
                                           value="3">
                                    <label class="btn btn-sm rounded" for="status_s_<?= $id ?>">Sakit</label>

                                    <!-- Alpha Toggle -->
                                    <input type="radio" 
                                           class="btn-check btn-check-hadir" 
                                           name="status[<?= $id ?>]" 
                                           id="status_a_<?= $id ?>" 
                                           value="4">
                                    <label class="btn btn-sm rounded" for="status_a_<?= $id ?>">Alpha</label>
                                </div>
                            </td>
                            <td>
                                <input type="text"
                                       name="keterangan[<?= $id ?>]"
                                       class="form-control form-control-sm"
                                       placeholder="Add remarks...">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Action buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary-subtle">
                <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>" class="btn btn-sm btn-outline-light px-4 py-2">
                    Cancel
                </a>
                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 shadow-sm">
                    Save Attendance
                </button>
            </div>

            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection() ?>