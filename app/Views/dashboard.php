<?php 
  echo view('layouts/header.php');

// get multi-divisi list from session
$divisiList = session()->get('divisi_list') ?? [];  
$divisiIds = array_column($divisiList, 'id'); 

$role = session()->get('guru_role');
$guruId = session()->get('guru_id');

// helpers for access control
function canAccessDivisi($list, $allowed) {
    return count(array_intersect($list, $allowed)) > 0;
}
?>

<style>
  body {
    background: #f3f4f6;
  }

  /* SECTION WRAPPER */
  .section-block, .hr-section-block {
    background: #ffffff;
    border-radius: 22px;
    padding: 25px 28px;
    
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }

  /* HEADER AREA */
  .section-title-main {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #2f2f2f;
  }

  /* SUBSECTION */
  .subsection-title {
    font-size: 17px;
    font-weight: 600;
    margin-top: 25px;
    margin-bottom: 14px;
    color: #4a4a4a;
    padding-left: 4px;
  }

  /* CARD */
  .card-soft {
    border-radius: 18px;
    padding: 22px;
    transition: all .2s ease;
    cursor: pointer;
    text-align: center;
  }

  .card-soft:hover {
    transform: translateY(-4px);
  }

  .card-title-soft {
    font-size: 16px;
    font-weight: 600;
    color: #333;
  }

  .card-subtitle-soft {
    font-size: 13px;
    color: #777;
  }

  .card-icon-wrapper {
    width: 85px;
    height: 85px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px auto;
    font-size: 34px;
    color: #2f2f2f;
    background: linear-gradient(145deg, var(--card-color-strong), var(--card-color-soft));
    box-shadow:
      0 4px 8px rgba(0,0,0,0.12),
      inset 0 2px 4px rgba(255,255,255,0.5),
      inset 0 -2px 3px rgba(0,0,0,0.08);
    transition: all .2s ease;
  }

  .card-soft:hover .card-icon-wrapper {
    transform: translateY(-3px) scale(1.06);
    box-shadow:
      0 7px 16px rgba(0,0,0,0.18),
      inset 0 3px 6px rgba(255,255,255,0.65),
      inset 0 -3px 5px rgba(0,0,0,0.1);
  }

  .row.gap-10 > [class*='col'] {
    padding-left: 8px;
    padding-right: 8px;
  }
</style>


