<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-4">Kelola Pengguna</h2>

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
                <a href="<?= site_url('admins/kelolapengguna/add_user'); ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Pengguna
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="table-datatable" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Inisial</th>
                                <th>Email</th>
                                <th>NIK</th>
                                <th>Role</th>
                                <th>Prodi</th>
                                <th>Fakultas</th>
                                <th width="18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $user['nama']; ?></td>
                                    <td><?= $user['username']; ?></td>
                                    <td><?= $user['inisial']; ?></td>
                                    <td><?= $user['email']; ?></td>
                                    <td><?= $user['nik']; ?></td>
                                    <td><?= $user['roles']; ?></td>
                                    <td><?= $user['nama_prodi']; ?></td>
                                    <td><?= $user['nama_fakultas']; ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= site_url('admins/kelolapengguna/edit_user/' . $user['id_user']); ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user['id_user']; ?>">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="deleteModal<?= $user['id_user']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $user['id_user']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $user['id_user']; ?>">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        Yakin ingin menghapus pengguna <strong><?= htmlspecialchars($user['nama']); ?></strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?= site_url('admins/kelolapengguna/delete_user/' . $user['id_user']); ?>" class="btn btn-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DataTables & Icons -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

        <script>
            $(document).ready(function() {
                $('#table-datatable').DataTable();
            });
        </script>
    </div>
</div>
