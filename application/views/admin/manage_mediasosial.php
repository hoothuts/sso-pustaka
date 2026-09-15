<script type="text/javascript">
    $(function() {
        $(document).ready(function() {
            $('#m-dropzone-one').hide();
        });
        $('#upld_gambar').click(function() {
            $('#m-dropzone-one').show();
        });
        $('#media_gambar').click(function() {
            $('#m-dropzone-one').hide();
        });
        $('button[type="button"]').click(function() {
            $('#add_media_logo').val($('input[name="gambar_media"]:checked').val());
            // $('button[type="cancel"]').click();
        });
    });

    function hapus(id) {
        if (confirm("Ingin menghapus data ini ?") == true) {
            $.ajax({
                url: '<?php echo base_url() ?>admin/manage_logo/delete/' + id,
                type: 'post',
                success: function(resp) {
                    location.reload();
                }
            });
        }
    }
</script>
<style>
    .h {
        height: 5px;
    }
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon flaticon-background"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Media Sosial
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <?php if ($this->session->flashdata('alert') != '') : ?>
                <script>
                    $(document).ready(function() {
                        setTimeout(function() {
                            document.getElementById('flashmsg').style.display = 'none';
                        }, 5000);
                    });
                </script>
                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade show" id='flashmsg'>
                    <div class="m-alert__icon">
                        <i class="flaticon-exclamation-1"></i>
                        <span></span>
                    </div>
                    <div class="m-alert__text">
                        <?php echo $this->session->flashdata('flash_message') ?>
                    </div>
                </div>
            <?php endif; // var_dump($this->session->userdata('perm_add'));
            ?>
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Manajemen Media Sosial
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <?php if (in_array($page_name, $this->session->userdata('perm_add'))) : ?>
                        <?php if ($logo == '') : ?>

                            <button type="button" class="btn btn-info" onclick="add_ajax()">
                                <i class="la la-plus"></i>
                                Tambah Media Sosial
                            </button>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Begin Data Artikel -->
            <div class="m-portlet__body">

                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-1 order-xl-2 m--align-left">
                            <div class="col-md-4">
                                <input type="text" class="form-control m-input" placeholder="Cari disini..." id="m_form_search">
                            </div>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->
                <div class="m_datatable" id="ajax_data"></div>
            </div>

        </div>


        <div class="modal fade" id="m_modal_6" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header m--bg-brand">
                        <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                            Tambah Media Sosial
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formAdd">
                        <div class="modal-body m--bg-metal">
                            <div class="m-form__content">
                                <div class="m-alert m-alert--icon alert alert-danger" role="alert" id="m_form_1_msg">
                                    <div class="m-alert__icon">
                                        <i class="la la-warning"></i>
                                    </div>
                                    <div class="m-alert__text">
                                        Upss .. ! Periksa kembali data yang anda inputkan, pastikan seluruh kolom required
                                        terisi.
                                    </div>
                                    <div class="m-alert__close">
                                        <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="mediasosial_id" value="">
                            <div class="form-group m-form__group row">
                                <label class="col-form-label col-md-3">
                                    Media Sosial<font class="m--font-danger">*</font>
                                </label>
                                <div class="col-md-6">
                                    <select id="country_id" name="nm_mediasosial" class="form-control m-input m-select2">
                                        <option value="">-- Pilih Media Sosial --</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="Twitter">Twitter/X</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="Telegram">Telegram</option>
                                        <option value="Whatsapp">Whatsapp</option>
                                        <option value="Youtube">Youtube</option>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-form-label col-md-3">
                                    Alamat Url Media Sosial<font class="m--font-danger">*</font>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" name="user_mediasosial" required class="form-control m-input" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer m--bg-brand">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Batal
                            </button>
                            <a href="#" onclick="save()" id="btnSaveAjax" class="btn btn-accent">
                                Simpan
                            </a>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    var DatatableRemoteAjaxDemo = function() {
        var t = function() {
            var t = $(".m_datatable").mDatatable({
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url: "<?php echo base_url(); ?>admin/manage_mediasosial/list/"
                            }
                        },
                        pageSize: 10,
                        saveState: {
                            cookie: !0,
                            webstorage: !0
                        },
                        serverPaging: false,
                        serverFiltering: false,
                        serverSorting: false
                    },
                    layout: {
                        theme: "default",
                        class: "",
                        scroll: !1,
                        footer: !1
                    },
                    sortable: !0,
                    filterable: !1,
                    pagination: !0,
                    columns: [{
                        field: "nm_mediasosial",
                        title: "Nama Media Sosial",
                        filterable: !1
                    }, {
                        field: "user_mediasosial",
                        title: "User Media Sosial",
                        filterable: !1
                    }, {
                        field: "action",
                        width: 110,
                        title: "Actions",
                        sortable: !1,
                        overflow: "visible",
                        template: function(t) {
                            return '\
                                <div class="dropdown ' + (t.getDatatable().getPageSize() - t.getIndex() <= 4 ? "dropup" : "") + '">\
                                    <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                        <i class="la la-gear"></i>\
                                    </a>\
                                    <div class="dropdown-menu dropdown-menu-right">\
                                        <?php if (in_array($page_name, $this->session->userdata('perm_edit'))) : ?>\
                                            <a class="dropdown-item" href="javascript:edit(\'' + t.id + '\')">\
                                                <i class="la la-edit"></i> Edit Data\
                                            </a>\
                                        <?php endif; ?>\
                                        <?php if (in_array($page_name, $this->session->userdata('perm_delete'))) : ?>\
                                            <a class="dropdown-item" href="javascript:hapus(\'' + t.id + '\')">\
                                                <i class="la la-remove"></i> Hapus Data\
                                            </a>\
                                        <?php endif; ?>\
                                    </div>\
                                </div>'
                        }


                    }]
                }),
                e = t.getDataSourceQuery();
            $("#m_form_search").on("keyup", function(e) {
                    var a = t.getDataSourceQuery();
                    a.generalSearch = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
                }).val(e.generalSearch),
                $("#m_form_status").on("change", function() {
                    var e = t.getDataSourceQuery();
                    e.Status = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
                }).val(void 0 !== e.Status ? e.Status : ""),
                $("#m_form_type").on("change", function() {
                    var e = t.getDataSourceQuery();
                    e.Type = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
                }).val(void 0 !== e.Type ? e.Type : ""),
                $('#m_datatable_reload').on('click', function() {
                    t.reload();
                }),
                $("#m_form_status, #m_form_type").selectpicker()
        };
        return {
            init: function() {
                t()
            }
        }
    }

    ();
    jQuery(document).ready(function() {
            DatatableRemoteAjaxDemo.init()
        }

    );

    function confirmHapus() {
        return confirm("Anda yakin menghapus data ini?");
    }

    function confirmReset() {
        if (confirm("Anda yakin melakukan reset password?") == true) {
            return true;
        } else {
            return false;
        }
    }

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

    function edit(id) {
        mApp.blockPage({ //block page
            overlayColor: "#000000",
            type: "loader",
            state: "primary",
            message: "Please wait..."
        });

        method = 'edit';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Edit Media Sosial");

        $.ajax({
            url: "<?php echo base_url() . 'admin/manage_mediasosial/edit' ?>/" +
                id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.data == true) {
                    $('#formAdd')[0].reset();
                    $('[name="mediasosial_id"]').val(data.mediasosial_id);
                    $('[name="user_mediasosial"]').val(data.user_mediasosial);
                    $('[name="nm_mediasosial"] option[value="' + data.nm_mediasosial + '"]').attr('selected', 'selected');
                    $('.m-select2').select2({
                        width: '100%'
                    });
                    $('[name="nm_mediasosial"]').prop("disabled", true);

                    $('#m_modal_6').modal('show');

                } else if (data.data == false) {
                    Swal.fire("Oops", "Data gagal mengambil data!", "error");
                }
                mApp.unblockPage();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                mApp.unblockPage();
                alert('Error get data from ajax');
            }
        });
        $('#formAdd')[0].reset();
    }


    function save() {
        mApp.block(".modal-content", { //block modal
            overlayColor: "#000000",
            type: "loader",
            state: "primary",
            message: "Please wait..."
        });

        var url;

        if (method == 'add') {
            url = "<?= base_url() . 'admin/manage_mediasosial/add' ?>";
        } else {
            url = "<?= base_url() ?>admin/manage_mediasosial/update";
        }

        // ajax adding data to database
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

                    $('#m_modal_6').modal('hide');
                    Swal.fire("Berhasil..", "Data anda berhasil disimpan " + data.message, "success");

                    //relaod datatablesnya
                    $('.m_datatable').mDatatable('reload');

                } else if (data.status == 'exist') {
                    Swal.fire("Gagal..", "Data telah digunakan", "success");
                } else {

                    Swal.fire({
                        text: data.message,
                        icon: "warning",
                        closeOnConfirm: true
                    });
                }
                mApp.unblock(".modal-content");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                mApp.unblock(".modal-content");
                Swal.fire("Oops", "Data gagal disimpan !", "error");
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            }
        });
    }

    function hapus(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "<span><i class='flaticon-interface-1'></i><span>Ya, Hapus!</span></span>",
            confirmButtonClass: "btn btn-danger m-btn m-btn--pill m-btn--icon",
            cancelButtonText: "<span><i class='flaticon-close'></i><span>Batal Hapus</span></span>",
            cancelButtonClass: "btn btn-metal m-btn m-btn--pill m-btn--icon"
        }).then(function(e) {
            if (e.value) {
                mApp.blockPage({ //block page
                    overlayColor: "#000000",
                    type: "loader",
                    state: "primary",
                    message: "Please wait..."
                });

                $.ajax({
                    url: "<?php echo base_url() . 'admin/manage_mediasosial/delete' ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.data == true) {
                            Swal.fire("Berhasil..", "Data berhasil dihapus", "success");
                            $('.m_datatable').mDatatable('reload');
                        } else if (data.status == 'exist') {
                            Swal.fire("Gagal..", "Data telah digunakan pada tabel lain", "error");
                        } else if (data.data == false) {
                            Swal.fire("Oops", "Data gagal dihapus!", "error");
                        }
                        mApp.unblockPage();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        mApp.unblockPage();
                        Swal.fire("Oops", "Data gagal dihapus!", "error");
                    }
                })
            }
        });
    }
</script>

<script src="<?php echo base_url(); ?>assets/admin/summernote.js" type="text/javascript"></script>