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
            <th>NO Anggota</th>
            <th>Nama Anggota</th>
            <th>Prodi</th>
            <th>Total Peminjaman</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($data as $row): ?>
        <tr>
            <td align="center"><?php echo $i++; ?></td>
            <td><?php echo $row->no_anggota; ?></td>
            <td><?php echo $row->nama; ?></td>
            <td><?php echo $row->kelas; ?></td>
            <td align="center"><?php echo $row->total_pinjam; ?> Kali</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>