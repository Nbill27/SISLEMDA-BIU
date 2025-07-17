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
        <input type="hidden" name="user_id" value="<?= $this->session->userdata('id_user'); ?>">
        <input type="hidden" name="role_pengaju" value="<?= htmlspecialchars($role_pengaju); ?>">

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="no" class="form-label">No</label>
            <input type="text" id="no" name="no" class="form-control" value="<?= $no_pengajuan; ?>" readonly>
          </div>
          <div class="col-md-6">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="unit" class="form-label">Unit Pengaju</label>
            <select id="unit" name="unit" class="form-select select2" required onchange="updateKlasifikasi()">
              <option value=""> -- Pilih Unit Pengaju --</option>
              <?php foreach ($unitpengajuan as $u): ?>
                <option value="<?= $u['id_unit']; ?>"><?= $u['kode_unit'] . ' - ' . $u['nama_unit']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="kode" class="form-label">Jenis Pengajuan</label>
            <span class="form-text text-muted small">*Pilih Unit Pengaju sebelum memilih Jenis Pengajuan.</span>
            <select id="kode" name="kode" class="form-select select2" required>
              <option value=""> -- Pilih Jenis --</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="perihal" class="form-label">Perihal</label>
          <input type="text" id="perihal" name="perihal" class="form-control" placeholder="Masukkan perihal pengajuan" required>
        </div>

        <!-- list role yang memiliki pilihan, diambil dari kode role -->
        <?php
        $roles_with_select = array_map('strtolower', [
          'wr1',
          'wr2',
          'wr3',
          'ppm',
          'yys',
          'dekan',
          'kaprodi',
          'sijaring',
          'perpes',
          'baa',
          'penpkm',
          'sdm',
          'keuangan',
          'opsumum',
          'kemkons',
          'makerja',
          'bic',
          'kwu',
          'dmarketing'
        ]);

        $should_show_tujuan = in_array(strtolower($current_role), $roles_with_select);
        ?>

        <?php if ($should_show_tujuan): ?>
          <div class="mb-3">
            <label for="tujuan" class="form-label">Role Tujuan</label>
            <select name="tujuan" id="tujuan" class="form-select" required>
              <option value="">Pilih Role Tujuan</option>
              <?php
              // Daftar role yang diizinkan berdasarkan role pengguna saat ini, ini diambil dengan kode role yang sudah didefinisikan
              $allowed_roles_for_warek1 = array_map('strtolower', [
                'wr2',
                'wr3',
                'rektor',
                'ppm',
                'perpusinvo',
                'sijaring',
                'perpes',
                'dekan',
                'baa',
                'penpkm',
                'bahasa'
              ]);
              $allowed_roles_for_dekan = array_map('strtolower', [
                'wr1',
                'kaprodi'
              ]);
              $allowed_roles_for_kaprodi = array_map('strtolower', [
                'dosen',
                'dekan'
              ]);
              $allowed_roles_for_baa = array_map('strtolower', [
                'wr1',
                'pddikti',
                'admperk'
              ]);
              $allowed_roles_for_penelitian_pkm = array_map('strtolower', [
                'wr1',
                'peneluar',
                'pkmluar'
              ]);

              $allowed_roles_for_perencanaan_pengembangan_sistem = array_map('strtolower', [
                'wr1',
                'prcsistem',
                'pgnsistem'
              ]);
              $allowed_roles_for_si_infrastruktur_jaringan = array_map('strtolower', [
                'wr1',
                'lab'
              ]);

              $allowed_roles_for_warek2 = array_map('strtolower', [
                'Wr1',
                'Wr3',
                'sdm',
                'keuangan',
                'opsumum',
                'ppm',
                'rektor'
              ]);
              $allowed_roles_for_pengembangan_sdm = array_map('strtolower', [
                'wr2',
                'ktnaga',
                'pgdosen'
              ]);
              $allowed_roles_for_keuangan = array_map('strtolower', [
                'wr2',
                'pkuang',
                'anggaran',
                'akuntansi'
              ]);
              $allowed_roles_for_operasional_pembelajaran_umum = array_map('strtolower', [
                'wr2',
                'ops',
                'umum'
              ]);

              $allowed_roles_for_warek3 = array_map('strtolower', [
                'wr1',
                'wr2',
                'Rektor',
                'ppm',
                'kemkons',
                'makerja',
                'bic',
                'kwu'
              ]);
              $allowed_roles_for_digital_marketing = array_map('strtolower', [
                'makerja',
                'danalyst',
                'editor',
                'kreator'
              ]);
              $allowed_roles_for_kemahasiswaan_konseling = array_map('strtolower', [
                'wr3',
                'kemhas',
                'konseling'
              ]);
              $allowed_roles_for_pemasaran_kerjasama = array_map('strtolower', [
                'wr3',
                'dmarketing',
                'admisipmb',
                'humas'
              ]);
              $allowed_roles_for_bic = array_map('strtolower', [
                'wr3',
                'magang',
                'tracer'
              ]);
              $allowed_roles_for_kewirausahaan = array_map('strtolower', [
                'wr3',
                'inkubator',
                'starup'
              ]);

              $allowed_roles_for_ppm = array_map('strtolower', [
                'wr1',
                'wr2',
                'wr3',
                'rektor',
                'monev',
                'doklapor',
                'spme'
              ]);

              ?>

              <?php
              $current_lower = strtolower($current_role);
              foreach ($all_roles as $role):
                $tampilkan = true;
                $kode_role = strtolower($role['kode_role']);

                switch (strtolower($current_role)) {
                  case 'yys':
                    // Yayasan boleh melihat semua role kecuali Admin
                    if ($kode_role == 'adm') {
                      $tampilkan = false;
                    }
                    break;

                  case 'dekan':
                    if (!in_array(strtolower($role['kode_role']), $allowed_roles_for_dekan)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'wr1':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_warek1)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'wr2':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_warek2)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'wr3':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_warek3)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'ppm':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_ppm)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'dmarketing':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_digital_marketing)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'kaprodi':
                    if (!in_array(strtolower($role['kode_role']), $allowed_roles_for_kaprodi)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'sijaring':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_si_infrastruktur_jaringan)) {
                      $tampilkan = false;
                    }
                    break;

                  case 'baa':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_baa)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'perpes':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_perencanaan_pengembangan_sistem)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'penpkm':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_penelitian_pkm)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'sdm':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_pengembangan_sdm)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'keuangan':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_keuangan)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'opsumum':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_operasional_pembelajaran_umum)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'kemkons':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_kemahasiswaan_konseling)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'makerja':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_pemasaran_kerjasama)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'bic':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_bina_insani_career)) {
                      $tampilkan = false;
                    }
                    break;
                  case 'kwu':
                    if (!in_array(strtolower($role['nama_role']), $allowed_roles_for_kewirausahaan)) {
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
                    <?php echo htmlspecialchars(ucfirst(strtolower($role['nama_role']))); ?>
                  </option>
                <?php endif; ?>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <!-- Pilih Kaprodi jika tujuan adalah Kaprodi untuk Dekan -->
        <?php if ($current_role === 'dekan'): ?>
          <div class="mb-3" id="userTujuanContainer" style="display: none;">
            <label for="user_tujuan" class="form-label">Nama Kaprodi</label>
            <select name="user_tujuan" id="user_tujuan" class="form-select select2">
              <option value="">Pilih Kaprodi</option> <?php foreach ($kaprodi_satu_fakultas as $kp): ?>
                <option value="<?= $kp['id_user']; ?>"> <?= htmlspecialchars($kp['nama']) . ' (' . $kp['nama_prodi'] . ')'; ?>
                </option> <?php endforeach; ?>
            </select>
          </div> <?php endif; ?>

        <?php if ($current_role === 'wr1'): ?>
          <div class="mb-3" id="userTujuanContainer" style="display: none;">
            <label for="user_tujuan" class="form-label">Nama Dekan</label>
            <select name="user_tujuan" id="user_tujuan" class="form-control select2">
              <option value="">Pilih Kaprodi</option> <?php foreach ($kaprodi_satu_fakultas as $kp): ?>
                <option value="<?= $kp['id_user']; ?>"> <?= htmlspecialchars($kp['nama']) . ' (' . $kp['nama_prodi'] . ')'; ?>
                </option> <?php endforeach; ?>
            </select>
          </div> <?php endif; ?>

        <?php if ($current_role === 'yys'): ?>
          <div class="mb-3" id="user_tujuan_group" style="display:none;">
            <label for="user_tujuan_yayasan" class="form-label">Pilih Nama User Tujuan</label>
            <select class="form-select" name="user_tujuan_yayasan" id="user_tujuan_yayasan">
              <option value="">-- Pilih User Tujuan --</option>
              <?php foreach ($all_users as $user): ?>
                <option value="<?= $user['id_user']; ?>" data-role="<?= strtolower($user['kode_role']); ?>">
                  <?= $user['nama']; ?> (<?= $user['nama_role']; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="disposisi" class="form-label">Disposisi</label>
          <textarea id="disposisi" name="disposisi" class="form-control" placeholder="Masukkan disposisi atau keterangan pengajuan..."></textarea>
        </div>

        <!-- <div class="mb-3">
          <label for="dokumen" class="form-label">Dokumen Pendukung</label>
          <input type="file" id="dokumen" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          <div id="selectedFiles" class="mt-2"></div>
        </div> -->

        <div class="mb-3">
          <label for="dokumen" class="form-label">Dokumen Pendukung (Maks. 3 file / max 2MB per file)</label>
          <input type="file" id="dokumen" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
          <div id="selectedFiles" class="mt-2"></div>
        </div>

        <div class="mb-3">
          <label for="link_dokumen" class="form-label">Link Dokumen (opsional)</label>
          <input type="url" name="link_dokumen" class="form-control" placeholder="https://example.com/dokumen">
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
    kodeSelect.innerHTML = '<option value=""> -- Pilih Jenis --</option>';
    const filtered = klasifikasi.filter(k => k.id_unit === selectedUnitId);
    filtered.forEach(k => {
      const option = document.createElement('option');
      option.value = k.id_klasifikasi;
      option.textContent = `${k.kode_klasifikasi} - ${k.nama_klasifikasi}`;
      kodeSelect.appendChild(option);
    });
  }

  document.addEventListener("DOMContentLoaded", function() {
    const tujuanSelect = document.getElementById('tujuan');
    const userTujuanContainer = document.getElementById('userTujuanContainer');
    const userTujuanSelect = document.getElementById('user_tujuan');
    if (tujuanSelect) {
      tujuanSelect.addEventListener('change', function() {
        const selectedText = tujuanSelect.options[tujuanSelect.selectedIndex].text.toLowerCase();
        if (selectedText.includes('kaprodi')) {
          userTujuanContainer.style.display = 'block';
        } else {
          userTujuanContainer.style.display = 'none';
          userTujuanSelect.value = '';
        }
      });
    }
  });

  document.addEventListener("DOMContentLoaded", function() {
    const tujuanSelect = document.getElementById('tujuan');
    const userTujuanContainer = document.getElementById('user_tujuan_group');
    const userTujuanSelect = document.getElementById('user_tujuan_yayasan');
    if (!tujuanSelect || !userTujuanContainer || !userTujuanSelect) return;
    tujuanSelect.addEventListener('change', function() {
      const selectedText = tujuanSelect.options[tujuanSelect.selectedIndex].textContent.toLowerCase();
      const roleMap = ['kaprodi', 'dosen', 'dekan'];
      if (roleMap.some(role => selectedText.includes(role))) {
        userTujuanContainer.style.display = 'block'; // Clear and fetch user list 
        userTujuanSelect.innerHTML = '<option value="">Memuat data...</option>'; // Lakukan AJAX ke server 
        $.ajax({
          url: '<?= site_url("users/lembarpengajuan/get_users_by_role") ?>',
          method: 'POST',
          data: {
            role: selectedText
          },
          dataType: 'json',
          success: function(response) {
            userTujuanSelect.innerHTML = '<option value="">Pilih User</option>';
            if (Array.isArray(response)) {
              response.forEach(user => {
                const opt = document.createElement('option');
                opt.value = user.id_user;
                opt.textContent = user.nama;
                userTujuanSelect.appendChild(opt);
              });
            }
            $(userTujuanSelect).trigger('change'); // untuk select2 
          },
          error: function() {
            userTujuanSelect.innerHTML = '<option value="">Gagal memuat data</option>';
          }
        });
      } else {
        userTujuanContainer.style.display = 'none';
        userTujuanSelect.innerHTML = '<option value="">Pilih User</option>';
      }
    });
  });

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
      tag.className = 'badge bg-info text-dark me-1 mb-1';
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

  document.addEventListener('DOMContentLoaded', function() {
    const dokumenInput = document.getElementById('dokumen');
    const selectedFilesContainer = document.getElementById('selectedFiles');
    const maxFiles = 3;
    const maxSizeMB = 2;
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
    dokumenInput.addEventListener('change', function() {
      const files = Array.from(dokumenInput.files);
      selectedFilesContainer.innerHTML = ''; // Clear previous 
      if (files.length > maxFiles) {
        alert(`Maksimal upload ${maxFiles} file!`);
        dokumenInput.value = '';
        return;
      }
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSizeMB = file.size / (1024 * 1024);
        if (!allowedTypes.includes(file.type)) {
          alert(`File "${file.name}" tidak didukung. Tipe file tidak valid.`);
          dokumenInput.value = '';
          return;
        }
        if (fileSizeMB > maxSizeMB) {
          alert(`File "${file.name}" melebihi batas ${maxSizeMB} MB.`);
          dokumenInput.value = '';
          return;
        }
        // Tampilkan preview file 
        const badge = document.createElement('div');
        badge.className = 'badge bg-primary text-white me-2 mb-1';
        badge.innerText = `${file.name} (${fileSizeMB.toFixed(2)} MB)`;
        selectedFilesContainer.appendChild(badge);
      }
    });
  });
</script>