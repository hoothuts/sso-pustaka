<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <title>Bukti Pengembalian Buku Mandiri</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            color: #333;
        }

        /* HEADER */
        .header {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            text-align: center;
            height: 120px;
        }

        /* TITLE */
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 90px;
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
            text-align: center;
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
</head><body>

<!-- TITLE -->
<div class="title">
    BUKTI PENGEMBALIAN BUKU MANDIRI
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
            <td>: <?= htmlspecialchars($siswa->nis ?? $siswa->nis) ?></td>
        </tr>
        <tr>
            <td class="label">Program Studi</td>
            <td>: <?= htmlspecialchars($siswa->kelas) ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal Pengembalian</td>
            <td>: <?= formatTanggalIndonesia($tgl_pengembalian) ?></td>
        </tr>
    </table>
</div>

<!-- TABLE -->
<table class="data">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">No Barcode</th>
            <th width="40%">Judul Buku</th>
            <th width="25%">Penulis</th>
            <th width="15%">Tanggal Pinjam</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($peminjaman)): ?>
            <tr>
                <td colspan="5" class="text-center">Tidak ada data pengembalian pada tanggal ini.</td>
            </tr>
        <?php else: ?>
            <?php $no = 1; foreach ($peminjaman as $p): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $p->no_barcode ?></td>
                    <td><?= htmlspecialchars($p->judul) ?></td>
                    <td><?= htmlspecialchars($p->penulis) ?></td>
                    <td class="text-center"><?= formatTanggalIndonesia($p->tgl_pinjam) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- QR -->
<div class="qr-box">
    <p><strong>Scan QR Code untuk verifikasi pengembalian:</strong></p>
    <?php
    $data_qr = $siswa->no_anggota . '|' . $tgl_pengembalian;
    $encoded = base64_encode($data_qr);
    $qr_link = "https://lib.pkr.ac.id/verifikasi/pengembalian/" . urlencode($encoded);
    ?>
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=<?= urlencode($qr_link) ?>" 
         alt="QR Code">
</div>

<!-- FOOTER -->
<div class="footer">
    <div class="footer-line">
        Terima kasih telah mengembalikan buku secara mandiri.
    </div>
    <div>
        Dicetak pada: <?= date('d F Y H:i:s') ?>
    </div>
</div>

</body></html>