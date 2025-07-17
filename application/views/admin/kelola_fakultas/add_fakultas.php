<div class="row">
    <div class="col-xl-12">
        <h2 class="mb-4 mt-3">Tambah Fakultas</h2>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Fakultas</h6>
            </div>

            <div class="card-body">
                <form method="post" action="<?= site_url('admins/kelolafakultas/add_fakultas'); ?>">
                    <div class="form-group">
                        <label for="nama_fakultas">Nama Fakultas</label>
                        <input
                            type="text"
                            class="form-control <?= form_error('nama_fakultas') ? 'is-invalid' : ''; ?>"
                            id="nama_fakultas"
                            name="nama_fakultas"
                            value="<?= set_value('nama_fakultas'); ?>"
                            maxlength="100"
                            pattern="[A-Za-z\s]+"
                            title="Hanya huruf dan spasi"
                            required>
                        <div class="invalid-feedback"><?= form_error('nama_fakultas'); ?></div>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Fakultas</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='<?= site_url('admins/kelolafakultas'); ?>'">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>
