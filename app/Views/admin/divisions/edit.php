<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Edit Division
                <span class="opacity-7 fw-normal">
                    / <?= esc($division['division_name']) ?>
                </span>
            </h6>
        </div>
    </div>

    <div class="card-body">

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

        <form action="<?= base_url('admin/divisions/update/'.$division['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Division Name</label>
                    <input
                        type="text"
                        name="division_name"
                        class="form-control"
                        value="<?= old('division_name', $division['division_name']) ?>"
                        required
                    >
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                    ><?= old('description', $division['description']) ?></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/divisions') ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Division
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
