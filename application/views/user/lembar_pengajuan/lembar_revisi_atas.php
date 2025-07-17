<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container my-4">
    <div class="card shadow">
        <div class="card-header ">
            <h4 class="mb-0">Lembar Pengajuan</h4>
        </div>
        <div class="card-body">
            <!-- Flashdata -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success "><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>

            <form action="<?= site_url('users/lembarpengajuan/insert'); ?>" method="post" enctype="multipart/form-data" id="pengajuanForm">
                <input type="hidden" name="id_pengajuan" value="<?= $pengajuan->id_pengajuan ?>">
                <input type="hidden" name="user_id" value="<?= $this->session->userdata('id_user'); ?>">
                <input type="hidden" name="nama_role" value="<?= htmlspecialchars($nama_role); ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="no" class="form-label">No</label>
                        <input type="text" id="no" name="no" class="form-control" value="<?= $no_surat; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="unit" class="form-label">Unit Pengajuan</label>
                        <select id="unit" name="unit" class="form-select select2" required onchange="updateKlasifikasi()">
                            <option value="">-- Pilih Unit Pengajuan --</option>
                            <?php foreach ($unitpengajuan as $u): ?>
                                <option value="<?= $u['id_unit']; ?>">
                                    <?= $u['kode_unit'] . ' - ' . $u['nama_unit']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kode" class="form-label">Kode Pengajuan</label>
                        <select id="kode" name="kode" class="form-select select2" required>
                            <option value="">-- Pilih Klasifikasi --</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label">Perihal</label>
                    <input type="text" id="perihal" name="perihal" class="form-control" placeholder="Masukkan perihal pengajuan" value='<?= $pengajuan->perihal; ?>' required>
                </div>

                <div class="mb-3">
                    <label for="tujuan" class="form-label">Role Tujuan</label>
                    <select name="tujuan" id="tujuan" class="form-control">
                        <option value="">Pilih Role Tujuan</option>
                        <?php
                        // Daftar role yang diizinkan berdasarkan role pengguna saat ini
                        $allowed_roles_for_warek1 = [
                            'Wakil Rektor 2',
                            'Wakil Rektor 3',
                            'Rektor',
                            'Dekan',
                            'Bagian Administrasi dan Akademik',
                            'Bagian Penelitian dan PKM',
                            'Pusat Penjamin Mutu',
                            'Perpustakaan dan Inovasi',
                            'Sistem Informasi dan Infrastruktur Jaringan',
                            'Perencanaan dan Pengembangan Sistem',
                            'Pengembangan Bahasa'
                        ];
                        $allowed_roles_for_dekan = [
                            'Wakil Rektor 1',
                            'Kaprodi'
                        ];
                        $allowed_roles_for_kaprodi = [
                            'Dosen',
                            'Dekan'
                        ];
                        $allowed_roles_for_baa = [
                            'Wakil Rektor 1',
                            'Subag PDDIKTI dan Beasiswa',
                            'Subag Administrasi Perkuliahan'
                        ];
                        $allowed_roles_for_penelitian_pkm = [
                            'Wakil Rektor 1',
                            'Subag Penelitian dan Luaran',
                            'Subag PKM dan Luaran'
                        ];
                        // $allowed_roles_for_pengembangan_bahasa = [
                        //     'Wakil Rektor 1', 'Badan Administrasi dan Akademik', 'Bagian Penelitian dan PKM', 'Dekan',
                        //     'Perencanaan Dan Pengembangan Sistem', 'Sistem Informasi Dan Infrastruktur Jaringan', 'Perpustakaan Dan Inovasi'
                        // ];

                        $allowed_roles_for_perencanaan_pengembangan_sistem = [
                            'Wakil Rektor 1',
                            'Subag Perencanaan Sistem',
                            'Subag Pengembangan Sistem'
                        ];
                        $allowed_roles_for_si_infrastruktur_jaringan = [
                            'Wakil Rektor 1',
                            'LAB'
                        ];
                        // $allowed_roles_for_perpus_inovasi = [
                        //     'Wakil Rektor 1', 'Badan Administrasi dan Akademik', 'Bagian Penelitian dan PKM', 'Pengembangan Bahasa',
                        //     'Perencanaan Dan Pengembangan Sistem', 'Sistem Informasi Dan Infrastruktur Jaringan', 'Dekan'
                        // ];

                        $allowed_roles_for_warek2 = [
                            'Wakil Rektor 1',
                            'Wakil Rektor 3',
                            'Pengembangan dan SDM',
                            'Keuangan',
                            'Operasional Pembelajaran dan Umum',
                            'Pusat Penjamin Mutu',
                            'Rektor'
                        ];
                        $allowed_roles_for_pengembangan_sdm = [
                            'Wakil Rektor 2',
                            'Subag Ketenagaan',
                            'Pengembangan Karir Dosen dan Tendik'
                        ];
                        $allowed_roles_for_keuangan = [
                            'Wakil Rektor 2',
                            'Subag Penerimaan Keuangan',
                            'Subag Anggaran dan Pengeluaran',
                            'Subag Akuntansi'
                        ];
                        $allowed_roles_for_operasional_pembelajaran_umum = [
                            'Wakil Rektor 2',
                            'Subag Operasional',
                            'Subag Umum'
                        ];

                        $allowed_roles_for_warek3 = [
                            'Wakil Rektor 1',
                            'Wakil Rektor 2',
                            'Rektor',
                            'Pusat Penjamin Mutu',
                            'Kemahasiswaan dan Konseling',
                            'Pemasaran dan Kerjasama',
                            'Bina Insani Career',
                            'Kewirausahaan'
                        ];
                        $allowed_roles_for_digital_marketing = [
                            'Pemasaran dan Kerjasama',
                            'Data Analyst',
                            'Konten Editor',
                            'Konten Kreator'
                        ];
                        $allowed_roles_for_kemahasiswaan_konseling = [
                            'Wakil Rektor 3',
                            'Subag Kemahasiswaan',
                            'Subag Konseling'
                        ];
                        $allowed_roles_for_pemasaran_kerjasama = [
                            'Wakil Rektor 3',
                            'Subag Digital Marketing',
                            'Subag Admisi PMB',
                            'Subag Kerjasama Marketing dan Humas'
                        ];
                        $allowed_roles_for_bic = [
                            'Wakil Rektor 3',
                            'Subag Pemagangan dan Penempatan Kerja',
                            'Subag Tracer Study'
                        ];
                        $allowed_roles_for_kewirausahaan = [
                            'Wakil Rektor 3',
                            'Subag Inkubasi Bisnis',
                            'Subag Pengembangan Starup'
                        ];

                        $allowed_roles_for_ppm = [
                            'Wakil Rektor 1',
                            'Wakil Rektor 2',
                            'Rektor',
                            'Wakil Rektor 3',
                            'Monitoring dan Evaluasi',
                            'Dokumen Pelaporan',
                            'SPME'
                        ];
                        // $allowed_roles_for_monitoring_eval = [
                        //     'Pusat Penjamin Mutu', 'Dokumen Pelaporan', 'SPME'
                        // ];
                        // $allowed_roles_for_dokumen_pelaporan = [
                        //     'Pusat Penjamin Mutu', 'Monitoring dan Evaluasi', 'SPME'
                        // ];
                        // $allowed_roles_for_spme = [
                        //     'Pusat Penjamin Mutu', 'Monitoring dan Evaluasi', 'Dokumen Pelaporan'
                        // ];

                        ?>

                        <?php foreach ($all_roles as $role): ?>
                            <?php
                            $tampilkan = true;
                            $nama_role = strtolower($role['nama_role']);

                            switch (strtolower($current_role)) {
                                case 'yayasan':
                                    // Yayasan boleh melihat semua role kecuali Admin
                                    if ($nama_role == 'admin') {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'dekan':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_dekan)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'wakil rektor 1':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_warek1)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'wakil rektor 2':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_warek2)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'wakil rektor 3':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_warek3)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'pusat penjamin mutu':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_ppm)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'subag digital marketing':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_digital_marketing)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'kaprodi':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_kaprodi)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'sistem informasi dan infrastruktur jaringan':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_si_infrastruktur_jaringan)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                case 'bagian administrasi dan akademik':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_baa)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'perencanaan dan pengembangan sistem':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_perencanaan_pengembangan_sistem)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'bagian penelitian dan pkm':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_penelitian_pkm)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'pengembangan dan sdm':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_pengembangan_sdm)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'keuangan':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_keuangan)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'operasional pembelajaran dan umum':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_operasional_pembelajaran_umum)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'kemahasiswaan dan konseling':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_kemahasiswaan_konseling)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'pemasaran dan kerjasama':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_pemasaran_kerjasama)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'bina insani career':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_bina_insani_career)) {
                                        $tampilkan = false;
                                    }
                                    break;
                                case 'kewirausahaan':
                                    if (!in_array($role['nama_role'], $allowed_roles_for_kewirausahaan)) {
                                        $tampilkan = false;
                                    }
                                    break;

                                default:
                                    // Role selain yang dispesifikasikan akan menampilkan semua role
                                    break;
                            }
                            ?>

                            <?php if ($tampilkan): ?>
                                <option value="<?php echo $role['id_role']; ?>">
                                    <?php echo htmlspecialchars(ucfirst($role['nama_role'])); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="disposisi" class="form-label">Disposisi</label>
                    <textarea id="disposisi" name="disposisi" class="form-control" placeholder="Masukkan disposisi atau keterangan pengajuan..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="dokumen" class="form-label">Dokumen Pendukung</label>
                    <input type="file" id="dokumen" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div id="selectedFiles" class="mt-2"></div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="batalkanForm()">Batalkan</button>
                    <button type="submit" class="btn btn-success">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS: jQuery, Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });

    const klasifikasi = <?= json_encode($klasifikasi); ?>;

    function updateKlasifikasi() {
        const unitSelect = document.getElementById('unit');
        const kodeSelect = document.getElementById('kode');
        const selectedUnitId = unitSelect.value;

        kodeSelect.innerHTML = '<option value="">-- Pilih Klasifikasi --</option>';

        const filtered = klasifikasi.filter(k => k.id_unit === selectedUnitId);
        filtered.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id_klasifikasi_surat;
            opt.textContent = `${k.kode_surat} - ${k.nama_surat}`;
            kodeSelect.appendChild(opt);
        });

        $(kodeSelect).trigger('change'); // refresh select2
    }

    // === File Upload Multiple with Preview & Remove ===
    let selectedFiles = [];
    const fileInput = document.getElementById('dokumen');
    fileInput.addEventListener('change', handleFileSelection);

    function handleFileSelection() {
        const newFiles = Array.from(fileInput.files);
        newFiles.forEach(f => {
            if (!selectedFiles.some(s => s.name === f.name && s.size === f.size)) {
                selectedFiles.push(f);
            }
        });
        renderFileTags();
        updateInputFiles();
    }

    function renderFileTags() {
        const container = document.getElementById('selectedFiles');
        container.innerHTML = '';
        selectedFiles.forEach((f, i) => {
            const tag = document.createElement('div');
            tag.className = 'badge bg-secondary me-2 mb-1';
            tag.innerHTML = `${f.name} <button type="button" class="btn-close btn-close-white btn-sm ms-1" onclick="removeFile(${i})"></button>`;
            container.appendChild(tag);
        });
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderFileTags();
        updateInputFiles();
    }

    function updateInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function batalkanForm() {
        document.getElementById('pengajuanForm').reset();
        selectedFiles = [];
        renderFileTags();
        $('.select2').val(null).trigger('change');
    }
</script>