<!-- Sidebar -->
<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion" style="background-color: #00923F; color: #ffffff;">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <!-- Sidebar User Profile -->
        <div class="sidebar-user text-white text-center pt-3 pb-2 px-3">
          <img src="<?php echo base_url('assets/img/gambarorg.jpg'); ?>" alt="Profile Picture" class="rounded-circle mb-2" width="60" height="60">
          <h6 class="fw-bold mb-0 text-uppercase">
            <?= strtoupper($this->session->userdata('name') ?? 'GUEST'); ?>
          </h6>
          <!-- <p class="pt-1"><?= ucfirst($this->session->userdata('active_role') ?? 'user'); ?></p> -->
          <hr class="border-white opacity-75 mt-3 mb-1">
        </div>

        <?php
        $segment = $this->uri->segment(2); // ambil segment URL untuk menentukan menu aktif
        function activeMenu($current, $target)
        {
          return $current === $target ? '' : '';
        }
        ?>

        <a class="nav-link text-white <?= activeMenu($segment, 'dashboard'); ?>" href="<?= site_url('admins/dashboard'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-home"></i></div>
          Dashboard
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'unitpengaju'); ?>" href="<?= site_url('admins/unitpengaju'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fa-solid fa-book-open"></i></div>
          Unit Pengaju
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'klasifikasipengajuan'); ?>" href="<?= site_url('admins/klasifikasipengajuan'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-tags"></i></div>
          Klasifikasi Pengajuan
        </a>

        <!-- Sidebar - Riwayat Pengajuan -->
<li class="nav-item">
    <a class="nav-link" href="<?= site_url('admins/riwayatpengajuan'); ?>">
       <i class="fas fa-history"></i>
        <span>Riwayat Pengajuan</span>
    </a>
</li>

        

        <a class="nav-link text-white <?= activeMenu($segment, 'kelolafakultas'); ?>" href="<?= site_url('admins/kelolafakultas'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-university"></i></div>
          Kelola fakultas
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'kelolaprodi'); ?>" href="<?= site_url('admins/kelolaprodi'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-graduation-cap"></i></div>
          Kelola Prodi
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'kelolaperan'); ?>" href="<?= site_url('admins/kelolaperan'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-user-shield"></i></div>
          Kelola Peran
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'kelolapengguna'); ?>" href="<?= site_url('admins/kelolapengguna'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-users-cog"></i></div>
          Kelola Pengguna
        </a>

        <a class="nav-link text-white <?= activeMenu($segment, 'penggunaperan'); ?>" href="<?= site_url('admins/penggunaperan'); ?>">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-user-tag"></i></div>
          Kelola Peran Pengguna
        </a>

        <hr class="border-white opacity-75 mx-3">
        <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <div class="sb-nav-link-icon text-white"><i class="fas fa-sign-out-alt"></i></div>
          Logout
        </a>
      </div>
    </div>
  </nav>
</div>

<!-- Konten -->
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid px-4">
      <?php if (isset($content_view)): ?>
        <?php $this->load->view($content_view, isset($data) ? $data : []); ?>
      <?php endif; ?>
    </div>
  </main>

  <!-- Modal Konfirmasi Logout -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="logoutModalLabel"><i class="fas fa-sign-out-alt"></i> Konfirmasi Logout</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin logout dari sistem?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <a href="<?= site_url('auth/logout'); ?>" class="btn btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </div>