    <?php 
    echo view('layouts/header.php');
    echo view('layouts/sidebar.php');
    ?>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0"></h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"></li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>

      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <!-- Info boxes -->
          <div class="row">

            <div class="card">
              <div class="card-header">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                  <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
              <?php endif; ?>
              
                <h3 class="card-title">Presence Data</h3>
                <div class="card-tools">
                  <!-- <a href="" class="btn btn-primary">Add Student</a> -->
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                 <form action="<?= base_url('lupaabsen/store') ?>" method="post">
                
                 <div class="mb-3">
                    <label for="guru_id" class="form-label">Pilih Guru</label>
                    <select class="form-control" id="guru_id" name="guru_id" required>
                      <option value="">-- Pilih Guru --</option>
                      <?php foreach ($guru as $g): ?>
                          <option value="<?= esc($g['guru_id']) ?>">
                              <?= esc($g['guru_nama']) ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="longitude" value="112.602802">
                </div>

                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="latitude" value="-7.970077">
                </div>

                <div class="mb-3">
                    <label for="presensidata_tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="presensidata_tanggal" name="presensidata_tanggal" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="1" >Hadir</option>
                        <option value="2" >Ijin</option>
                        <option value="3" >Sakit</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
              
              </div>
                
               
              </div>
            <!-- /.card -->

          </div>
          <!-- /.row -->
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
<!--end::App Main-->
<!--begin::Footer-->

<?php 
echo view('layouts/footer.php');
?>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
  
$(document).ready(function() {
    $('#guru_id').select2({
        placeholder: "Cari guru...",
        allowClear: true,
        width: '100%' // match Bootstrap form width
    });
});
</script>
