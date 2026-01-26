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

<?php /* Future / disabled section */ ?>
<?php if (false): ?>
<div class="glass-card">
  <h5 class="mb-4">Quick Actions</h5>
  <div class="action-grid">
    <a class="action-btn"><i class="bi bi-person"></i><span>Profile</span></a>
    <a class="action-btn"><i class="bi bi-person-check"></i><span>Attendance</span></a>
    <a class="action-btn"><i class="bi bi-graph-up-arrow"></i><span>Analytics</span></a>
    <a class="action-btn"><i class="bi bi-wallet2"></i><span>Payments</span></a>
    <a class="action-btn"><i class="bi bi-gear"></i><span>Settings</span></a>
    <a class="action-btn"><i class="bi bi-envelope"></i><span>Messages</span></a>
    <a class="action-btn"><i class="bi bi-shield-check"></i><span>Security</span></a>
    <a class="action-btn"><i class="bi bi-database"></i><span>Database</span></a>
    <a class="action-btn"><i class="bi bi-cloud-arrow-up"></i><span>Backups</span></a>
  </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
