<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f8;
    color: #333;
  }

  .timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    border-left: 3px solid #ddd;
    position: relative;
  }

  .timeline-item {
    position: relative;
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .timeline-item .dot {
    position: absolute;
    left: -9px;
    top: 0;
    width: 15px;
    height: 15px;
    background-color: #bbb;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #ccc;
  }

  .timeline-item.ditolak .dot {
    background-color: #e74c3c;
    box-shadow: 0 0 0 2px #e74c3c;
  }

  .timeline-item.direvisi .dot {
    background-color: #f1c40f;
    box-shadow: 0 0 0 2px #f1c40f;
  }

  .timeline-item.disetujui .dot {
    background-color: #2ecc71;
    box-shadow: 0 0 0 2px #2ecc71;
  }

  .timeline-item.tidak-tersedia .dot {
    background-color: #9b59b6;
    box-shadow: 0 0 0 2px #9b59b6;
  }

  .timeline-item.dilanjutkan .dot {
    background-color: #3498db;
    box-shadow: 0 0 0 2px #3498db;
  }

  .status-progress-dot {
    width: 20px;
    height: 20px;
    border: 3px solid #fff;
    border-radius: 50%;
    margin: 0 auto;
    box-shadow: 0 0 0 2px #ccc;
  }

  .status-progress-step.diproses .status-progress-label {
    color: #777777;
  }

  .status-progress-step.diproses .status-progress-dot {
    background-color: #bbbbbb;
    box-shadow: 0 0 0 2px #cccccc;
  }

  .status-progress-step.ditolak .status-progress-dot {
    background-color: #e74c3c;
    box-shadow: 0 0 0 2px #e74c3c;
  }

  .status-progress-step.direvisi .status-progress-dot {
    background-color: #f1c40f;
    box-shadow: 0 0 0 2px #f1c40f;
  }

  .status-progress-step.disetujui .status-progress-dot {
    background-color: #2ecc71;
    box-shadow: 0 0 0 2px #2ecc71;
  }

  .status-progress-step.tidak-tersedia .status-progress-dot {
    background-color: #9b59b6;
    box-shadow: 0 0 0 2px #9b59b6;
  }

  .status-progress-step.tidak-tersedia .status-progress-label {
    color: #9b59b6;
  }

  .status-progress-step.dilanjutkan .status-progress-dot {
    background-color: #3498db;
    box-shadow: 0 0 0 2px #3498db;
  }

  @media (max-width: 576px) {
    .status-progress-step {
      width: 80px;
    }

    .status-progress-label {
      font-size: 0.75rem;
    }
  }
</style>

<div class="container py-4">
  <div class="row g-4">
    <!-- Timeline -->
    <div class="col-lg-4">
      <div class="p-4 bg-white rounded shadow-sm">
        <h3 class="fw-bold text-success mb-4">Status Disposisi</h3>
        <?php
        $this->db->select('disposisi.*, role.nama_role');
        $this->db->from('disposisi');
        $this->db->join('role', 'disposisi.ke_role = role.id_role', 'left');
        $this->db->where('disposisi.id_pengajuan', $pengajuan->id_pengajuan ?? 0);
        $this->db->order_by('disposisi.id_disposisi', 'asc');
        $query = $this->db->get();
        $disposisi_list = $query->result_array();

        $flow = [];
        foreach ($disposisi_list as $i => $d) {
          $flow[] = [
            'code' => $i + 1,
            'label' => $d['nama_role'],
            'date' => $d['tanggal_disposisi'] ? 'Diterima pada ' . date('d/m/Y', strtotime($d['tanggal_disposisi'])) : '',
            'status' => strtolower($d['status_disposisi']),
            'catatan' => $d['catatan'] ?? ''
          ];
        }

        $last_status = end($flow)['status'] ?? '';
        $current = count($flow);
        ?>

        <ul class="timeline">
          <?php foreach ($flow as $index => $step): ?>
            <?php
            $status_class = $step['status'];
            $colorClass = $status_class === 'ditolak' ? 'text-danger' : ($status_class === 'direvisi' ? 'text-warning' : 'text-secondary');
            ?>
            <li class="timeline-item <?= $status_class ?>">
              <div class="dot"></div>
              <div class="content">
                <strong><?= htmlspecialchars($step['label']) ?></strong><br>
                <small><?= htmlspecialchars($step['date']) ?></small><br>
                <?php if (in_array($status_class, ['ditolak', 'direvisi']) && !empty($step['catatan'])): ?>
                  <a href="#" class="<?= $colorClass ?> text-decoration-none" data-bs-toggle="modal"
                    data-bs-target="#catatanModal"
                    data-catatan="<?= htmlspecialchars($step['catatan']) ?>"
                    data-status="<?= $status_class ?>"
                    data-id="<?= $pengajuan->id_pengajuan ?? 0 ?>">
                    Lihat Catatan
                  </a>
                <?php endif; ?>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Detail Pengajuan-->
    <div class="col-lg-8">
      <div class="p-4 bg-white rounded shadow-sm h-100 d-flex flex-column justify-content-between">
        <h3 class="fw-bold text-success mb-4">Detail Pengajuan</h3>
        <div>
          <p><strong>No Pengajuan:</strong> <?= htmlspecialchars($pengajuan->no_pengajuan ?? '-') ?></p>
          <p><strong>Perihal:</strong> <?= htmlspecialchars($pengajuan->perihal ?? '-') ?></p>
          <p><strong>Jenis Pengajuan:</strong> <?= htmlspecialchars($pengajuan->nama_klasifikasi ?? '-') ?></p>
          <p><strong>Tanggal Pengajuan:</strong> <?= isset($pengajuan->tanggal_pengajuan) ? date('d/m/Y', strtotime($pengajuan->tanggal_pengajuan)) : '-' ?></p>

          <?php
          $status_raw = strtolower($pengajuan->status_pengajuan ?? '');
          $status_pengajuan = ucfirst($pengajuan->status_pengajuan ?? '-');

          $label_pengajuan = 'secondary'; // default
          switch ($status_raw) {
            case 'ditolak':
              $label_pengajuan = 'danger';
              break;
            case 'direvisi':
              $label_pengajuan = 'warning';
              break;
            case 'disetujui':
              $label_pengajuan = 'success';
              break;
            case 'dilanjutkan':
            case 'diproses':
              $label_pengajuan = 'secondary';
              break;
            case 'tidak tersedia':
              $label_pengajuan = 'dark';
              break;
          }
          ?>

          <p><strong>Status Pengajuan:</strong>
            <span class="badge bg-<?= $label_pengajuan ?>"><?= $status_pengajuan ?></span>
          </p>
          <hr class="border-secondary opacity-75 ">
          <!-- Progress Status -->
          <p><strong>Infromasi Status Disposisi:</strong>
          <div class="status-progress-horizontal mt-4 mb-4">
            <div class="status-progress-steps d-flex justify-content-between">
              <?php
              $status_labels = ['diproses', 'tidak-tersedia', 'ditolak', 'direvisi', 'dilanjutkan', 'disetujui'];

              foreach ($status_labels as $status_label): ?>
                <div class="status-progress-step <?= $status_label ?>">
                  <div class="status-progress-dot"></div>
                  <div class="status-progress-label"><?= ucfirst(str_replace('-', ' ', $status_label)) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <!-- Tombol Kembali di bawah detail -->
        <div class="mt-2 text-start">
          <a href="<?= site_url('users/StatusPengajuan') ?>" class="btn btn-secondary btn-sm">
            Kembali
          </a>
        </div>
      </div>
    </div>

  </div>



  <!-- Modal Catatan -->
  <div class="modal fade" id="catatanModal" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" id="catatanModalContent">
        <div class="modal-header" id="catatanModalHeader">
          <h5 class="modal-title">Catatan Disposisi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body" id="catatanModalBody"></div>
        <div class="modal-footer" id="catatanModalFooter"></div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <script>
    const catatanModal = document.getElementById('catatanModal');
    catatanModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const catatan = button.getAttribute('data-catatan');
      const status = button.getAttribute('data-status');
      const idPengajuan = button.getAttribute('data-id');

      const modalHeader = document.getElementById('catatanModalHeader');
      const modalBody = document.getElementById('catatanModalBody');
      const modalFooter = document.getElementById('catatanModalFooter');

      modalHeader.className = 'modal-header';
      modalFooter.innerHTML = '';

      if (status === 'ditolak') {
        modalHeader.classList.add('bg-danger', 'text-white');
      } else if (status === 'direvisi') {
        modalHeader.classList.add('bg-warning', 'text-dark');
        modalFooter.innerHTML = `
          <a href="<?= base_url('users/lembarpengajuan/lembar_revisi/') ?>${idPengajuan}" class="btn btn-warning text-dark fw-bold">
            Ajukan Kembali
          </a>`;
      } else {
        modalHeader.classList.add('bg-secondary', 'text-white');
      }

      modalBody.innerHTML = `<p>${catatan}</p>`;
    });
  </script>
  >