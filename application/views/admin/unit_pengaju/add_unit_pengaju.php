<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-3">Tambah Unit Pengajuan</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Unit Pengajuan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admins/unitpengaju/add_unit_pengaju'); ?>">
                    <div class="form-group mb-3">
                        <label for="kode_unit">Kode Unit</label>
                        <input type="number" class="form-control" id="kode_unit" name="kode_unit" min="1" max="" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="nama_unit">Nama Unit (Hanya Huruf)</label>
                        <input type="text" class="form-control" id="nama_unit" name="nama_unit" maxlength="100" pattern="[A-Za-z\s]+" title="Hanya boleh huruf dan spasi" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Unit</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='<?= site_url('admins/unitpengaju/'); ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>
