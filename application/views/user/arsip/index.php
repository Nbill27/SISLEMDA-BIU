<!-- Styles & Icons -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"> -->

<style>
    .bg-purple {
        background-color: #9b59b6 !important;
        color: #fff;
    }
</style>

<div class="container py-4">
    <div class="mb-4 border-bottom pb-2 d-flex justify-content-between align-items-center">
        <h3 class="fw-bold text-dark">Arsip Pengajuan</h3>
    </div>

    <!-- Filter Form -->
    <form method="post" action="<?= site_url('users/arsip') ?>" class="bg-light p-4 rounded shadow-sm mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" id="dari" value="<?= set_value('dari', $dari ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" id="sampai" value="<?= set_value('sampai', $sampai ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status Pengajuan</label>
                <select name="status" class="form-select" id="status">
                    <option value="">Semua Status</option>
                    <option value="disetujui" <?= ($status ?? '') == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="ditolak" <?= ($status ?? '') == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    <option value="tidak tersedia" <?= ($status ?? '') == 'tidak tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card">
        <div class="card-body">

            <?php if (empty($arsip)): ?>
                <div class="alert alert-info">Data tidak tersedia.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="arsipTable">
                        <thead class="table-light">
                            <tr>
                                <th>No </th>
                                <th>No Pengajuan</th>
                                <th>Nama Pengaju</th>
                                <th>Jenis Pengajuan</th>
                                <th>Perihal</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1 ?>
                            <?php foreach ($arsip as $row): ?>
                                <?php
                                $status = strtolower($row->status_pengajuan);
                                if ($status == 'disetujui') {
                                    $badgeClass = 'bg-success';
                                } elseif ($status == 'ditolak') {
                                    $badgeClass = 'bg-danger';
                                } elseif ($status == 'tidak tersedia') {
                                    $badgeClass = 'bg-purple';
                                }
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row->no_pengajuan) ?></td>
                                    <td><?= htmlspecialchars($row->nama_user) ?></td>
                                    <td><?= htmlspecialchars($row->nama_klasifikasi) ?></td>
                                    <td><?= htmlspecialchars($row->perihal) ?></td>
                                    <td><?= date('d F Y', strtotime($row->tanggal_pengajuan)) ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($row->status_pengajuan) ?></span></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('users/arsip/detail_arsip/' . $row->id_pengajuan) ?>" class="btn btn-link text-dark">
                                            <i class="fa-solid fa-eye"></i> </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/template/js/jquery-3.7.1.min.js'); ?>"></script>

<script>
    $(document).ready(function() {
        $('#arsipTable').DataTable({
            ordering: true,
            pageLength: 10
        });
    });
</script>