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

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Show Report</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Export to Excel</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"> 
                  <br><br>
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
                  </select>
                  <br>

                  <label>Start</label>
                  <input class="form-control" type="date" name="start" value="<?= $start ?>">
                  <br>
                  <label>End</label>
                  <input class="form-control" type="date" name="end" value="<?= $end ?>">
                  <br>
                  <button class="btn btn-primary float-sm-end">Submit</button>
                </form></div>


                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                  <br><br>
                  <form action="<?php echo base_url() ;?>presensidataexcel" method="get" enctype="multipart/form-data">
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
                  </select>
                  <br>

                  <label>Start</label>
                  <input class="form-control" type="date" name="start" value="<?= $start ?>">
                  <br>
                  <label>End</label>
                  <input class="form-control" type="date" name="end" value="<?= $end ?>">
                  <br>
                  <button class="btn btn-primary float-sm-end">Submit</button>
                </form></div>
            
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


