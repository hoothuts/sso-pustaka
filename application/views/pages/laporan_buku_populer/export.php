<?php
if ($export == 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$page_title.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>
<style>
    body { font-family: Arial; font-size: 12px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
    th { background: #f2f2f2; text-align: center; }
</style>

<h2><?php echo strtoupper($page_title); ?></h2>
<h3>POLITEKNIK KESEHATAN RIAU</h3>
<hr />

<table border="1">
    <thead>
        <tr>
            <th>NO</th>
            <th>Judul Buku</th>
            <th>NO Klasifikasi</th>
            <th>ISBN</th>
            <th>Tahun Terbit</th>
            <th>Kategori</th>
            <th>Total Peminjaman</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($data as $row): ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><?php echo $row->judul; ?></td>
            <td align="center"><?php echo $row->no_klas; ?></td>
            <td><?php echo $row->isbn; ?></td>
            <td><?php echo $row->thn_terbit; ?></td>
             <td><?php echo $row->kategori; ?></td>
            <td align="center"><?php echo $row->total_dipinjam; ?> Kali</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>