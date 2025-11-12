    <?php 
      echo view('layouts/header.php');

    ?>

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">

              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">List Subject</h3>
                  <div class="card-tools">
                     
                        <a href="unit/new?subjek_id=<?= $_GET['subjek_id']?>" class="btn btn-primary">Add <?= $title ?></a>    
                  
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
                        
                          <?php for ($i=0; $i < count($fieldList); $i++) { 
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
                url: "<?= base_url($table.'/data_unit/'.$_GET['subjek_id']) ?>",
                type: "POST"
            },
            columns: [
               
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