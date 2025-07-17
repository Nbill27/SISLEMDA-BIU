<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lembar Kendali</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            margin: 0mm 10mm 20mm 10mm;
            /* lebih kecil di kiri-kanan */
        }

        .header-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            /* border: 1px solid black; */

            padding: 3px 3px 3px 3px;
            /* jarak antar kolom */
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            vertical-align: top;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 6px;
            vertical-align: top;
        }

        .main-table th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .main-table td {
            text-align: left;
            word-wrap: break-word;
            word-break: break-word;
        }

        .signature-note {
            margin-top: 30px;
            font-style: italic;
            font-size: 9pt;
        }
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 30px;">
        <img src="<?= base_url('assets/img/logo_binainsani.png') ?>" alt="Logo Bina Insani" style="height: 70px;">
    </div>

    <div class="header-title">LEMBAR KENDALI</div>

    <!-- Tabel informasi pengajuan -->
    <table class="info-table">
        <tr>
            <td style="width: 20%;">No Pengajuan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%; padding-right:35px;"><?= htmlspecialchars($row->no_pengajuan) ?></td>

            <td style="width: 15%;">Jenis Pengajuan</td>
            <td style="width: 2%;">:</td>
            <td><?= htmlspecialchars($row->nama_klasifikasi) ?></td>
        </tr>
        <tr>
            <td style=" width: 20%;">Nama Pengaju</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%; padding-right:35px;"><?= htmlspecialchars($row->user_nama) ?></td>

            <td style="width: 15%;">Perihal</td>
            <td style="width: 2%;">:</td>
            <td><?= htmlspecialchars($row->perihal) ?></td>
        </tr>
        <tr>
            <td style="width: 20%;">Tanggal Pengajuan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 35%; padding-right:35px;"><?= date('d F Y', strtotime($row->tanggal_pengajuan)) ?></td>

            <td style="width: 15%;">Status</td>
            <td style="width: 2%;">:</td>
            <td><?= htmlspecialchars($row->status_pengajuan) ?></td>
        </tr>
    </table>

    <!-- Tabel riwayat disposisi -->
    <table class=" main-table">
        <thead>
            <tr>
                <th width=30%>Kepada Yth</th>
                <th width=5%>N/K</th>
                <th width=15%>Tanggal</th>
                <th width=10%>Inisial Pengirim</th>
                <th width=40%>Disposisi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($riwayat_disposisi)): ?>
                <?php foreach ($riwayat_disposisi as $d): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($d->ke_role ?? '-') ?>
                            <?php if (in_array(strtolower($d->ke_role), ['dosen', 'kaprodi']) && !empty($d->ke_user_prodi)): ?>
                                <?= htmlspecialchars($d->ke_user_prodi) ?>.
                            <?php elseif (strtolower($d->ke_role) == 'dekan' && !empty($d->ke_user_fakultas)): ?>
                                <?= htmlspecialchars($d->ke_user_fakultas) ?>.
                            <?php endif; ?>
                            <br>
                            <?= htmlspecialchars($d->ke_user_nama ?? '-') ?>
                        </td>
                        <td><?= htmlspecialchars($d->urutan) ?></td>
                        <td><?= date('d F Y', strtotime($d->tanggal_disposisi)) ?></td>
                        <td><?= htmlspecialchars($d->dari_inisial) ?></td>
                        <td><?= nl2br(htmlspecialchars($d->catatan)) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <tr>
                        <td style="height: 40px;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endfor; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php date_default_timezone_set('Asia/Jakarta'); ?>

    <p class="signature-note">
        *Dicetak oleh sistem pada <?= date('d/m/Y H:i') ?>
    </p>

</body>

</html>