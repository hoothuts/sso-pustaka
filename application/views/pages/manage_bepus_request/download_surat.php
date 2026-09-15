<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            line-height: 1.4;
        }
        h1{ 
            text-align: center; 
            margin-bottom: -4px; 
            font-size: 18px;
        }
        h2{ 
            text-align: center; 
            margin-top: 0px; 
            margin-bottom: 15px; 
            font-size: 15px;
        }
        .info-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 0px; 
        }
        .info-table td { 
            padding: 2px 4px; 
            /*border: 1px solid #333;*/ 
        }
        .info-table td.label { 
            width: 35%; 
            /*background-color: #f0f0f0;*/ 
            font-weight: bold; 
        }

        .detail-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        .detail-table th, .detail-table td { 
            border: 1px solid #333; 
            padding: 3px 2px; 
            vertical-align: middle;
        }
        .detail-table th { 
            background-color: #343a40; 
            color: white; 
            text-align: center;
        }

        .parent-row { 
            background-color: #f0f4f8 !important; 
/*            font-weight: bold; */
        }
        
        /* Kotak Checkbox yang lebih besar */
        .checkbox-box {
            width: 13px; 
            height: 13px; 
            border: 1px solid #000; 
            display: inline-block; 
            text-align: center; 
            line-height: 13px;
            font-size: 11px;
            vertical-align: middle;
        }

        .footer { 
            margin-top: 50px; 
            text-align: center; 
            font-size: 11px; 
            color: #555;
        }
        
        /* Tabel Nested untuk Child */
        .child-table {
            width: 100%;
            border-collapse: collapse;
        }
        .child-table td {
            border: 0px solid #ddd;
            padding: 1px 1px; 
            vertical-align: top;
        }
        .child-table td:first-child {
            width: 85%;
        }
        .child-table td:last-child {
            width: 15%;
            text-align: center;
        }
        
        
    </style>
</head>
<body>

    <h1>SURAT KETERANGAN BEBAS PUSTAKA</h1>
    <h2>Nomor : <?= htmlspecialchars($request->no_request ?? '') ?></h2>

    <table class="info-table">
        <tr><td class="label">NAMA</td><td> : <?= htmlspecialchars($request->nama ?? '') ?></td></tr>
        <tr><td class="label">NIM</td><td> : <?= htmlspecialchars($request->nim ?? '') ?></td></tr>
        <tr><td class="label">PROGRAM STUDI</td><td> : <?= htmlspecialchars($request->kelas ?? '') ?></td></tr>
        <tr><td class="label">TAHUN AKADEMIK</td><td> : <?= htmlspecialchars($request->tahun_akademik ?? '') ?></td></tr>
