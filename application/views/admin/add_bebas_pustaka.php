<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Manage Bebas Pustaka
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Manage Bebas Pustaka
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->


    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    <?= $page_title ?> & Data Hibah Buku Dari Mahasiswa
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form method="post" action="" class="m-form m-form--fit m-form--label-align-right" autocomplete="off" id="formAdd">
                        <div class="m-portlet__body">


                            <?php if ($this->session->flashdata('alert') != '') : ?>
                                <div class="form-group m-form__group">
                                    <?php $alert =  $this->session->flashdata('alert') ?>
                                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                                        <div class="m-alert__icon">
                                            <i class="flaticon-exclamation-1"></i>
                                            <span></span>
                                        </div>
                                        <div class="m-alert__text">
                                            <?= $alert['msg'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_pesan)) : ?>
                                <div class="form-group m-form__group">
                                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-warning alert-dismissible fade show" role="alert">
                                        <div class="m-alert__icon">
                                            <i class="flaticon-exclamation-1"></i>
                                            <span></span>
                                        </div>
                                        <div class="m-alert__text">
                                            <?= $_pesan ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <h3 class="m-portlet__head-text">
                                <center>Form Bebas Pustaka</center>
                            </h3>
                            <div class="form-group m-form__group">
                                <label>Nomor Anggota <span class="text-danger">*</span></label>
                                <select class="form-control pegawai" name="anggota_id">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group m-form__group">
                                <label>No Surat Bebas Pustaka<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_surat_bebas_pustaka" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tanggal Bebas Pustaka<span class="text-danger">*</span></label>
                                        <div class='input-group date' id='m_datepicker_2_modal'>
                                            <input type='text' id="tanggal" name="tanggal" class="form-control m-input" readonly placeholder="Masukkan tanggal" />
                                            <span class="input-group-addon">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tahun Akademik<span class="text-danger">*</span></label>
                                        <select class="form-control m-input m-input--square" name="tahun_akademik">
                                            <?php
                                            $current_year = date('Y');
                                            $last_year = $current_year + 1;

                                            for ($i = $current_year - 10; $i <= $last_year; $i++) :
                                                $option_value = ($i - 1) . '/' . $i;
                                                $selected = ($i == $last_year) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $option_value; ?>" <?php echo $selected; ?>><?php echo $option_value; ?></option>
                                            <?php endfor; ?>



                                        </select>
                                    </div>
                                </div>



                            </div>

                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Kepala Perpus<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_kepala_perpus" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>NIP Kepala Perpus<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nip_kepala_perpus" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Petugas Perpus<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_petugas_perpus" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>NIP Petugas Perpus<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nip_petugas_perpus" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3 class="m-portlet__head-text">
                                <center>Form Hibah Buku</center>
                            </h3>


                            <div class="form-group m-form__group">
                                <label>No Surat Hibah Buku</label>
                                <input type="text" class="form-control" name="no_surat_hibah_buku" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <label>Judul Buku 1</label>
                                <input type="text" class="form-control" name="judul1" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <label>Pengarang Buku 1</label>
                                <input type="text" class="form-control" name="pengarang1" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <label>Penerbit Buku 1</label>
                                <input type="text" class="form-control" name="penerbit1" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tempat Terbit Buku 1</label>
                                        <input type="text" class="form-control" name="tempat_terbit1" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tahun Terbit Buku 1</label>
                                        <div class='input-group date m_datepicker_3_modal'>
                                            <input type='text' id="tanggal" name="tahun_terbit1" class="form-control m-input" readonly placeholder="Masukkan tahun" />
                                            <span class="input-group-addon">
                                                <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-group m-form__group">
                                <label>ISBN Buku 1</label>
                                <input type="text" class="form-control" name="isbn1" placeholder="">

                            </div>

                            <div class="form-group m-form__group">
                                <label>Judul Buku 2</label>
                                <input type="text" class="form-control" name="judul2" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <label>Pengarang Buku 2</label>
                                <input type="text" class="form-control" name="pengarang2" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <label>Penerbit Buku 2</label>
                                <input type="text" class="form-control" name="penerbit2" placeholder="">

                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tempat Terbit Buku 2</label>
                                        <input type="text" class="form-control" name="tempat_terbit2" placeholder="">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tahun Terbit Buku 2</label>
                                        <div class='input-group date m_datepicker_3_modal'>
                                            <input type='text' id="tanggal" name="tahun_terbit2" class="form-control m-input" readonly placeholder="Masukkan tahun"" />
                                    <span class=" input-group-addon">
                                            <i class="la la-calendar-check-o"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-group m-form__group">
                                <label>ISBN Buku 2</label>
                                <input type="text" class="form-control" name="isbn2" placeholder="">

                            </div>




                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <a onclick="save()" id="btnSaveAjax" class="btn btn-info text-white">
                                    Simpan
                                </a>
                                <button type="button" onclick="history.back()" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php base_url() ?>assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script type="text/javascript">
    $("#m_datepicker_2_modal").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: !0,
        orientation: "bottom left",
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });

    $(".m_datepicker_3_modal").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom left",
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    });

    function show_parent_field(e) {
        $('[name="menuparent_id"]').attr('disabled', true);
        if (e.value != 1) {
            $('[name="menuparent_id"]').attr('disabled', false);
        }
    }

    $(document).ajaxStop($.unblockUI);

    function resetForm() {
        // hide allert
        $('#m_form_1_msg').hide();

        // reset form input normal
        $('#formAdd')[0].reset();

        // reset form select 

        $('[name="nm_mediasosial"]').prop("disabled", false);
        $('[name="nm_mediasosial"]').val(null).trigger("change");

        $('.m-select2').select2({
            width: '100%'
        });
    }


    function add_ajax() {
        method = 'add';
        resetForm();

        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#m_form_1_msg').hide();
        $('#m_modal_6').modal('show');
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Tambah Media Sosial");
    }

    function save() {
        var url;

        url = "<?= base_url() ?>admin/manage_bebas_pustaka/save";
        $.blockUI();
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($('#formAdd')[0]),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            dataType: "JSON",
            success: function(data) {
                if (data.status == 'success') {
                    Swal.fire({
                        title: "Berhasil..",
                        text: "Data anda berhasil disimpan " + data.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'admin/manage_bebas_pustaka/';
                        }
                    });
                } else {
                    Swal.fire({
                        text: data.message,
                        icon: "warning",
                        closeOnConfirm: true
                    });
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire("Oops", "Data gagal disimpan !", "error");

            }
        });
    }

    $(".pegawai").select2({
        width: "100%",
        closeOnSelect: true,
        placeholder: "Cari NIM atau nama Mahasiswa",
        ajax: {
            url: "<?php echo base_url('admin/manage_bebas_pustaka/serach_anggota'); ?>",
            dataType: "json",
            type: "GET",
            delay: 500,
            data: function(e) {
                return {
                    searchtext: e.term,
                    page: e.page,
                }
            },
            processResults: function(e, t) {
                $(e.items).each(function() {
                    this.id = this.id;
                    this.text = this.nama;
                });

                return t.page = t.page || 1, {
                    results: e.items,
                }
            },
            cache: !0
        },
        escapeMarkup: function(e) {
            return e
        },
        minimumInputLength: 1,
        templateResult: function(e) {
            console.log(e);
            if (e.loading) return e.text;
            var t = "<div class='select2-result-repository clearfix'><div class='select2-result-repository__meta'><div class='select2-result-repository__title'>" +
                "[" + e.no_anggota + "] " + e.nama +
                "</div></div></div>";
            return t
        },
        templateSelection: function(e) {
            return e.text
        }
    });
</script>