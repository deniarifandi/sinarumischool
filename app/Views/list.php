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
              <div class="col-sm-6"><h3 class="mb-0"><?= $title ?></h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
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
                  <h3 class="card-title"><?= $title ?> list</h3>
                  <div class="card-tools">
                    <a href="<?= $table ?>/new" class="btn btn-primary">Tambah <?= $title ?></a>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                  <table id="<?php $table ?>Table" class="display">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <?php for ($i=0; $i < count($field); $i++) { 
                            ?><th><?= $fieldList[$i][1]; ?></th><?php 
                          } ?>
                          <th>action</th>
                      </tr>
                  </thead>
              </table>
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
    $(document).ready(function () {
        $('#<?php $table ?>Table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url($table.'/data') ?>",
                type: "POST"
            },
            columns: [
                { data: '<?= $primaryKey ?>' },
                <?php 
                  for($i=0; $i < count($fieldList); $i++){
                    ?>
                    { data: '<?= $fieldList[$i][0] ?>' },
                    <?php
                  }
                ?>
                { 
                 data: '',
                 render: (data,type,row) => {
                   return `<a class="btn btn-warning btn-sm" href='<?= $table ?>/${row.<?= $primaryKey ?>}/edit'>Edit</a>
                   <form action='<?= $table ?>/${row.<?= $primaryKey ?>}' method="post" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
      </form>

                 `;
                 }
              }

            ]
        });

     
    });
    </script>