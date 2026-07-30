<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sinarumi | Dashboard</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<!-- jQuery (for DataTables only) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS (REQUIRED for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<!-- =======================
     COHESIVE GLOBAL STYLING (Light Pastel Glassmorphism Theme)
======================= -->
<style>
/* =======================
   ROOT & GLOBAL
======================= */
:root {
  /* Logo Pastel Colors */
  --logo-blue: #71C9E6; /* Bright Elly Light Blue */
  --logo-pink: #F49AC2; /* Bright Elly Pink */
  
  /* Glow Effects */
  --logo-blue-glow: rgba(113, 201, 230, 0.4);
  --logo-pink-glow: rgba(244, 154, 194, 0.4);

  /* Glass Effects - Light Mode */
  --glass-bg: rgba(255, 255, 255, 0.65);
  --glass-bg-solid: rgba(255, 255, 255, 0.95);
  --glass-border: rgba(255, 255, 255, 1);
  --glass-border-subtle: rgba(255, 255, 255, 0.5);
  --glass-shadow: 0 10px 30px -10px rgba(113, 201, 230, 0.25);

  /* Text Colors */
  --text-main: #334155; /* Slate 700 for good readability */
  --text-light: #64748b; /* Slate 500 */
  --text-active: #0284c7; /* Darker blue for active states */

  /* Layout */
  --sidebar-width: 240px;
  --sidebar-collapsed: 70px;
}

* { box-sizing: border-box; }

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  margin: 0;
  min-height: 100vh;
  /* Soft pastel gradient blending light blue and light pink */
  background: linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%);
  color: var(--text-main);
  overflow: auto;
  overscroll-behavior-y: none;
}

body::before {
  content: '';
  position: fixed;
  inset: 0;
  opacity: .05;
  pointer-events: none;
  z-index: -1;
}

.cursor-pointer { cursor: pointer; }

/* =======================
   LAYOUT
======================= */
.app {
  display: flex;
  min-height: 100vh;
}

.main {
  flex: 1;
  padding: 30px;
  padding-bottom: calc(env(safe-area-inset-bottom) + 80px);
}

/* =======================
   SIDEBAR
======================= */
.sidebar {
  width: var(--sidebar-width);
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border-right: 1px solid var(--glass-border);
  padding: 15px;
  transition: width .3s;
  box-shadow: 4px 0 24px rgba(0,0,0,0.02);
}

.content {
  flex: 1;
  transition: margin-left .3s;
}

.sidebar.collapsed { width: var(--sidebar-collapsed); }
.sidebar.collapsed .text { display: none; }

.sidebar a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 12px;
  color: var(--text-main);
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
}

.sidebar a:hover {
  background: var(--glass-bg-solid);
  color: var(--logo-blue);
  box-shadow: 0 4px 12px var(--logo-blue-glow);
}

.sidebar a i {
  color: var(--logo-pink); /* Pink icons for contrast */
  font-size: 1.2rem;
}

.brand {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 5px 0;
}

.brand img { height: 45px; }
.brand .text {
  font-weight: 700;
  color: var(--logo-blue);
  white-space: nowrap;
  line-height: 1.2;
}

/* =======================
   TOPBAR
======================= */
.topbar {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}

.toggle-btn {
  background: var(--glass-bg);
  border: 1px solid var(--glass-border);
  border-radius: 8px;
  color: var(--logo-blue);
  font-size: 1.4rem;
  padding: 4px 10px;
  cursor: pointer;
  box-shadow: var(--glass-shadow);
  transition: 0.3s;
}

.toggle-btn:hover {
  background: var(--glass-bg-solid);
  color: var(--logo-pink);
}

/* =======================
   GLASS CARD
======================= */
.glass-card {
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: 16px;
  padding: 16px;
  margin-bottom: 25px;
  box-shadow: var(--glass-shadow);
}

/* =======================
   ACTION GRID
======================= */
.action-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 15px;
}

.action-btn {
  background: var(--glass-bg-solid);
  border: 1px solid var(--glass-border);
  border-radius: 12px;
  padding: 10px;
  color: var(--text-main);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  transition: .3s;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}

.action-btn i {
  font-size: 1.5rem;
  color: var(--logo-blue);
}

.action-btn span {
  font-size: .8rem;
  font-weight: 600;
  color: var(--text-main);
}

.action-btn:hover {
  border-color: var(--logo-pink);
  transform: translateY(-4px);
  box-shadow: 0 10px 20px -5px var(--logo-pink-glow);
}

.action-btn:hover i { 
  color: var(--logo-pink); 
}

/* =======================
   MOBILE OVERLAY
======================= */
.overlay {
  position: fixed;
  inset: 0;
  background: rgba(255,255,255,.45);
  backdrop-filter: blur(4px);
  z-index: 1040;
  display: none;
}

.overlay.show { display: block; }

@media (max-width: 768px) {
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    transform: translateX(-100%);
    z-index: 1050;
    height: 100vh;
  }
  .sidebar.active { transform: translateX(0); }
  .sidebar.collapsed { width: var(--sidebar-width); }
}

@media (max-width: 360px) {
  .status-dot { width: 6px; height: 6px; }
}
</style>

<!-- =======================
     COHESIVE TABLE STYLING
