<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 mb-0">Class Management</h6>
            <a href="<?= base_url('admin/classes/create') ?>"
               class="btn btn-sm btn-outline-light me-3">
               + Add Class
            </a>
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

        <div class="table-responsive">
            <table id="classTable" class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th width="120">Action</th>
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
    $('#classTable').DataTable({
        processing: true,
        serverSide: true,
        pagingType: 'simple',
        language: {
            paginate: {
                previous: '<i class="material-symbols-rounded">chevron_left</i>',
                next: '<i class="material-symbols-rounded">chevron_right</i>'
            }
        },
        ajax: {
            url: "<?= base_url('admin/classes/datatable') ?>",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: 'class_name' },
            { data: 'description' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('admin/classes/edit') ?>/${data}"
                           class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('admin/classes/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this class?')">
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
