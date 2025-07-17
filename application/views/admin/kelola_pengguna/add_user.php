<head>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            box-sizing: border-box;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 24px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }
    </style>
</head>
<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-4">Tambah Pengguna</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Pengguna</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo site_url('admins/kelolapengguna/add_user'); ?>">

                    <div class="form-group mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required pattern="[a-z]+" title="Username hanya boleh huruf kecil tanpa spasi">
                    </div>

                    <div class="form-group mb-3 position-relative">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6" title="Password minimal 6 karakter">
                        <i class="fa fa-eye toggle-password" toggle="#password" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
                    </div>

                    <div class="form-group mb-3">
                        <label for="inisial">Inisial</label>
                        <input type="text" class="form-control" id="inisial" name="inisial">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nik">NIK</label>
                        <input type="number" class="form-control" id="nik" name="nik" pattern="[0-9]*" inputmode="numeric" title="NIK hanya boleh angka" min="0" required>
                    </div>

                    
                    <div class="form-group mb-3">
                        <label for="role_id">Role</label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="">Pilih Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id_role']; ?>"><?php echo $role['nama_role']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group prodi-field mb-3" style="display: none;">
                        <label for="prodi_id">Prodi</label>
                        <select class="form-control" id="prodi_id" name="prodi_id">
                            <option value="">Pilih Prodi</option>
                            <?php foreach ($prodis as $prodi): ?>
                                <option value="<?php echo $prodi['id_prodi']; ?>" data-fakultas="<?php echo $prodi['id_fakultas'] ?? ''; ?>">
                                    <?php echo $prodi['nama_prodi']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group fakultas-field mb-3" style="display: none;">
                        <label for="fakultas_id">Fakultas</label>
                        <select class="form-control" id="fakultas_id" name="fakultas_id">
                            <option value="">Pilih Fakultas</option>
                            <?php foreach ($fakultas as $fakultas_item): ?>
                                <option value="<?php echo $fakultas_item['id_fakultas']; ?>"><?php echo $fakultas_item['nama_fakultas']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Pengguna</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='<?php echo site_url('admins/kelolapengguna/') ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#role_id').select2({ placeholder: "Pilih Role", width: '100%' });
        $('#prodi_id').select2({ placeholder: "Pilih Prodi", width: '100%' });
        $('#fakultas_id').select2({ placeholder: "Pilih Fakultas", width: '100%' });
    });
</script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
    var prodiField = document.querySelector('.prodi-field');
    var fakultasField = document.querySelector('.fakultas-field');

    function updateFields() {
        var selectedRole = $('#role_id').find(':selected').text().toLowerCase();

        prodiField.style.display = 'none';
        fakultasField.style.display = 'none';

        if (selectedRole === 'dosen' || selectedRole === 'kaprodi') {
            prodiField.style.display = 'block';
            fakultasField.style.display = 'block';
        } else if (selectedRole === 'dekan') {
            fakultasField.style.display = 'block';
        }
    }

    $('#role_id').on('change', updateFields);
    updateFields();
});
</script>