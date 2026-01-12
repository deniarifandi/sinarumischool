<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Edit Sub-Chapter
                <span class="opacity-7 fw-normal">
                    / <?= esc($sub['sub_name']) ?>
                </span>
            </h6>
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/subchapters/update/' . $sub['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <!-- Order Number -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Order Number</label>
                    <input
                        type="number"
                        name="order_number"
                        class="form-control"
                        value="<?= old('order_number', $sub['order_number']) ?>"
                        min="1"
                    >
                </div>

                <!-- Sub-Chapter Code -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Sub-Chapter Code</label>
                    <input
                        type="text"
                        name="sub_code"
                        class="form-control"
                        value="<?= old('sub_code', $sub['sub_code']) ?>"
                    >
                </div>

                <!-- Sub-Chapter Name -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Sub-Chapter Name</label>
                    <input
                        type="text"
                        name="sub_name"
                        class="form-control"
                        value="<?= old('sub_name', $sub['sub_name']) ?>"
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
                    ><?= old('description', $sub['description']) ?></textarea>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/subchapters/' . $sub['chapter_id']) ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Sub-Chapter
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
