<?= $this->extend('main') ?>
<?= $this->section('content') ?>

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


<div class="glass-card">
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

        <a href="<?= base_url('student?divisi='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-people"></i>
          <span>Students</span>
        </a>

        <a href="<?= base_url('subject?divisi='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-journal-text"></i>
          <span>Subjects</span>
        </a>

        <a href="<?= base_url('presence?divisi='.$d['id']) ?>" class="action-btn">
          <i class="<?= 'bi bi-clipboard-check' ?>"></i>
          <span>Attendance</span>
        </a>
      </div>


    </div>
  <?php endforeach ?>
</div>

<?= $this->endSection() ?>
