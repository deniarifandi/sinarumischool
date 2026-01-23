<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-md-12 glass-card p-1">

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>


    <form action="<?= base_url('profile/change-password') ?>" method="post" class="p-4">

      <h5 class="mb-4">Change Password</h5>
      <hr>

      <?php
      function pwd($name,$label){
        return "
        <div class='mb-3'>
          <label class='form-label'>$label</label>
          <div class='input-group'>
            <input type='password' name='$name' class='form-control pwd-input' required>
            <button type='button' class='btn btn-outline-secondary toggle-pwd'>
              <i class='bi bi-eye'></i>
            </button>
          </div>
        </div>";
      }
      ?>

      <?= pwd('current_password','Current Password') ?>
      <?= pwd('new_password','New Password') ?>
      <?= pwd('confirm_password','Confirm New Password') ?>

      <div class="d-flex justify-content-end gap-2">
        <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary">Update Password</button>
      </div>

    </form>
  </div>
</div>

<script>
document.querySelectorAll('.toggle-pwd').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const input = btn.closest('.input-group').querySelector('.pwd-input');
    const icon  = btn.querySelector('i');
    const show  = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
  });
});
</script>

<?= $this->endSection() ?>
