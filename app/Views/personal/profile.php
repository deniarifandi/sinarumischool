<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<style>
  /* =======================
   PROFILE
======================= */
.profile-layout{
  display:grid;
  grid-template-columns:280px 1fr;
  gap:25px;
}

.profile-avatar-wrap{
  position:relative;
  width:110px;
  height:110px;
  margin:auto;
}

.profile-avatar{
  width:100%;
  height:100%;
  border-radius:50%;
  object-fit:cover;
  border:3px solid var(--accent-color);
}

.status-dot{
  position:absolute;
  bottom:6px;
  right:6px;
  width:14px;
  height:14px;
  background:#22c55e;
  border-radius:50%;
  border:2px solid #0f172a;
}

.section-title{
  font-size:.75rem;
  letter-spacing:1px;
  color:#94a3b8;
  text-transform:uppercase;
  margin-bottom:12px;
}

.info-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:15px;
}

.info-item{
  background:rgba(255,255,255,.05);
  border:1px solid var(--glass-border);
  border-radius:14px;
  padding:6px 14px;
  font-size:.8rem;
}

.info-item span{
  display:block;
  font-size:.6rem;
  color:#94a3b8;
  text-transform:uppercase;
}

/* =======================
   DOC GRID
======================= */
.doc-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
  gap:15px;
}

.doc-card{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:14px;
  border-radius:14px;
  border:1px solid var(--glass-border);
  color:#cbd5f5;
  text-decoration:none;
}

.doc-card:hover{
  border-color:var(--accent-color);
  color:#fff;
}
</style>

<style>
  @media (max-width:768px){

  .profile-layout{grid-template-columns:1fr;}
}
</style>

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
  <div class="glass-card text-center">

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