<main class="app-main">


    <div class="container-fluid">
      <div class="row">
        <?php if ($presence < 1): ?>
        <div class="alert alert-danger" role="alert">
          You haven’t submitted your attendance yet. 
          <a href="<?= base_url() ?>showstatus?id=<?= $nama ?>">Click to submit now</a>
        </div>
        <?php endif; ?>

      </div>
    </div>



  <div class="app-content">
    <div class="container-fluid">

      <!-- HR BLOCK -->
      <div class="hr-section-block">

        <div class="section-title-main">📁 Human Resource</div>

        <div class="row gap-10">

          <?php if ($guruId == 0): ?>
            <?= card('Personel', 'Personel Manager', 'Personel', 'bi-person-badge', '#82adf3') ?>
            <?= card('Division List', 'Division Manager', 'Divisi', 'bi-diagram-3', '#b2dfdb') ?>
            <?= card('Role List', 'Role Manager', 'Jabatan', 'bi-shield-lock', '#fdd835') ?>
            <?= card('Personel Division', '', 'Gurudivisi', 'bi-diagram-3', '#aed581') ?>
            <?= card('Personel Role', '', 'Gurujabatan', 'bi-person-lines-fill', '#90caf9') ?>
            <?= card('Injector', '', 'lupaabsen', 'bi-clock-history', '#d6d9dd') ?>
          <?php endif ?>

          <?= card('Attendance Form', '', 'showform', 'bi-journal-text', '#edab86') ?>
          <?= card('Attendance List', '', 'Presensidata', 'bi-geo-alt', '#d6d9dd') ?>

          <?php if ($guruId == 0): ?>
            <?= card('Attendance Report', '', 'presensidatafront', 'bi-person-check', '#c3e6cb') ?>
          <?php endif ?>

        </div>
      </div>




      <!-- DIVISION BLOCKS -->
      <?php foreach ($divisiList as $div): ?>

      <div class="section-block">

        <!-- MAIN SECTION -->
        <div class="section-title-main">
          🏫 School <?= $div['nama'] ?>
        </div>

        <div class="row gap-10 mb-3">
          <?= card('School Dashboard', 'Overview', 'school/dashboard?divisi=' . $div['id'], 'bi-building-check', '#ffe8b3') ?>
          <?= card('Schedule', 'View Schedule', 'school/schedule?divisi=' . $div['id'], 'bi-calendar-event', '#d9f3ff') ?>
        </div>


        <!-- SUBSECTION: CLASS MGMT -->
        <div class="subsection-title">🧑‍🏫 Class Management</div>

        <div class="row gap-10">
          <?= card('Class', 'Class Manager', 'Kelompok?divisi=' . $div['id'], 'bi-people', '#fdfe9c') ?>
          <?= card('Student', 'Student Manager', 'Murid?divisi=' . $div['id'], 'bi-mortarboard', '#c5f1dc') ?>
          <?= card('Activity Type', 'Manage Activity', 'Tipeaktifitas?divisi=' . $div['id'], 'bi-check-circle', '#e9b5fb') ?>
        </div>

        <!-- SUBSECTION: SUBJECT MGMT -->
        <div class="subsection-title">📖 Subject Management</div>

        <div class="row gap-10">
          <?= card('Subject', 'Manage Subject', 'Subjek?divisi=' . $div['id'], 'bi-folder', '#adfbcf') ?>
          <?= card('Chapter', 'Manage Chapter', 'Unit?divisi=' . $div['id'], 'bi-file-earmark-text', '#a9b9f1') ?>
          <?= card('Sub-Chapter', 'Manage Sub-Chapter', 'Subunit?divisi=' . $div['id'], 'bi-file-earmark-text', '#ffb8b8') ?>
          <?= card('Lesson Objective', 'Manage LO', 'Tujuan?divisi=' . $div['id'], 'bi-flag', '#6de9b2') ?>
          <?= card('Daily Activity', 'Daily Tasks', 'Aktifitas?divisi=' . $div['id'], 'bi-calendar-check', '#a2e4fb') ?>
        </div>
        
        <div class="row gap-10">

          <?php foreach ($subjek as $s): ?>
              <?= card(
                    $s['subjek_nama'],                   // Title on card (Math)
                    'Manage ' . $s['subjek_nama'],       // Subtitle (Manage Math)
                    'Subunit?subjek=' . $s['subjek_id'], // Link
                    'bi-file-earmark-text',
                    '#ffb8b8'
              ) ?>
          <?php endforeach ?>

        </div>

      </div>

      <?php endforeach; ?>


      <div class="section-block">

        <!-- MAIN SECTION -->
        <div class="section-title-main">
          Account
        </div>

        <div class="row gap-10 mb-3">
          <?= card('Logout', '', 'logout', 'bi-box-arrow-right', '#ffe8b3') ?>
        </div>

      </div>


    </div>
  </div>

</main>


<?php 
  echo view('layouts/footer.php');
?>


<?php
function card($title, $subtitle, $url, $icon, $baseColor) {

  $strong = $baseColor . "cc";   // 80% opacity
  $soft   = $baseColor . "66";   // 40% opacity

  return '
    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
      <a href="'. base_url($url) .'" class="text-decoration-none">
        <div class="card-soft">

          <div class="card-icon-wrapper"
               style="--card-color-strong: '.$strong.'; --card-color-soft: '.$soft.';">
            <i class="bi '.$icon.'"></i>
          </div>

          <div class="card-title-soft">'.$title.'</div>
          <div class="card-subtitle-soft">'.$subtitle.'</div>

        </div>
      </a>
    </div>
  ';
}
?>
