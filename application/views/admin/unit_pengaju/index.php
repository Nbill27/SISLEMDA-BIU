<div class="row">
    <div class="col-xl-12">
        <h2 class="my-3">Kelola Unit Pengajuan</h2>

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

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Unit Pengajuan</h6>
                <a href="<?php echo site_url('admins/unitpengaju/add_unit_pengaju'); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Unit Pengajuan
                </a>
            </div>
            <div class="card-body">
                <!-- Tambahkan style overflow agar tabel bisa scroll horizontal -->
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered table-hover w-100" id="table-datatable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Unit</th>
                                <th>Nama Unit</th>
                                <th width="17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($unit_pengaju)): ?>
                                <?php $no = $this->uri->segment(3) ? $this->uri->segment(3) + 1 : 1; ?>
                                <?php foreach ($unit_pengaju as $unit): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $unit['kode_unit']; ?></td>
                                        <td><?php echo $unit['nama_unit']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url('admins/unitpengaju/edit_unit_pengaju/' . $unit['id_unit']); ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit </a>
                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $unit['id_unit']; ?>">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>

                                            <!-- Modal Bootstrap (centered) -->
                                            <div class="modal fade" id="deleteModal<?php echo $unit['id_unit']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $unit['id_unit']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $unit['id_unit']; ?>">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <p class="mt-3 mb-0">Yakin ingin menghapus unit pengajuan <strong><?php echo $unit['nama_unit']; ?></strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?php echo site_url('admins/unitpengaju/delete_unit_pengaju/' . $unit['id_unit']); ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                </div>
                </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?>
        </tbody>
        </table>
            </div>
            <!-- DataTables dan CSS -->
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
</div>
</div>