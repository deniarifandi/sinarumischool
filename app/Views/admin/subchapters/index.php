<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white ps-3 mb-0">
                Sub-Chapters
                <span class="opacity-7 fw-normal">
                    / <?= esc($chapter['chapter_name']) ?>
                    / <?= esc($chapter['subject_name']) ?>
                </span>
            </h6>

            <a href="<?= base_url('admin/subchapters/create/' . $chapter['id']) ?>"
               class="btn btn-sm btn-outline-light me-3">
                + Add Sub-Chapter
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
            <table id="subChapterTable" class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th width="70">Order</th>
                        <th width="120">Code</th>
                        <th>Sub-Chapter</th>
                        <th>Description</th>
                        <th width="140">Action</th>
                        <th width="120">Objective</th>
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
    $('#subChapterTable').DataTable({
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
            url: "<?= base_url('admin/subchapters/datatable/' . $chapter['id']) ?>",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: 'order_number' },
            { data: 'sub_code' },
            { data: 'sub_name' },
            { data: 'description' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('admin/subchapters/edit') ?>/${data}"
                           class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('admin/subchapters/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete sub-chapter?')">
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
                        <a href="<?= base_url('admin/objectives') ?>/${data}"
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
