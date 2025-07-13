<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sinarumi | My Little Island</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE | Dashboard v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
    crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
    crossorigin="anonymous"
    />
  </head>
  <!--end::Head-->

  <style type="text/css">
    

    body {
      margin: 0;
      padding: 0;
      background-image: url('<?php echo base_url(); ?>assets/img/bg.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      height: 100vh;
    }

  </style>
  <!--begin::Body-->
  <body class="login-page bg-body-secondary" style="display: flex; justify-content: center; align-items: center; height: 100vh;  ">
    <div class="login-box" style="width: 600px;">
      <div class="login-logo">
        <b>Attendance Form</b><br> <h5>SinaRumi v3.0.0</h5>
      </div>
      <!-- /.login-logo -->
      <div class="card" style="width:360px; margin: 0 auto;">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign to start your day</p>
          <?php if(session()->getFlashdata('error')): ?>
              <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
          <?php endif; ?>
          
           <form method="post" action="<?= site_url('auth/loginauth') ?>">
            <div class="input-group">
              
            </div>  
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" placeholder="Username" />

              <a class="btn btn-primary" onclick="startQR()">Scan QR</a>
            </div>
            <div class="input-group text-center">
              <div class="" id="reader" style="width:200px; height:200px;"></div>
            </div>
            <div class="input-group mb-3">
              <input class="form-control" type="text" id="qrResult" placeholder="Scanned data will appear here">
            </div>
            <div class="input-group mb-3">
              <select name="status" id="status" class="form-control">
                <option value="1">Hadir</option>
                <option value="2">Izin</option>
                <option value="3">Sakit</option>
              </select>
            </div>
            <!--begin::Row-->
            <div class="row">
              
              <!-- /.col -->
              <div class="col-12">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Sign</button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>
         
          <!-- /.social-auth-links -->
         
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?php echo base_url(); ?>assets/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>

    <script type="text/javascript">
      
      function forgotPassword(){
        alert('Contact Administrator : 0818 - 0517 - 3445');
      }

    </script>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
    let html5Qr;
    function startQR() {
      const qrResult = document.getElementById("qrResult");
      const reader = document.getElementById("reader");
      if (!html5Qr) html5Qr = new Html5Qrcode("reader");

      const config = { fps: 10, qrbox: 250 };
      const facingMode = { facingMode: { exact: "environment" } };

      html5Qr.start(
        facingMode,
        config,
        decodedText => {
          qrResult.value = decodedText;
          html5Qr.stop().then(() => reader.innerHTML = "");
        }
      ).catch(err => {
        console.error("Start failed:", err);
        alert("Error: " + err.message);
      });
    }
  </script>

    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