======================= -->
<style>
.glass-table {
  color: var(--text-main);
  border-color: var(--glass-border);
  border-collapse: collapse;
  width: 100%;
}
.glass-table thead th {
  background: rgba(113, 201, 230, 0.15); /* Very soft blue */
  backdrop-filter: blur(10px);
  color: #0284c7; /* Darker blue for readable headers */
  border-bottom: 2px solid var(--logo-blue);
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 1px;
  padding: 12px;
}
.glass-table tbody tr td {
  background: transparent;
  border-bottom: 1px solid var(--glass-border-subtle);
  padding: 12px;
}

.btn-glass-edit {
  background: rgba(244, 154, 194, 0.15); /* Soft pink bg */
  color: #be185d; /* Darker pink for text */
  border: 1px solid var(--logo-pink);
  border-radius: 6px;
  padding: 4px 12px;
  transition: 0.3s;
}
.btn-glass-edit:hover {
  background: var(--logo-pink);
  color: #fff;
  box-shadow: 0 4px 10px var(--logo-pink-glow);
}
</style>

<!-- =======================
     COHESIVE PAGINATION STYLING
======================= */
<style>
.pagination {
  display: flex;
  gap: 8px;
  list-style: none;
  padding-left: 0;
}

.pagination li a,
.pagination li span {
  display: block;
  padding: 6px 12px;
  border: 1px solid var(--glass-border);
  border-radius: 8px;
  text-decoration: none;
  background: var(--glass-bg-solid);
  color: var(--text-light);
  transition: all 0.2s ease;
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.pagination li.active span {
  background: var(--logo-blue);
  color: #fff;
  border-color: var(--logo-blue);
  box-shadow: 0 4px 10px var(--logo-blue-glow);
}

.pagination li a:hover {
  background: var(--logo-pink);
  border-color: var(--logo-pink);
  color: #fff;
}
</style>

<!-- =======================
     RECONCILING 'TABLE-WHITE-CUSTOM' (Adapted for Pastel Theme)
======================= -->
<style>
.table-white-custom-container {
  background: var(--glass-bg);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: var(--glass-shadow);
}

.table-white-custom {
  background: var(--glass-bg-solid) !important;
  color: var(--text-main) !important;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid var(--glass-border);
}
.table-white-custom th {
  background: rgba(244, 154, 194, 0.15) !important; /* Soft pink header */
  color: #be185d !important; /* Readable dark pink */
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
  padding: 14px 12px;
  border-bottom: 2px solid var(--logo-pink) !important;
}
.table-white-custom td {
  background: transparent !important;
  color: var(--text-main) !important;
  padding: 12px;
  vertical-align: middle;
  border-bottom: 1px solid var(--glass-border-subtle) !important;
}

.table-white-custom .form-control {
  background-color: var(--glass-bg) !important;
  color: var(--text-main) !important;
  border: 1px solid var(--glass-border) !important;
  border-radius: 8px;
}
.table-white-custom .form-control:focus {
  background-color: #fff !important;
  border-color: var(--logo-blue) !important;
  box-shadow: 0 0 0 0.25rem var(--logo-blue-glow) !important;
}

/* Segmented Touch Toggles (Status Kehadiran) */
.btn-group-toggle .btn-check + .btn {
  background: var(--glass-bg-solid);
  color: var(--text-light);
  border: 1px solid var(--glass-border);
  font-size: 0.78rem;
  padding: 0.4rem 0.8rem;
  border-radius: 8px; 
  transition: all 0.2s ease;
  font-weight: 600;
}
.btn-group-toggle .btn-check:hover + .btn {
  background: #f1f5f9;
  border-color: var(--logo-blue);
  color: var(--logo-blue);
}

/* Dynamic status colors - Adjusted for light pastel theme */
.btn-check-hadir:checked + .btn {
  background-color: #dcfce7 !important; 
  border-color: #86efac !important;
  color: #166534 !important; 
}
.btn-check-izin:checked + .btn {
  background-color: #e0f2fe !important; 
  border-color: var(--logo-blue) !important;
  color: #0369a1 !important; 
}
.btn-check-sakit:checked + .btn {
  background-color: #fef9c3 !important; 
  border-color: #fde047 !important;
  color: #854d0e !important; 
}
.btn-check-alpha:checked + .btn {
  background-color: #fce7f3 !important; 
  border-color: var(--logo-pink) !important;
  color: #be185d !important; 
}
</style>

</head>

<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="<?= base_url() ?><?php echo env('LOGO', ''); ?>" alt="logo">
      <span class="text"><?php echo env('OWNER', ''); ?><br>SINARUMI</span>
    </div>
    <hr style="border-color:var(--glass-border-subtle); opacity:1;">
    <a href="<?= base_url('') ?>"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a>
    <a href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a>
  </aside>
<div class="content" id="content">
    <div class="row">
      <main class="main">
        <div class="topbar">
          <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
          </button>
          <h5 class="mb-0" style="color: var(--text-main); font-weight: 700;">Dashboard</h5>
        </div>
        <?= $this->renderSection('content') ?>
      </main>
    </div>
  </div>
</div>

<?= $this->renderSection('modal') ?>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleSidebar() {
  if(window.innerWidth <= 768){
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
  } else {
    sidebar.classList.toggle('collapsed');
  }
}

function closeSidebar() {
  sidebar.classList.remove('active');
  overlay.classList.remove('show');
}

window.addEventListener('resize', () => {
  if(window.innerWidth > 768) closeSidebar();
});
</script>

<?= $this->renderSection('script') ?>

</body>
</html>