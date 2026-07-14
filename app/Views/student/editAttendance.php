<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="">
    <div class="glass-card">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-secondary-subtle">
            <div>
                <h4 class="mb-1 text-white">Edit Attendance</h4>
                <p class="text-white-50 small mb-0">
                    Update attendance for <?= date('d M Y', strtotime($tanggal)) ?>
                </p>
            </div>

            <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
               class="btn btn-sm btn-outline-light px-3 py-2">
                ← Back to List
            </a>
        </div>

        <form method="post" action="<?= base_url('student/attendance/update') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="class_id" value="<?= esc($class_id) ?>">
            <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

            <?php if (empty($data)): ?>

                <div class="text-center py-5 border border-secondary-subtle rounded-3">
                    <p class="text-white-50 mb-0">
                        No students found.
                    </p>
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-white-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th width="8%">No</th>
                                <th width="30%">Student</th>
                                <th width="37%" class="text-center">Status</th>
                                <th width="25%">Note</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php $no = 1; ?>

                        <?php foreach ($data as $row): ?>

                            <?php $id = $row['murid_id']; ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td>
                                    <?= esc($row['name']) ?>

                                    <input
                                        type="hidden"
                                        name="student_id[]"
                                        value="<?= $id ?>">
                                </td>

                                <td>

                                    <div class="d-flex justify-content-center gap-1">

                                        <input
                                            class="btn-check btn-check-hadir"
                                            type="radio"
                                            id="hadir<?= $id ?>"
                                            name="status[<?= $id ?>]"
                                            value="1"
                                            <?= ($row['status'] == 1) ? 'checked' : '' ?>>

                                        <label class="btn btn-sm rounded btn-light" for="hadir<?= $id ?>">
                                            Hadir
                                        </label>

                                        <input
                                            class="btn-check btn-check-hadir"
                                            type="radio"
                                            id="izin<?= $id ?>"
                                            name="status[<?= $id ?>]"
                                            value="2"
                                            <?= ($row['status'] == 2) ? 'checked' : '' ?>>

                                        <label class="btn btn-sm rounded btn-light" for="izin<?= $id ?>">
                                            Izin
                                        </label>

                                        <input
                                            class="btn-check btn-check-hadir"
                                            type="radio"
                                            id="sakit<?= $id ?>"
                                            name="status[<?= $id ?>]"
                                            value="3"
                                            <?= ($row['status'] == 3) ? 'checked' : '' ?>>

                                        <label class="btn btn-sm rounded btn-light" for="sakit<?= $id ?>">
                                            Sakit
                                        </label>

                                        <input
                                            class="btn-check btn-check-hadir"
                                            type="radio"
                                            id="alpha<?= $id ?>"
                                            name="status[<?= $id ?>]"
                                            value="4"
                                            <?= ($row['status'] == 4) ? 'checked' : '' ?>>

                                        <label class="btn btn-sm rounded btn-light" for="alpha<?= $id ?>">
                                            Alpha
                                        </label>

                                    </div>

                                </td>

                                <td>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="keterangan[<?= $id ?>]"
                                        value="<?= esc($row['absensi_keterangan']) ?>"
                                        placeholder="Remarks">

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Attendance
                    </button>

                </div>

            <?php endif; ?>

        </form>

    </div>
</div>

<?= $this->endSection() ?>