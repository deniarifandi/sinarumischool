<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sinarumi | Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ================= ROOT ================= */
:root{
  --glass-bg: rgba(255,255,255,0.07);
  --glass-border: rgba(255,255,255,0.125);
  --accent:#3b82f6;
  --accent-glow: rgba(59,130,246,.45);
  --sidebar:240px;
  --sidebar-collapsed:72px;
}

/* ================= SCROLL SAFETY ================= */
html, body{
  height:100%;
  overscroll-behavior-y: contain;
}

body{
  margin:0;
  font-family:'Plus Jakarta Sans',sans-serif;
  background:radial-gradient(circle at top left,#0f172a,#1e293b);
  color:#fff;
  overflow-y:auto;
}

/* ================= APP ================= */
.app{
  display:flex;
  min-height:100vh;
}

/* ================= SIDEBAR ================= */
.sidebar{
  width:var(--sidebar);
  background:var(--glass-bg);
  backdrop-filter:blur(16px);
  border-right:1px solid var(--glass-border);
  padding:16px;
  transition:.25s ease;
}

.sidebar.collapsed{
  width:var(--sidebar-collapsed);
}

.sidebar.collapsed .text{
  display:none;
}

.brand{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  margin-bottom:12px;
}

.brand img{
  height:44px;
}

.brand .text{
  font-weight:600;
  white-space:nowrap;
}

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

/* ================= MAIN ================= */
.main{
  flex:1;
  padding:28px;
  min-height:100vh;
}

/* content anchor (IMPORTANT) */
.content-wrap{
  max-width:1200px;
  margin:0 auto;
}

/* ================= TOPBAR ================= */
.topbar{
  display:flex;
  align-items:center;
  gap:14px;
  margin-bottom:24px;
}

.toggle-btn{
  background:none;
  border:none;
  color:#fff;
  font-size:1.6rem;
}

/* ================= CARD ================= */
.glass-card{
  background:var(--glass-bg);
  backdrop-filter:blur(16px);
  border:1px solid var(--glass-border);
  border-radius:20px;
  padding:24px;
  margin-bottom:24px;
  box-shadow:0 25px 40px rgba(0,0,0,.4);
}

/* ================= DASHBOARD STACK ================= */
.dashboard-stack{
  display:flex;
  flex-direction:column;
  gap:24px;
}

/* ================= ACTION GRID ================= */
.action-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(140px,1fr));
  gap:16px;
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
  transition:.25s ease;
  text-decoration:none;
}

.action-btn i{
  font-size:1.6rem;
  color:var(--accent);
}

.action-btn span{
  font-size:.9rem;
  color:#cbd5f5;
}

.action-btn:hover{
  background:rgba(255,255,255,.1);
  border-color:var(--accent);
  transform:translateY(-4px);
  box-shadow:0 10px 20px -6px var(--accent-glow);
}

.action-btn:hover i{
  color:#fff;
}

/* ================= MOBILE ================= */
@media(max-width:768px){
  .sidebar{
    position:fixed;
    inset:0 auto 0 0;
    transform:translateX(-100%);
    z-index:1050;
  }

  .sidebar.active{
    transform:translateX(0);
  }

  .overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.45);
    backdrop-filter:blur(2px);
    z-index:1040;
    display:none;
  }

  .overlay.show{
    display:block;
  }

  .main{
    padding:20px;
  }
}
</style>
</head>

<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <img src="<?= base_url() ?>logosmall.png" alt="logo">
      <span class="text">SINARUMI<br>CBIS 4.0</span>
    </div>
    <hr>
    <a href="<?= base_url() ?>">
      <i class="bi bi-speedometer2"></i>
      <span class="text">Dashboard</span>
    </a>
    <a href="<?= base_url('logout') ?>">
      <i class="bi bi-box-arrow-right"></i>
      <span class="text">Logout</span>
    </a>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <div class="topbar">
      <button class="toggle-btn" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
      </button>
      <h5 class="mb-0">Dashboard</h5>
    </div>

    <div class="content-wrap">
      <?= $this->renderSection('content') ?>
    </div>
  </main>

</div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleSidebar(){
  if(window.innerWidth <= 768){
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
  if(window.innerWidth > 768){
    closeSidebar();
  }
});
</script>

</body>
</html>
