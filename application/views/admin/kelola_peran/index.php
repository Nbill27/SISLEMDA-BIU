<div class="row">
    <div class="col-xl-12">
        <h2 class="my-3">Kelola Peran</h2>

        <!-- Flashdata -->
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

        <!-- Tabel Daftar Role -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Peran</h6>
                <!-- Tombol untuk membuka modal Tambah Peran -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="bi bi-plus-lg"></i> Tambah Peran
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover w-100" id="table-datatable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Peran</th>
                                <th>Nama Peran</th>
                                <th width="17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles)): ?>
                                <?php $no = $this->uri->segment(3) ? $this->uri->segment(3) + 1 : 1; ?>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?php echo htmlspecialchars($role['kode_role']); ?></td>
                                        <td><?php echo htmlspecialchars($role['nama_role']); ?></td>
                                        <td>
                                            <!-- Tombol Edit untuk membuka modal -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $role['id_role']; ?>">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            
                                            <!-- Tombol Hapus untuk membuka modal -->
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $role['id_role']; ?>">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="editModal<?= $role['id_role']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $role['id_role']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel<?= $role['id_role']; ?>">Edit Peran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="<?= site_url('admins/kelolaperan/edit_role_action'); ?>" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_role" value="<?= $role['id_role']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="kode_role_<?= $role['id_role']; ?>" class="form-label">Kode Peran</label>
                                                                    <input type="text" class="form-control" id="kode_role_<?= $role['id_role']; ?>" name="kode_role" value="<?= htmlspecialchars($role['kode_role']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="nama_role_<?= $role['id_role']; ?>" class="form-label">Nama Peran</label>
                                                                    <input type="text" class="form-control" id="nama_role_<?= $role['id_role']; ?>" name="nama_role" value="<?= htmlspecialchars($role['nama_role']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="deleteModal<?= $role['id_role']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $role['id_role']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?= $role['id_role']; ?>">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            Yakin ingin menghapus peran <strong><?= htmlspecialchars($role['nama_role']); ?></strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?= site_url('admins/kelolaperan/delete_role/' . $role['id_role']); ?>" class="btn btn-danger">Hapus</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data Peran.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Peran -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Form Tambah Peran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo site_url('admins/kelolaperan/add_role'); ?>">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="kode_role">Kode Peran</label>
                        <input type="text" class="form-control" id="kode_role" name="kode_role" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_role">Nama Peran</label>
                        <input type="text" class="form-control" id="nama_role" name="nama_role" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Peran</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DataTables Scripts & Styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-datatable').DataTable();
    });
</script>
