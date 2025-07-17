<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="container py-4">
  <div class="card shadow">
    <div class="card-header text-dark">
      <h4 class="mb-0">Lembar Revisi</h4>
    </div>
    <div class="card-body">
      <!-- Flashdata -->
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
      <?php endif; ?>

      <form action="<?= site_url('users/lembarpengajuan/submit_revisi'); ?>" method="post" enctype="multipart/form-data" id="pengajuanForm">
        <input type="hidden" name="id_pengajuan" value="<?= $pengajuan->id_pengajuan ?>">
        <input type="hidden" name="user_id" value="<?= $this->session->userdata('id_user'); ?>">
        <input type="hidden" name="nama_role" value="<?= htmlspecialchars($nama_role); ?>">

        <div class="row mb-3">
          <div class="col-md-6">
            <label for="no" class="form-label">No</label>
            <input type="text" id="no" name="no" class="form-control" value="<?= $pengajuan->no_surat; ?>" readonly>
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
              <option value=""> -- Pilih Unit Pengajuan --</option>
              <?php foreach ($unitpengajuan as $u): ?>
                <option value="<?= $u['id_unit']; ?>"><?= $u['kode_unit'] . ' - ' . $u['nama_unit']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="kode" class="form-label">Kode Pengajuan</label>
            <select id="kode" name="kode" class="form-select select2" required>
              <option value=""> -- Pilih Klasifikasi --</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="perihal" class="form-label">Perihal</label>
          <input type="text" id="perihal" name="perihal" class="form-control" placeholder="Masukkan perihal pengajuan" value="<?= $pengajuan->perihal; ?>" required>
        </div>

        <div class="mb-3">
          <label for="disposisi" class="form-label">Disposisi</label>
          <textarea id="disposisi" name="disposisi" class="form-control" rows="3" placeholder="Masukkan disposisi atau keterangan pengajuan..."></textarea>
        </div>

        <div class="mb-3">
          <label for="dokumen" class="form-label">Dokumen Pendukung</label>
          <input type="file" id="dokumen" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
          <?php if (!empty($lampiran) && isset($lampiran->file)): ?>
            <small class="text-muted">Dokumen sebelumnya:
              <a href="<?= base_url('uploads/dokumen/' . $lampiran->file); ?>" target="_blank"><?= $lampiran->file; ?></a>
            </small>
          <?php endif; ?>
        </div>

        <div id="selectedFiles" class="mb-3"></div>

        <div class="d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-outline-secondary" onclick="batalkanForm()">Batalkan</button>
          <button type="submit" class="btn btn-success">Kirim Revisi</button>
        </div>
      </form>
    </div>
  </div>
</div>

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
    kodeSelect.innerHTML = '<option value=""> -- Pilih Klasifikasi --</option>';
    const filtered = klasifikasi.filter(k => k.id_unit === selectedUnitId);
    filtered.forEach(k => {
      const option = document.createElement('option');
      option.value = k.id_klasifikasi_surat;
      option.textContent = `${k.kode_surat} - ${k.nama_surat}`;
      kodeSelect.appendChild(option);
    });
  }

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
      const tag = document.createElement('span');
      tag.className = 'badge bg-info text-dark me-1 mb-1';
      tag.innerHTML = `${f.name} <button type="button" class="btn-close btn-close-white btn-sm ms-1" onclick="removeFile(${i})" aria-label="Remove"></button>`;
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
    if (confirm("Yakin ingin membatalkan revisi?")) {
      document.getElementById("pengajuanForm").reset();
      selectedFiles = [];
      renderFileTags();
      updateInputFiles();
    }
  }
</script>