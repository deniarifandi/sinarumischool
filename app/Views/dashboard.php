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
            <div>
              <h4>School Management</h4>
            </div>
            <div class="row">
              <!-- /.col -->
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Guru">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#d81b60!important">
                    <i class="bi bi-person-badge text-white"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Teacher</span>
                    <span class="info-box-number">
                      
                      <small>Teacher Manager</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
                <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Kelompok">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#fd7e14!important">
                    <i class="bi bi-person-lines-fill text-white"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Class</span>
                    <span class="info-box-number">
                      
                      <small>Class Manager</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Murid">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#6610f2">
                    <i class="bi bi-mortarboard text-white"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Student</span>
                    <span class="info-box-number">
                      
                      <small>Student Manager</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
                <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Subjek">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#01ff70!important">
                    <i class="bi bi-chat-dots text-dark"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Subject</span>
                    <span class="info-box-number">
                      
                      <small>Manage Subject</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
               <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Tipeaktifitas">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#d568fb!important">
                    <i class="bi bi-chat-dots text-dark"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Activity Type</span>
                    <span class="info-box-number">
                      
                      <small>Manage Activity</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
            </div>
            <!-- Info boxes -->
            <div>
              <h4>Class Management</h4>
            </div>
            <div class="row">
              <!-- /.col -->
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>absensi">
                <div class="info-box">
                  <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-person-check text-dark"></i>
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
            
            <div class="row">
            <div>
              <h4>Subject Management</h4>
            </div>
            <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Unit">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#698cba!important">
                    <i class="bi bi-chat-dots text-white"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Chapter</span>
                    <span class="info-box-number">
                      
                      <small>Manage Chapter</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Subunit">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#fb4647!important">
                    <i class="bi bi-chat-dots text-dark"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Sub-Chapter</span>
                    <span class="info-box-number">
                      
                      <small>Manage Sub-Chapter</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Tujuan">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#3d9970!important">
                    <i class="bi bi-chat-dots text-white"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Lesson Objective</span>
                    <span class="info-box-number">
                      
                      <small>Manage LO</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
              <div class="col-md-3">
                <a href="<?php echo base_url(); ?>Aktifitas">
                <div class="info-box">
                  <span class="info-box-icon shadow-sm" style="background-color:#4ecbf8!important">
                    <i class="bi bi-person-walking text-dark"></i>
                  </span>
                  <div class="info-box-content">
                    <span class="info-box-text">Daily Activity </span>
                    <span class="info-box-number">
                      
                      <small>Manage LO</small>
                    </span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                  </a>
                <!-- /.info-box -->
              </div>
            </div>
      
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
