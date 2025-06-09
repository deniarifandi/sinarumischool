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


              <div class="row">
                <div class="col-sm-12">
                   <h3 class="card-title"><label for="startDate">Edit Student Absence</label></h3>
                  <div class="card-tools">
                   <h5 class="card-title float-end">Date : <?= date('j-M-Y', strtotime($data['tanggal'])) ?></h5>
                  </div>
                </div>
              </div>
            </div>

              <div class="card-body">
                
                  <form action="<?php echo base_url(); ?>absensi" method="post">
                    <input type="hidden" name="date" value="<?= $data['tanggal'] ?>">

                  <table class="table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Attendance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($data['absensi'] as $i => $row): ?>
                        <tr>
                          <td><?= $row->murid_nama ?></td>
                          <td><?= $row->kelompok_nama ?></td>
                          <td>
                            <input type="hidden" name="murid_id[]" value="<?= $row->murid_id ?>">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" id="present<?= $i ?>" value="1" <?= $row->status == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="present<?= $i ?>">Present</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" value="2" id="absent<?= $i ?>" <?= $row->status == 2 ? 'checked' : '' ?>>
                               <label class="form-check-label" for="absent<?= $i ?>">Absent</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="attendance[<?= $i ?>]" value="3"  id="sick<?= $i ?>" <?= $row->status == 3 ? 'checked' : '' ?>> 
                              <label class="form-check-label" for="sick<?= $i ?>">Sick</label>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                   
                   <a href="<?php echo base_url() ?>absensi" class="btn btn-danger">Cancel</a>

                  <button type="submit" class="btn btn-primary float-end">Update Attendance</button>

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