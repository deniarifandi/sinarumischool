<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Rekap Management</h5>

        </div>
        <a href="<?= base_url('rekap/create') ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Setting
        </a>
    </div>

    <div class="glass-card">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

    <div>
        <h5 class="mb-2">Print Rekap</h5>

        <form method="get" action="<?= base_url('rekap/print') ?>" class="d-flex gap-2 align-items-center flex-wrap">

            <!-- Division Dropdown -->
            <select name="division_id"
                    class="form-select form-select-sm bg-white text-dark border-secondary"
                    style="width:200px">
                <option value="">- All Divisions -</option>

                <?php foreach ($divisions as $d): ?>
                    <option value="<?= $d['id'] ?>"
                        <?= request()->getGet('division_id') == $d['id'] ? 'selected' : '' ?>>
                        <?= esc($d['division_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>

          <?php
$dateStart = request()->getGet('date_start') 
    ?? date('Y-m-21', strtotime('-1 month'));

$dateEnd = request()->getGet('date_end') 
    ?? date('Y-m-20');
?>

            <!-- Date Start -->
      <input type="date"
       name="date_start"
       value="<?= esc($dateStart) ?>"
       class="form-control form-control-sm bg-white text-dark border-secondary">

<input type="date"
       name="date_end"
       value="<?= esc($dateEnd) ?>"
       class="form-control form-control-sm bg-white text-dark border-secondary">

            <!-- Print Button -->
            <button type="submit"
                    class="btn btn-success btn-sm rounded-pill px-3">
                <i class="bi bi-printer me-1"></i> Print Report
            </button>

        </form>
    </div>

</div>

</div>


    <?php if (empty($rekaps)): ?>
        <div class="text-center py-5">
            <i class="bi bi-table display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No rekap data found.</p>
        </div>
    <?php else: ?>

        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;">

            <table class="table align-middle mb-0" id="rekapTable">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Division</th>
                        <th>User Group</th>
                        <th>Group Sort</th>
                        <th>User Role</th>
                        <th>Role Sort</th>
                        <th>User ID</th>
                        <th>Nullified</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($rekaps as $r): ?>
                    <tr>

                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($r['id']) ?>
                            </span>
                        </td>

                        <td><?= esc($r['division_name']) ?></td>

                        <td>
                            <span class="fw-bold text-dark">
                                <?= esc($r['user_group']) ?>
                            </span>
                        </td>

                        <td><?= esc($r['group_sort']) ?></td>

                        <td><?= esc($r['user_role']) ?></td>

                        <td><?= esc($r['role_sort']) ?></td>

                        <td><?= esc($r['name']) ?></td>

                        <td>
                            <?php if ($r['nullified'] == 0): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nullified</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-3">

                            <a href="<?= base_url('rekap/edit/'.$r['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                        </td>

                    </tr>
                <?php endforeach ?>
                </tbody>

            </table>
        </div>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
$(function () {
    $('#rekapTable').DataTable({
        pageLength: 10,
        searching: true,
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