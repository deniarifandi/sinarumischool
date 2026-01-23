<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<?php
function val($v){ return esc($v ?? ''); }
?>

<form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data" class="glass-card p-4">

  <h5 class="mb-4">Edit Profile</h5>

  <!-- BASIC -->
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="<?= val($user['name']) ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Username</label>
      <input type="text" class="form-control" value="<?= val($user['username']) ?>" disabled>
    </div>

    <div class="col-md-6">
      <label class="form-label">NIP</label>
      <input type="text" name="nip" class="form-control" value="<?= val($user['nip']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">NIK</label>
      <input type="text" name="nik" class="form-control" value="<?= val($user['nik']) ?>">
    </div>

    <div class="col-md-6">
      <label class="form-label">Gender</label>
      <select name="gender" class="form-select">
        <option value="">-</option>
        <option value="Male"   <?= $user['gender']==='Male'?'selected':'' ?>>Male</option>
        <option value="Female" <?= $user['gender']==='Female'?'selected':'' ?>>Female</option>
      </select>
    </div>

    </div>
    <hr>
    <div class="row mt-4">

    <div class="col-md-6">
      <label class="form-label">Birth Place</label>
      <input type="text" name="placebirth" class="form-control" value="<?= val($user['placebirth']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">Birth Date</label>
      <input type="date" name="datebirth" class="form-control" value="<?= val($user['datebirth']) ?>">
    </div>

</div>
<div class="row mt-4">

<div class="col-md-6">
  <label class="form-label">Religion</label>
  <select name="religion" class="form-select">
    <option value="">-</option>
    <option value="Islam"     <?= $user['religion']==='Islam'?'selected':'' ?>>Islam</option>
    <option value="Kristen"   <?= $user['religion']==='Kristen'?'selected':'' ?>>Kristen</option>
    <option value="Katolik"   <?= $user['religion']==='Katolik'?'selected':'' ?>>Katolik</option>
    <option value="Hindu"     <?= $user['religion']==='Hindu'?'selected':'' ?>>Hindu</option>
    <option value="Buddha"    <?= $user['religion']==='Buddha'?'selected':'' ?>>Buddha</option>
    <option value="Konghucu"  <?= $user['religion']==='Konghucu'?'selected':'' ?>>Konghucu</option>
  </select>
</div>

<div class="col-md-6">
  <label class="form-label">Marital Status</label>
  <select name="marital" class="form-select">
    <option value="">-</option>
    <option value="Single"  <?= $user['marital']==='Single'?'selected':'' ?>>Single</option>
    <option value="Married" <?= $user['marital']==='Married'?'selected':'' ?>>Married</option>
    <option value="Divorced" <?= $user['marital']==='Divorced'?'selected':'' ?>>Divorced</option>
    <option value="Widowed" <?= $user['marital']==='Widowed'?'selected':'' ?>>Widowed</option>
  </select>
</div>


  </div>

  <hr class="my-4">

  <!-- CONTACT -->
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Phone</label>
      <input type="text" name="phone" class="form-control" value="<?= val($user['phone']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">BCA</label>
      <input type="text" name="bca" class="form-control" value="<?= val($user['bca']) ?>">
    </div>
    <div class="col-12">
      <label class="form-label">Address</label>
      <textarea name="address" class="form-control" rows="2"><?= val($user['address']) ?></textarea>
    </div>
  </div>

  <hr class="my-4">

  <!-- FILES -->
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Photo</label>
      <input type="file" name="pasfoto" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">KTP</label>
      <input type="file" name="filektp" class="form-control">
    </div>
    <div class="col-md-6">
      <label class="form-label">KK</label>
      <input type="file" name="filekk" class="form-control">
    </div>
  </div>
    <hr class="my-4">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">BPJS Kesehatan</label>
      <input type="text" name="bpjskesehatan" class="form-control" value="<?= val($user['bpjskesehatan']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">BPJS Ketenagakerjaan</label>
      <input type="text" name="bpjsketenagakerjaan" class="form-control" value="<?= val($user['bpjsketenagakerjaan']) ?>">
    </div>
  </div>
  <hr class="my-4">
    <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Masa KKB (Tahun)</label>
      <input type="number" name="kkb" class="form-control" value="<?= val($user['kkb']) ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Tanggal KKB</label>
      <input type="date" name="kkbstart" class="form-control" value="<?= val($user['kkbstart']) ?>">
    </div>
     <div class="col-md-4">
      <label class="form-label">Nomor KKB</label>
      <input type="text" name="kkbnomor" class="form-control" value="<?= val($user['kkbnomor']) ?>">
    </div>

  </div>
  <hr class="my-4">
  <div class="col-md-12">
  
      <label class="form-label">Signed Archive</label>
      <input type="file" name="arsip" class="form-control">
  
  </div>

  <div class="d-flex justify-content-end gap-2 mt-4">
    <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary">Cancel</a>
    <button class="btn btn-primary">Save Changes</button>
  </div>

</form>

<?= $this->endSection() ?>
