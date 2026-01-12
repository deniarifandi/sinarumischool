<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
     <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Edit Chapters of: 
                <span class="badge bg-light text-dark ms-2">
                    <?= esc($chapter['subject_name']) ?>
                </span>
            </h6>

        </div>
    </div>

    <div class="card-body">

        <!-- FLASH ERROR -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- VALIDATION ERRORS -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/chapters/update/' . $chapter['id']) ?>" method="post">
            <?= csrf_field() ?>

            <?php
$selectedGrade = old('grade', $chapter['grade']);
?>

            <div class="row">

                <!-- Order Number -->
               <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Grade</label>
                <select name="grade" class="form-select" required>
                    <option value="">-- Select Grade --</option>

                    <optgroup label="PGK">
                        <option value="PG-1" <?= $selectedGrade === 'PG-1' ? 'selected' : '' ?>>PG-1</option>
                        <option value="PG-2" <?= $selectedGrade === 'PG-2' ? 'selected' : '' ?>>PG-2</option>
                        <option value="K-1"  <?= $selectedGrade === 'K-1'  ? 'selected' : '' ?>>K-1</option>
                        <option value="K-2"  <?= $selectedGrade === 'K-2'  ? 'selected' : '' ?>>K-2</option>
                    </optgroup>

                    <optgroup label="PRIMARY">
                        <option value="P1" <?= $selectedGrade === 'P1' ? 'selected' : '' ?>>P1</option>
                        <option value="P2" <?= $selectedGrade === 'P2' ? 'selected' : '' ?>>P2</option>
                        <option value="P3" <?= $selectedGrade === 'P3' ? 'selected' : '' ?>>P3</option>
                        <option value="P4" <?= $selectedGrade === 'P4' ? 'selected' : '' ?>>P4</option>
                        <option value="P5" <?= $selectedGrade === 'P5' ? 'selected' : '' ?>>P5</option>
                        <option value="P6" <?= $selectedGrade === 'P6' ? 'selected' : '' ?>>P6</option>
                    </optgroup>

                    <optgroup label="SECONDARY">
                        <option value="SECONDARY 7" <?= $selectedGrade === 'SECONDARY 7' ? 'selected' : '' ?>>SECONDARY 7</option>
                        <option value="SECONDARY 8" <?= $selectedGrade === 'SECONDARY 8' ? 'selected' : '' ?>>SECONDARY 8</option>
                        <option value="SECONDARY 9" <?= $selectedGrade === 'SECONDARY 9' ? 'selected' : '' ?>>SECONDARY 9</option>
                    </optgroup>

                    <optgroup label="COLLEGE">
                        <option value="COLLEGE 10" <?= $selectedGrade === 'COLLEGE 10' ? 'selected' : '' ?>>COLLEGE 10</option>
                        <option value="COLLEGE 11" <?= $selectedGrade === 'COLLEGE 11' ? 'selected' : '' ?>>COLLEGE 11</option>
                        <option value="COLLEGE 12" <?= $selectedGrade === 'COLLEGE 12' ? 'selected' : '' ?>>COLLEGE 12</option>
                    </optgroup>
                </select>
            </div>


                <!-- Chapter Code -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Chapter Code</label>
                    <input
                        type="text"
                        name="chapter_code"
                        class="form-control"
                        value="<?= old('chapter_code', $chapter['chapter_code']) ?>"
                    >
                </div>

                <!-- Chapter Name -->
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Chapter Name</label>
                    <input
                        type="text"
                        name="chapter_name"
                        class="form-control"
                        value="<?= old('chapter_name', $chapter['chapter_name']) ?>"
                        required
                    >
                </div>
                  <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Teaching Hours</label>
                    <input
                        type="text"
                        name="jp"
                        class="form-control"
                       value="<?= old('jp', $chapter['jp']) ?>"
                    >
                </div>

                <!-- Description -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                    ><?= old('description', $chapter['description']) ?></textarea>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/chapters/' . $chapter['subject_id']) ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Chapter
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
