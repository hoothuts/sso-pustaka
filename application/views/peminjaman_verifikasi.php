<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>

    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #eef2f7;
            margin: 0;
            padding: 30px;
        }

        /* A4 STYLE CONTAINER */
        .container {
            width: 794px; /* Lebar A4 */
            min-height: 1123px;
            margin: auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* TITLE */
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 14px;
        }

        /* INFO CARD */
        .info {
            background: linear-gradient(135deg, #f8f9fb, #eef3f8);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid #e0e6ed;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 6px 4px;
            font-size: 13px;
        }

        .label {
            width: 200px;
            font-weight: bold;
            color: #34495e;
        }

        /* SECTION TITLE */
        h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        /* TABLE */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.data th {
            background: #2c3e50;
            color: white;
            padding: 10px;
            border: none;
        }

        table.data td {
            padding: 10px;
            border-bottom: 1px solid #e0e6ed;
        }

        table.data tr:nth-child(even) {
            background-color: #f8f9fb;
        }

        table.data tr:hover {
            background-color: #eef3f8;
        }

        .text-center {
            text-align: center;
        }

        /* STATUS BADGE */
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .dipinjam {
            background: #fff3cd;
            color: #856404;
        }

        .dikembalikan {
            background: #d4edda;
            color: #155724;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 13px;
            color: #7f8c8d;
        }

        .footer-line {
            border-top: 1px solid #e0e6ed;
            margin-top: 15px;
            padding-top: 10px;
        }

        /* RESPONSIVE (optional biar tetap enak di laptop kecil) */
        @media (max-width: 820px) {
            .container {
                width: 100%;
                padding: 20px;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <h2>VERIFIKASI PEMINJAMAN BUKU MANDIRI</h2>
    <p class="subtitle">Perpustakaan Poltekkes Kemenkes Riau</p>

    <!-- INFO -->
    <div class="info">
        <table>
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td>: <?= htmlspecialchars($siswa->nama ?? 'Tidak ditemukan') ?></td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td>: <?= htmlspecialchars($nim) ?></td>
            </tr>
            <tr>
                <td class="label">Program Studi</td>
                <td>: <?= htmlspecialchars($siswa->kelas) ?></td>
            </tr>
            <tr>
                <td class="label">Tanggal Peminjaman</td>
                <td>: <?= formatTanggalIndonesia($tgl_pinjam) ?></td>
            </tr>
        </table>
    </div>

    <h4>Daftar Buku yang Dipinjam</h4>

    <!-- TABLE -->
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Barcode</th>
                <th width="40%">Judul Buku</th>
                <th width="25%">Penulis</th>
                <th width="13%">Batas Kembali</th>
                <th width="7%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peminjaman)): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman.</td>
                </tr>
            <?php else: $no=1; foreach($peminjaman as $p): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $p->no_barcode ?></td>
                    <td><?= htmlspecialchars($p->judul) ?></td>
                    <td><?= htmlspecialchars($p->penulis) ?></td>
                    <td class="text-center"><?= date('d-M-Y', strtotime(($p->batas))); ?></td>
                    <td class="text-center">
                        <?php if ($p->kembali == 1): ?>
                            <span class="status dikembalikan">Sudah Dikembalikan</span>
                        <?php else: ?>
                            <span class="status dipinjam">Sedang Dipinjam</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-line">
            QR Code ini discan oleh pustakawan untuk verifikasi peminjaman mandiri.
        </div>
        <div>
            Diverifikasi pada: <?= date('d F Y H:i:s') ?>
        </div>
    </div>

</div>

</body>
</html>