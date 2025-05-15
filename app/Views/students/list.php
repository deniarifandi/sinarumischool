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
                  <h3 class="card-title">Student List</h3>
                  <div class="card-tools">
                    <a href="students/new" class="btn btn-primary">Add Student</a>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="studentTable" class="display">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Password</th>
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
        $('#studentTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('students/data') ?>",
                type: "POST"
            },
            columns: [
                { data: 'student_id' },
                { data: 'student_name' },
                { data: 'student_email' },
                { data: 'student_password' },
                { 
                 data: '',
                 render: (data,type,row) => {
                   return `<a href='link_to_edit/${row.student_id}'>update</a>`;
                 }
              }

            ]
        });

     
    });
    </script>