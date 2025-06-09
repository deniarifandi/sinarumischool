    <?php 
      echo view('layouts/header.php');
      echo view('layouts/sidebar.php');
    ?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0"><img src="<?php echo base_url() ?>assets/img/class.svg" style="max-width: 35px;"> Dashboard <?php echo $data->kelompok_nama ?> </h3></div>

              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
              
              <!-- /.col -->
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>absensi">
                <div class="info-box">
                  <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-person-check"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Absensi</span>
                    <span class="info-box-number">
                      
                      <small>Student Absence List</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <!--begin::Row-->
           
            <!--end::Row-->
            <!--begin::Row-->
           
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
     
      <?php 
        echo view('layouts/footer.php');
      ?>
