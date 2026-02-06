<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($class);
$action = $isEdit
    ? base_url('class/save/'.$class['id'])
    : base_url('class/save');

$title = $isEdit ? 'Edit Class' : 'Add Class';
?>

<div class="glass-card" style="max-width:600px">
    <h5 class="mb-4"><?= $title ?></h5>

    <?php if (session('error')): ?>
        <div class="alert alert-danger bg-transparent border border-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="division_id" value="<?= esc($divisiId) ?>">

        <!-- Grade -->
        <div class="mb-3">
            <label class="form-label text-white-50">Grade</label>
          <?php
                    $selectedGrade = old('grade') ?? ($class['grade'] ?? '');
            ?>

            <select name="grade"
                    class="form-select bg-white text-dark border-secondary"
                    required>
                <option value="">-- Select Grade --</option>

                <?php foreach ($grades as $g): ?>
                    <option value="<?= esc($g['id']) ?>"
                        <?= (string)$selectedGrade === (string)$g['id'] ? 'selected' : '' ?>>
                        <?= esc($g['grade_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>

        </div>

        <!-- Class Name -->
        <div class="mb-3">
            <label class="form-label text-white-50">Class Name</label>
            <input type="text"
                   name="class_name"
                   value="<?= old('class_name', $class['class_name'] ?? '') ?>"
                   class="form-control bg-white text-dark border-secondary"
                   required>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="form-label text-white-50">Description</label>
            <textarea name="description"
                      rows="3"
                      class="form-control bg-white text-dark border-secondary"><?= old('description', $class['description'] ?? '') ?></textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= base_url('class?divisi='.$divisiId) ?>"
               class="btn btn-outline-light rounded-pill">
                Cancel
            </a>

            <button type="submit"
                    class="btn btn-primary rounded-pill px-4">
                <?= $isEdit ? 'Update Class' : 'Save Class' ?>
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
