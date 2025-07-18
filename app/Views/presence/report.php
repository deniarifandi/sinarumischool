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
                <h3 class="card-title">Presence Data Not Found</h3>
                <div class="card-tools">
                  
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
               
              <h3>No data Found in the selected Filter</h3>
              <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>presensidatafront">Back</a>
          
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