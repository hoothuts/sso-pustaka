<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Surat Hibah Buku</title>

<style>
body{
    font-family:Arial,sans-serif;
    font-size:12px;
    line-height:1.5;
}

.info-table{
    width:100%;
    border-collapse:collapse;
}

.info-table td{
    padding:3px;
}
</style>

</head>
<body>

<h2 style="text-align:center;">
    SURAT PERNYATAAN HIBAH BUKU DARI ALUMNI
</h2>

<p>
Saya yang bertandatangan di bawah ini :
</p>

<table class="info-table">
    <tr>
        <td style="width:200px;">N a m a</td>
        <td>: <?= htmlspecialchars($request->nama ?? '') ?></td>
    </tr>
    <tr>
        <td>NIM</td>
        <td>: <?= htmlspecialchars($request->nim ?? '') ?></td>
    </tr>
    <tr>
        <td>Prodi</td>
        <td>: <?= htmlspecialchars($request->kelas ?? '') ?></td>
    </tr>
    <tr>
        <td>Tahun Akademik</td>
        <td>: <?= htmlspecialchars($request->tahun_akademik ?? '') ?></td>
    </tr>
</table>

<p>
Menyumbangkan buku untuk koleksi Perpustakaan
Poltekkes Kemenkes Riau dengan rincian sebagai berikut :
</p>

<?php
$no = 1;
foreach($request->hibah as $h):
?>

<p><strong>BUKU <?= $no++ ?> :</strong></p>

<table class="info-table">
    <tr>
        <td style="width:200px;">1. Judul</td>
        <td>: <?= htmlspecialchars($h->judul_buku) ?></td>
    </tr>
    <tr>
        <td>2. Pengarang</td>
        <td>: <?= htmlspecialchars($h->pengarang) ?></td>
    </tr>
    <tr>
        <td>3. Penerbit</td>
        <td>: <?= htmlspecialchars($h->penerbit) ?></td>
    </tr>
    <tr>
        <td>4. Tempat Terbit</td>
        <td>: <?= htmlspecialchars($h->tempat_terbit) ?></td>
    </tr>
    <tr>
        <td>5. Tahun Terbit</td>
        <td>: <?= htmlspecialchars($h->tahun_terbit) ?></td>
    </tr>
    <tr>
        <td>6. No. ISBN</td>
        <td>: <?= htmlspecialchars($h->isbn) ?></td>
    </tr>
</table>

<br>

<?php endforeach; ?>

<p>
Demikian surat keterangan ini saya buat dengan
sebenarnya tanpa paksaan dari pihak manapun,
dan dapat dipergunakan sebagaimana mestinya.
</p>

<br>

<table style="width:100%;margin-top:10px;">
    <tr>
        <td style="width:50%;text-align:center;">
            Pekanbaru,
            <?= date('d F Y', strtotime($request->tgl_approve ?? '')) ?>
        </td>
        <td style="width:50%;"></td>
    </tr>

    <tr>
        <td style="text-align:center;">
            Penerima,
        </td>
        <td style="text-align:center;">
            Penyumbang,
        </td>
    </tr>

    <tr>
        <td style="height:100px;text-align:center;">

            <?php if(!empty($request->ttd_pstk)): ?>
                <img src="<?= FCPATH.'uploads/ttd/'.$request->ttd_pstk ?>"
                     style="height:90px;">
            <?php endif; ?>

        </td>

        <td></td>
    </tr>

    <tr>
        <td style="text-align:center;">
            (
            <?= htmlspecialchars($request->nama_pstk ?? '....................') ?>
            )
        </td>

        <td style="text-align:center;">
            (
            <?= htmlspecialchars($request->nama ?? '') ?>
            )
        </td>
    </tr>
</table>

</body>
</html>