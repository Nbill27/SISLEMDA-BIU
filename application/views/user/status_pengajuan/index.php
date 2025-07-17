    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"> -->
    <div class="row">
        <div class=" col-xl-12">
            <h3 class="fw-bold text-dark my-3">Status Pengajuan</h3>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover w-100" id="dataTable" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pengajuan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($riwayat_pengajuan as $row) :
                                    // Lewati baris jika status adalah ditolak, disetujui, atau tidak tersedia
                                    if (in_array($row->status_pengajuan, ['ditolak', 'disetujui', 'tidak tersedia'])) {
                                        continue;
                                    }
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $row->no_pengajuan; ?></td>
                                        <td><?= date('d-m-Y', strtotime($row->tanggal_pengajuan)); ?></td>
                                        <td><?= $row->perihal; ?></td>
                                        <td>
                                            <?php
                                            switch ($row->status_pengajuan) {
                                                case 'diproses':
                                                    echo "<span class='badge bg-secondary'>Diproses</span>";
                                                    break;
                                                case 'direvisi':
                                                    echo "<span class='badge bg-warning'>Direvisi</span>";
                                                    break;
                                                // Tidak perlu tampilkan yang lain karena sudah difilter
                                                default:
                                                    echo "<span class='badge bg-secondary'>Status Tidak Diketahui</span>";
                                                    break;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('users/statuspengajuan/detail_status/' . $row->id_pengajuan); ?>" class="btn btn-link text-dark">
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

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>