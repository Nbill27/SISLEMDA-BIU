<div class="row">
  <div class="col-xl-12">
    <h2 class="my-3">Kelola Peran Pengguna</h2>

    <!-- Flashdata Messages -->
    <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

    <!-- Tabel Role User -->
    <div class="card shadow mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover w-100" id="table-datatable" cellspacing="0">
            <thead>
              <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Prodi</th>
                <th>Fakultas</th>
                <th style="min-width:195px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = $this->uri->segment(3) ? $this->uri->segment(3) + 1 : 1; ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td><?php echo $no++; ?></td>
                  <td><?php echo $user['username']; ?></td>
                  <td><?php echo $user['nama']; ?></td>
                  <td><?php echo $user['roles']; ?></td>
                  <td><?php echo $user['nama_prodi']; ?></td>
                  <td><?php echo $user['nama_fakultas']; ?></td>
                  <td>
                    <?php if (count($user['role_details']) < count($all_roles)): ?>
                      <button type="button" class="btn btn-sm btn-success mb-1"
                        onclick='showModalTambah(<?php echo $user["id_user"]; ?>, <?php echo json_encode($user["role_details"]); ?>)'>
                        Tambah Role
                      </button>
                    <?php endif; ?>
                    <?php if (!empty($user['role_details']) && count($user['role_details']) > 1): ?>
                      <button type="button" class="btn btn-sm btn-danger mb-1"
                        onclick='showModalHapus(<?php echo $user["id_user"]; ?>, <?php echo json_encode($user["role_details"]); ?>)'>
                        Hapus Role
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div> <!-- end card -->
  </div> <!-- end col -->
</div> <!-- end row -->

<!-- Modal Tambah Role -->
<div class="modal fade" id="modalTambahRole" tabindex="-1" aria-labelledby="modalTambahRoleLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= site_url('admins/penggunaperan/add_role_action') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_user" id="idUserTambah">

          <div class="mb-3">
            <label class="form-label">Pilih Role</label>
            <select name="id_role" class="form-control" id="roleSelectTambah" required>
              <!-- Diisi dinamis oleh JS -->
            </select>
          </div>

          <div class="mb-3" id="fakultasGroup" style="display: none;">
            <label class="form-label">Fakultas</label>
            <select name="id_fakultas" class="form-control" id="fakultasSelectTambah">
              <option value="" disabled selected>-- Pilih Fakultas --</option>
              <?php foreach ($fakultas_list as $f): ?>
                <option value="<?= $f['id_fakultas']; ?>"><?= $f['nama_fakultas']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3" id="prodiGroup" style="display: none;">
            <label class="form-label">Prodi</label>
            <select name="id_prodi" class="form-control" id="prodiSelectTambah">
              <option value="" disabled selected>-- Pilih Prodi --</option>
              <?php foreach ($prodi_list as $p): ?>
                <option value="<?= $p['id_prodi']; ?>"><?= $p['nama_prodi']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- Modal Hapus Role -->
<div class="modal fade" id="modalHapusRole" tabindex="-1" aria-labelledby="modalHapusRoleLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= site_url('admins/penggunaperan/delete_role_action') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalHapusRoleLabel">Hapus Role</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_user" id="idUserHapus">
          <div class="mb-3">
            <label class="form-label">Pilih Role yang ingin dihapus</label>
            <select name="id_role" class="form-control" id="roleSelectHapus" required>
              <!-- Diisi dinamis lewat JS -->
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  const ALL_ROLES = <?= json_encode($all_roles); ?>;

  $(document).ready(function() {
    $('#table-datatable').DataTable();
  });

  // Show Modal Tambah Role
  function showModalTambah(id_user, ownedRoles) {
    $('#idUserTambah').val(id_user);
    const select = $('#roleSelectTambah');
    select.empty();

    const ownedIds = ownedRoles.map(role => role.id_role);
    const availableRoles = ALL_ROLES.filter(role => !ownedIds.includes(role.id_role));

    if (availableRoles.length === 0) {
      select.append('<option disabled selected>Tidak ada role tersedia</option>');
    } else {
      select.append('<option disabled selected>-- Pilih Role --</option>');
      availableRoles.forEach(role => {
        select.append(`<option value="${role.id_role}">${role.nama_role}</option>`);
      });
    }

    // Reset fakultas/prodi visibility
    $('#fakultasGroup').hide();
    $('#prodiGroup').hide();
    $('#fakultasSelectTambah').val('');
    $('#prodiSelectTambah').val('');

    $('#modalTambahRole').modal('show');
  }


  // Show Modal Hapus Role
  function showModalHapus(id_user, roles) {
    $('#idUserHapus').val(id_user);
    const select = $('#roleSelectHapus');
    select.empty();

    select.append('<option disabled selected>-- Pilih Role --</option>');
    roles.forEach(role => {
      select.append(`<option value="${role.id_role}">${role.nama_role}</option>`);
    });

    $('#modalHapusRole').modal('show');
  }

  // Event listener saat memilih role → tampilkan fakultas/prodi bila perlu
  $(document).on('change', '#roleSelectTambah', function() {
    const selectedRoleId = parseInt($(this).val());
    const selectedRole = ALL_ROLES.find(r => r.id_role == selectedRoleId);
    const roleName = selectedRole?.nama_role?.toLowerCase();

    $('#fakultasGroup').hide();
    $('#prodiGroup').hide();

    if (roleName === 'dekan') {
      $('#fakultasGroup').show();
    } else if (roleName === 'dosen' || roleName === 'kaprodi') {
      $('#fakultasGroup').show();
      $('#prodiGroup').show();
    }
  });
</script>