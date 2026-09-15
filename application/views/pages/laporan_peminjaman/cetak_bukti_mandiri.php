<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bukti Peminjaman Mandiri</title>

<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #333;
    }

    /* TITLE */
    .title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .subtitle {
        text-align: center;
        font-size: 11px;
        color: #555;
        margin-bottom: 15px;
    }

    /* INFO BOX */
    .info-box {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 15px;
        background-color: #fafafa;
    }

    .info-table {
        width: 100%;
    }

    .info-table td {
        padding: 3px 5px;
    }

    .label {
        width: 180px;
        font-weight: bold;
        color: #555;
    }

    /* TABLE */
    table.data {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table.data th {
        background-color: #2c3e50;
        color: #fff;
        font-size: 11px;
        padding: 6px;
        border: 1px solid #2c3e50;
    }

    table.data td {
        border: 1px solid #ccc;
        padding: 6px;
    }

    table.data tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .text-center {
        text-align: center;
    }

    /* QR BOX */
    .qr-box {
        text-align: center;
        margin-top: 20px;
        padding: 10px;
        border: 1px dashed #bbb;
    }

    /* FOOTER */
    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 10px;
        color: #666;
    }

    .footer-line {
        border-top: 1px solid #ddd;
        margin-top: 10px;
        padding-top: 5px;
    }

</style>
</head>

<body>

<!-- TITLE -->
<div class="title">
    BUKTI PEMINJAMAN BUKU MANDIRI
</div>

<div class="subtitle">
    Perpustakaan Poltekkes Kemenkes Riau
</div>

<!-- INFO -->
<div class="info-box">
    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td>: <?= htmlspecialchars($siswa->nama ?? '') ?></td>
        </tr>

        <tr>
            <td class="label">NIM</td>
            <td>: <?= htmlspecialchars($siswa->nis ?? $siswa->no_anggota ?? '') ?></td>
        </tr>

        <tr>
            <td class="label">Program Studi</td>
            <td>: <?= htmlspecialchars($siswa->kelas ?? '') ?></td>
        </tr>

        <tr>
            <td class="label">Tanggal Peminjaman</td>
            <td>: <?= formatTanggalIndonesia($tgl_pinjam) ?></td>
        </tr>

        <tr>
            <td class="label">Jenis Peminjaman</td>
            <td>: Mandiri</td>
        </tr>
    </table>
</div>

<!-- TABLE -->
<table class="data">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">Barcode</th>
            <th width="40%">Judul Buku</th>
            <th width="20%">Penulis</th>
            <th width="12%">Pinjam</th>
            <th width="13%">Batas Kembali</th>
        </tr>
    </thead>

    <tbody>

        <?php if (empty($peminjaman)): ?>

            <tr>
                <td colspan="6" class="text-center">
                    Tidak ada peminjaman pada tanggal ini.
                </td>
            </tr>

        <?php else: ?>

            <?php $no = 1; ?>

            <?php foreach ($peminjaman as $p): ?>

                <tr>
                    <td class="text-center">
                        <?= $no++ ?>
                    </td>

                    <td>
                        <?= $p->no_barcode ?>
                    </td>

                    <td>
                        <?= $p->judul ?>
                    </td>

                    <td>
                        <?= $p->penulis ?>
                    </td>

                    <td class="text-center">
                        <?= formatTanggalIndonesia($p->tgl_pinjam) ?>
                    </td>

                    <td class="text-center">
                        <?= formatTanggalIndonesia($p->batas) ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </tbody>
</table>

<!-- QR -->
<div class="qr-box">

    <strong>Scan QR Code untuk verifikasi</strong>
    <br><br>

    <img
        src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=https://lib.pkr.ac.id/verifikasi/peminjaman/<?= urlencode(base64_encode(($siswa->no_anggota ?? '').'|'.$tgl_pinjam)) ?>"
        alt="QR Code">

</div>

<!-- FOOTER -->
<div class="footer">

    <div class="footer-line">
        Terima kasih telah menggunakan layanan Peminjaman Mandiri Perpustakaan
    </div>

    <div>
        Dicetak pada: <?= date('d F Y H:i:s') ?>
    </div>

</div>

</body>
</html>