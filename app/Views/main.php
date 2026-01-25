<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sinarumi | Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
:root{
  --glass-bg: rgba(255,255,255,0.07);
  --glass-border: rgba(255,255,255,0.125);
  --accent-color:#3b82f6;
  --accent-glow: rgba(59,130,246,.5);
  --sidebar-width:240px;
  --sidebar-collapsed:70px;
}

.cursor-pointer{cursor:pointer;}

body{
  font-family:'Plus Jakarta Sans',sans-serif;
  margin:0;
  height:100vh;
  background: radial-gradient(circle at top left,#0f172a,#1e293b);
  overflow:hidden;
  color:#fff;
}

/* ambient glow */
body::before{
  content:'';
  position:fixed;
  inset:0;
  background:url('logo.png') center/contain no-repeat;
  opacity:0.1;
  pointer-events:none;
  z-index:-1;

}

.app{display:flex;height:100vh}

/* sidebar */
.sidebar{
  width:var(--sidebar-width);
  background:var(--glass-bg);
  backdrop-filter:blur(20px);
  border-right:1px solid var(--glass-border);
  padding:15px;
  transition:.3s;
}

.sidebar.collapsed{width:var(--sidebar-collapsed)}
.sidebar.collapsed .text{display:none}

.brand{
  font-weight:700;
  letter-spacing:2px;
  background:linear-gradient(to right,#fff,#94a3b8);
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
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

/* main */
.main{
  flex:1;
  padding:30px;
  overflow:auto;
  padding-bottom: calc(env(safe-area-inset-bottom) + 80px);
}

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

/* cards */
.glass-card{
  background:var(--glass-bg);
  backdrop-filter:blur(20px);
  border:1px solid var(--glass-border);
  border-radius:20px;
  padding:24px;
  margin-bottom: 25px;
  box-shadow:0 25px 50px -12px rgba(0,0,0,.5);
}
</style>


<style>
  /* Container for the buttons */
.action-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 15px;
}

/* Custom Glass Button */
.action-btn {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid var(--glass-border);
  border-radius: 16px;
  padding: 20px;
  color: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.action-btn i {
  font-size: 1.5rem;
  color: var(--accent-color);
  transition: transform 0.3s ease;
}

.action-btn span {
  font-size: 0.9rem;
  font-weight: 500;
  color: #cbd5f5;
}

/* Hover Effects */
.action-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: var(--accent-color);
  transform: translateY(-5px);
  box-shadow: 0 10px 20px -5px var(--accent-glow);
}

.action-btn:hover i {
  transform: scale(1.1);
  color: #fff;
}
</style>

<style>
  .sidebar .brand{
    display:flex;
    align-items:center;        /* vertical center */
    justify-content:center;    /* horizontal center */
    gap:10px;
    padding:0px 0;
}

.sidebar .brand img{
    height:45px;
    display:block;
}

.sidebar .brand .text{
    font-weight:600;
    white-space:nowrap;
}

</style>
<style>
/* MOBILE SIDEBAR OVERLAY */
@media (max-width: 768px){
  .sidebar{
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 240px;
    transform: translateX(-100%);
    z-index: 1050;
  }

  .sidebar.active{
    transform: translateX(0);
  }

  .sidebar.collapsed{
    width: 240px;
  }

  .overlay{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(2px);
    z-index: 1040;
    display: none;
  }

  .overlay.show{
    display: block;
  }
}


</style>

<style>
  .profile-layout{
  display:grid;
  grid-template-columns:280px 1fr;
  gap:25px;
}

.profile-glass{
  background:var(--glass-bg);
  backdrop-filter:blur(20px);
  border:1px solid var(--glass-border);
  border-radius:24px;
  padding:28px;
  text-align:center;
}

.profile-avatar-wrap{
  position:relative;
  width:110px;
  height:110px;
  margin:auto;
}

.profile-avatar{
  width:100%;
  height:100%;
  border-radius:50%;
  object-fit:cover;
  border:3px solid var(--accent-color);
}

.status-dot{
  position:absolute;
  bottom:6px;
  right:6px;
  width:14px;
  height:14px;
  background:#22c55e;
  border-radius:50%;
  border:2px solid #0f172a;
}

.section-title{
  font-size:.75rem;
  letter-spacing:1px;
  color:#94a3b8;
  text-transform:uppercase;
  margin-bottom:12px;
}

.info-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:15px;
}

.info-item{
  background:rgba(255,255,255,.05);
  border:1px solid var(--glass-border);
  border-radius:14px;
  padding-top: 4px;
  padding-bottom: 4px;
  padding-left: 14px;
  padding-right: 14px;
  font-size: 0.8rem;
}

.info-item span{
  font-size:.6rem;
  color:#94a3b8;
  text-transform:uppercase;
}

.doc-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
  gap:15px;
}

.doc-card{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:14px;
  border-radius:14px;
  border:1px solid var(--glass-border);
  color:#cbd5f5;
  text-decoration:none;
}

.doc-card:hover{
  border-color:var(--accent-color);
  color:#fff;
}

@media(max-width:768px){
  .profile-layout{grid-template-columns:1fr;}
}

</style>

</head>

<body >
  <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="app">
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">

    <div class="brand">
      <img src="<?= base_url() ?>logosmall.png" alt="logo">
      <span class="text">
        SINARUMI<br>
        CBIS 4.0

      </span>

    </div>
    <hr>
    <a href="<?= base_url('') ?>"><i class="bi bi-speedometer2"></i><span class="text">Dashboard</span></a>
    <a href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i><span class="text">Logout</span></a>

  </aside>


  <!-- MAIN -->
  <main class="main">
    <div class="topbar">
      <button class="toggle-btn" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
      </button>
      <h5 class="mb-0">Dashboard</h5>
    </div>


    <?= $this->renderSection('content') ?>      


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

window.addEventListener('resize', () => {
  if(window.innerWidth > 768){
    closeSidebar();
  }
});
</script>


</body>
</html>
