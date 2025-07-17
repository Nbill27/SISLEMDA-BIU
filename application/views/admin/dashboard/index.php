<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="row mb-4 mt-3">
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h1 class="mb-0">Dashboard</h1>
        </div>
        <div class="col-md-6 text-end">
            <h3><small id="tanggal-jam" class="text-muted"></small></h3>
        </div>
    </div>

    <div class="alert alert-success" role="alert">
        <h5 class="text-dark">Halo, <b><?= $user_name; ?>!</b></h5>
        <p>Selamat datang di halaman admin SISLEMDA. Di sini Anda dapat mengelola data prodi, fakultas, dan lainnya.</p>
    </div>

    <style>
        .dashboard-card {
            border: none;
            color: #fff;
            border-radius: 0.75rem;
            padding: 20px;
            transition: 0.3s;
            cursor: pointer;
        }
        .dashboard-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        .bg-primary-gradient { background: linear-gradient(135deg, #007bff, #00c6ff); }
        .bg-success-gradient { background: linear-gradient(135deg, #28a745, #80d993); }
        .bg-warning-gradient { background: linear-gradient(135deg, #ffc107, #ffe194); color: #212529; }
        .bg-danger-gradient { background: linear-gradient(135deg, #dc3545, #ff7b8a); }
        .bg-info-gradient { background: linear-gradient(135deg, #17a2b8, #65d3e4); }
        .bg-secondary-gradient { background: linear-gradient(135deg, #6c757d, #a3a6ab); }
        .bg-dark-gradient { background: linear-gradient(135deg, #343a40, #5c636a); }
    </style>

    <div class="row mb-4">
        <?php
        $cards = [
            ['label' => 'Total Pengguna', 'count' => $total_users, 'color' => 'primary-gradient', 'icon' => 'fas fa-users', 'link' => 'kelolapengguna'],
            ['label' => 'Total Prodi', 'count' => $total_prodis, 'color' => 'success-gradient', 'icon' => 'fas fa-book', 'link' => 'kelolaprodi'],
            ['label' => 'Total Fakultas', 'count' => $total_fakultas, 'color' => 'info-gradient', 'icon' => 'fas fa-university', 'link' => 'kelolafakultas'],
            ['label' => 'Klasifikasi Pengajuan', 'count' => $total_klasifikasi, 'color' => 'warning-gradient', 'icon' => 'fas fa-tags', 'link' => 'klasifikasipengajuan'],
            ['label' => 'Unit Pengaju', 'count' => $total_unit_pengaju, 'color' => 'dark-gradient', 'icon' => 'fas fa-building', 'link' => 'unitpengaju'],
            ['label' => 'Total Peran', 'count' => $total_roles, 'color' => 'secondary-gradient', 'icon' => 'fas fa-user-shield', 'link' => 'kelolaperan'],
            ['label' => 'Pengguna per Peran', 'count' => $total_pengguna_peran, 'color' => 'danger-gradient', 'icon' => 'fas fa-user-tag', 'link' => 'penggunaperan'],
        ];
        foreach ($cards as $card): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="dashboard-card bg-<?= $card['color']; ?> shadow h-100" onclick="window.location='<?= site_url('admins/' . $card['link']); ?>'">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase mb-1"><?= $card['label']; ?></div>
                            <div class="h4 mb-0 font-weight-bold"><?= $card['count']; ?></div>
                        </div>
                        <div><i class="<?= $card['icon']; ?> fa-3x" style="opacity: 0.4;"></i></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Chart Bulanan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Statistik Pengajuan per Bulan</h6>
        </div>
        <div class="card-body">
            <canvas id="pengajuanChart"></canvas>
        </div>
    </div>

    <!-- Chart Harian -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">Statistik Pengajuan per Hari (<?= date('F Y'); ?>)</h6>
        </div>
        <div class="card-body">
            <canvas id="pengajuanChartHarian"></canvas>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card shadow mb-4">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="table-users">
                    <thead>
                        <tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Prodi</th><th>Fakultas</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['nama']; ?></td>
                                <td><?= $user['username']; ?></td>
                                <td><?= $user['email']; ?></td>
                                <td><?= $user['roles']; ?></td>
                                <td><?= $user['nama_prodi']; ?></td>
                                <td><?= $user['nama_fakultas']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Pengajuan Terbaru -->
    <div class="card shadow mb-5">
        <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-success">Pengajuan Terbaru</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover w-100" id="table-pengajuan">
                    <thead>
                        <tr><th>No</th><th>No. Pengajuan</th><th>Jenis</th><th>Unit</th><th>Pengaju</th><th>Status</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengajuan_terbaru)): $no=1; foreach ($pengajuan_terbaru as $row): ?>
                            <?php
                            $status = strtolower($row['status_pengajuan']);
                            $badge_class = match ($status) {
                                'diterima', 'disetujui' => 'success',
                                'ditolak' => 'danger',
                                'direvisi' => 'warning',
                                'tidak tersedia' => 'secondary',
                                'di proses', 'diproses' => 'primary',
                                'dilanjutkan' => 'info',
                                default => 'light',
                            };
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['no_pengajuan']; ?></td>
                                <td><?= $row['nama_klasifikasi']; ?></td>
                                <td><?= $row['nama_unit']; ?></td>
                                <td><?= $row['nama_pengaju']; ?></td>
                                <td><span class="badge bg-<?= $badge_class; ?>"><?= ucfirst($status); ?></span></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#table-users, #table-pengajuan').DataTable({ responsive: true, autoWidth: false });
});

const chartData = <?= $chart_data ?>;

const ctx = document.getElementById('pengajuanChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.labels_bulan,
        datasets: [{
            label: 'Jumlah Pengajuan per Bulan',
            data: chartData.data_bulan,
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});

const ctxHarian = document.getElementById('pengajuanChartHarian').getContext('2d');
new Chart(ctxHarian, {
    type: 'bar',
    data: {
        labels: chartData.labels_hari,
        datasets: [{
            label: 'Jumlah Pengajuan per Hari',
            data: chartData.data_hari,
            backgroundColor: 'rgba(23, 162, 184, 0.7)',
            borderColor: 'rgba(23, 162, 184, 1)',
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});

function updateClock() {
    const hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    const bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    const now = new Date();
    const hasil = `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()} - ${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}:${now.getSeconds().toString().padStart(2,'0')}`;
    document.getElementById('tanggal-jam').innerText = hasil;
}
setInterval(updateClock, 1000); updateClock();
</script>
