<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-3">Edit Unit Pengajuan</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Unit Pengajuan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?= site_url('admins/unitpengaju/edit_unit_pengaju/' . $unit['id_unit']); ?>">
                    
                    <!-- Kode Unit: angka, min 1 max 99 -->
                    <div class="form-group mb-3">
                        <label for="kode_unit">Kode Unit</label>
                        <input type="number" class="form-control" id="kode_unit" name="kode_unit"
                               value="<?= $unit['kode_unit']; ?>" min="1" max="" required>
                    </div>
                    
                    <!-- Nama Unit: hanya 2 digit angka -->
                    <div class="form-group mb-4">
                        <label for="nama_unit">Nama Unit (Hanya Huruf)</label>
                        <input type="text" class="form-control" id="nama_unit" name="nama_unit"
                               value="<?= $unit['nama_unit']; ?>" maxlength="100" pattern="[A-Za-z\s]+" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary btn-sm"
                            onclick="window.location.href='<?= site_url('admins/unitpengaju/'); ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>
