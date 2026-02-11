<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h5 class="mb-0">Grade Management</h5>
      <small class="text-white-50">Division :  <?= esc($division[0]['division_name']) ?></small>
    </div>

    <a href="<?= base_url('grade/create?divisi='.$division[0]['id']) ?>"
       class="btn btn-primary rounded-pill px-3">
      <i class="bi bi-plus-lg me-1"></i> Add Grade
    </a>
  </div>

  <?php if (empty($grades)): ?>
    <div class="text-center py-5">
      <i class="bi bi-layers display-5 text-white-50"></i>
      <p class="mt-3 text-muted">No grades found.</p>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table glass-table align-middle">
        <thead>
          <tr>
            <th class="ps-3">Grade</th>
            <th>Order</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($grades as $g): ?>
            <tr>
              <td class="ps-3 fw-semibold text-dark">
                <?= esc($g['grade_name']) ?>
              </td>
              <td>
                <span class="badge bg-primary bg-opacity-25 text-primary">
                  <?= esc($g['sort_order']) ?>
                </span>
              </td>
              <td class="text-end pe-3">
                <a href="<?= base_url('grade/edit/'.$g['id'].'?divisi='.$division[0]['id']) ?>"
                   class="btn btn-sm btn-glass-edit">
                  <i class="bi bi-pencil-square"></i>
                </a>

                <form action="<?= base_url('grade/delete/'.$g['id']) ?>"
                      method="post"
                      class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="divisi" value="<?= $division[0]['id'] ?>">
                  <button type="submit"
                          onclick="return confirm('Delete this grade?')"
                          class="btn btn-sm btn-outline-danger ms-1"
                          style="border-color: rgba(220,53,69,.3)">
                    <i class="bi bi-trash"></i>
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
