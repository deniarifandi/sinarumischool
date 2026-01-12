<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>


<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 mb-0">Dashboard</h6>
           
     </div>
 </div>
 <div class="card-body">

    <div class="row">

        <!-- Users -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/users') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">group</i>
                    <h6 class="text-dark">User Management</h6>
                </div>
            </a>
        </div>

        <!-- Classes -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/classes') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">school</i>
                    <h6 class="text-dark">Class Management</h6>
                </div>
            </a>
        </div>

        <!-- Students -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/students') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">face</i>
                    <h6 class="text-dark">Student Management</h6>
                </div>
            </a>
        </div>

        <!-- Subjects -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/subjects') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">menu_book</i>
                    <h6 class="text-dark">Subjects Management</h6>
                </div>
            </a>
        </div>

        <!-- Divisions -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/divisions') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">account_tree</i>
                    <h6 class="text-dark">Divisions Management</h6>
                </div>
            </a>
        </div>

        <!-- Teaching Journal -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('journal') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">edit_document</i>
                    <h6 class="text-dark">Teaching Journal</h6>
                </div>
            </a>
        </div>

         <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('admin/report') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">grading</i>
                    <h6 class="text-dark">Teaching Journal Repont</h6>
                </div>
            </a>
        </div>

        <!-- Gradebook -->
        <div class="col-xl-3 col-sm-6 mb-4">
            <a href="<?= base_url('gradebook') ?>" class="text-decoration-none">
                <div class="card card-hover text-center p-4">
                    <i class="material-symbols-rounded fs-1 text-dark mb-2">grading</i>
                    <h6 class="text-dark">Gradebook</h6>
                </div>
            </a>
        </div>

    </div>


</div>
</div>


</div>

<?= $this->endSection() ?>


