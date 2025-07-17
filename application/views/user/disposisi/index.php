<div class="row">
    <div class="col-xl-12 ">
        <h3 class="fw-bold text-dark my-3">Disposisi Masuk</h3>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Disposisi Masuk</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="disposisiTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pengajuan</th>
                                <th>Perihal</th>
                                <th>Nama Pengaju</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($disposisi as $d): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($d['no_pengajuan']); ?></td>
                                    <td><?= htmlspecialchars($d['perihal']); ?></td>
                                    <td><?= htmlspecialchars($d['nama_pengaju']); ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('users/disposisi/detail/' . $d['id_pengajuan']) ?>" class="btn btn-link text-dark">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/js/jquery-3.7.1.min.js'); ?>"></script>

<script>
    // Pastikan DOM sudah siap sebelum menginisialisasi DataTable
    $(document).ready(function() {
        let table = new DataTable('#disposisiTable', {
            perPage: 10,
            perPageSelect: [10, 25, 50, 100],
            searchable: true,
            sortable: true,
            labels: {
                placeholder: "Cari...",
                perPage: "{select} baris per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} baris"
            }
        });
    });
</script>