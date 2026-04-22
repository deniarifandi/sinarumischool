<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Attendance List</h5>
    </div>

    <div class="glass-card mb-3">

        <form method="get" action="<?= base_url('attendance') ?>" 
              class="d-flex flex-wrap gap-2 align-items-center">

            <input type="date" name="date"
                   value="<?= esc($filters['date'] ?? '') ?>"
                   class="form-control form-control-sm"
                   style="width:160px">

            <input type="date" name="start"
                   value="<?= esc($filters['start'] ?? '') ?>"
                   class="form-control form-control-sm"
                   style="width:160px">

            <input type="date" name="end"
                   value="<?= esc($filters['end'] ?? '') ?>"
                   class="form-control form-control-sm"
                   style="width:160px">

            <select name="status" class="form-select form-select-sm" style="width:140px">
                <option value="">- Status -</option>
                <option value="1" <?= ($filters['status'] ?? '') == 1 ? 'selected' : '' ?>>Masuk</option>
                <option value="2" <?= ($filters['status'] ?? '') == 2 ? 'selected' : '' ?>>Izin</option>
                <option value="3" <?= ($filters['status'] ?? '') == 3 ? 'selected' : '' ?>>Sakit</option>
            </select>

            <input type="text" name="search"
                   value="<?= esc($filters['search'] ?? '') ?>"
                   placeholder="Search..."
                   class="form-control form-control-sm"
                   style="width:200px">

            <button class="btn btn-primary btn-sm px-3">
                <i class="bi bi-funnel"></i> Filter
            </button>

        </form>

    </div>

    <?php if (empty($data)): ?>
        <div class="text-center py-5">
            <p class="text-muted">No attendance data found.</p>
        </div>
    <?php else: ?>

        <div class="table-responsive" style="border-radius:12px;overflow:hidden;">
            <table class="table align-middle mb-0" id="attendanceTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?= esc($row['presensidata_id']) ?></td>
                        <td><?= esc($row['presensidata_tanggal']) ?></td>
                        <td>
                            <?php if ($row['status'] == 1): ?>
                                <span class="badge bg-success">Masuk</span>
                            <?php elseif ($row['status'] == 2): ?>
                                <span class="badge bg-warning">Izin</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Sakit</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($row['latitude']) ?></td>
                        <td><?= esc($row['longitude']) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?= $pager->links() ?>
        </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
$(function () {
    $('#attendanceTable').DataTable({
        pageLength: 10,
        searching: false,
        ordering: true,
        language: {
            paginate: {
                previous: '‹',
                next: '›'
            }
        }
    });
});
</script>
<?= $this->endSection() ?>