<?php
if ($export == 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$title.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
if ($export == 'web') {
    ?>
    <!--<body onload="window.print();">-->
    <?php
}
?>
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/demo/demo2/media/img/logo/favicon.ico" />
<h2><?php echo strtoupper($page_title); ?></h2>
<h3>POLITEKNIK KESEHATAN RIAU</h3>
<small>Jl. Melur No. 103 Pekanbaru Telp.(0761) 36581, Fax. 20656, E-mail: pkr.riau@gmail.com</small><br />
<hr />
    <style>
     body{
         font-family: Arial, Helvetica, sans-serif;
         font-size: 12px;
     }
     .report-title{
         text-align:center;
         font-size:18px;
         font-weight:bold;
         margin-bottom:15px;
     }
     table{
         border-collapse: collapse;
         width:100%;
     }
     th, td{
         border:1px solid #000;
         padding:6px;
         vertical-align:top;
         font-family: calibri;
     }
     th{
         background:#f2f2f2;
         text-align:center;
         font-weight:bold;
     }
     td.center{ text-align:center; }
     td.right{ text-align:right; }
     td.wrap{ white-space: normal; }
 </style>
<h2><?php echo $page_title; ?></h2><br>
<table border="1" width="100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>No.Anggota</th>
            <th>Nama</th>
            <?php if ($jenis != 'anggota+luar') { ?>
                <th>Program Studi</th>
            <?php } ?>
            <th>NO INV</th>
            <th>NO Barcode</th>
            <th>Judul Buku</th>
            <th>Tgl.Pinjam</th>
            <th>Batas Kembali</th>
            <th>Peminjaman Mandiri</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($data as $row) {
            ?>
            <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $row->no_anggota ?></td>
                <td><?php echo $row->nama ?></td>
                <?php if ($jenis != 'anggota+luar') { ?>
                    <td><?php echo $row->kelas ?></td>
                <?php } ?>
                <td><?php echo $row->no_inv ?></td>
                <td><?php echo $row->no_barcode ?></td>
                <td><?php echo $row->judul ?></td>
                <td><?php echo $row->tgl_pinjam ?></td>
                <td><?php echo $row->batas ?></td>
                <td><?php echo $row->is_mandiri != NULL ? $row->is_mandiri : "-";?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
</table>