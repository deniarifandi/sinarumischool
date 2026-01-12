<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white ps-3 mb-0">
                Lesson Objectives
                <span class="opacity-7 fw-normal">
                    / <?= esc($subject['subject_name']) ?>
                    / <?= esc($chapter['chapter_name']) ?>
                    / <?= esc($sub['sub_name']) ?>
                </span>
            </h6>

            <a href="<?= base_url('admin/objectives/create/'.$sub['id']) ?>"
               class="btn btn-sm btn-outline-light me-3">
                + Add Objective
            </a>
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="objectiveTable" class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th width="70">Order</th>
                        <th width="150">Code</th>
                        <th>Objective</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
$(function () {
    $('#objectiveTable').DataTable({
        processing: true,
        serverSide: true,
        pagingType: 'simple',
        order: [[0, 'asc']],
        language: {
            paginate: {
                previous: '<i class="material-symbols-rounded">chevron_left</i>',
                next: '<i class="material-symbols-rounded">chevron_right</i>'
            }
        },
        ajax: {
            url: "<?= base_url('admin/objectives/datatable/'.$sub['id']) ?>",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: 'order_number' },
            { data: 'objective_code' },
            { data: 'objective_text' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('admin/objectives/edit') ?>/${data}"
                           class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('admin/objectives/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete objective?')">
                           Delete
                        </a>
                    `;
                }
            }
        ]
    });
});
</script>

<?= $this->endSection() ?>
