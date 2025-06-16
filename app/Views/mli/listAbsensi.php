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
                  <h3 class="card-title">Student Attendance list</h3>
                  <div class="card-tools">
                    <a href="<?php echo base_url(); ?>absensi/add" class="btn btn-primary">Fill Attendance</a>
                  </div>
                  <!-- /.card-tools -->
                </div>

              <div class="card-body">

                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <?= session()->getFlashdata('success') ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>


              <br>

              <form action="<?php base_url(); ?>absensi" method="post">
                <table class="table" id="attendanceTable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Group</th>
                      <th>Tanggal</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php for ($i = 0; $i < count($data); $i++) { ?>
                      <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= $data[$i]->kelompok_nama ?></td>
                        <td><?= date('j-M-Y', strtotime($data[$i]->tanggal)) ?></td>
                        <td>
                          <a href="<?php echo base_url(); ?>absensi/edit/<?= $data[$i]->tanggal ?>" class="btn btn-warning btn-sm">Edit</a>
                          <a href="<?php echo base_url(); ?>absensi/delete/<?= $data[$i]->tanggal ?>" 
                             onclick="return confirm('Are you sure you want to delete attendance for <?= date('j-M-Y', strtotime($data[$i]->tanggal)) ?>?')" 
                             class="btn btn-danger btn-sm">
                             Delete
                          </a>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>

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

<script>
  $(document).ready(function() {
    $('#attendanceTable').DataTable({
      responsive: true,
      columnDefs: [
        { orderable: false, targets: 3 } // disable sorting on Action column
      ]
    });
  });
</script>