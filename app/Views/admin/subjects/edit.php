<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white text-capitalize ps-3 mb-0">
                Edit Subject
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

        <form action="<?= base_url('admin/subjects/update/' . $subject['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <!-- Division -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Division</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">-- Select Division --</option>
                        <?php foreach ($divisions as $d): ?>
                            <option value="<?= $d['id'] ?>"
                                <?= old('division_id', $subject['division_id']) == $d['id'] ? 'selected' : '' ?>>
                                <?= esc($d['division_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Subject Code -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Subject Code</label>
                    <input
                        type="text"
                        name="subject_code"
                        class="form-control"
                        value="<?= old('subject_code', $subject['subject_code']) ?>"
                        required
                    >
                </div>

                <!-- Subject Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Subject Name</label>
                    <input
                        type="text"
                        name="subject_name"
                        class="form-control"
                        value="<?= old('subject_name', $subject['subject_name']) ?>"
                        required
                    >
                </div>

                <!-- Description -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                    ><?= old('description', $subject['description']) ?></textarea>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/subjects') ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Subject
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
