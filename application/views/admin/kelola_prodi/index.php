<div class="row">
    <div class="col-12">
        <h2 class="my-3">Kelola Prodi</h2>

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
                <h6 class="m-0 font-weight-bold text-primary">Daftar Prodi</h6>
                <a href="<?= site_url('admins/kelolaprodi/add_prodi'); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Prodi
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableProdi" class="table table-bordered table-hover w-100" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Prodi</th>
                                <th>Fakultas</th>
                                <th width="17%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = $this->uri->segment(3) ? $this->uri->segment(3) + 1 : 1; ?>
                            <?php foreach ($prodis as $prodi): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $prodi['nama_prodi']; ?></td>
                                    <td>
                                        <?php
                                        $related = array_filter(
                                            $fakultas,
                                            fn($f) =>
                                            isset($prodi['id_fakultas']) && $f['id_fakultas'] == $prodi['id_fakultas']
                                        );
                                        echo $related ? array_values($related)[0]['nama_fakultas'] : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admins/kelolaprodi/edit_prodi/' . $prodi['id_prodi']); ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal<?= $prodi['id_prodi']; ?>">
                                            <i class="bi bi-trash3-fill"></i> Hapus
                                        </button>

                                        <!-- Modal konfirmasi -->
                                        <div class="modal fade" id="deleteModal<?= $prodi['id_prodi']; ?>" tabindex="-1"
                                            aria-labelledby="deleteModalLabel<?= $prodi['id_prodi']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $prodi['id_prodi']; ?>">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        Yakin ingin menghapus prodi
                                                        <strong><?= $prodi['nama_prodi']; ?></strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?= site_url('admins/kelolaprodi/delete_prodi/' . $prodi['id_prodi']); ?>"
                                                            class="btn btn-danger">Hapus</a>
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

        <!-- DataTables, jQuery, Bootstrap Icons -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

        <script>
            $(function() {
                $('#tableProdi').DataTable({
                    autoWidth: false
                });
            });
        </script>
    </div>
</div>