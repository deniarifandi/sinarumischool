<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
  .profile-avatar{
  width:100%;
  height:100%;
  border-radius:50%;
  object-fit:cover;
  border:3px solid var(--accent-color);
}
</style>

<?php
function safe_url($path, $fallback = 'avatar/default.png'){
  return $path ? base_url($path) : base_url($fallback);
}
?>

<?php if (empty($checkedToday)): ?>
  <div class="glass-card">
    <p class="mb-2">
      You haven’t submitted your attendance yet.
    </p>
    <a href="<?= base_url('presence') ?>" class="btn btn-sm btn-primary">
      Submit Attendance
    </a>
  </div>
<?php endif; ?>

<div class="glass-card d-flex align-items-center p-2 px-3 border rounded-3 shadow-sm bg-white py-2">
    <div class="position-relative">
       <div class="profile-avatar-wrap">
       <img src="<?= safe_url($user['pasfoto']) ?>" class="profile-avatar" alt="Avatar" style="max-width: 50px; max-height: 50px;">
        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle" title="Online"></span>
      </div>
    </div>

    <div class="ms-3 flex-grow-1">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold text-dark">
                <?= esc($user['name'] ?? 'Personel') ?>
            </h6>
            <span class="badge bg-light text-primary border rounded-pill fw-normal" style="font-size: 0.75rem;">
                <?= esc($user['role'] ?? 'Unpositioned') ?>
            </span>
        </div>
        
        <div class="d-flex gap-3">
           <small class="text-muted">
                <b><i class="bi bi-people-fill me-1"></i>Role :</b> <?= esc($user['role']) ?>
            </small>
            <small class="text-muted">
                <i class="bi bi-door-open me-1"></i>Class: <strong><?= esc($user['class_room'] ?? 'N/A') ?></strong>
            </small>
        </div>
    </div>
</div>

<div class="glass-card" style="display:none">
  <h5 class="mb-4">Superadmin Menu</h5>
    <div class="mb-3">
      <div class="fw-semibold text-primary mb-2">
        
      </div>
      <div class="action-grid">
        <a href="<?= base_url('division') ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Division</span>
        </a>
        <a href="<?= base_url('users') ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Users</span>
        </a>
      </div>

    </div>
</div>


<div class="glass-card">
  <h5 class="mb-4">Personal Management</h5>
  <div class="action-grid">
    <a href="<?= base_url('profile') ?>" class="action-btn">
      <i class="bi bi-person"></i>
      <span>Profile</span>
    </a>
    <a href="<?= base_url('presence') ?>" class="action-btn">
      <i class="bi bi-person-check"></i>
      <span>Attendance</span>
    </a>
  </div>
</div>


<div class="glass-card" style="display:none">
  <h5 class="mb-4">CSI</h5>

  <?php foreach ($divisions as $d): ?>

    <div class="mb-3">
      <div class="fw-semibold text-primary mb-2">
        <?= esc($d['division_name']) ?>
      </div>

      <div class="action-grid">

        <a href="<?= base_url('socioreport?divisi='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Socio-Emotional Report</span>
        </a>

    </div>
  </div>
  <?php endforeach ?>
  
</div>


<div class="glass-card" style="display:none">
  <h5 class="mb-4">Teachers Menu</h5>

  <?php foreach ($divisions as $d): ?>
    <div class="mb-3">
      <div class="fw-semibold text-primary mb-2">
        <?= esc($d['division_name']) ?>
      </div>

      <div class="action-grid">

        <a href="<?= base_url('grade?divisi='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Grades</span>
        </a>

        <a href="<?= base_url('class?divisi='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-diagram-3"></i>
          <span>Classes</span>
        </a>

        <a href="<?= base_url('student?division='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-people"></i>
          <span>Students</span>
        </a>

        <a href="<?= base_url('subject?division='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-journal-text"></i>
          <span>Subjects</span>
        </a>

        <a href="<?= base_url('absence') ?>" class="action-btn">
          <i class="<?= 'bi bi-clipboard-check' ?>"></i>
          <span>Student Attendance</span>
        </a>
      </div>


    </div>
  <?php endforeach ?>
</div>

<div class="glass-card" style="display:none">
  <h5 class="mb-4">Class Menu</h5>

    <div class="mb-3">
      <div class="fw-semibold text-primary mb-2">

      </div>

      <div class="action-grid">
        <a href="<?php //echo base_url('grade?classid='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Student Absence</span>
        </a>

        <a href="<?php //echo base_url('socioreport?classid='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Socio-Emotional Report</span>
        </a>

      </div>
    </div>

</div>

<?= $this->endSection() ?>
