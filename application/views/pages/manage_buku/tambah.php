<style>
    .file-input-container {
        margin-bottom: 10px;
    }
    .file-input-container .btn-remove {
        margin-top: 1px;
    }
    .form-group{
        padding-bottom: 2px !important;
        padding-top: 2px !important;
    }
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                    <small>
                        <i class="glyphicon glyphicon-refresh"></i>
                        <button style="display:none" class="btn btn-default" id="m_datatable_reload"><i class="fa fa-refresh"></i> Reload</button>
                    </small>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="<?php echo base_url(); ?>dir/manage_buku" class="m-nav__link"><span class="m-nav__link-text">Data Buku</span></a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"><span class="m-nav__link-text"><?php echo $page_title; ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <?php if ($this->session->flashdata('alert')) { ?>
            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
                <div class="m-alert__icon">
                    <i class="flaticon-exclamation-1"></i>
                    <span></span>
                </div>
                <div class="m-alert__text">
                    <?php echo $this->session->flashdata('flash_message') ?>
                </div>
            </div>
        <?php } ?>

        <script type="text/javascript"> var page_action = 'tambah'; </script>
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon m--hide">
                            <i class="la la-gear"></i>
                        </span>
                        <h3 class="m-portlet__head-text">
                            <?php echo $page_title; ?>
                        </h3>
                    </div>
                </div>
            </div>
            <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/submit" method="post" id="form_tambah2" enctype="multipart/form-data">
                <div class="col-md-12">

                    <div class="m-portlet__body">
                        <!-- Begin alert -->
                        <div class="m-form__content">
                            <div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_1_msg">
                                <div class="m-alert__icon">
                                    <i class="la la-warning"></i>
                                </div>
                                <div class="m-alert__text">
                                    Please Insert The Empty Field.
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                                </div>
                            </div>
                            <?php if ($this->session->flashdata('error_addbuku_tambah')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error_addbuku_tambah'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- End alert -->
                        
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Nomor Klasifikasi : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="no_klas" id="no_klas" class="form-control m-input" placeholder="Nomor Klasifikasi" required>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    ISBN/ISSN : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="isbn" id="isbn" class="id_isbn form-control m-input" placeholder="ISBN/ISSN" required>
                                <span class="m-form__help" color="red">
                                    Silahkan Isi Tanda <strong>-</strong> (strip) Untuk Buku Tanpa ISBN
                                </span>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Kelompok Buku:
                                </label>
                                <select class="form-control" name="kategori_buku" id="ktg" onchange="kti()">
                                    <?php
                                    $x = 0;
                                    foreach ($data['kel_buku'] as $i) {
                                        $x++;
                                        ?>
                                        <option value="<?php echo $i->idkategori ?>"><?php echo $i->nmkategori ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Judul Buku : <font size="3" color="red">*</font>
                                </label>
                                <input required type="text" name="judul" class="form-control m-input" placeholder="Masukkan Judul Buku" onkeyup="pengalInputIni(this.value, 'cetakkatalog_judulpenggal')">
                            </div>
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Penggalan Judul Katalog:
                                </label>
                                <input type="text" id="cetakkatalog_judulpenggal" name="cetakkatalog_judulpenggal" class="form-control m-input" placeholder="Masukkan Penggalan Judul Katalog">
                                
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Judul Asli:
                                </label>
                                <input type="text" name="judulasli" class="form-control m-input" placeholder="Masukkan Judul Asli">
                            </div>
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Deskripsi Buku:
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input type="checkbox" name="allow_review">Perbolehkan Komentar Publik
                                    <span></span>
                                </label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan Deskripsi Buku"></textarea>
                                <span class="m-form__help">
                                    Deskripsi Buku Boleh Dikosongkan
                                </span>
                            </div>
                        </div>
                        
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penulis : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="penulis" class="form-control m-input" placeholder="Penulis" required>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyadur : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="penyadur" class="form-control m-input" placeholder="Masukkan Penyadur" required>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penerjemah :
                                </label>
                                <input type="text" name="penerjemah" class="form-control m-input" placeholder="Masukkan Penerjemah">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyusun :
                                </label>
                                <input type="text" name="penyusun" class="form-control m-input" placeholder="Masukkan Penyusun">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyunting :
                                </label>
                                <input type="text" name="penyunting" class="form-control m-input" placeholder="Masukkan Penyunting">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Illustrator :
                                </label>
                                <input type="text" name="illustrator" class="form-control m-input" placeholder="Illustrator">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Editor :
                                </label>
                                <input type="text" name="editor" class="form-control m-input" placeholder="Editor">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Edisi :
                                </label>
                                <input type="text" name="edisi" class="form-control m-input" placeholder="Edisi">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Cetakan :
                                </label>
                                <input type="text" name="cetakan" class="form-control m-input" placeholder="Cetakan">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label"> Tahun Terbit : <font size="3" color="red">*</font></label>
                                <input type="text" name="thn_terbit" class="form-control m-input" placeholder="Tahun Terbit" required>
                            </div>
                        </div>
                        <div id="penerbit" class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penerbit : <font size="3" color="red">*</font>
                                </label>
                                <select class="form-control" name="penerbit" id="penerbit_tambah" required>
                                    <option value=" "></option>
                                </select>
                                
                            </div>
                            <div class="col-lg-2 m-form__group-sub">
<!--                                 <div class="m--space-10"></div>-->
                                <label class="col-form-label">&nbsp; &nbsp;</label>
                                <button type="button" class="btn btn-info btn-sm " onclick="tambah_penerbit()">Tambah Penerbit <i class="la la-files-o"></i></button>
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Jilid :
                                </label>
                                <input type="text" name="jilid" class="form-control m-input" placeholder="Jilid">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    No. Hal. Romawi :
                                </label>
                                <input type="text" name="hlm_romawi" class="form-control m-input">
                            </div>
                        </div>
                        
                        <div class="form-group m-form__group row" style="margin-top:12px !important;">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Ilustrasi :
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input name="ilustrasi" type="checkbox" value="1">Tandai jika ada ilustrasi
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Tabel :
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input name="tabel" type="checkbox" value="1">Tandai jika ada tabel
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Jumlah Halaman : <font size="3" color="red">*</font>
                                </label>
                                <input type="number" name="jml_hal" class="form-control m-input" required>
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Ukuran Fisik : <font size="3" color="red">*</font>
                                </label>
                                <input type="number" name="ukuran_fisik" class="form-control m-input" required>
                                <span class="m-form__help" color="red">
                                    Masukkan Ukuran Fisik (cm)
                                </span>
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Bibliografi :
                                </label>
                                <input type="text" name="bibliografi" class="form-control m-input" placeholder="Bibliografi">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Index :
                                </label>
                                <input type="text" name="indeks" class="form-control m-input" placeholder="Index">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Bahasa :
                                </label>
                                <select class="form-control" name="bahasa">
                                    <?php
                                    $x = 0;
                                    foreach ($data['bahasa'] as $i) {
                                        $x++;
                                        ?>
                                        <option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
<!--                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    No. Rak :
                                </label>
                                <input type="text" name="no_rak" class="form-control m-input">
                            </div>-->
                             <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Seri :
                                </label>
                                <input type="text" name="seri" class="form-control m-input" placeholder="Seri">
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Tajuk Utama :
                                </label>
                                <input type="text" name="tajuk" class="form-control m-input" placeholder="Tajuk Utama ">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Tajuk Subyek :  <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="tajuksubyek" class="form-control m-input" placeholder="Tajuk Subyek" required>
                               
                            </div>
                            <div class="col-lg-3 m-form__group-sub">
                                <label class="col-form-label">
                                    Kategori Buku Berdasarkan Prodi :
                                </label>
                                <select class="form-control m-select2" id="m_select2_3" name="prodi[]" multiple="multiple">
                                    <?php
                                    $x = 0;
                                    foreach ($data['prodi'] as $i) {
                                        $x++;
                                        ?>
                                        <option value="<?php echo $i->idmspst ?>"><?php echo $i->nmmspst ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-1 m-form__group-sub"></div>
                            <div class="col-lg-5 m-form__group-sub">
                                <label class="col-form-label">
                                    Displayed Books:
                                </label>
                                <div class="m-radio-inline">
                                    <label class="m-radio">
                                        <input type="radio" name="displayed" value="0" checked>
                                        Tampilkan Buku
                                        <span></span>
                                    </label>
                                    <label class="m-radio">
                                        <input type="radio" name="displayed" value="1">
                                        Sembunyikan Buku
                                        <span></span>
                                    </label>
                                </div>
                                <span class="m-form__help">Pilih untuk menampilkan/sembunyikan buku pada Penelusuran Buku</span>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            
                            
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label ">
                                    Upload Gambar Buku :
                                </label>
                                <div class="fileinput fileinput-new col-lg-12" data-provides="fileinput">
                                    <span class="btn btn-info btn-sm btn-file"><span class="fileinput-new">Pilih file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="gambar" id="gambar"></span>
                                    <span class="fileinput-filename"></span>
                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a><br>
                                    <code>Upload Gambar Dengan Format <b>.gif | .jpg | .png | .jpeg | bmp</b></code>
                                </div>
                                <!-- <label class="custom-file">
                                      <input type="file" id="gambar" name="gambar" class="custom-file-input" >
                                      <span class="custom-file-control"></span>
                                                        <div class="m--space-6"></div>
                                    </label> -->
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 m-form__group-sub">
                                <label class="col-form-label">
                                    Upload File Buku :
                                </label>

                                <div class="container mt-2">
                                    <div class="alert alert-warning mt-2" role="alert">
                                        <strong>Perhatian!</strong> File yang dapat dibaca hanya file dengan ekstensi <b>PDF</b>.
                                    </div>
                                    
                                    <div id="repeaterbook">
                                        <div class="file-input-container">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn btn-info btn-sm btn-file">
                                                    <span class="fileinput-new">Pilih File</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="file[]" multiple>
                                                </span>
                                                <span class="fileinput-filename"></span>
                                                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a><br/>
                                                <code>Upload File Dengan Format <b>.pdf | .doc | .docx | .xls | .xlsx | .zip</b> Max Size: <b>20 MB</b></code>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="d-flex flex-row bd-highlight mb-3 col-lg-3">
                                                        <div class="p-2 bd-highlight"><label>Is Baca:</label></div>
                                                        <div class="p-2 bd-highlight">
                                                            <select name="is_baca_file[]">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row bd-highlight mb-3 col-lg-3">
                                                        <div class="p-2 bd-highlight"><label>Is Download:</label></div>
                                                        <div class="p-2 bd-highlight">
                                                            <select name="is_download_file[]">
                                                                <option value="Ya">Ya</option>
                                                                <option value="Tidak">Tidak</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="addFileInput" class="btn btn-primary">Tambah File</button>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        let fileInputCounter = 1; // Initialize counter for unique naming

                                        const repeaterbook = document.getElementById('repeaterbook');
                                        const addFileInputBtn = document.getElementById('addFileInput');

                                        addFileInputBtn.addEventListener('click', function () {
                                            const fileInputContainer = document.createElement('div');
                                            fileInputContainer.classList.add('file-input-container');

                                            fileInputContainer.innerHTML = `
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <span class="btn btn-info btn-sm btn-file">
                                                                    <span class="fileinput-new">Pilih File Tambahan</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input type="file" name="file[]" multiple>
                                                            </span>
                                                            <span class="fileinput-filename"></span>
                                                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                    </div> <button type="button" class="btn btn-danger btn-sm btn-remove"><i class="fa fa-trash"></i></button>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="d-flex flex-row bd-highlight mb-3 col-lg-3">
                                                                <div class="p-2 bd-highlight"><label>Is Baca:</label></div>
                                                                <div class="p-2 bd-highlight">
                                                                    <select name="is_baca_file[]">
                                                                            <option value="Ya">Ya</option>
                                                                            <option value="Tidak">Tidak</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-row bd-highlight mb-3 col-lg-3">
                                                                <div class="p-2 bd-highlight"><label>Is Download:</label></div>
                                                                <div class="p-2 bd-highlight">
                                                                    <select name="is_download_file[]">
                                                                            <option value="Ya">Ya</option>
                                                                            <option value="Tidak">Tidak</option>
                                                                    </select>
                                                                </div>
                                                            </div>                        
                                                        </div>
                                                    </div>

                                            `;

                                            fileInputCounter++; // Increment the counter for the next set of inputs

                                            repeaterbook.appendChild(fileInputContainer);

                                            fileInputContainer.querySelector('.btn-remove').addEventListener('click', function () {
                                                fileInputContainer.remove();
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                        <div id="form_kti" class="collapse" style="display:none;">
                            <!-- <div class="m-portlet m-portlet--tab"> -->
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <span class="m-portlet__head-icon m--hide">
                                            <i class="la la-gear"></i>
                                        </span>
                                        <h3 class="m-portlet__head-text">
                                            Khusus Pustaka Skrispi/Tugas Akhir
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-2 col-form-label">
                                        NIM:
                                    </label>
                                    <div class="col-lg-5">
                                        <label class="custom-file">
                                            <input type="text" name="nis_ta" class="form-control m-input">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-2 col-form-label">
                                        Tabel TA:
                                    </label>
                                    <div class="col-lg-2">
                                        <label class="m-checkbox m-checkbox--solid">
                                            <input name="tabel_ta" type="checkbox" value="1">Tandai jika ada tabel
                                            <span></span>
                                        </label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">
                                        Lampiran TA:
                                    </label>
                                    <div class="col-lg-2">
                                        <label class="m-checkbox m-checkbox--solid">
                                            <input name="lampiran_ta" type="checkbox" value="1">Tandai jika ada Lampiran
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-2 col-form-label">
                                        Dosen Pembimbing:
                                    </label>
                                    <div class="col-lg-5">
                                        <label class="custom-file">
                                            <input id="pembimbing_ta" type="text" name="pembimbing_ta" class="form-control m-input">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-2 col-form-label">
                                        Example textarea
                                    </label>
                                    <div class="col-lg-5">
                                        <textarea class="form-control m-input" id="abstrak" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions m-form__actions">
                        <div class="row">
                            <div class="col-lg-9 ml-lg-auto">
                                <button type="submit" class="btn btn-success" id="tambah_buku_simpan">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="button" class="btn btn-primary ml-5" onclick="window.location.href='<?php echo base_url(); ?>dir/manage_buku'">
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            var kd_penerbit = 0;
        </script>
    </div>
</div>

<!-- end:: Body -->
<script type="text/javascript">
    var save_method; //for save method string
    var table;
    var barcode = "<?php echo base_url(); ?>assets";
    var barcode2 = "<?php echo base_url(); ?>dir/";
    var base_site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>";
    var submit = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/submit/";
    var site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/list/";
    $(document).ready(function () {
        getPenerbit(kd_penerbit);
    });
    $("#button_tambah").click(function () {
        $('#tambah_inv').on('shown.bs.collapse', function (e) {
            var $card = $(this).closest('#tambah_inv');
            $('html,body').animate({
                scrollTop: $card.offset().top - 25
            }, "slow");
        });
        $("#head").text(function () {
            return $("#tambah_inv").is(":visible") ? "Tambah Inventaris" : "Inventaris";
        })
    });

    function kti() {
        var ktg = document.getElementById('ktg');
        if (ktg.value != '') {
            if (ktg.value == 3) {
                document.getElementById('form_kti').style.display = 'block';
            } else {
                document.getElementById('form_kti').style.display = 'none';
            }
        }
    }
    kti();

    function statusFile(file_id) {
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/setfilestatus/" + file_id;
        $.ajax({
            url: url,
            type: "POST",
            dataType: "JSON",
            success: function (data) {
                viewFile(isbn, no_klas);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Post data from ajax');
            }
        });
    }

    function viewFile(isbn, no_klas) {
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/getfile/" + isbn + "/" + no_klas;
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#t_file').find('tbody').empty();
                for (var i = 0; i < data.length; i++) {
                    if (data[i]['status_akses'] == 1)
                        var status_akses = "Umum";
                    else
                        var status_akses = 'Hanya Anggota';
                    $("#t_file").append("<tr><td>" + data[i]['file_name'] + "<\/td><td>" + status_akses + "<\/td><td><a onclick=\"statusFile('" + data[i]['file_id'] + "')\" class=\"m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill\" title=\"Edit details\"><i class=\"la la-gears\"><\/i><\/a><\/td><\/tr>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function getPenerbit(kd) {
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/getpenerbit/";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("#penerbit_tambah").empty();
                for (var i = 0; i < data.length; i++) {
                    var id = data[i]['kd_penerbit'];
                    var name = data[i]['nama_penerbit'];
                    var kota = data[i]['kota'];
                    if (kd == id) {
                        $("#penerbit_tambah").append("<option value='" + id + "' selected>" + name + "; " + kota + "</option>");
                    } else {
                        $("#penerbit_tambah").append("<option value='" + id + "'>" + name + "; " + kota + "</option>");
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function pengalInputIni(id1, id2) {
        document.getElementById(id2).value = id1;
    }

    function cek_isbn(isbn) {
        if (isbn == '') {
            swal({
                title: 'Mohon Isi ISBN',
                type: 'warning',
                showConfirmButton: false,
                timer: 1000
            });
            return;
        }
        var isbn = isbn.split('/').join('_');
        var cek = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/cek/" + isbn;
        $.ajax({
            url: cek,
            type: "POST",
            data: $('.id_isbn').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status == true) { // if data is already exist
                    swal({
                        title: 'Oops..!! Data Sudah Ada',
                        type: 'warning',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    swal({
                        title: 'Dapat Digunakan',
                        type: 'info',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }

    function delete_file(isbn, no_klas, file_name) {
        if (confirm('Are you sure you want to delete this item ?')) {
            // ajax delete data to database
            var id = isbn.split('/').join('_');
            var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/delete_file/" + id + "/" + no_klas + "/" + file_name;

            $.ajax({
                url: url,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.status = 'TRUE') {
                        swal({
                            title: 'Success',
                            text: 'File Buku Berhasil Dihapus',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        location.reload();
                        // reload_table();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal({
                        title: 'Warning',
                        text: 'Data Buku Tidak Berhasil Dihapus',
                        type: 'warning',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    }

    function reload_table() {
        $("#m_datatable_reload").trigger("click");
    }

    function tambah_penerbit() {
        $('#form_penerbit')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Penerbit'); // Set Title to Bootstrap modal title
    }
    //save penerbit
    function save() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable
        var url = "<?php echo base_url(); ?>admin/penerbit/submit/";
        var div = $('#penerbit').html();
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_penerbit').serialize(),
            dataType: "JSON",
            success: function (data) {
                // console.log(data.msg);return;
                if (data.status) //if success close modal and reload ajax table
                {
                    if (data.status = 'TRUE') {
                        if (data.hasil == 0) {
                            toastr.options = {
                                "positionClass": "toast-top-full-width",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            toastr.error(data.msg);
                        } else if (data.hasil == 1) {
                            toastr.options = {
                                "positionClass": "toast-top-full-width",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            toastr.error(data.msg);
                        } else if (data.hasil == 2) {
                            swal({
                                title: 'Success',
                                text: data.msg,
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#penerbit').load(location.href + " #penerbit");
                        }
                        $('#modal_form').modal('hide');
                        getPenerbit(data.id);
                    }
                }
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function get_barcode() {
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_barcode/";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $("input[name='no_barcode']").val(data.barcode);
                // document.getElementsByName('no_barcode').value=data.barcode;
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        })
    }

    $(document).ready(function() {
        // Inisialisasi jQuery Validate
        $("#form_tambah2").validate({
            rules: {
                jml_hal: {
                    required: true
                },
                no_klas: {
                    required: true
                },
                isbn: {
                    required: true,
                    remote: {
                        url: base_site + "/cek2",  // URL cek duplikat ISBN + no_klas
                        type: "post",
                        data: {
                            no_klas: function() {
                                return $("#no_klas").val();
                            },
                            isbn: function() {
                                return $("#isbn").val();
                            }
                        }
                    }
                },
                thn_terbit: {
                    required: true,
                    digits: true
                },
                penerbit: {
                    required: true
                },
                ukuran_fisik: {
                    required: true
                },
                penulis: {
                    required: true
                },
                judul: {
                    required: true
                },
                tajuksubyek: {
                    required: true
                }
            },
            messages: {
                // Optional: custom pesan error (bisa ditambah sesuai kebutuhan)
                isbn: {
                    remote: "Kombinasi ISBN dan No Klas sudah ada di database."
                }
            },
            invalidHandler: function(event, validator) {
                // Tampilkan pesan error umum jika ada field kosong/invalid
                var errors = validator.errorList;
                var errorMsg = errors.map(function(err) {
                    return err.message;
                }).join('<br>');
                
                swal({
                    title: 'Gagal Validasi Form',
                    text: ' Cek input form bertanda bintang',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                // Scroll ke error pertama (opsional)
                if (errors.length > 0) {
                    $('html, body').animate({
                        scrollTop: $(errors[0].element).offset().top - 100
                    }, 500);
                }
            },
            submitHandler: function(form) {
                // Validasi lolos → jalankan AJAX submit
                var formData = new FormData(form); // support upload file

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status === 'success') {
                            swal({
                            title: 'Berhasil',
                            text: data.message || 'Data Buku Berhasil Disimpan',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                            });

                            // Redirect otomatis setelah 1.5 detik (fallback aman, tidak tergantung .then())
                            setTimeout(function() {
                                window.location.href = '<?= base_url('dir/manage_buku') ?>';
                            }, 1500);
                        } else {
                            swal({
                                title: 'Gagal',
                                text: data.message
                                    .replace(/<[^>]+>/g, '')          // hapus semua tag HTML (<p>, <br>, dll.)
                                    .replace(/&lt;/g, '<')             // decode entity jika ada
                                    .replace(/&gt;/g, '>')
                                    .replace(/\s*<br\s*\/?>\s*/gi, '\n')  // ganti <br> jadi newline biasa
                                    .trim(),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal({
                            title: 'Error Server',
                            text: 'Terjadi kesalahan koneksi server.<br>Silakan coba lagi atau hubungi administrator.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                    }
                });
            }
        });

        // Tombol Submit (type="button", bukan submit form biasa)
        $('#tambah_buku_simpan').click(function() {
            $("#form_tambah2").valid(); // trigger validasi
            // Jika lolos, submitHandler akan jalan otomatis
        });
    });
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/validasi.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>

<!--begin::Modal Tambah Penerbit-->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelModalTambah">
                    <i class="m-menu__link-icon flaticon-add"></i> Tambah Penerbit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_penerbit" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <div class="form-group m-form__group">
                        <input type="hidden" name="id1">
                        <label for="">
                            Penerbit:
                        </label>
                        <input type="text" id="nama_penerbit" name="nama_penerbit" class="form-control m-input" placeholder="Masukkan Penerbit">
                        <span class="m-form__help">
                            Penerbit
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Kota :
                        </label>
                        <input type="text" id="kota" name="kota" class="form-control m-input" placeholder="Masukkan Penerbit">
                        <span class="m-form__help">
                            Kota
                        </span>
                    </div>
            </div>
            <div class="modal-footer">
                <input id="btnSave" onclick="save()" type="submit" class="btn btn-primary" value="Simpan">
            </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->