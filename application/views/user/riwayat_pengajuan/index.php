<div class="row">
    <div class="col-xl-12 ">
        <h3 class="fw-bold text-dark my-3">Riwayat Pengajuan</h3>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Pengajuan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="riwayatTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pengajuan</th>
                                <th>Nama Pengaju</th>
                                <th>Jenis Pengajuan</th>
                                <th>Perihal</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($riwayat_data as $row): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_pengajuan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_pengajuan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['perihal']); ?></td>
                                    <td><?php echo date('d F Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    <td>
                                        <?php
                                        $status_color = '';
                                        switch ($row['status_pengajuan']) {
                                            case 'disetujui':
                                                $status_color = 'success';
                                                break;
                                            case 'ditolak':
                                                $status_color = 'danger';
                                                break;
                                            case 'tidak tersedia':
                                                $status_color = 'secondary';
                                                break;
                                            default:
                                                break;
                                        }
                                        ?>
                                        <span class="badge bg-<?php echo $status_color; ?>"><?php echo htmlspecialchars($row['status_pengajuan']); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('users/riwayatpengajuan/detail_pengajuan/' . $row['id_pengajuan']) ?>" class="btn btn-link text-dark">
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
        let table = new DataTable('#riwayatTable', {
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