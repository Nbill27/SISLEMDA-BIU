<div class="row">
    <div class="col-12">
        <h2 class="my-3">Kelola Fakultas</h2>

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show position-relative pe-5">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?= validation_errors('<div class="alert alert-danger alert-dismissible fade show position-relative pe-5">', '<button type="button" class="btn-close position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="alert" aria-label="Tutup"></button></div>'); ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Fakultas</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFakultasModal">
                    <i class="bi bi-plus-lg"></i> Tambah Fakultas
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-fakultas" class="table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Fakultas</th>
                                <th width="17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fakultas)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data fakultas</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($fakultas as $f): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($f['nama_fakultas']); ?></td>
                                        <td>
                                            <!-- Tombol Edit Modal -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editModal<?= $f['id_fakultas']; ?>">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>

                                            <!-- Tombol Hapus Modal -->
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?= $f['id_fakultas']; ?>">
                                                <i class="bi bi-trash3-fill"></i> Hapus
                                            </button>

                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="editModal<?= $f['id_fakultas']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $f['id_fakultas']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form method="post" action="<?= site_url('admins/kelolafakultas/edit_fakultas/' . $f['id_fakultas']); ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel<?= $f['id_fakultas']; ?>">Edit Fakultas</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="edit_nama_fakultas_<?= $f['id_fakultas']; ?>" class="form-label">Nama Fakultas</label>
                                                                    <input type="text" class="form-control" name="nama_fakultas" value="<?= htmlspecialchars($f['nama_fakultas']); ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="deleteModal<?= $f['id_fakultas']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $f['id_fakultas']; ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel<?= $f['id_fakultas']; ?>">Konfirmasi Hapus</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            Yakin ingin menghapus fakultas <strong><?= htmlspecialchars($f['nama_fakultas']); ?></strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?= site_url('admins/kelolafakultas/delete_fakultas/' . $f['id_fakultas']); ?>" class="btn btn-danger">Hapus</a>
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

        <!-- Modal Tambah Fakultas -->
        <div class="modal fade" id="addFakultasModal" tabindex="-1" aria-labelledby="addFakultasLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="<?= site_url('admins/kelolafakultas/add_fakultas'); ?>">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addFakultasLabel">Form Tambah Fakultas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_fakultas" class="form-label">Nama Fakultas</label>
                                <input type="text" class="form-control" name="nama_fakultas" id="nama_fakultas" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- DataTables, jQuery, Bootstrap Icons -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

        <script>
            $(function() {
                $('#table-fakultas').DataTable({ autoWidth: false });
            });
        </script>
    </div>
</div>
