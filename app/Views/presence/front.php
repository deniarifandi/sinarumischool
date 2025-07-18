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
                <h3 class="card-title">Presence Data</h3>
                <div class="card-tools">
                  <!-- <a href="" class="btn btn-primary">Add Student</a> -->
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <form action="<?php echo base_url() ;?>presensidatareport" method="get" enctype="multipart/form-data">
                  <label>Division</label>
                  <select class="form-select" name="division" aria-label="Default select example">
                    <option selected>Select Division</option>
                    <?php 
                      foreach ($divisis as $divisi) {
                        ?>
                          <option value="<?= $divisi->divisi_id; ?>"><?= $divisi->divisi_nama; ?></option>    
                        <?php
                      }
                    ?>
                    
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  <br>

                  <label>Start</label>
                  <input class="form-control" type="date" name="start" value="<?= $start ?>">
                  <br>
                  <label>End</label>
                  <input class="form-control" type="date" name="end" value="<?= $end ?>">
                  <br>
                  <button class="btn btn-primary float-sm-end">Submit</button>
                </form>
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


