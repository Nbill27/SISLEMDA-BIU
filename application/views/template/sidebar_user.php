<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion" style="background-color: #00923F; color: #ffffff;">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <!-- Sidebar User Profile -->
                <div class="sidebar-user text-white text-center pt-3 pb-2 px-3">
                    <img src="<?= base_url('assets/img/gambarorg.jpg') ?>" alt="Profile Picture" class="rounded-circle mb-2" width="60" height="60">
                    <h6 class="fw-bold mb-0 text-uppercase"><?= strtoupper($nama ?? '-') ?></h6>

                    <!-- Dropdown Dinamis Role -->
                    <!-- <div class="dropdown mt-1">
                        <a href="#" class="text-white text-decoration-none dropdown-toggle" id="roleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= ucwords($active_role ?? '-') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-start shadow" style="background-color: #00923F;" aria-labelledby="roleDropdown">
                            <?php if (isset($roles) && is_array($roles)): ?>
                                <?php foreach ($roles as $r): ?>
                                    <li>
                                        <a class="dropdown-item <?= (strtolower($r->nama_role) == strtolower($active_role)) ? 'active' : '' ?>"
                                            href="<?= site_url('auth/set_role/' . $r->nama_role) ?>">
                                            <i class="fas fa-user-tag"></i> <?= ucwords($r->nama_role) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><span class="dropdown-item disabled">Tidak ada peran</span></li>
                            <?php endif; ?>
                        </ul>
                    </div> -->

                    <hr class="border-white opacity-75 mt-3 mb-1">
                </div>

                <!-- Menu Utama -->
                <a class="nav-link text-white" href="<?= site_url('users/dashboard'); ?>">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-home"></i></div>
                    Dashboard
                </a>

                <!-- Dropdown Pengajuan -->
                <a class="nav-link collapsed text-white" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePengajuan" aria-expanded="false" aria-controls="collapsePengajuan">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-paste"></i></div>
                    Pengajuan
                    <div class="sb-sidenav-collapse-arrow text-white"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePengajuan" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link text-white" href="<?= site_url('users/lembarpengajuan'); ?>">
                            <div class="sb-nav-link-icon text-white"><i class="fas fa-edit"></i></div>
                            Lembar Pengajuan
                        </a>
                        <a class="nav-link text-white" href="<?= site_url('users/statuspengajuan'); ?>">
                            <div class="sb-nav-link-icon text-white"><i class="fas fa-shield-check"></i></div>
                            Status Pengajuan
                        </a>
                        <a class="nav-link text-white" href="<?= site_url('users/riwayatpengajuan'); ?>">
                            <div class="sb-nav-link-icon text-white"><i class="fas fa-history"></i></div>
                            Riwayat Pengajuan
                        </a>
                        <a class="nav-link text-white" href="<?= site_url('users/disposisi'); ?>">
                            <div class="sb-nav-link-icon text-white"><i class="fas fa-share-square"></i></div>
                            Disposisi Masuk
                        </a>
                    </nav>
                </div>

                <a class="nav-link text-white" href="<?= site_url('users/laporan'); ?>">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-file-alt"></i></div>
                    Laporan
                </a>

                <!-- Role Khusus -->
                <?php $role = $this->session->userdata('active_role_code'); ?>
                <?php if (in_array($role, ['yss', 'sdm'])): ?>
                    <a class="nav-link text-white" href="<?= site_url('users/arsip'); ?>">
                        <div class="sb-nav-link-icon text-white"><i class="fas fa-archive"></i></div>
                        Arsip
                    </a>
                <?php endif; ?>

                <hr class="border-white opacity-75 mx-3">

                <!-- Logout -->
                <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <div class="sb-nav-link-icon text-white"><i class="fas fa-sign-out-alt"></i></div>
                    Logout
                </a>
            </div>
        </div>
    </nav>
</div>


<!-- koten -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <?php if (isset($content_view)): ?>
                <?php $this->load->view($content_view, isset($data) ? $data : []); ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Logout Modal -->
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