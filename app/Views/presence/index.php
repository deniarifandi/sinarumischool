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
                        <th>No.</th>
                        <th>Presensi id</th>
                        <th>Personel Name</th>
                        <th>Date</th>    
                        <th>Time</th> 
                        <th>Long</th>   
                        <th>Lat</th>
                        <th>Divisi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                         <?php if (session()->get('role') == 100): ?>
                        <th>Action</th>
                      <?php endif ?>
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
             order: [[1, 'desc']],
            ajax: {
                url: "<?= base_url('Presensidata/data') ?>",
                type: "POST"
            },
            columns: [
                 {
                    data: null, // no actual field needed
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'presensidata_id' },
                { data: 'guru_nama' },
                { data: 'date_formatted' },
                { data: 'time_formatted' },
                { data: 'longitude'},
                { data: 'latitude'},
                { data: 'semua_divisi'},
                { data: 'semua_jabatan'},
                 {
                    data: 'status',
                    render: function (data, type, row) {
                      if (data == 1) return 'Hadir';
                      if (data == 2) return 'Ijin';
                      if (data == 3) return 'Sakit';
                      return '-';
                    }
                  },

                  <?php if (session()->get('role') == 100): ?>
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<a href="<?= base_url(); ?>presensidata/editstatus/${row.presensidata_id}" class="btn btn-sm btn-primary">Edit Status</a>`;
                    },
                    orderable: false,
                    searchable: false
                }
              <?php endif ?>

            ]
        });

     
    });
    </script>