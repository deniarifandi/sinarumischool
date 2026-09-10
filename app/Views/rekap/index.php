<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Rekap Management</h5>

        </div>
        <a href="<?= base_url('rekap/create') ?>"
           class="btn btn-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Add Setting
        </a>
    </div>

    <div class="glass-card">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

    <div>
        <h5 class="mb-2">Print Rekap</h5>

        <form method="get" class="d-flex gap-2 align-items-center flex-wrap">

<?php
$dateStart = request()->getGet('date_start') 
    ?? date('Y-m-21', strtotime('-1 month'));

$dateEnd = request()->getGet('date_end') 
    ?? date('Y-m-20');
?>

    <!-- Date Start -->
    <input type="date"
           name="date_start"
           value="<?= esc($dateStart) ?>"
           class="form-control form-control-sm bg-white text-dark border-secondary">

    <input type="date"
           name="date_end"
           value="<?= esc($dateEnd) ?>"
           class="form-control form-control-sm bg-white text-dark border-secondary">

    <!-- Print 501 -->


    <!-- Print TU -->
    <button type="submit"
            formaction="<?= base_url('rekap/printcomplete') ?>"
            class="btn btn-success btn-sm rounded-pill px-3">
        <i class="bi bi-printer me-1"></i> Print TU
    </button>

</form>
    </div>

</div>

</div>

</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<?= $this->endSection() ?>