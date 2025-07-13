      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="<?= base_url(); ?>" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="<?php echo base_url(); ?>/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Sinarumi v3.0</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >
              <li class="nav-item menu-open">
                <a href="<?php echo base_url();?>" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-easel"></i>
                  <p>
                    School Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Divisi" class="nav-link">
                      <i class="nav-icon bi bi-home"></i>
                      <p>                       
                        Division
                      </p>
                    </a>
                  </li>                 
              
                 
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Presensi" class="nav-link">
                      <i class="nav-icon bi bi-home"></i>
                      <p>                       
                        Presensi
                      </p>
                    </a>
                  </li>                 
               
                
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>showform" class="nav-link">
                      <i class="nav-icon bi "></i>
                      <p>                       
                        Form Presensi
                      </p>
                    </a>
                  </li>                 
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-person-gear"></i>
                  <p>
                    User Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Murid" class="nav-link">
                      <i class="nav-icon bi bi-people"></i>
                      <p >
                        Murid
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Guru" class="nav-link">
                      <i class="nav-icon bi bi-person-badge"></i>
                      <p>
                        Guru
                      </p>
                    </a>
                  </li>
                 
                 
                </ul>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark-text"></i>
                  <p>
                    Report
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Guru/print" class="nav-link">
                      <i class="nav-icon bi bi-people"></i>
                      <p >
                        Print Teacher List
                      </p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url(); ?>Kelompok/print" class="nav-link">
                      <i class="nav-icon bi bi-person-badge"></i>
                      <p>
                        Print Class List
                      </p>
                    </a>
                  </li>
                   <li class="nav-item">
                    <a href="<?= base_url(); ?>Unit/print" class="nav-link">
                      <i class="nav-icon bi bi-journal-bookmark"></i>
                      <p>
                        Print Chapter List
                      </p>
                    </a>
                  </li>
                <!--   <li class="nav-item">
                    <a href="<?= base_url(); ?>Subunit/print" class="nav-link">
                      <i class="nav-icon bi bi-journal-bookmark"></i>
                      <p>
                        Print Sub-Chapter List
                      </p>
                    </a>
                  </li> -->
                 
                 
                </ul>
              </li>
          
              
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
