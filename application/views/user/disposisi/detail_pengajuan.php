<style>
    .bg-purple {
        background-color: #9b59b6 !important;
        color: #fff;
    }
</style>
<div class="container my-4">
    <h3 class="fw-bold text-dark my-3">Detail Pengajuan</h3>
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="row mb-4">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>No Pengajuan:</strong><br>
                        <?= htmlspecialchars($pengajuan->no_pengajuan ?? '-') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Nama Pengaju:</strong><br>
                        <?= htmlspecialchars($pengajuan->nama_user ?? '-') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Tanggal Pengajuan:</strong><br>
                        <?= isset($pengajuan->tanggal_pengajuan) ? date('d F Y', strtotime($pengajuan->tanggal_pengajuan)) : '-' ?>
                    </div>

                    <div class="mb-3">
                        <strong>Status Pengajuan:</strong><br>
                        <span class="badge bg-<?= ($pengajuan->status_pengajuan == 'disetujui') ? 'success' : (($pengajuan->status_pengajuan == 'ditolak') ? 'danger' : 'purple') ?>">
                            <?= ucfirst(htmlspecialchars($pengajuan->status_pengajuan ?? 'Belum diproses')) ?>
                        </span>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>Jenis Pengajuan:</strong><br>
                        <?= htmlspecialchars($pengajuan->nama_klasifikasi ?? '-') ?>
                    </div>
                    <div class="mb-3">
                        <strong>Perihal:</strong><br>
                        <?= htmlspecialchars($pengajuan->perihal ?? '-') ?>
                    </div>
                    <div class="mb-3">
                        <strong>Lampiran:</strong><br>
                        <?php if (!empty($pengajuan->lampiran)): ?>
                            <ul class="list-inline mb-0">
                                <?php foreach ($pengajuan->lampiran as $lampiran): ?>
                                    <li class="list-inline-item">
                                        <!-- Nama File -->
                                        <span class="ms-2"><?= htmlspecialchars($lampiran->nama_file) ?></span>
                                        <!-- Tombol Preview -->
                                        <a href="<?= base_url('assets/uploads/' . rawurlencode($lampiran->file_path)) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat Lampiran">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <!-- Tombol Download -->
                                        <a href="<?= base_url('assets/uploads/' . rawurlencode($lampiran->file_path)) ?>" download class="btn btn-sm btn-outline-success" title="Unduh Lampiran">
                                            <i class=" fa-solid fa-file-arrow-down"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada lampiran tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Riwayat Disposisi -->
            <div class="mb-3">
                <strong>Riwayat Disposisi:</strong><br>
                <?php if (!empty($pengajuan->disposisi)): ?>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-sm ">
                            <thead class="table-light text-center align-middle">
                                <tr>
                                    <th width=35%>Kepada YTH</th>
                                    <th>N/K</th>
                                    <th>Inisial Pengirim</th>
                                    <th width=13%>Tanggal</th>
                                    <th width=40%>Disposisi</th>
                                </tr>
                            </thead>
                            <tbody class="align-top">
                                <?php foreach ($pengajuan->disposisi as $d): ?>
                                    <tr>
                                        <td class="p-2">
                                            <?= htmlspecialchars($d->role_tujuan ?? '-') ?>
                                            <?php if (in_array(strtolower($d->role_tujuan), ['dosen', 'kaprodi']) && !empty($d->nama_prodi)): ?>
                                                <?= htmlspecialchars($d->nama_prodi) ?>.
                                            <?php elseif (strtolower($d->role_tujuan) === 'dekan' && !empty($d->nama_fakultas)): ?>
                                                <?= htmlspecialchars($d->nama_fakultas) ?>.
                                            <?php endif; ?>
                                            <br>
                                            <?= htmlspecialchars($d->nama_tujuan ?? '-') ?>
                                        </td>
                                        <td class="p-2"><?= htmlspecialchars($d->urutan ?? '-') ?></td>
                                        <td class="p-2"><?= htmlspecialchars($d->inisial_pengirim ?? '-') ?></td>
                                        <?php date_default_timezone_set('Asia/Jakarta'); ?>
                                        <td class="p-2"><?= date('d F Y', strtotime($d->tanggal_disposisi)) ?></td>
                                        <td class="p-2"><?= nl2br(htmlspecialchars($d->catatan)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Tidak ada data disposisi.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
    <!-- </div> -->
    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <a href="<?= site_url('users/disposisi') ?>" class="btn btn-secondary btn-sm">Kembali</a>

        <?php
        $role = strtolower($this->session->userdata('active_role'));
        $user1 = [
            'dosen',
            'pelayanan_akademik',
            'komputasi_data',
            'penelitian_pkm',
            'publikasi_hki',
            'inkubator_bisnis',
            'pendidikan_pelatihan',
            'pengembangan_karir',
            'pelayanan',
            'akuntansi',
            'pajak',
            'kerumahtanggaan',
            'sarpras',
            'upt_perpustakaan',
            'lab',
            'ppks',
            'data_analyst',
            'konten_editor',
            'monitoring_evaluasi',
            'pelaporan_data',
            'spme'
        ]; // isi dengan daftar role yang hanya bisa lanjutkan
        $user2 = [
            'kaprodi',
            'dekan',
            'bak',
            'lppm',
            'kerjasama',
            'keuangan',
            'umum',
            'si_infrastruktur_jaringan',
            'kemahasiswaan',
            'marketing_promosi',
            'bic',
            'ppm',
            'rektor',
            'wakil rektor 1',
            'wakil rektor 2',
            'wakil rektor 3'
        ]; // isi dengan role yang bisa tolak/revisi/lanjut/selesai
        ?>
        <?php if (in_array($role, $user1)): ?>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi" data-status="lanjut">Lanjutkan</button>
        <?php elseif (in_array($role, $user2)): ?>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi" data-status="ditolak">Tolak</button>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi" data-status="revisi">Revisi</button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi" data-status="lanjut">Setuju & Lanjutkan</button>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi" data-status="setuju">Setuju & Selesai</button>
            </div>
        <?php endif; ?>
    </div>


    <!-- Modal Kirim Disposisi -->
    <div class="modal fade" id="modalDisposisi" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form method="post" action="<?= site_url('users/disposisi/kirim') ?>">
                    <div class="modal-header border-0 justify-content-center">
                        <h4 class="modal-title text-center">Form Disposisi</h4>
                        <button type="button" class="btn-close position-absolute end-0 m-3" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_pengajuan" value="<?= htmlspecialchars($pengajuan->no_pengajuan) ?>" />
                        <input type="hidden" name="status_disposisi" id="statusDisposisi" />

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Silahkan Isi Disposis</label>
                            <textarea class="form-control" name="catatan" rows="4" required></textarea>
                        </div>

                        <div class="mb-3" id="tujuanDisposisi" style="display: none;">
                            <label class="form-label">Lanjutkan ke:</label>
                            <select name="ke_role" id="ke_role" class="form-select" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <?php foreach ($unit_list as $unit): ?>
                                    <?php
                                    $label = $unit['nama_role'];

                                    // Cek apakah ada nama_prodi atau nama_fakultas
                                    $has_location = false;
                                    if (!empty($unit['nama_prodi'])) {
                                        $label .= ' - ' . $unit['nama_prodi'];
                                        $has_location = true;
                                    } elseif (!empty($unit['nama_fakultas'])) {
                                        $label .= ' - ' . $unit['nama_fakultas'];
                                        $has_location = true;
                                    }

                                    // Jika ada lokasi (prodi/fakultas), tambahkan nama user
                                    if ($has_location && !empty($unit['nama'])) {
                                        $label .= ' (' . trim($unit['nama']) . ')';
                                    }
                                    ?>
                                    <option value="<?= $unit['id_role'] ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-sm">Kirim Disposisi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script: Tampilkan select tujuan jika status = lanjut -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('[data-status]');
            const statusInput = document.getElementById('statusDisposisi');
            const tujuanDiv = document.getElementById('tujuanDisposisi');
            const tujuanSelect = document.getElementById('ke_unit');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const status = btn.getAttribute('data-status');
                    console.log("Status klik:", status); // debug log
                    statusInput.value = status;

                    if (status === 'lanjut') {
                        tujuanDiv.style.display = 'block';
                        tujuanSelect.setAttribute('required', true);
                    } else {
                        tujuanDiv.style.display = 'none';
                        tujuanSelect.removeAttribute('required');
                    }
                });
            });
        });
    </script>