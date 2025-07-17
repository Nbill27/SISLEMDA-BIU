<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4">Edit Role</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Role</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo site_url('admins/kelolaperan/edit_role/' . $role['id_role']); ?>">
                    <div class="form-group">
                        <label for="kode_role">Kode Peran</label>
                        <input type="text" class="form-control" id="kode_role" name="kode_role" value="<?php echo $role['kode_role']; ?>" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_role">Nama Peran</label>
                        <input type="text" class="form-control" id="nama_role" name="nama_role" value="<?php echo $role['nama_role']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan perubahan</button>
                    <a href="<?php echo site_url('admins/kelolaperan'); ?>" class="btn btn-secondary mt-3">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>