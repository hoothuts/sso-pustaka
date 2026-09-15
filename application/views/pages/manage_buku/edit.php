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
        <?php if ($this->session->flashdata('error')) : ?>
            <div class="alert alert-danger">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
                
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

        <script type="text/javascript">
            var page_action = 'edit'
        </script>
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
            <!--begin::Form-->
            <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/update" method="post" enctype="multipart/form-data">

                <div class="col-md-12">
                    <div class="m-portlet__body">
                        <!-- Begin alert -->
                        <div class="m-form__content">
                            <div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_2_msg">
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
                        </div>
                        <?php if ($this->session->flashdata('error_updatebuku')) : ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error_updatebuku'); ?>
                            </div>
                        <?php endif; ?>
                        <!-- End alert -->
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Nomor Klasifikasi : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="no_klas2" class="form-control m-input" placeholder="Nomor Klasifikasi" value="<?php echo $data[0]->no_klas ?>">
                                
                                <input type="hidden" name="no_klas" value="<?php echo $data[0]->no_klas ?>">
                                <input type="hidden" name="buku_id" value="<?php echo $data[0]->buku_id ?>">
                                
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    ISBN/ISSN :  <font size="3" color="red">*</font>
                                </label>
                                <input type="text" id="isbn2" name="isbn2" class="id_isbn form-control m-input" placeholder="ISBN/ISSN" value="<?php echo $data[0]->ISBN ?>">
                               
                                <input type="hidden" id="isbn" name="isbn" value="<?php echo $data[0]->ISBN ?>">
                                <span class="m-form__help" color="red">
                                    Silahkan Isi Tanda <strong>-</strong> (strip) Untuk Buku Tanpa ISBN
                                </span>
                                <div class="m--space-5"></div>
                                <button type="button" class="btn" onclick="cek_isbn(isbn2.value)">Check It ? <i class="la la-files-o"></i></button>
                            </div>
                            <div class="col-lg-2 m-form__group-sub">
                                <label class="col-form-label">
                                    Kelompok Buku:
                                </label>
                                <select class="form-control" name="kategori_buku" id="ktg" onchange="kti()">
                                    <?php
                                    $x = 0;
                                    foreach ($data['kel_buku'] as $i) {
                                        $x++;
                                        ?>
                                        <option value="<?php echo $i->idkategori ?>" <?php if ($data[0]->idkategori == $i->idkategori) echo "selected" ?>><?php echo $i->nmkategori ?></option>
                                    <?php } ?>
                                </select>
                               
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Judul Buku : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="judul" class="form-control m-input" placeholder="Masukkan Judul Buku" value="<?php echo $data[0]->judul ?>" onkeyup="pengalInputIni(this.value, 'cetakkatalog_judulpenggal')">
                                
                                
                            </div>
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Penggalan Judul Katalog :
                                </label>
                                <input type="text" id="cetakkatalog_judulpenggal" value="<?php echo $data[0]->cetakkatalog_judulpenggal ?>" name="cetakkatalog_judulpenggal" class="form-control m-input" placeholder="Masukkan Penggalan Judul Katalog">                                
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Judul Asli :
                                </label>
                                <input type="text" name="judulasli" class="form-control m-input" placeholder="Masukkan Judul Asli" value="<?php echo $data[0]->judulasli ?>">                                
                            </div>
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Deskripsi Buku :
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input type="checkbox" name="allow_review" value="1"  <?php if ($data[0]->allow_review == 1) echo 'checked'; ?>>Perbolehkan Komentar Publik
                                    <span></span>
                                </label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan Deskripsi Buku" value=<?php echo $data[0]->deskripsi; ?>></textarea>                                
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penulis : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="penulis" class="form-control m-input" placeholder="Penulis" value="<?php echo $data[0]->penulis ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyadur : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="penyadur" class="form-control m-input" placeholder="Masukkan Penyadur" value="<?php echo $data[0]->penyadur ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penerjemah :
                                </label>
                                <input type="text" name="penerjemah" class="form-control m-input" placeholder="Masukkan Penerjemah" value="<?php echo $data[0]->penerjemah ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyusun:
                                </label>
                                <input type="text" name="penyusun" class="form-control m-input" placeholder="Masukkan Penyusun" value="<?php echo $data[0]->penyusun ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Penyunting:
                                </label>
                                <input type="text" name="penyunting" class="form-control m-input" placeholder="Masukkan Penyunting" value="<?php echo $data[0]->penyunting ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Illustrator:
                                </label>
                                <input type="text" name="illustrator" class="form-control m-input" placeholder="Illustrator" value="<?php echo $data[0]->illustrator ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Editor:
                                </label>
                                <input type="text" name="editor" class="form-control m-input" placeholder="Editor" value="<?php echo $data[0]->editor ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Edisi :
                                </label>
                                <input type="text" name="edisi" class="form-control m-input" placeholder="Edisi" value="<?php echo $data[0]->edisi ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Cetakan :
                                </label>
                                <input type="text" name="cetakan" class="form-control m-input" placeholder="Cetakan" value="<?php echo $data[0]->cetakan ?>">
                            </div>
                        </div>
                        <div id="penerbit" class="form-group m-form__group row">
                            <div class="col-lg-8 m-form__group-sub">
                                <label class="col-form-label">
                                    Penerbit :  <font size="3" color="red">*</font>
                                </label>
                                <select id="penerbit_tambah" class="form-control" name="kd_penerbit" required>
                                    <option value=""></option>
                                </select>
                                <div class="m--space-5"></div>
                            </div>
                            <div class="col-lg-2 m-form__group-sub">
                                <label class="col-form-label">&nbsp;</label>
                                <button type="button" class="btn " onclick="tambah_penerbit()">Tambah Penerbit <i class="la la-files-o"></i></button>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Tahun Terbit : <font size="3" color="red">*</font>
                                </label>
                                <input type="number" name="thn_terbit" class="form-control m-input" placeholder="Tahun Terbit" value="<?php echo $data[0]->thn_terbit ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Jilid :
                                </label>
                                <input type="text" name="jilid" class="form-control m-input" placeholder="Jilid" value="<?php echo $data[0]->jilid ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    No. Hal. Romawi :
                                </label>
                                <input type="text" name="hlm_romawi" class="form-control m-input" value="<?php echo $data[0]->hlm_romawi ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row" style="margin-top:12px !important;">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Ilustrasi :
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input name="ilustrasi" type="checkbox" value="1" <?php if ($data[0]->ilustrasi == 1) echo "checked"; ?>>Tandai jika ada ilustrasi
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Tabel :
                                </label>
                                <label class="m-checkbox m-checkbox--solid">
                                    <input name="tabel" type="checkbox" value="1" <?php if ($data[0]->tabel == 1) echo "checked"; ?>>Tandai jika ada tabel
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Jumlah Halaman : <font size="3" color="red">*</font>
                                </label>
                                <input type="number" name="jml_hal" class="form-control m-input" value="<?php echo $data[0]->jml_hal ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Ukuran Fisik : <font size="3" color="red">*</font>
                                </label>
                                <input type="number" name="ukuran_fisik" class="form-control m-input" value="<?php echo $data[0]->ukuran_fisik ?>">
                                <span class="m-form__help">
                                    Masukkan Ukuran Fisik (cm)
                                </span>
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Bibliografi :
                                </label>
                                <input type="text" name="bibliografi" class="form-control m-input" placeholder="Bibliografi" value="<?php echo $data[0]->bibliografi ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Index :
                                </label>
                                <input type="text" name="indeks" class="form-control m-input" placeholder="Index" value="<?php echo $data[0]->indeks ?>">
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Bahasa :
                                </label>
                                <select class="form-control" name="bahasa">
                                    <?php
                                    $x = 0;
                                    foreach ($data['bahasa'] as $i) {
                                        $x++;
                                        ?>
                                        <option value="<?php echo $i->id ?>" <?php if ($data[0]->bahasa == $i->id) echo "selected" ?>><?php echo $i->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
<!--                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    No. Rak :
                                </label>
                                <input type="text" name="no_rak" class="form-control m-input" value="<?php //echo $data[0]->no_rak ?>">
                            </div>-->
                        </div>

                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Seri :
                                </label>
                                <input type="text" name="seri" class="form-control m-input" placeholder="Seri" value="<?php echo $data[0]->seri ?>">
                            </div>
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Tajuk Utama :
                                </label>
                                <input type="text" name="tajuk" class="form-control m-input" placeholder="Tajuk Utama " value="<?php echo $data[0]->tajuk ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Tajuk Subyek : <font size="3" color="red">*</font>
                                </label>
                                <input type="text" name="tajuksubyek" class="form-control m-input" placeholder="Tajuk Subyek" value="<?php echo $data[0]->tajuksubyek ?>">
                                
                            </div>
                            <div class="col-lg-4 m-form__group-sub">
                                <label class="col-form-label">
                                    Kategori Buku Berdasarkan Prodi :
                                </label>
                                <select class="form-control m-select2" id="m_select2_3" name="prodi[]" multiple="multiple">
                                    <?php for ($i = 0; $i < sizeof($data['prodi']); $i++) { ?>
                                        <option value="<?php echo $data['prodi'][$i]['idmspst'] ?>" <?php //echo $data['prodi'][$i]['idmspst'] 
                                        ?> <?php
                                        if ($data_prodi) {
                                            for ($j = 0; $j < sizeof($data_prodi); $j++) {
                                                if ($data['prodi'][$i]['idmspst'] == $data_prodi[$j]['idmspst']) {
                                                    echo "selected";
                                                }
                                            }
                                        }
                                        ?>><?php echo $data['prodi'][$i]['nmmspst']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6 m-form__group-sub">
                                <label class="col-form-label">
                                    Displayed Books:
                                </label>
                                <div class="m-radio-inline">
                                    <label class="m-radio">
                                        <input type="radio" name="displayed" value="0" <?php if ($data[0]->displayed == 0) echo "checked"; ?>>
                                        Tampilkan Buku
                                        <span></span>
                                    </label>
                                    <label class="m-radio">
                                        <input type="radio" name="displayed" value="1" <?php if ($data[0]->displayed == 1) echo "checked"; ?>>
                                        Sembunyikan Buku
                                        <span></span>
                                    </label>
                                </div>
                                <span class="m-form__help">Pilih untuk menampilkan/sembunyikan buku pada Penelusuran Buku</span>
                            </div>
                        </div>
                       
                        <input type="hidden" name="cover" value="<?php echo $data[0]->cover; ?>">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label"></label>
                            <div class="col-lg-5">
                                <a class="btn btn-info m-btn--air" data-toggle="collapse" data-target="#demo">Edit Cover & File</a>
                            </div>
                        </div>
                        <div id="demo" class="collapse">
                            <div class="form-group m-form__group row">
                                <label class="col-lg-2 col-form-label">
                                    Edit Gambar Buku :
                                </label>
                                <div class="col-lg-5">
                                    <div>
                                        <?php if ($data[0]->cover) { ?>
                                            <img src="<?php echo base_url() . 'uploads/covers/' . $data[0]->cover; ?>">
                                        <?php } else { ?>
                                            <img src="<?php echo base_url() . 'assets/media/cover-small.jpg' ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="m--space-5"></div>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file m-btn--air"><span class="fileinput-new">Select file</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" name="gambar" id="gambar"></span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a><br>
                                        <font>Upload Gambar Dengan Format <b>.gif | .jpg | .png | .jpeg | bmp</b></font>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-2 col-form-label">
                                    Edit File Buku :
                                </label>
                                <div class="col-lg-5">
                                    <label class="custom-file">
                                        <button type="button" class="btn btn-info m-btn--air" data-toggle="modal" data-target="#edit_file">Edit file</button>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="file_id" value="<?php echo $file_id ?>" name="file_id" class="form-control m-input" placeholder="File Id">
                        <div class="modal fade" id="edit_file" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="labelModalTambah">
                                            Tambah & Edit File
                                        </h5>
                                    </div>
                                    <div class="m-portlet__body">
                                        <!-- <div class="form-group m-form__group row">
                                        <?php if ($data['file']) : ?>

                                            <?php
                                            $no = 0;
                                            foreach ($data['file'] as $row) :
                                                ?>
                                                <?php if ($data['jnsfile'][$no] == 3) : ?>
                                                                    <div class="col-lg-4">
                                                                            <div class="m-demo-icon">
                                                                                    <div class="m-demo-icon__preview">
                                                                                            <i class="flaticon-file-1"></i>
                                                                                    </div>
                                                                                    <div class="m-demo-icon__class">
                                                    <?php echo substr($row->raw_name, 0, 15) . ".."; ?>
                                                                        </div>
                                                                        <div class="uppercase text-center uppercase text-center">
                                                                                <a href="javascript:void(0)" onclick="delete_file('<?php echo $row->ISBN; ?>','<?php echo $row->no_klas; ?>','<?php echo $row->file_name; ?>')">
                                                                                        <div class="m-demo-icon__preview">
                                                                                            <i class="la la-trash"></i>
                                                                                        </div>
                                                                                </a>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                <?php endif; ?>
                                                <?php
                                                $no++;
                                            endforeach;
                                            ?>
                                        <?php endif; ?>
                                        </div> -->
                                        <div class="form-group m-form__group row">
                                            <div class="alert alert-warning" role="alert">
                                                <strong>Perhatian!</strong> File yang dapat dibaca hanya file dengan ekstensi <b>pdf</b>.
                                            </div>
                                            <div class="container mt-4 mb-4">
                                                <code>Upload File Dengan Format <b>.pdf | .doc | .docx | .xls | .xlsx | .zip</b>, Max Size: <b>20 MB</b></code><br>

                                                <div id="repeaterbook_edit">
                                                    <?php if ($data['file']) : ?>
                                                        <?php
                                                        $nofile = 0;
                                                        foreach ($data['filebuku'] as $datafile) :
                                                            ?>
                                                            <div class="file-input-container">

                                                                <div class="form-group">
                                                                    <div class="d-flex flex-row bd-highlight mb-3">
                                                                        <div class="p-2 bd-highlight"><label>File Buku:</label></div>
                                                                        <div class="p-2 bd-highlight">
                                                                            <a href="<?php echo base_url() . 'uploads/files/' . $datafile['namafile'] ?>" class="btn btn-primary rounded-0 mb-2" download><i class="flaticon-download"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="d-flex flex-row bd-highlight mb-3 col-lg-3">
                                                                            <div class="p-2 bd-highlight"><label>Is Baca:</label></div>
                                                                            <div class="p-2 bd-highlight">
                                                                                <select name="is_baca_file_<?php echo $nofile ?>">
                                                                                    <option value="Ya" <?php echo ($datafile['is_baca'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                                                                    <option value="Tidak" <?php echo ($datafile['is_baca'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex flex-row bd-highlight mb-3 col-lg-4">
                                                                            <div class="p-2 bd-highlight"><label>Is Download:</label></div>
                                                                            <div class="p-2 bd-highlight">
                                                                                <select name="is_download_file_<?php echo $nofile ?>">
                                                                                    <option value="Ya" <?php echo ($datafile['is_download'] == 'Ya') ? 'selected' : ''; ?>>Ya</option>
                                                                                    <option value="Tidak" <?php echo ($datafile['is_download'] == 'Tidak') ? 'selected' : ''; ?>>Tidak</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <button type="button" class="btn btn-danger btn-remove"><i class="fa fa-trash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <hr>
                                                            <?php
                                                            $nofile++;
                                                        endforeach;
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                                <button type="button" id="addFileInput_edit" class="btn btn-primary">Tambah File</button>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">
                                                        Next <i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </div>

                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    let fileInputCounter = 1; // Initialize counter for unique naming

                                                    const repeaterbook = document.getElementById('repeaterbook_edit');
                                                    const addFileInputBtn = document.getElementById('addFileInput_edit');

                                                    // Function to add remove event listener
                                                    function addRemoveEventListener(btn) {
                                                        btn.addEventListener('click', function () {
                                                            btn.closest('.file-input-container').remove();
                                                        });
                                                    }

                                                    // Attach event listeners to existing remove buttons
                                                    document.querySelectorAll('.btn-remove').forEach(btn => addRemoveEventListener(btn));

                                                    addFileInputBtn.addEventListener('click', function () {
                                                        const fileInputContainer = document.createElement('div');
                                                        fileInputContainer.classList.add('file-input-container');

                                                        fileInputContainer.innerHTML = `
                                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <span class="btn btn-default btn-file">
                                                                                <span class="fileinput-new">Pilih file tambahan</span>
                                                                                <span class="fileinput-exists">Change</span>
                                                                                <input type="file" name="file[]" multiple>
                                                                        </span>
                                                                        <span class="fileinput-filename"></span>
                                                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">                
                                                                        <div class="d-flex flex-row bd-highlight mb-3  col-lg-3">
                                                                            <div class="p-2 bd-highlight"><label>Is Baca:</label></div>
                                                                            <div class="p-2 bd-highlight">
                                                                                    <select name="is_baca_file[]">
                                                                                            <option value="Ya">Ya</option>
                                                                                            <option value="Tidak">Tidak</option>
                                                                                    </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex flex-row bd-highlight mb-3 col-lg-4">
                                                                            <div class="p-2 bd-highlight"><label>Is Download:</label></div>
                                                                            <div class="p-2 bd-highlight">
                                                                                    <select name="is_download_file[]">
                                                                                            <option value="Ya">Ya</option>
                                                                                            <option value="Tidak">Tidak</option>
                                                                                    </select>
                                                                            </div>
                                                                        </div>  
                                                                        <div class="col-lg-3">
                                                                            <button type="button" class="btn btn-danger btn-remove"><i class="fa fa-trash"></i></button>
                                                                        </div>
                                                                    </div>                        
                                                                </div>
                                                                
                                                                <hr>
                                                        `;

                                                        fileInputCounter++; // Increment the counter for the next set of inputs

                                                        repeaterbook.appendChild(fileInputContainer);

                                                        // Attach event listener to the new remove button
                                                        addRemoveEventListener(fileInputContainer.querySelector('.btn-remove'));
                                                    });
                                                });
                                            </script>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="form_kti" class="collapse" style="display:none;">
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
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="button" class="btn btn-primary ml-5" onclick="window.location.href='<?php echo base_url(); ?>dir/manage_buku'">Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Form-->
            <script type="text/javascript">
                var kd_penerbit = <?php echo $data[0]->kd_penerbit ?>;
            </script>
        </div>
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
        var update = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/update/";
        var tambah_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/tambah_inv/";
        var edit_site_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/edit_inv/";
        var site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/list/";
        var simpan_ubah_rak = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/simpan_ubah_rak/";
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

        function cek_isbn(isbn_val) {
            // Validasi input awal
            if (isbn_val == '') {
                swal({
                    title: 'Mohon Isi ISBN',
                    type: 'warning',
                    showConfirmButton: false,
                    timer: 1000
                });
                return;
            }

            var buku_id_val = $('[name="buku_id"]').val();
            var no_klas_val2 = $('[name="no_klas2"]').val(); 
            var isbn_input_val2 = $('[name="isbn2"]').val();

            var isbn_url = isbn_val.split('/').join('_');
            var cek_url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/cek_isbn_for_edit/" + isbn_url;

            $.ajax({
                url: cek_url,
                type: "POST",
                // Mengirimkan payload dalam bentuk objek
                data: {
                    buku_id: buku_id_val,
                    isbn2: isbn_input_val2,
                    no_klas2: no_klas_val2
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status == true) { 
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
                    console.error("Terjadi kesalahan: " + textStatus);
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
</script>

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