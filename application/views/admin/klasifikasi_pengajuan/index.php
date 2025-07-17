<div class="row">
    <div class="col-xl-12">
        <h2 class="my-3">Kelola Klasifikasi Pengajuan</h2>

        <!-- Notifikasi -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <!-- Filter Unit Pengaju -->
        <form method="get" action="<?= site_url('admins/klasifikasipengajuan'); ?>" class="mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="unit" class="col-form-label">Filter Unit Pengaju:</label>
                </div>
                <div class="col-auto">
                    <select name="unit" id="unit" class="form-select">
                        <option value="">-- Semua Unit --</option>
                        <?php foreach ($unit_pengaju as $unit): ?>
                            <option value="<?= $unit['id_unit']; ?>" <?= ($this->input->get('unit') == $unit['id_unit']) ? 'selected' : ''; ?>>
                                <?= $unit['nama_unit']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </div>
        </form>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Klasifikasi Pengajuan</h6>
                <a href="<?php echo site_url('admins/klasifikasipengajuan/add_klasifikasi_pengajuan'); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Klasifikasi Pengajuan
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered table-hover w-100" id="table-datatable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pengajuan</th>
                                <th>Nama Pengajuan</th>
                                <th>Nama Unit Pengaju</th>
                                <th width="17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($klasifikasi_pengajuan)): ?>
                                <?php $no = $this->uri->segment(3) ? $this->uri->segment(3) + 1 : 1; ?>
                                <?php foreach ($klasifikasi_pengajuan as $klasifikasi): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $klasifikasi['kode_klasifikasi']; ?></td>
                                        <td><?php echo $klasifikasi['nama_klasifikasi']; ?></td>
                                        <td><?php echo $klasifikasi['nama_unit']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admins/klasifikasipengajuan/edit_klasifikasi_pengajuan/' . $klasifikasi['id_klasifikasi']); ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $klasifikasi['id_klasifikasi']; ?>">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="deleteModal<?php echo $klasifikasi['id_klasifikasi']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $klasifikasi['id_klasifikasi']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $klasifikasi['id_klasifikasi']; ?>">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <p class="mt-3 mb-0">Yakin ingin menghapus klasifikasi <strong><?php echo $klasifikasi['nama_klasifikasi']; ?></strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?php echo site_url('admins/klasifikasipengajuan/delete_klasifikasi_pengajuan/' . $klasifikasi['id_klasifikasi']); ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DataTables -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

        <script>
            $(document).ready(function() {
                $('#table-datatable').DataTable({
                    "autoWidth": false
                });
            });
        </script>
    </div>
</div>
