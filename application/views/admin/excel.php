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
<?php if ($laporan == 'laporan_anggota') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <?php if ($jenis == 'mahasiswa') : ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nim</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Jenis Kelamin</th>
                    <th>Status Studi</th>
                    <th>Status Anggota</th>
                    <th>Tgl Daftar</th>
                    <th>Berlaku Sampai.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row->nis ?></td>
                        <td><?php echo $row->nama ?></td>
                        <td><?php echo $row->kelas ?></td>
                        <td>
                            <?php if ($row->jk == 'L') echo 'Laki-Laki' ?>
                            <?php if ($row->jk == 'P') echo 'Perempuan' ?>
                        </td>
                        <td><?php
                            if ($row->status_siswa == 'A') {
                                echo 'Aktif';
                            } else {
                                echo 'Non-Aktif';
                            }
                            ?></td>
                        <td><?php
                            if ($row->status_siswa == 'A') {
                                echo 'Aktif';
                            } else {
                                echo 'Non-Aktif';
                            }
                            ?></td>
                        <td><?php echo $row->tanggal; ?></td>
                        <td><?php echo $row->berlaku_sampai; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>

        </table>
    <?php elseif ($jenis == 'dosen') : ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Anggota</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Tgl Daftar</th>
                    <th>Berlaku Sampai.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $row) {
                    ?>

                    <tr>

                        <td><?php echo $i ?></td>
                        <td><?php echo $row->nip ?></td>
                        <td><?php echo $row->nama ?></td>
                        <td>
                            <?php if ($row->jk == 'L') echo 'Laki-Laki' ?>
                            <?php if ($row->jk == 'P') echo 'Perempuan' ?>
                        </td>
                        <td><?php echo $row->tanggal; ?></td>
                        <td><?php echo $row->berlaku_sampai; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>

        </table>
    <?php elseif ($jenis == 'anggota+luar') : ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Instansi Asal</th>
                    <th>Telp</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row->nama ?></td>
                        <td>
                            <?php if ($row->jk == 'L') echo 'Laki-Laki' ?>
                            <?php if ($row->jk == 'P') echo 'Perempuan' ?>
                        </td>
                        <td><?php echo $row->instansi_asal_alamat ?></td>
                        <td><?php echo $row->telepon ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>

        </table>
    <?php endif; ?>
<?php endif; ?>
<?php if ($laporan == 'laporan_pengunjung') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Tanggal Upload</th>
                <th>Email</th>
                <th>No Whatsapp</th>
                <th>Asal Instansi</th>
                <th>Tujuan Berkunjung</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $row) {
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row->nama ?></td>
                    <td><?php echo $row->tgl_post ?></td>
                    <td><?php echo $row->email ?></td>
                    <td><?php echo $row->no_wa; ?></td>
                    <td><?php echo $row->asal_instansi; ?></td>
                    <td><?php echo $row->tujuan_berkunjung; ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>

<?php endif; ?>
<?php if ($laporan == 'laporan_hilang') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Inventaris</th>
                <th>Judul Buku</th>
                <th>Tgl. Hilang/Rusak</th>
                <th>No. Anggota</th>
                <th>Biaya Ganti</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $i = 1;
            foreach ($data as $row) {
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row->penulis ?></td>
                    <td><?php echo $row->judul ?></td>
                    <td><?php echo $row->edisi ?></td>
                    <td><?php echo $row->kd_penerbit ?></td>
                    <td><?php echo $row->thn_terbit ?></td>
                    <td><?php echo $row->ISBN ?></td>
                    <td><?php echo $row->jml_buku ?></td>
                    <td><?php echo $row->no_klas ?></td>
                    <td><?php echo $row->tanggal ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if ($laporan == 'laporan_denda') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Anggota</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>Judul Buku</th>
                <th>Tanggal</th>
                <th>Denda</th </tr>
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
                    <td><?php echo $row->kelas ?></td>
                    <td><?php echo $row->judul ?></td>
                    <td><?php echo $row->tgl_kembali ?></td>
                    <td><?php echo $row->denda ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if ($laporan == 'laporan_presensi') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>NIM/NIP</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $row) {
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row->tanggal ?></td>
                    <td><?php echo $row->jam ?></td>
                    <td><?php echo $row->nis ?></td>
                    <td><?php echo $row->nama ?></td>
                </tr>

                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if ($laporan == 'laporan_belumkembali') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>No.Anggota</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>No. Inv</th>
                <th>Judul</th>
                <th>Nama</th>
                <th>Tgl.Pinjam</th>
                <th>Batas Kembali</th>
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
                    <td><?php echo $row->kelas ?></td>
                    <td><?php echo $row->no_inv ?></td>
                    <td><?php echo $row->nama ?></td>
                    <td><?php echo $row->judul ?></td>
                    <td><?php echo $row->tgl_pinjam ?></td>
                    <td><?php echo $row->batas ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>

    </table>
<?php endif; ?>
<?php if ($laporan == 'laporan_kataloginventaris') : ?>
    <h2><?php echo $page_title; ?></h2><br>
    <table cellpadding="5" width="100%">
        <?php
        $i = 1;
        foreach ($data as $d) {
            ?>
            <tr>
                <td>
                    <?php echo $d->no_klas; ?>
                    <br />
                    <?php echo $d->cetakkatalog_judulpenggal; ?>, <?php echo $d->penulis; ?>, <?php echo $d->nama_penerbit; ?>,<?php echo $d->thn_terbit; ?>,<?php echo $d->ISBN; ?>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </table>
<?php endif; ?>
<?php if ($laporan == 'laporan_buku_prodi') : ?>
    <h2><?php echo $page_title; ?></h2>
    <?php if ($klas) echo 'Berdasarkan Klasifikasi ' . $klas; ?><?php if (count($nmktg) > 0) echo ' Kategori ' . $nmktg[0]->nmkategori; ?><?php if (count($nmprodi) > 0) echo ' Program Studi ' . $nmprodi[0]->nmmspst; ?>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Klas</th>
                <th>Judul</th>
                <th>Penerbit</th>
                <th>Jurusan</th>
                <th>Jumlah Buku</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($data as $row) {
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $row->no_klas ?></td>
                    <td><?php echo $row->judul ?></td>
                    <td><?php echo $row->penerbit ?></td>
                    <td>
                        <?php
                        $prodi = $this->Md_siperpus_buku_prodi->getBukuByISBN($row->ISBN, $row->no_klas);
                        if (count($prodi) > 0) {
                            $count = 0;
                            foreach ($prodi as $p) {
                                if ($count == 0)
                                    echo $p->namaprodi;
                                else
                                    echo ' , ' . $p->namaprodi;
                                $count++;
                            }
                        } else {
                            echo '';
                        }
                        ?>
                    </td>
                    <td><?php echo $row->jml_buku ?></td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>