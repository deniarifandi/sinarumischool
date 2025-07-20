<?php 
  echo view('layouts/header.php');
  echo view('layouts/sidebar.php');
?>

<!--begin::App Main-->
<main class="app-main">

  <!--begin::App Content Header-->
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">
            <img src="<?= base_url() ?>assets/img/class.svg" style="max-width: 35px;"> 
            Dashboard <?= $data->kelompok_nama ?> <?= session()->get('gurudivisi_id') ?>
          </h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>

        <?php if ($presence < 1): ?>
        <div class="alert alert-danger mt-3" role="alert">
          You haven’t submitted your attendance yet. 
          <a href="<?= base_url() ?>showstatus?id=<?= $nama ?>">Click to submit now</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <div class="container-fluid">

      <!-- Section: Human Resource -->
      <div class="mb-3"><h4>📁 Human Resource</h4></div>

      <div class="row">
        <?php if (session()->get('guru_id') == 0): ?>
          <?= card('Personel', 'Personel Manager', 'Personel', 'bi-person-badge', '#82adf3') ?>
          <?= card('Division', 'Division Manager', 'Divisi', 'bi-diagram-3', '#b2dfdb') ?>
          <?= card('Role', 'Role Manager', 'Jabatan', 'bi-shield-lock', '#fdd835') ?>
          <?= card('Personel Division', '', 'Gurudivisi', 'bi-diagram-3', '#aed581') ?>
          <?= card('Personel Role', '', 'Gurujabatan', 'bi-person-lines-fill', '#90caf9') ?>
         
        <?php endif ?>
        
        <?= card('Attendance Form', '', 'showform', 'bi-journal-text', '#edab86') ?>
        <?= card('Attendance List', '', 'Presensidata', 'bi-geo-alt', '#d6d9dd') ?>

        <?php if (session()->get('guru_id') == 0): ?>
         
        <?= card('Attendance Report', '', 'presensidatafront', 'bi-person-check', '#c3e6cb') ?>
        <?php endif ?>
      </div>

      
      <!-- Section: School Management -->
      <?php if (session()->get('guru_id') == 0 || session()->get('divisi_id') == 3 ): ?>
      <div class="mt-5 mb-3"><h4>🏫 School Management</h4></div>
      <div class="row">
        <?= card('Class', 'Class Manager', 'Kelompok', 'bi-people', '#fdfe9c') ?>
        <?= card('Student', 'Student Manager', 'Murid', 'bi-mortarboard', '#c5f1dc') ?>
        <?= card('Subject', 'Manage Subject', 'Subjek', 'bi-folder', '#adfbcf') ?>
        <?= card('Activity Type', 'Manage Activity', 'Tipeaktifitas', 'bi-check-circle', '#e9b5fb') ?>
      </div>


      <!-- Section: Class Management -->
      <div class="mt-5 mb-3"><h4>🧑‍🏫 Class Management</h4></div>
      <div class="row">
        <?= card('Absensi', 'Student Absence List', 'absensi', 'bi-person-check', '#ffc5e5') ?>
      </div>

      <!-- Section: Subject Management -->
      <div class="mt-5 mb-3"><h4>📖 Subject Management</h4></div>
      <div class="row">
        <?= card('Chapter', 'Manage Chapter', 'Unit', 'bi-file-earmark-text', '#a9b9f1') ?>
        <?= card('Sub-Chapter', 'Manage Sub-Chapter', 'Subunit', 'bi-file-earmark-text', '#ffb8b8') ?>
        <?= card('Lesson Objective', 'Manage LO', 'Tujuan', 'bi-flag', '#6de9b2') ?>
        <?= card('Daily Activity', 'Manage LO', 'Aktifitas', 'bi-calendar-check', '#a2e4fb') ?>
      </div>
       <?php endif ?>
      

    </div>
  </div>
  <!--end::App Content-->

</main>
<!--end::App Main-->

<?php 
  echo view('layouts/footer.php');
?>

<?php
function card($title, $subtitle, $url, $icon, $bgColor) {
  return '
    <div class="col-md-3 mb-4">
      <a href="' . base_url($url) . '" class="text-decoration-none">
        <div class="info-box">
          <span class="info-box-icon shadow-sm" style="background-color:' . $bgColor . '!important">
            <i class="bi ' . $icon . '" style="color: #524f4f;"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">' . $title . '</span>
            <span class="info-box-number"><small>' . $subtitle . '</small></span>
          </div>
        </div>
      </a>
    </div>
  ';
}
?>