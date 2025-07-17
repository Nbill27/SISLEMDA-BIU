<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-3">Tambah Klasifikasi Pengajuan</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Klasifikasi Pengajuan</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo site_url('admins/klasifikasipengajuan/add_klasifikasi_pengajuan'); ?>">

                    <div class="form-group mb-3">
                        <label for="kode_klasifikasi">Kode Pengajuan</label>
                        <input type="text" class="form-control" id="kode_klasifikasi" name="kode_klasifikasi" required pattern="^\d+(\.\d+ -)?$" title="Hanya boleh angka">
                    </div>

                    <div class="form-group mb-3">
                        <label for="nama_klasifikasi">Nama pengajuan</label>
                        <input type="text" class="form-control" id="nama_klasifikasi" name="nama_klasifikasi" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="id_unit">Unit Pengajuan</label>
                        <select class="form-control" id="id_unit" name="id_unit" required>
                            <option value="">-- Pilih Unit Pengajuan --</option>
                            <?php foreach ($unit_pengaju as $unit): ?>
                                <option value="<?php echo $unit['id_unit']; ?>">
                                    <?php echo $unit['nama_unit']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Simpan Klasifikasi</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='<?php echo site_url('admins/klasifikasipengajuan/') ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>