<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Add Lesson Objective
            </h6>
        </div>
    </div>

    <div class="card-body">

        <form action="<?= base_url('admin/objectives/store') ?>" method="post">
            <?= csrf_field() ?>

            <input type="hidden" name="subchapter_id" value="<?= $subchapter_id ?>">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Order Number</label>
                    <input type="number" name="order_number" class="form-control" min="1">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Objective Code</label>
                    <input type="text" name="objective_code" class="form-control">
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Objective Description</label>
                    <textarea
                        name="objective_text"
                        class="form-control"
                        rows="3"
                        required
                    ></textarea>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/objectives/'.$subchapter_id) ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Save Objective
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
