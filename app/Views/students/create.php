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
            <div class="col-sm-6"><h3 class="mb-0">Students</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Students</li>
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
                <h3 class="card-title">Add Student</h3>
                <div class="card-tools">
                  <!-- <a href="" class="btn btn-primary">Add Student</a> -->
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
             
           <?php if (session('errors')) : ?>
              <div style="color:red;">
                  <ul>
                      <?php foreach (session('errors') as $error) : ?>
                          <li><?= esc($error) ?></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
          <?php endif; ?>

            <form action="<?= site_url('students') ?>" method="post">
              <?= csrf_field() ?>
              <div class="row mb-3">
                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputName" name="student_name" value="<?= old('student_name') ?>" />
                </div>
              </div>

              <div class="row mb-3">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" name="student_email" id="inputEmail" value="<?= old('student_email') ?>" />
                </div>
              </div>

               <div class="row mb-3">
                <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputUsername" name="student_username" value="<?= old('student_username') ?>" />
                </div>
              </div>
              
              <a href="../students" class="btn btn-danger">Cancel</a>

              <button type="submit" class="btn float-end btn-success">Submit</button>
              

            </div>
            <!-- /.card-body -->
            
          </form>
          <!-- /.card-footer -->
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

