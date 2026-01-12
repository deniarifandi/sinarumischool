<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white ps-3 mb-0">
                Teaching Journal
            </h6>

            <a href="<?= base_url('journal/create') ?>"
               class="btn btn-sm btn-outline-light me-3">
                + Add Journal
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
            <table id="journalTable" class="table table-sm table-hover align-items-center">
                <thead>
                    <tr>
                        <th width="120">Date</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Activities</th>
                        <th>TH Spend</th>
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
    $('#journalTable').DataTable({
        processing: true,
        serverSide: true,
        pagingType: 'simple',
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: '<i class="material-symbols-rounded">chevron_left</i>',
                next: '<i class="material-symbols-rounded">chevron_right</i>'
            }
        },
        ajax: {
            url: "<?= base_url('journal/datatable') ?>",
            type: "POST",
            data: function (d) {
                d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
            }
        },
        columns: [
            { data: 'date' },
            { data: 'class_name' },
            { data: 'subject_name' },
            {
                data: 'activities',
                render: function (data) {
                    if (!data) return '';
                    return data.length > 80
                        ? data.substring(0, 80) + '…'
                        : data;
                }
            },
             { data: 'jpspend'},
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `
                        <a href="<?= base_url('journal/edit') ?>/${data}"
                           class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('journal/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this journal entry?')">
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
