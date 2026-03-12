<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    .profile-avatar-wrap {
  width: 50px;
  height: 50px;
  border-radius: 90%;
  overflow: hidden;
  position: relative;
   border:3px solid var(--accent-color);
}

.profile-avatar {
  width: 100%;
  height: 100%;
  object-fit: cover;     /* crop to fill */
  object-position: center;
  display: block;

}

</style>

<?php
function safe_url($path, $fallback = 'avatar/default.png'){
  return $path ? base_url($path) : base_url($fallback);
}
?>

<?php if (empty($checkedToday)): ?>
  <div class="glass-card" style="background: rgba(213, 0, 0, 0.4);">
    <p class="mb-1">
      Attendance not submitted yet.
      <a href="<?= base_url('presence') ?>" class="btn btn-sm btn-primary float-end">
      Submit Attendance
    </a>
    </p>
    
  </div>
<?php endif; ?>

<div class="glass-card d-flex align-items-center p-2 px-3 border rounded-3 shadow-sm bg-white py-2">
   <div class="position-relative">
    <div class="profile-avatar-wrap">
      <img src="<?= safe_url($user['pasfoto']) ?>" class="profile-avatar" alt="Avatar">
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
                <b><i class="bi bi-people-fill me-1"></i>Divisions :</b> 
                <?php
                  $names = array_column($divisions, 'division_name');
                  $limited = array_slice($names, 0, 2);

                  echo implode(', ', $limited);

                  if (count($names) > 2) {
                      echo ', etc.';
                  }
                ?>
            </small>
            <?php if ($mainClass['class_name'] != ""): ?>
              <small class="text-muted">
                <i class="bi bi-door-open-fill me-1"></i>ClassTeacher of: <strong><?= esc($mainClass['class_name'] ?? 'N/A') ?></strong>
              </small>
            <?php endif ?>
        </div>
    </div>
</div>

<?php
  $names = array_column($divisions, 'division_name');
  if (in_array('pos paud bunga pelangi', array_map('strtolower', $names))): ?>
  <div class="glass-card" >
    <h6 class="mb-2">PPBP Menu</h5>

      <div class="mb-1">
        <div class="fw-semibold text-primary mb-2">

        </div>

        <div class="action-grid">
          <a href="<?php echo base_url('lessonplan') ?>" class="action-btn">
            <i class="bi bi-layers"></i>
            <span>Modul Ajar</span>
          </a>

        </div>
      </div>

  </div>

<?php endif ?>

<div class="glass-card">
  <h6 class="mb-2">Personal Management</h5>
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

  <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
  <div class="glass-card">
    <h6 class="mb-2">Admin Menu</h5>
      <div class="mb-1">
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

           <a href="<?= base_url('roles') ?>" class="action-btn">
            <i class="bi bi-layers"></i>
            <span>Roles</span>
          </a>
        </div>

      </div>
  </div>
  <?php endif ?>



<?php if (
  $user['role'] == "superadmin" || 
  $user['role'] == "teacher" || 
  $user['role'] == "teacher_admin")
: ?>
<div class="glass-card">
  <h6 class="mb-2">CSI</h5>

  <?php foreach ($divisions as $d): ?>

    <div class="mb-1">
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
<?php endif ?>


<?php if (
  $user['role'] == "superadmin" || 
  $user['role'] == "teacher" || 
  $user['role'] == "admin"|| 
  $user['role'] == "teacher_admin")
: ?>
<div class="glass-card">
  <h6 class="mb-2">Division Admin Menu</h5>

  <?php foreach ($divisions as $d): ?>
    <div class="mb-1">
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


         <a href="<?= base_url('unit?division='.$d['id']) ?>" class="action-btn" style="display:none">
          <i class="bi bi-journal-text"></i>
          <span>Units</span>
        </a>

        <a href="<?= base_url('user-subject?division='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-journal-text"></i>
          <span>User Subject</span>
        </a>


      </div>


    </div>
  <?php endforeach ?>
</div>

<?php endif ?>

<?php if (
  $user['role'] == "superadmin" || 
  $user['role'] == "teacher" || 
  $user['role'] == "teacher_admin"
): ?>
<div class="glass-card" >
  <h6 class="mb-2">Class Menu</h5>

    <div class="mb-1">
      <div class="fw-semibold text-primary mb-2">

      </div>

      <div class="action-grid">
        <a href="<?php //echo base_url('grade?classid='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Student Presence</span>
        </a>

      <!--   <a href="<?php //echo base_url('socioreport?classid='.$d['id']) ?>" class="action-btn">
          <i class="bi bi-layers"></i>
          <span>Socio-Emotional Report</span>
        </a> -->

      </div>
    </div>

</div>

<?php endif ?>

<?php if (
  ($user['role'] == "superadmin" || 
  $user['role'] == "teacher" || 
  $user['role'] == "teacher_admin" ) && $userSubjects != null
): ?>
<div class="glass-card" >
  <h6 class="mb-2">Subject Menu</h5>
  
  <?php foreach ($divisions as $di): ?>  
    <h6 class="mb-2 text-primary"><?php echo $di['division_name']  ?></h5>

    <?php foreach ($userSubjects as $d): ?>
      <?php if ($di['division_name'] == $d['division_name']): ?>
          <div class="mb-1">
            <div class="fw-semibold text-danger mb-2" style="margin-left: 25px;">
              <?= esc($d['subject_name']) ?>
            </div>
            <div class="action-grid">
              <a href="<?= base_url('unit?divisi='.$d['id']) ?>" class="action-btn">
                <i class="bi bi-layers"></i>
                <span>Units</span>
              </a>
            </div>
            
          </div>
        <?php endif ?>
  <?php endforeach ?>

  <?php endforeach ?>

  

</div>

<?php endif ?>

<?= $this->endSection() ?>
