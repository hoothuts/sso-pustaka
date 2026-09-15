<?php
if ($export == 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$title.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

if ($export == 'web') {
    ?>

    <body onload="window.print();">

    <?php
}
?>
<?php if ($import == 'chart'): ?>
        <style>
        @media print {
            @page { 
                size: landscape; 
                margin: 5mm; 
            }
            body { 
                padding: 0;
                margin: 0;
            }
            /* Pastikan gambar chart memenuhi lebar kertas */
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
        </style>
        <img src="<?php echo $imagedata; ?>"></img>
<?php endif; ?>
<?php if ($import == 'table'): ?>
        <table class="table table-hovered">
        <?php
        if (count($klas) > 0 && count($prodi) > 0) {
            echo '<thead class="thead-default">';
            echo '<tr>';
            echo '<th>Klasifikasi</th>';
            foreach ($prodi as $p) {
                ?><th><?php echo $p->nmmspst; ?></th><?php
        }
        echo '</tr>';
        echo '</thead>';
        foreach ($klas as $k) {
            echo '<tr>';
            echo '<td>' . $k->nama . '</td>';
            foreach ($prodi as $p) {
                $jum = $this->Md_siperpus_buku_prodi->getJumlahBukuProdiKlas($p->idmspst, $k->id);
                echo '<td>' . $jum . ' buku (0%)</td>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        echo '<td>Total</td>';
        foreach ($prodi as $p) {
            $jum = $this->Md_siperpus_buku_prodi->getJumlahBukuProdi($p->idmspst);
            echo '<td>' . $jum . ' buku</td>';
        }
        echo '</tr>';
    }
        ?>
        </table>
        <?php endif; ?>
