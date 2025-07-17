<!-- Tambahkan link Bootstrap Icons di <head> layout Anda -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .bg-purple {
        background-color: #9b59b6 !important;
        color: #fff;
    }
</style>
<div class="container my-4">
    <h4 class="mb-4 fw-bold">Detail Pengajuan</h4>
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="row mb-4">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>No Pengajuan:</strong><br>
                        <?= htmlspecialchars($arsip->no_pengajuan ?? '-') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Nama Pengaju:</strong><br>
                        <?= htmlspecialchars($arsip->nama_user ?? '-') ?>
                    </div>

                    <div class="mb-3">
                        <strong>Tanggal Pengajuan:</strong><br>
                        <?= isset($arsip->tanggal_pengajuan) ? date('d F Y', strtotime($arsip->tanggal_pengajuan)) : '-' ?>
                    </div>

                    <div class="mb-3">
                        <strong>Status Pengajuan:</strong><br>
                        <span class="badge bg-<?= ($arsip->status_pengajuan == 'disetujui') ? 'success' : (($arsip->status_pengajuan == 'ditolak') ? 'danger' : 'purple') ?>">
                            <?= ucfirst(htmlspecialchars($arsip->status_pengajuan ?? 'Belum diproses')) ?>
                        </span>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>Jenis Pengajuan:</strong><br>
                        <?= htmlspecialchars($arsip->nama_klasifikasi ?? '-') ?>
                    </div>
                    <div class="mb-3">
                        <strong>Perihal:</strong><br>
                        <?= htmlspecialchars($arsip->perihal ?? '-') ?>
                    </div>
                    <div class="mb-3">
                        <strong>Lampiran:</strong><br>
                        <?php if (!empty($arsip->lampiran)): ?>
                            <ul class="list-inline mb-0">
                                <?php foreach ($arsip->lampiran as $lampiran): ?>
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
                <?php if (!empty($arsip->disposisi)): ?>
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
                                <?php foreach ($arsip->disposisi as $d): ?>
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

            <!-- Tombol Kembali dan Unduh Disposisi -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="<?= site_url('users/arsip') ?>" class="btn btn-secondary btn-sm">
                        Kembali
                    </a>
                    <a href="<?= site_url('users/arsip/unduh_arsip/' . $arsip->id_pengajuan) ?>" target="_blank" class="btn btn-success btn-sm">
                        Unduh Disposisi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>