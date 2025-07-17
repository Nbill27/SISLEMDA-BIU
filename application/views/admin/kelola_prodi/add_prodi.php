<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-4">Tambah Prodi</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Prodi</h6>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo site_url('admins/kelolaprodi/add_prodi'); ?>">
                    <div class="form-group">
                        <label for="nama_prodi">Nama Prodi</label>
                        <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" required>
                    </div> <br>
                    <div class="form-group">
                        <label for="id_fakultas">Fakultas</label>
                        <select class="form-control" id="id_fakultas" name="id_fakultas" required>
                            <option value="">Pilih Fakultas</option>
                            <?php foreach ($fakultas as $fakultas): ?>
                                <option value="<?php echo $fakultas['id_fakultas']; ?>">
                                    <?php echo $fakultas['nama_fakultas']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Prodi</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='<?php echo site_url('admins/kelolaprodi/') ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>