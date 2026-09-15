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
            <?php if ($jenis == 'mahasiswa') { ?>
                <th>Program Studi</th>
            <?php } ?>
            <th>No. Inv</th>
            <th>Judul</th>
            <th>Nama</th>
            <th>Tgl.Kembali</th>
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
                <?php if ($jenis == 'mahasiswa') { ?>
                    <td><?php echo isset($row->kelas) ? $row->kelas :''; ?></td>
                <?php } ?>
                <td><?php echo $row->no_inv ?></td>
                <td><?php echo $row->nama ?></td>
                <td><?php echo $row->judul ?></td>
                <td><?php echo $row->tgl_kembali ?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>

</table>