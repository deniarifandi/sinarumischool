<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>


<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
        <h6 class="text-white text-capitalize ps-3 mb-0">Users table</h6>
         <a href="<?php echo base_url() ?>admin/users/create" class="btn btn-primary btn-sm float-right mx-3">Add Users</a>
    </div>
 </div>
 <div class="card-body">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table id="userTable" class="table table-sm table-hover align-items-center">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>NIP</th>
                    <th>KKB</th>
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
        $('#userTable').DataTable({
         
            language: {
                paginate: {
                    previous: '<i class="material-symbols-rounded">chevron_left</i>',
                    next: '<i class="material-symbols-rounded">chevron_right</i>'
                }
            },
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: {
                url: "<?= base_url('admin/users/datatable') ?>",
                type: "POST",
                data: function (d) {
                    d['<?= csrf_token() ?>'] = '<?= csrf_hash() ?>';
                }
            },
            columns: [
                { data: 'username' },
                { data: 'name' },
                { data: 'nip' },
                { data: 'kkb' },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return `
                        <a href="<?= base_url('admin/users/edit') ?>/${data}" class="btn btn-sm btn-primary">Edit</a>
                        |
                        <a href="<?= base_url('admin/users/delete') ?>/${data}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete user?')">
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