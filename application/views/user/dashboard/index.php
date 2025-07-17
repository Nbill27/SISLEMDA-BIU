<div class="row">
    <div class="col-xl-12">
        <h3 class="fw-bold text-dark my-3">Dashboard</h3>
        <!-- Konten Dashboard -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selamat Datang, <?php echo $this->session->userdata('name'); ?>!</h6>
                </div>
                <div class="card-body">
                    <p>Selamat datang di dashboard user. Berikut adalah ringkasan aktivitas Anda:</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Form LK</h5>
                                    <p class="card-text">Lihat dan isi form LK Anda.</p>
                                    <a href="<?php echo site_url('user/form_lk'); ?>" class="btn btn-light">Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Tracking</h5>
                                    <p class="card-text">Lacak status dokumen Anda.</p>
                                    <a href="<?php echo site_url('user/tracking'); ?>" class="btn btn-light">Lihat</a>
                                </div>
                            </div>
                        </div>
                        <?php if ($this->session->userdata('active_role') != 'non_jabatan'): ?>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Surat Masuk</h5>
                                        <p class="card-text">Cek surat masuk Anda.</p>
                                        <a href="<?php echo site_url('user/surat_masuk'); ?>" class="btn btn-light">Lihat</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>