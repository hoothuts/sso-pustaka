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
    <!--<h2><?php echo $page_title; ?></h2>--><br>
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

    <div class="report-title">
        LAPORAN INVENTARIS BUKU
    </div>

    <table>
        <thead>
            <tr>
                <th width="40">No</th>
                <th width="120">No. Klas</th>
                <th width="120">ISBN</th>
                <th width="250">Judul Buku</th>
                <th width="150">Penulis</th>
                <th width="60">Edisi</th>
                <th width="150">Penerbit</th>
                <th width="70">Tahun</th>
                <th width="60">Eks</th>
                <th width="60">Hil/Rsk</th>
                <th width="90">Tgl Inv</th>
                <th width="120">Kampus</th>
                <th width="120">Gedung</th>
                <th width="120">Rak</th>
                <th width="120">Asal Buku</th>
                <th width="200">Program Studi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach ($data as $row): 
            ?>
            <tr>
                <td class="center"><?= $i ?></td>
                <td><?= $row->no_klas ?></td>
                <td><?= $row->ISBN ?></td>
                <td class="wrap"><?= $row->judul ?></td>
                <td><?= $row->penulis ?></td>
                <td class="center"><?= $row->edisi ?></td>
                <td><?= $row->penerbit ?></td>
                <td class="center"><?= $row->thn_terbit ?></td>
                <td class="center"><?= $row->jml_buku ?></td>
                <td class="center"><?= $row->hilang ?></td>
                <td class="center"><?= $row->tanggal ?></td>
                <td><?= $row->nama_kampus ?></td>
                <td><?= $row->nama_gedung ?></td>
                <td><?= $row->nama_rak ?></td>
                <td><?= $row->asal_buku ?></td>
                <td class="wrap"><?= $row->nama_prodi ?></td>
            </tr>
            <?php 
            $i++;
            endforeach; 
            ?>
        </tbody>
    </table>