<!--        <tr><td class="label">Tanggal Request</td><td> : <?= date('d M Y H:i', strtotime($request->tgl_post ?? '')) ?></td></tr>-->
    </table>

    <p>Terhitung sejak tanggal <?= date('d F Y H:i', strtotime($request->tgl_approve ?? '')) ?> dinyatakan telah bebas dari peminjaman buku dan koleksi lainnya 
        di Perpustakaan Poltekkes Kemenkes Riau, dan telah memenuhi syarat sebagai berikut :</p>
    <table class="detail-table">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Persyaratan</th>
                <th width="55" style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $child_list = [];

            foreach ($request->details as $detail): 
                $is_parent = ($detail->level == 1);

                if ($is_parent) {
                    // Tampilkan child dari parent sebelumnya
                    if (!empty($child_list)) {
                        ?>
                        <tr>
                            <td style="text-align:center;"></td>
                            <td style="padding: 0;" colspan="2">
                                <table class="child-table">
                                    <?php 
                                    $letter = 'a';
                                    foreach ($child_list as $child): 
                                    ?>
                                        <tr>
                                            <td >
                                                <strong><?= $letter ?>.</strong> 
                                            </td>
                                            <td>
                                                <?= $child->persyaratan ?>   <!-- htmlspecialchars dihapus -->
                                            </td>
                                            <td width="45">
                                                <?php if ($child->is_isian == 'Ya'): ?>
                                                    <div class="checkbox-box">
                                                        <?= ($child->status_syarat == 'OK') ? 'OK' : '✘' ?>
                                                    </div>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php 
                                        $letter++;
                                    endforeach; 
                                    ?>
                                </table>
                            </td>
                        </tr>
                        <?php
                        $child_list = [];
                    }

                    // Tampilkan Parent
                    ?>
                    <tr class="parent-row">
                        <td style="text-align:center;"><?= $no++ ?></td>
                        <td><?= $detail->persyaratan ?></td>
                        <td style="text-align:center;">
                            <?php if ($detail->is_isian == 'Ya'): ?>
                                <div class="checkbox-box"><?= ($detail->status_syarat == 'OK') ? 'OK' : '✘' ?></div>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                } 
                else {
                    $child_list[] = $detail;
                }
            endforeach;

            // Sisa child parent terakhir
            if (!empty($child_list)) {
                ?>
                <tr>
                    <td style="text-align:center;"></td>
                    <td style="padding: 0;" colspan="2">
                        <table class="child-table">
                            <?php 
                            $letter = 'a';
                            foreach ($child_list as $child): 
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= $letter ?>.</strong> 
                                    </td>
                                    <td>
                                        <?= $child->persyaratan ?>
                                    </td>
                                    <td width="45">
                                        <?php if ($child->is_isian == 'Ya'): ?>
                                            <div class="checkbox-box">
                                                <?= ($child->status_syarat == 'OK') ? 'OK' : '✘' ?>
                                            </div>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                                $letter++;
                            endforeach; 
                            ?>
                        </table>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <div>
        <p>Demikianlah surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>
        <!-- Bagian Tanda Tangan -->
    <!-- Bagian Tanda Tangan -->
    <div style="margin-top:20px; page-break-inside: avoid;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:50%; text-align:center; vertical-align:top;">
                    <br>
                    Mengetahui,<br>
                    Kepala Unit Perpustakaan
                    <div style="height:10px;"></div>

                    <table style="margin:0 auto; border-collapse:collapse;">
                        <!-- TTD -->
                        <tr>
                            <td style="padding:0; text-align:center;">

                                <?php if (!empty($request->ttd_kaperpus)): ?>
                                    <img src="<?= base_url().'uploads/ttd/'.$request->ttd_kaperpus ?>"
                                         style="height:90px;"
                                         alt="" />
                                <?php else: ?>
                                    <div style="height:90px;"></div>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- CAP -->
                        

                        <!-- NAMA -->
                        <tr>
                            <td style="padding:0; text-align:center;">
                                <div style="margin-top:-130px;">
                                    <strong>
                                        <?= htmlspecialchars($request->nama_kaperpus ?? 'Nama Kepala Perpustakaan') ?>
                                    </strong>
                                    <br>
                                    NIP. <?= htmlspecialchars($request->nip_kaperpus ?? '..................') ?>
                                </div>
                            </td>
                        </tr>
                        
                        <tr>
                            <td style="padding:0; text-align:center;">
                                <img src="<?= FCPATH.'assets/media/cap pkr lib.png' ?>" style="height:165px;margin-top:-150px;margin-left:20px;opacity:0.75;margin-bottom: -15px;position:absolute;" alt="" />
                            </td>
                        </tr>
                        
                    </table>
                </td>

                <!-- Petugas -->
                <td style="width:50%; text-align:center; vertical-align:top;">

                    Pekanbaru,
                    <?= date('d F Y', strtotime($request->tgl_approve ?? date('Y-m-d'))) ?>
                    <br>
                    Petugas Perpustakaan<br/><br/>

                    <div style="height:10px;"></div>
                    
                    <?php
                    if (!empty($request->ttd_pstk)) {
                        echo "<img src='".base_url()."uploads/ttd/".$request->ttd_pstk."' style='height:90px;' alt=''/>";
                    } else {
                        echo "<div style='height:90px;'></div>";
                    }
                    ?>

                    <br>

                    <strong>
                        <?= htmlspecialchars($request->nama_pstk ?? 'Nama Pustakawan') ?>
                    </strong>
                    <br>
                    NIP. <?= htmlspecialchars($request->nip_pstk ?? '..................') ?>

                </td>
            </tr>
        </table>
    </div>

    <!-- NB (Catatan Bawah) -->
<!--    <div style="margin-top: 40px; font-size: 11px;">
        <strong>NB :</strong><br>
        : 1 RANGKAP UNTUK MAHASISWA<br>
        : 1 RANGKAP UNTUK ARSIP PERPUSTAKAAN
    </div>-->

</body>
</html>