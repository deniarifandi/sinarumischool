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
            <div class="col-sm-6"><h3 class="mb-0">Student's Attendance</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>absensi">List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Absensi</li>
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

              <form action="<?php echo base_url(); ?>absensi" method="post">
              <div class="row">
                <div class="col-sm-3">
                   <h3 class="card-title"><label for="startDate">Fill New Student Absence</label></h3>
                  <div class="card-tools">
                   
                  </div>
                </div>
               
                <div class="offset-sm-6 col-sm-3">
                  <input id="startDate" name="date" class="form-control float-end" type="date" value="<?= date('Y-m-d') ?>" />

                </div>
              </div>
              <br>
              </div>

              <div class="card-body">

              
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Group</th>
                      <th>Attendance</th>
                      <th>Note</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php for ($i = 0; $i < count($data); $i++) { ?>
                      <tr>
                        <td ><?= $data[$i]->murid_id ?></td>
                        <td><?= $data[$i]->murid_nama ?></td>
                        <td><?= $data[$i]->kelompok_nama ?></td>
                        <td>
                          <input type="hidden" name="murid_id[]" value="<?= $data[$i]->murid_id ?>">
                          <div class="d-flex gap-3">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" id="present<?= $i ?>" value="1" checked>
                              <label class="form-check-label" for="present<?= $i ?>">Present</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" id="absent<?= $i ?>" value="2">
                              <label class="form-check-label" for="absent<?= $i ?>">Absent</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" id="sick<?= $i ?>" value="3">
                              <label class="form-check-label" for="sick<?= $i ?>">Sick</label>
                            </div>

                            
                          </div>
                            
                        </td>
                        <td>
                          <input class="form-control" type="text" name="keterangan[]"  value="">
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <br>
                
                 <a href="<?php echo base_url() ?>absensi" class="btn btn-danger">Cancel</a>

                  <button type="submit" class="btn btn-primary float-end">Save Attendance</button>

                <br>
              </form>

              <br>
            
            </div>
            <!-- /.card-body -->

            <!-- /.card-footer -->
          </div>
          <!-- /.card -->

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