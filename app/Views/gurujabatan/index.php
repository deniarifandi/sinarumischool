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
                <h3 class="card-title">Personel Manager</h3>
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

                 <table id="guruTable" class="display">
                  <thead>
                      <tr>
                          <th>ID</th>
                         <th>GuruNama</th>
                          <th>action</th>
                      </tr>
                  </thead>
              </table>
              
          
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


<script>
    $(document).ready(function () {
        $('#guruTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('Guru/data') ?>",
                type: "POST"
            },
            columns: [
                { data: 'guru_id' },
              
                  { data: 'guru_nama' },
                  
                { 
                 data: '',
                 render: (data,type,row) => {
                   return `<a class="btn btn-warning btn-sm" href='Gurujabatan/${row.guru_id}/edit'>Edit</a>`;
                 }
              }

            ]
        });

     
    });
    </script>