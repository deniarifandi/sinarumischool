<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 mb-0">Subject Management</h6>
            <a href="<?= base_url('admin/subjects/create') ?>"
               class="btn btn-sm btn-outline-light me-3">
                + Add Subject
            </a>
        </div>
    </div>

    <div class="card-body">

        <!-- FLASH SUCCESS -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- FLASH ERROR -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="subjectTable" class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Description</th>
                        <th width="160">Action</th>
                        <th width="110">Chapter</th>
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
    $('#subjectTable').DataTable({
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
            url: "<?= base_url('admin/subjects/datatable') ?>",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: 'subject_code' },
            { data: 'subject_name' },
            { data: 'description' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('admin/subjects/edit') ?>/${data}"
                           class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('admin/subjects/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete subject?')">
                           Delete
                        </a>
                    `;
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('admin/chapters') ?>/${data}"
                           class="btn btn-sm btn-info">
                           Open
                        </a>
                    `;
                }
            }
        ]
    });
});
</script>

<?= $this->endSection() ?>
