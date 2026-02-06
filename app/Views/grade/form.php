<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($grade);
$action = $isEdit
    ? base_url('grade/save/'.$grade['id'])
    : base_url('grade/save');
$title  = $isEdit ? 'Edit Grade' : 'Add Grade';
?>

<div class="glass-card" style="max-width:520px">
  <h5 class="mb-4"><?= $title ?></h5>

  <?php if (session('error')): ?>
    <div class="alert alert-danger bg-transparent border border-danger">
      <?= session('error') ?>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="division_id" value="<?= esc($divisiId) ?>">

    <div class="mb-3">
      <label class="form-label text-white-50">Grade Name</label>
      <input type="text"
             name="grade_name"
             value="<?= old('grade_name', $grade['grade_name'] ?? '') ?>"
             class="form-control bg-white text-dark border-secondary"
             placeholder="e.g. PG 1, KG 1, Primary 1, Secondary 1, etc."
             required>
    </div>

    <div class="mb-4">
      <label class="form-label text-white-50">Sort Order (Placement)</label>
      <input type="number"
             name="sort_order"
             value="<?= old('sort_order', $grade['sort_order'] ?? 0) ?>"
             class="form-control bg-white text-dark border-secondary">
    </div>

    <div class="d-flex justify-content-between">
      <a href="<?= base_url('grade?divisi='.$divisiId) ?>"
         class="btn btn-outline-light rounded-pill">
        Cancel
      </a>

      <button type="submit"
              class="btn btn-primary rounded-pill px-4">
        <?= $isEdit ? 'Update Grade' : 'Save Grade' ?>
      </button>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
