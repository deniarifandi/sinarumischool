<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Sinarumi | My Little Island</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.css" />

  <!-- ApexCharts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">

  <!-- Overlay Scrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


  <style>
    body {
      background: #f4f5f7;
      font-family: "Source Sans 3", sans-serif;
      color: #333;
    }

    /* NAVBAR */
    .app-header {
/*      height: 65px;*/
      background: #ffffffcc;
      backdrop-filter: blur(12px);
      display: flex;
      align-items: center;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      padding: 0 25px;
      position: fixed;
      width: 100%;
      z-index: 900;
    }

    .app-header .nav-left {
      display: flex;
      align-items: center;
      gap: 20px;
      font-weight: 500;
    }

    .app-header .nav-left a {
      text-decoration: none;
      color: #444;
    }

    .app-header .nav-right {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 18px;
    }

    .user-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: 2px solid #e8e8e8;
      cursor: pointer;
    }

    .dropdown-menu {
      border-radius: 16px;
      padding: 0;
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
      overflow: hidden;
    }

    .dropdown-header-box {
      background: linear-gradient(135deg, #8EC5FF, #62A0FF);
      padding: 22px;
      text-align: center;
      color: #fff;
    }

    .dropdown-header-box img {
      width: 78px;
      height: 78px;
      border-radius: 50%;
      border: 3px solid rgba(255,255,255,0.6);
    }

    .dropdown-item {
      padding: 12px 18px;
      font-weight: 500;
    }

    /* MAIN */
    .app-main {
      
      
    }

    .container-custom {
      padding: 0 25px;
    }

    .section-title {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #444;
    }

    /* CARD */
    .soft-card {
      border-radius: 20px;
      padding: 24px;
      background: #ffffff;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      transition: .2s ease;
      cursor: pointer;
      text-align: center;
    }

    .soft-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 22px rgba(0,0,0,0.1);
    }

    .soft-icon {
      width: 78px;
      height: 78px;
      border-radius: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 14px;
      font-size: 34px;
      background: var(--icon-color);
      box-shadow:
        inset 0 3px 6px rgba(255,255,255,0.6),
        inset 0 -3px 6px rgba(0,0,0,0.08),
        0 4px 10px rgba(0,0,0,0.1);
      transition: .2s ease;
    }

    .soft-card:hover .soft-icon {
      transform: scale(1.07);
    }

    .soft-title {
      font-size: 16px;
      font-weight: 600;
      color: #333;
    }

    .soft-sub {
      font-size: 13px;
      color: #777;
    }
  </style>
</head>

<body>

<a href="<?= base_url(); ?>" style="text-decoration:none;">
  <div style="
    width: 100%;
    text-align: center;
    padding: 10px 0 10px;
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: 700;
    color: #2f2f2f;
    letter-spacing: .5px;
    background: #ffffff;
    border-bottom: 1px solid rgba(0,0,0,0.07);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  ">
    Sinarumi
    <span style="font-weight:400;color:#888;">x </span>
    My Little Island
  </div>
</a>


          <!-- <li><a class="dropdown-item py-2" href="<?= base_url('logout'); ?>">Logout</a></li> -->
