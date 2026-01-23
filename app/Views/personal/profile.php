<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<?php
function safe_url($path, $fallback = 'avatar/default.png'){
  return $path ? base_url($path) : base_url($fallback);
}
function val($v){
  return $v !== null && $v !== '' ? esc($v) : '-';
}
?>

<div class="profile-layout">

  <!-- LEFT -->
  <div class="profile-glass text-center">

    <div class="profile-avatar-wrap">
      <img src="<?= safe_url($user['pasfoto']) ?>" class="profile-avatar" alt="Avatar">
      <span class="status-dot"></span>
    </div>

    <h5 class="mt-3 mb-1 fw-semibold"><?= esc($user['name']) ?></h5>
    <small class="text-danger text-uppercase"><?= esc($user['role']) ?></small>

    <div class="profile-actions mt-3">
      <a href="<?= base_url('profile/edit') ?>" class="btn btn-outline-primary w-100 mb-2">
        <i class="bi bi-pencil-square"></i> Edit Profile
      </a>
      <a href="<?= base_url('profile/security') ?>" class="btn btn-outline-warning w-100">
        <i class="bi bi-shield-lock"></i> Change Password
      </a>
    </div>

  </div>

  <!-- RIGHT -->
  <div class="profile-details glass-card">

    <h6 class="section-title">Personal Information</h6>
    <div class="info-grid">
      <div class="info-item"><span>Username</span>
        <strong>
          <br><?= val($user['username']) ?>
        </strong>
      </div>
      <div class="info-item"><span>NIP</span><strong><br><?= val($user['nip']) ?></strong></div>
      <div class="info-item"><span>NIK</span><strong><br><?= val($user['nik']) ?></strong></div>
      <div class="info-item"><span>Gender</span><strong><br><?= val($user['gender']) ?></strong></div>
      <div class="info-item"><span>Religion</span><strong><br><?= val($user['religion']) ?></strong></div>
      <div class="info-item"><span>Marital</span><strong><br><?= val($user['marital']) ?></strong></div>
      <div class="info-item">
        <span>Birth</span>
        <strong><br>
          <?= $user['datebirth']
          ? esc($user['placebirth']).', '.date('d M Y', strtotime($user['datebirth']))
          : '-' ?>
        </strong>
      </div>
    </div>

    <h6 class="section-title mt-4">Contact</h6>
    <div class="info-grid">
      <div class="info-item"><span>Phone</span><strong><br><?= val($user['phone']) ?></strong></div>
      <div class="info-item"><span>Address</span><strong><br><?= val($user['address']) ?></strong></div>
      <div class="info-item"><span>BCA</span><strong><br><?= val($user['bca']) ?></strong></div>
    </div>

    <h6 class="section-title mt-4">Training</h6>
    <div class="info-grid">
      <div class="info-item"><span>Period</span><strong><br><?= val($user['trainingperiod']) ?></strong></div>
      <div class="info-item"><span>Start</span><strong><br><?= val($user['trainingstart']) ?></strong></div>
      <div class="info-item"><span>Division</span><strong><br><?= val($user['trainingdivisi']) ?></strong></div>
      <div class="info-item"><span>Position</span><strong><br><?= val($user['trainingposition']) ?></strong></div>
      <div class="info-item"><span>Trainer</span><strong><br><?= val($user['trainingtrainer']) ?></strong></div>
    </div>

    <h6 class="section-title mt-4">Documents</h6>
    <div class="info-grid">
      <div class="info-item"><span>BPJS Kesehatan</span><strong><br><?= val($user['bpjskesehatan']) ?></strong></div>
      <div class="info-item"><span>BPJS Ketenagakerjaan</span><strong><br><?= val($user['bpjsketenagakerjaan']) ?></strong></div>
    </div>

    <div class="doc-grid mt-4">

      <?php
      $docs = [
        'filektp' => ['KTP','bi-person-vcard'],
        'filekk'  => ['KK','bi-people']
      ];
      $hasDoc = false;
      foreach($docs as $key => [$label,$icon]):
        if(!empty($user[$key])):
          $hasDoc = true;
          ?>
          <a href="<?= safe_url($user[$key]) ?>" target="_blank" class="doc-card">
            <i class="bi <?= $icon ?>"></i> <?= $label ?>
          </a>
        <?php endif; endforeach; ?>

        <?php if(!$hasDoc): ?>
          <span class="text-muted small">No documents uploaded</span>
        <?php endif; ?>

      </div>

      <h6 class="section-title mt-4">KKB</h6>
      <div class="info-grid">
         <div class="info-item"><span>Masa KKB</span><strong><br><?= val($user['kkb']) ?></strong></div>
        <div class="info-item"><span>Nomor KKB</span><strong><br><?= val($user['kkbnomor']) ?></strong></div>
        <div class="info-item"><span>Tanggal KKB</span><strong><br><?= val($user['kkbstart']) ?></strong></div>
      </div>

      <div class="doc-grid mt-4">

      <?php
      $docs = [
        'arsip' => ['arsip','bi-person-vcard']
      ];
      $hasDoc = false;
      foreach($docs as $key => [$label,$icon]):
        if(!empty($user[$key])):
          $hasDoc = true;
      ?>
        <a href="<?= safe_url($user[$key]) ?>" target="_blank" class="doc-card">
          <i class="bi <?= $icon ?>"></i> <?= $label ?>
        </a>
      <?php endif; endforeach; ?>

      <?php if(!$hasDoc): ?>
        <span class="text-muted small">No documents uploaded</span>
      <?php endif; ?>

    </div>



    </div>
  </div>

  <?= $this->endSection() ?>
