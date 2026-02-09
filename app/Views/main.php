<!DOCTYPE html>
<html lang="en">
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

<style>
/* =======================
   ROOT & GLOBAL
======================= */
:root{
  --glass-bg: rgba(255,255,255,0.07);
  --glass-border: rgba(255,255,255,0.125);
  --accent-color:#3b82f6;
  --accent-glow: rgba(59,130,246,.5);
  --sidebar-width:240px;
  --sidebar-collapsed:70px;
}

*{ box-sizing:border-box; }

body{
  font-family:'Plus Jakarta Sans',sans-serif;
  margin:0;
  min-height:100vh;
  background:radial-gradient(circle at top left,#0f172a,#1e293b);
  color:#fff;
  overflow:auto;
  overscroll-behavior-y:none;
}

body::before{
  content:'';
  position:fixed;
  inset:0;
  /*background:url('logo.png') center/contain no-repeat;*/
  opacity:.1;
  pointer-events:none;
  z-index:-1;
}

.cursor-pointer{cursor:pointer;}

/* =======================
   LAYOUT
======================= */
.app{
  display:flex;
  min-height:100vh;
}

.main{
  flex:1;
  padding:30px;
  padding-bottom:calc(env(safe-area-inset-bottom) + 80px);
}

/* =======================
   SIDEBAR
======================= */
.sidebar{
  width:var(--sidebar-width);
/*  height:100vh;*/
  background:var(--glass-bg);
  backdrop-filter:blur(20px);
  border-right:1px solid var(--glass-border);
  padding:15px;
  transition: width .3s;
}

.content{
   flex: 1;
  transition: margin-left .3s;
}

.sidebar.collapsed{width:var(--sidebar-collapsed);}
.sidebar.collapsed .text{display:none;}

.sidebar a{
  display:flex;
  align-items:center;
  gap:12px;
  padding:10px 12px;
  border-radius:12px;
  color:#cbd5f5;
  text-decoration:none;
}

.sidebar a:hover{
  background:rgba(255,255,255,.08);
  color:#fff;
}

.brand{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  padding:5px 0;
}

.brand img{height:45px;}
.brand .text{
  font-weight:600;
  white-space:nowrap;
  line-height:1.2;
}

/* =======================
   TOPBAR
======================= */
.topbar{
  display:flex;
  align-items:center;
  gap:15px;
  margin-bottom:20px;
}

.toggle-btn{
  background:none;
  border:none;
  color:#fff;
  font-size:1.6rem;
}

/* =======================
   GLASS CARD
======================= */
.glass-card{
  background:var(--glass-bg);
  backdrop-filter:blur(20px);
  border:1px solid var(--glass-border);
  border-radius:20px;
  padding:24px;
  margin-bottom:25px;
  box-shadow:0 25px 50px -12px rgba(0,0,0,.5);
}

/* =======================
   ACTION GRID
======================= */
.action-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
  gap:15px;
}

.action-btn{
  background:rgba(255,255,255,.05);
  border:1px solid var(--glass-border);
  border-radius:16px;
  padding:20px;
  color:#fff;
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:10px;
  transition:.3s;
  cursor:pointer;
}

.action-btn i{
  font-size:1.5rem;
  color:var(--accent-color);
}

.action-btn span{
  font-size:.9rem;
  font-weight:500;
  color:#cbd5f5;
}

.action-btn:hover{
  background:rgba(255,255,255,.1);
  border-color:var(--accent-color);
  transform:translateY(-4px);
  box-shadow:0 10px 20px -5px var(--accent-glow);
}

.action-btn:hover i{color:#fff;}


/* =======================
   MOBILE
======================= */
.overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.45);
  backdrop-filter:blur(2px);
  z-index:1040;
  display:none;
}

.overlay.show{display:block;}

@media (max-width:768px){
  .sidebar{
    position:fixed;
    top:0;
    left:0;
    transform:translateX(-100%);
    z-index:1050;
    height: 100vh;
  }
  .sidebar.active{transform:translateX(0);}
  .sidebar.collapsed{width:var(--sidebar-width);}
  
}

@media (max-width:360px){
  .status-dot{width:6px;height:6px;}
}
</style>
</head>

<body>
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="app">
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="<?= base_url() ?>logosmall.png" alt="logo">
      <span class="text">SINARUMI<br>CBIS 4.0</span>
    </div>
    <hr>
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
          <h5 class="mb-0">Dashboard</h5>
        </div>
        <?= $this->renderSection('content') ?>
      </main>


    </div>
  </div>


</div>


<?= $this->renderSection('modal') ?>



<script>
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('overlay');

function toggleSidebar(){
  if(window.innerWidth<=768){
    sidebar.classList.toggle('active');
    overlay.classList.toggle('show');
  }else{
    sidebar.classList.toggle('collapsed');
  }
}

function closeSidebar(){
  sidebar.classList.remove('active');
  overlay.classList.remove('show');
}

window.addEventListener('resize',()=>{
  if(window.innerWidth>768) closeSidebar();
});
</script>

<?= $this->renderSection('script') ?>

</body>
</html>
