<?= $this->extend('main') ?>
<?= $this->section('content') ?>

  <?php if (count($checkedToday) <= 0): ?>
    <div class="glass-card">
        You haven’t submitted your attendance yet. <a href="<?= base_url() ?>presence" class="btn btn-sm btn-primary">Click to submit now</a>
  </div>  
  <?php endif ?>
  

  <div class="glass-card">
    <h5 class="mb-4">Personal Management</h5>
    <div class="action-grid">
        <a href="<?= base_url() ?>profile" class="action-btn"><i class="bi bi-person"></i><span>Profile</span></a>
        <a href="<?= base_url() ?>presence" class="action-btn"><i class="bi bi-person-check"></i><span>Attendance</span></a>
        
    </div>
  </div>

  <div class="glass-card" style="display: none;">
    <h5 class="mb-4">Quick Actions</h5>
    <div class="action-grid">
     <button class="action-btn"><i class="bi bi-person"></i><span>Profile</span></button>
      <button class="action-btn">
        <i class="bi bi-person-check"></i>
        <span>Attendance</span>
      </button>
      <button class="action-btn"><i class="bi bi-graph-up-arrow"></i><span>Analytics</span></button>
      <button class="action-btn"><i class="bi bi-wallet2"></i><span>Payments</span></button>
      <button class="action-btn "><i class="bi bi-gear"></i><span>Settings</span></button>
      <button class="action-btn"><i class="bi bi-envelope"></i><span>Messages</span></button>
      <button class="action-btn"><i class="bi bi-shield-check"></i><span>Security</span></button>
      <button class="action-btn"><i class="bi bi-database"></i><span>Database</span></button>
      <button class="action-btn"><i class="bi bi-cloud-arrow-up"></i><span>Backups</span></button>
    </div>
  </div>

<?= $this->endSection() ?>
