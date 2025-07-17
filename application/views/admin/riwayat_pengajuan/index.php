<div class="container-fluid mt-4">
    <h2 class="mb-4">Riwayat Pengajuan Terbaru</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">Data Pengajuan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-riwayat" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Pengajuan</th>
                            <th>Jenis Pengajuan</th>
                            <th>Nama Pengaju</th>
                            <th>Unit Pengaju</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengajuan_terbaru)): ?>
                            <?php $no = 1; foreach ($pengajuan_terbaru as $p): ?>
                                <?php
                                    $status = strtolower($p['status_pengajuan']);
                                    $badge_class = match ($status) {
                                        'disetujui', 'diterima'        => 'success',
                                        'ditolak'                      => 'danger',
                                        'direvisi'                     => 'warning',
                                        'tidak tersedia'               => 'secondary',
                                        'di proses', 'diproses'        => 'primary',
                                        'dilanjutkan'                  => 'info',
                                        default                        => 'light',
                                    };
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $p['no_pengajuan']; ?></td>
                                    <td><?= $p['nama_klasifikasi']; ?></td>
                                    <td><?= $p['nama_pengaju']; ?></td>
                                    <td><?= $p['nama_unit']; ?></td>
                                    <td>
                                        <span class="badge bg-<?= $badge_class; ?> text-uppercase">
                                            <?= ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($p['tanggal_pengajuan'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pengajuan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables & Bootstrap -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#table-riwayat').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                },
                emptyTable: "Tidak ada data tersedia"
            }
        });
    });
</script>
