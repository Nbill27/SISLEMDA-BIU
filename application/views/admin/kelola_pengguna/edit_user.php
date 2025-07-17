<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-3">Edit Pengguna</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Pengguna</h6>
            </div>

            <div class="card-body">
                <form method="post" action="<?php echo site_url('admins/kelolapengguna/edit_user/' . $user['id_user']); ?>">

                    <div class="form-group mb-3">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $user['nama']; ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                    </div>

                    <div class="form-group mb-3 position-relative">
                        <label for="password">Password (kosongkan jika tidak ingin ubah)</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <i class="fa fa-eye toggle-password" toggle="#password" style="position: absolute; top: 38px; right: 15px; cursor: pointer;"></i>
                    </div>

                    <div class="form-group mb-3">
                        <label for="inisial">Inisial</label>
                        <input type="text" class="form-control" id="inisial" name="inisial" value="<?php echo $user['inisial']; ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="nik">NIK</label>
                        <input type="number" class="form-control" id="nik" name="nik" value="<?php echo $user['nik']; ?>" pattern="[0-9]*" inputmode="numeric" title="NIK hanya boleh angka" min="0" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo site_url('admins/kelolapengguna/') ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan Font Awesome & Script JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.toggle-password', function () {
        let input = $($(this).attr('toggle'));
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>
