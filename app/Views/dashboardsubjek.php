<?php 
echo view('layouts/header.php');

// data coming directly from controller:
// $guru
// $divisi   → list of user’s divisi
// $subjects → subjects grouped by divisi_id
// $kelompok
$divisiList = $divisi;

$role = session()->get('guru_role');
$guruId = session()->get('guru_id');

// helpers
function canAccessDivisi($list, $allowed) {
    return count(array_intersect($list, $allowed)) > 0;
}
?>

<style>
  body {
    background: #f3f4f6;
  }

  .section-block, .hr-section-block {
    background: #ffffff;
    border-radius: 22px;
    padding: 25px 28px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  }

  .section-title-main {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #2f2f2f;
  }

  .subsection-title {
    font-size: 17px;
    font-weight: 600;
    margin-top: 25px;
    margin-bottom: 14px;
    color: #4a4a4a;
    padding-left: 4px;
  }

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

<style>
.subject-badge {
  background: linear-gradient(135deg, #007bff, #00bcd4);
  color: white;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 14px;
  display: inline-block;
  margin-left: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  transition: all 0.2s ease;
}
.subject-badge:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}
.badge-missing {
  background: #ccc;
  color: #333;
}
</style>

<main class="app-main">

  <div class="app-content">
    <div class="container-fluid">

      <!-- HR BLOCK -->
      <div class="hr-section-block">

        <?php 
        $subjekId = $_GET['subjek_id'] ?? null;
          $subjeknya = null;

          if ($subjekId && !empty($subjek)) {
              foreach ($subjek as $divisi_id => $subjekList) {
                  foreach ($subjekList as $s) {
                      if ($s['subjek_id'] == $subjekId) {
                          $subjeknya = $s['subjek_nama'];
                          break 2; // stop both loops once found
                      }
                  }
              }
          }


        ?>
        <div class="section-title-main">
          Subject Menu :
          <?php if ($subjeknya): ?>
            <span class="subject-badge"><?= esc($subjeknya) ?></span>
          <?php else: ?>
            <span class="subject-badge badge-missing">Not Found</span>
          <?php endif; ?>
        </div>


        <div class="row gap-10">
<?= card('Chapter', 'Manage academic units', 'unit?divisi='.$_GET['divisi'].'&subjek_id='.$_GET['subjek_id'], 'bi-building', '#82adf3') ?>
<?= card('Sub-Chapter', 'Organize learning subunits', 'Subunit', 'bi-diagram-3', '#b2dfdb') ?>
<?= card('Objective', 'Set and track learning goals', 'Tujuan', 'bi-bullseye', '#fdd835') ?>
<?= card('Journal', 'View and record daily reflections', 'Aktifitas', 'bi-journal-bookmark', '#aed581') ?>


        </div>
      </div>

      <!-- DIVISION BLOCKS -->

    </div>
  </div>

</main>

<?php echo view('layouts/footer.php'); ?>

<?php
function card($title, $subtitle, $url, $icon, $baseColor) {

  $strong = $baseColor . "cc";   
  $soft   = $baseColor . "66";   

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
