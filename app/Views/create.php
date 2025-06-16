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

             <form action="<?= site_url($table) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <?php for ($i = 0; $i < count($field); $i++): ?>
                  <div class="row mb-3">
                    <label for="<?= $field[$i][1] ?>" class="col-sm-2 col-form-label"><?= $fieldName[$i] ?></label>
                    <div class="col-sm-10">
                      <?php
                      $type = $field[$i][0];
                      $name = $field[$i][1];
                      $oldValue = old($name);
                      ?>

                      <?php if (in_array($type, ['text', 'date', 'file', 'password', 'email'])): ?>
                        <input type="<?= $type ?>" class="form-control" id="<?= $name ?>" name="<?= $name ?>" value="<?= esc($oldValue) ?>" />

                      <?php elseif ($type === 'select'): ?>
                        <select class="form-control" id="<?= $name ?>" name="<?= $name ?>">
                           <option value="" >-Select-</option>
                          <?php foreach ($fieldOption[$i] as $option): ?>
                            <option value="<?= esc($option[0]) ?>" <?= ($oldValue === $option[0]) ? 'selected' : '' ?>>
                              <?= esc($option[1]) ?>

                            </option>
                          <?php endforeach; ?>
                        </select>
              
                        <?php elseif ($type === 'radio'): ?>
                         <div class="row">
                          <?php foreach ($fieldOption[$i] as $option): ?>
                            <div class="form-check col-sm-4 mt-4">

                              <input class="form-check-input" type="radio" name="<?= $name ?>" id="<?= $name . '_' . esc($option[0]) ?>" value="<?= esc($option[0]) ?>" <?= ($oldValue === $option[0]) ? 'checked' : '' ?>>
                              <label class="form-check-label" for="<?= $name . '_' . esc($option[0]) ?>">
                                <?= esc($option[1]) ?><br>
                                  <img src="<?php echo base_url()."uploads/".$option[2]; ?>" style="max-width: 150px;">
                              </label>

                            </div>
                          <?php endforeach; ?>
                        </div>
                        <?php elseif ($type === 'textarea'): ?>
                          <textarea class="form-control" id="<?= $name ?>" name="<?= $name ?>"><?= esc($oldValue) ?></textarea>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endfor; ?>

                <a href="<?= site_url($table) ?>" class="btn btn-danger">Cancel</a>
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

