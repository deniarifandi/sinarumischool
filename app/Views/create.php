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
            <div class="col-sm-6"><h3 class="mb-0"><?php echo $title; ?></h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
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
                <h3 class="card-title">Add <?php echo $title; ?></h3>
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

          <form action="<?= site_url($table) ?>" method="post">
      <?= csrf_field() ?>

      <?php for ($i = 0; $i < count($field); $i++) :
        $type = $field[$i][0];
        $name = $field[$i][1];
        $label = $fieldName[$i];
        $oldValue = old($name);
      ?>
        <div class="mb-3">
          <label class="form-label"><?= esc($label) ?></label>
          <input type="<?= esc($type) ?>" class="form-control" name="<?= esc($name) ?>" value="<?= esc($oldValue) ?>">
        </div>
      <?php endfor; ?>

      <a href="<?= site_url($table) ?>" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-success float-end">Submit</button>
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

