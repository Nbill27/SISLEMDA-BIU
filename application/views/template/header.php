<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SISLEMDA | Universitas Bina Insani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?= base_url('assets/css/styles.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap JS (wajib) -->

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Saat hover, ubah warna teks jadi hitam */
        .dropdown-menu .dropdown-item:hover {
            color: #000 !important;
            background-color: #f8f9fa !important;
            /* opsional: warna hover ringan */
        }

        /* Role aktif - background kuning dan teks hitam */
        .dropdown-menu .dropdown-item.active {
            background-color: #FFF500 !important;
            color: #000 !important;
            font-weight: 600;
        }


        #layoutSidenav {
            display: flex;
            flex-direction: row;
            /* Changed back to row to place sidebar and content side-by-side */
            flex-grow: 1;
        }

        #layoutSidenav_nav {
            /* Sidebar styles remain the same */
        }

        #layoutSidenav_content {
            flex-grow: 1;
            display: flex;
            /* Add flex display to the content area */
            flex-direction: column;
            /* Arrange main and footer vertically */
        }

        main {
            flex-grow: 1;
            /* Allow main content to take up available vertical space */
        }

        #layoutSidenav_nav .sb-sidenav a,
        #layoutSidenav_nav .sb-sidenav .sb-sidenav-menu-heading,
        #layoutSidenav_nav .sb-sidenav .nav-link .sb-nav-link-icon i {
            color: white !important;
        }

        #layoutSidenav_nav .sb-sidenav .sb-sidenav-footer {
            color: white !important;
            background-color: rgba(0, 0, 0, 0.2);
        }

        footer.mt-auto {
            /* Remove margin-top: auto here */
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-white border-bottom border-3 border-success">
        <img src="<?php echo base_url('assets/img/logo_binainsani.png') ?>" alt="Logo" style="width: 220px; height: 40px; object-fit: contain;" />
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

        <ul class="navbar-nav ms-auto me-3 my-2 my-md-0 d-flex align-items-center gap-3">


            <li>
                <?php if ($this->session->userdata('active_role_code') != 'adm' && isset($roles) && is_array($roles) && count($roles) > 1): ?>
                    <div class="dropdown mt-1">
                        <a href="#" class="text-dark text-decoration-none dropdown-toggle p-2 rounded-3"
                            id="roleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="background-color: #FFF500;">
                            <?= ucwords($active_role ?? '-') ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow"
                            style="background-color: #00923F;" aria-labelledby="roleDropdown">
                            <?php foreach ($roles as $r): ?>
                                <?php $is_active = (strtolower($r->nama_role) == strtolower($active_role)); ?>
                                <li>
                                    <a href="#"
                                        class="dropdown-item text-white d-flex justify-content-between align-items-center <?= $is_active ? 'active' : '' ?>"
                                        data-role="<?= ucwords($r->nama_role) ?>"
                                        data-kode-role="<?= strtolower($r->kode_role) ?>"
                                        onclick="openRoleModal(this)">
                                        <span><i class="fas fa-user-tag me-2"></i><?= ucwords($r->nama_role) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (!empty($active_role)): ?>
                    <div class="mt-1 p-2 rounded-3 text-dark" style="background-color: #FFF500;">
                        <?= ucwords($active_role) ?>
                    </div>
                <?php endif; ?>


            </li>
            <!-- Notifikasi -->
            <li class="nav-item">
                <div class="bg-success rounded-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-bell text-white"></i>
                </div>
            </li>
        </ul>
    </nav>
    <!-- js ganti peran -->
    <div id="layoutSidenav">


        <!-- modal ganti peran -->
        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">
                    <div class="modal-header bg-warning text-dark ">
                        <h5 class="modal-title" id="roleModalLabel">Konfirmasi Ganti Peran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Apakah Anda yakin ingin mengganti peran ke <strong id="roleName"></strong>?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <a id="confirmRoleBtn" class="btn btn-success btn-sm">Ya, Ganti Peran</a>
                    </div>
                </div>
            </div>
        </div>