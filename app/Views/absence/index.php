<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Student Absence</h5>

        <a href="<?= base_url('absence/create') ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Absence
        </a>
    </div>

    <?php if (empty($absences)): ?>
        <div class="text-center py-5">
            <p class="text-muted">No absence data.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student ID</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($absences as $a): ?>
                    <tr>
                        <td><?= esc($a['tanggal']) ?></td>
                        <td><?= esc($a['murid_id']) ?></td>
                        <td><?= esc($a['status']) ?></td>
                        <td><?= esc($a['absensi_keterangan'] ?: '-') ?></td>
                        <td class="text-end">
                            <a href="<?= base_url('absence/edit/'.$a['absensi_id']) ?>"
                               class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <form action="<?= base_url('absence/delete/'.$a['absensi_id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        onclick="return confirm('Delete?')"
                                        class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
