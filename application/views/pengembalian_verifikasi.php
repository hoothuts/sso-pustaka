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

        /* A4 CONTAINER */
        .container {
            width: 794px;
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
            font-size: 14px;
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
            font-size: 14px;
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

        /* RESPONSIVE */
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

    <h2>VERIFIKASI PENGEMBALIAN BUKU MANDIRI</h2>
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
                <td class="label">Tanggal Pengembalian</td>
                <td>: <?= tanggal($tgl_pengembalian) ?></td>
            </tr>
        </table>
    </div>

    <h4>Daftar Buku yang Dikembalikan</h4>

    <!-- TABLE -->
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No Barcode</th>
                <th width="30%">Judul Buku</th>
                <th width="20%">Penulis</th>
                <th width="15%">Tanggal Pinjam</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pengembalian)): ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengembalian.</td>
                </tr>
            <?php else: $no=1; foreach($pengembalian as $p): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $p->no_barcode ?></td>
                    <td><?= htmlspecialchars($p->judul) ?></td>
                    <td><?= htmlspecialchars($p->penulis) ?></td>
                    <td class="text-center"><?= tanggal($p->tgl_pinjam) ?></td>
                    <td class="text-center">
                        <span class="status dikembalikan">Sudah Dikembalikan</span>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-line">
            QR Code ini discan oleh pustakawan untuk verifikasi pengembalian mandiri.
        </div>
        <div>
            Diverifikasi pada: <?= date('d F Y H:i:s') ?>
        </div>
    </div>

</div>

</body>
</html>