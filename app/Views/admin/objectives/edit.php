<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Edit Lesson Objective
            </h6>
        </div>
    </div>

    <div class="card-body">

        <form action="<?= base_url('admin/objectives/update/'.$objective['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Order Number</label>
                    <input
                        type="number"
                        name="order_number"
                        class="form-control"
                        value="<?= $objective['order_number'] ?>"
                        min="1"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Objective Code</label>
                    <input
                        type="text"
                        name="objective_code"
                        class="form-control"
                        value="<?= esc($objective['objective_code']) ?>"
                    >
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Objective Description</label>
                    <textarea
                        name="objective_text"
                        class="form-control"
                        rows="3"
                        required
                    ><?= esc($objective['objective_text']) ?></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/objectives/'.$objective['subchapter_id']) ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update Objective
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
