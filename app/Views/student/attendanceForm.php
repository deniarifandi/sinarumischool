<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Attendance Form</h5>

        <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
           class="btn btn-secondary">
            ← Back
        </a>
    </div>

    <form method="post" action="<?= base_url('student/attendance/save') ?>">
        <?= csrf_field() ?>

        <input type="hidden" name="class_id" value="<?= esc($class_id) ?>">

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date"
                   name="tanggal"
                   class="form-control"
                   value="<?= esc($tanggal) ?>"
                   required>
        </div>

        <?php if (empty($data)): ?>
            <p class="text-muted">No student found.</p>
        <?php else: ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Student Name</th>
                        <th style="width:320px;">Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= esc($row['name']) ?>
                            <input type="hidden"
                                   name="student_id[]"
                                   value="<?= esc($row['murid_id']) ?>">
                        </td>

                        <td>
                            <?php $id = $row['murid_id']; ?>

                            <div class="d-flex gap-3">
                                <label>
                                    <input type="radio" name="status[<?= $id ?>]" value="1" checked> Hadir
                                </label>

                                <label>
                                    <input type="radio" name="status[<?= $id ?>]" value="2"> Izin
                                </label>

                                <label>
                                    <input type="radio" name="status[<?= $id ?>]" value="3"> Sakit
                                </label>

                                <label>
                                    <input type="radio" name="status[<?= $id ?>]" value="4"> Alpha
                                </label>
                            </div>
                        </td>

                        <td>
                            <input type="text"
                                   name="keterangan[<?= $id ?>]"
                                   class="form-control"
                                   placeholder="optional">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary">
            Save
        </button>

        <?php endif; ?>
    </form>

</div>

<?= $this->endSection() ?>