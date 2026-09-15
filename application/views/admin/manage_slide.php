<script type="text/javascript">
    $(function () {
        $(document).ready(function () {
            $('#m-dropzone-one').hide();
        });
        $('#upld_gambar').click(function () {
            $('#m-dropzone-one').show();
        });
        $('#media_gambar').click(function () {
            $('#m-dropzone-one').hide();
        });
        $('button[type="button"]').click(function () {
            $('#add_media_slide').val($('input[name="gambar_media"]:checked').val());
            // $('button[type="cancel"]').click();
        });
    });

    function hapus(id) {
        if (confirm("Ingin menghapus data ini ?") == true) {
            $.ajax({
                url: '<?php echo base_url() ?>admin/manage_slide/delete/' + id,
                type: 'post',
                success: function (resp) {
                    location.reload();
                }
            });
        }
    }

</script>
<style>
    .h {height: 5px;}
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
                                Slide
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
            <?php if ($this->session->flashdata('alert') != ''): ?>
                <script>
                    $(document).ready(function () {
                        setTimeout(function () {
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
            <?php endif; ?>
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Manajemen Slide
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <?php if (in_array($page_name, $this->session->userdata('perm_add'))): ?>
                        <?php if ($slide == ''): ?>

                            <a href="<?php echo base_url('admin/manage_slide/add'); ?>" class="btn btn-info" >
                                <i class="la la-plus"></i>
                                Tambah Slide
                            </a>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($slide == '') {
                ?>
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
                <!-- End Data Artikel -->
                <?php
            } elseif ($slide == 'edit') {
                if (!empty($edit)) {
                    $mediafile = $this->Md_media->getMediaById($edit->media_id);
                    ?>
                    <!-- Begin Edit Artikel -->
                    <div class="m-portlet__body">
                        <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('admin/manage_slide/edit/do_edit/' . $edit->slide_id); ?>" method="post"  enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="file" class="form-control-label">
                                    Media
                                </label>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                    <img src="<?php echo base_url(); ?>uploads/small/small_<?php echo $mediafile[0]['judul']; ?>">
                                </div>
                                <button type="button" class="btn btn-info btn-sm m-btn--air" id="media_gambar" data-toggle="modal" data-target="#tambah_modal">Galeri Media</button>
                                <button type="button" class="btn btn-warning btn-sm m-btn--air" id="upld_gambar">Upload Gambar</button>
                                <div class="fileinput fileinput-new form-control" data-provides="fileinput" id="m-dropzone-one">
                                    <span class="btn default btn-file">
                                        <input type="file" class="form-control" id="file" name="file"> 
                                        <button type="button" class="btn btn-info btn-sm fileinput-new">Pilih Gambar</button>
                                        <button type="button" class="btn btn-info btn-sm fileinput-exists"> Change </button>
                                    </span>
                                    <a href="javascript:;" class="btn btn-danger btn-sm fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                    <i>Gambar yang diupload harus berukuran 1920x1280.</i>
                                </div>

                                <input type="hidden" class="form-control" id="edit_media_slide" name="edit_media_slide" value="<?php echo $edit->media_id; ?>">
                            </div>
                            <div class="form-group">
                                <label for="edit_judul_slide" class="form-control-label">
                                    Judul
                                </label>
                                <input type="text" class="form-control" id="edit_judul_slide" name="edit_judul_slide"  value="<?php echo $edit->judul; ?>">
                            </div>
                            <div class="form-group">
                                <label for="edit_isi_slide" class="form-control-label">
                                    Keterangan
                                </label>
                                <textarea class="summernote" id="edit_keterangan_slide" name="edit_keterangan_slide" ><?php echo $edit->keterangan; ?></textarea>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="edit_status_slide" class="form-control-label">
                                    Urutan
                                </label>
                                <input type="number" class="form-control m-input" id="edit_urutan_slide" name="edit_urutan_slide" value="<?php echo $edit->urutan; ?>" required=""></input>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                    <button type="reset" class="btn btn-secondary" onclick="location.href = '<?php echo base_url('admin/manage_slide'); ?>';">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Edit Artikel -->
                    <?php
                }
            } elseif ($slide == 'add') {
                ?>
                <!-- Begin Edit Artikel -->
                <div class="m-portlet__body">
                    <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('admin/manage_slide/add/do_add'); ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="file" class="form-control-label">
                                Media
                            </label>
                            <button type="button" class="btn btn-info btn-sm m-btn--air" id="media_gambar" data-toggle="modal" data-target="#tambah_modal">Galeri Media</button>
                            <button type="button" class="btn btn-warning btn-sm m-btn--air" id="upld_gambar">Upload Gambar</button>
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput" id="m-dropzone-one">
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                <span class="btn default btn-file">
                                    <input type="file" class="form-control" id="file" name="file"> 
                                    <button type="button" class="btn btn-info btn-sm fileinput-new">Pilih Gambar</button>
                                    <button type="button" class="btn btn-info btn-sm fileinput-exists"> Change </button>
                                </span>
                                <a href="javascript:;" class="btn btn-danger btn-sm fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                <i>Gambar yang diupload harus berukuran 1920x1280.</i>
                            </div>
                            <input type="hidden" class="form-control" id="add_media_slide" name="add_media_slide">
                        </div>
                        <div class="form-group">
                            <label for="edit_judul_slide" class="form-control-label">
                                Judul
                            </label>
                            <input type="text" class="form-control" id="add_judul_slide" name="add_judul_slide"  value="">
                        </div>
                        <div class="form-group">
                            <label for="add_isi_slide" class="form-control-label">
                                Keterangan
                            </label>
                            <textarea class="summernote" id="add_keterangan_slide" name="add_keterangan_slide"  ></textarea>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="edit_status_slide" class="form-control-label">
                                Urutan
                            </label>
                            <input type="number" class="form-control m-input" id="add_urutan_slide" name="add_urutan_slide" value="" required></input>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary" onclick="location.href = '<?php echo base_url('admin/manage_slide'); ?>';">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- End Edit Artikel -->
            <?php }
            ?>
        </div>
        <!--begin::Modal-->
        <div class="modal fade" id="tambah_modal" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelModalTambah">
                            Tambah Media
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <?php foreach ($media as $row) {
                                    ?>
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <div class="well h"></div>
                                        <label for="<?php echo $row['media_id']; ?>">
                                            <input type="radio" name="gambar_media" id="<?php echo $row['media_id']; ?>" value="<?php echo $row['media_id']; ?>" />
                                            <img src="<?php echo base_url() . 'uploads/small/small_' . $row['judul']; ?>" alt="" width="100%" heigth="100%">
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">
                                Pilih Gambar
                            </button>
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Modal-->
    </div>
</div>

<script type="text/javascript">
    var DatatableRemoteAjaxDemo = function () {
        var t = function () {
            var t = $(".m_datatable").mDatatable({
                data: {
                    type: "remote", source: {
                        read: {
                            url: "<?php echo base_url(); ?>admin/manage_slide/list/"
                        }
                    }
                    , pageSize: 10, saveState: {
                        cookie: !0, webstorage: !0
                    }
                    , serverPaging: false, serverFiltering: false, serverSorting: false
                }
                , layout: {
                    theme: "default", class: "", scroll: !1, footer: !1
                }
                , sortable: !0, filterable: !1, pagination: !0, columns: [
                    {
                        field: "slide", title: "Foto", filterable: !1
                    },
                    {
                        field: "judul",
                        title: "Judul Slide",
                        sortable: !1,
                        selector: !1,
                        textAlign: "center"
                    }
                    , {
                        field: "keterangan", title: "Keterangan", filterable: !1
                    }
                    , {
                        field: "author", title: "Author", filterable: !1
                    }
                    , {
                        field: "tgl_post", title: "Tanggal Post", filterable: !1
                    }
                    , {
                        field: "action", width: 110, title: "Actions", sortable: !1, overflow: "visible", template: function (t) {
                            return'\t\t\t\t\t\t<div class="dropdown ' + (t.getDatatable().getPageSize() - t.getIndex() <= 4 ? "dropup" : "") + '">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<?php if (in_array($page_name, $this->session->userdata('perm_edit'))): ?><a class="dropdown-item" href="<?php echo base_url('admin/manage_slide/edit/'); ?>' + t.id + '"><i class="la la-edit"></i> Edit Data</a><?php endif; ?>\t\t\t\t\t\t    \t<?php if (in_array($page_name, $this->session->userdata('perm_delete'))): ?><a class="dropdown-item" onClick="hapus(\'' + t.id + '\')" href="#"><i class="la la-remove"></i> Hapus Data</a><?php endif; ?>\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
                        }
                    }
                ]
            }
            ),
                    e = t.getDataSourceQuery();
            $("#m_form_search").on("keyup", function (e) {
                var a = t.getDataSourceQuery();
                a.generalSearch = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
            }
            ).val(e.generalSearch),
                    $("#m_form_status").on("change", function () {
                var e = t.getDataSourceQuery();
                e.Status = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
            }
            ).val(void 0 !== e.Status ? e.Status : ""),
                    $("#m_form_type").on("change", function () {
                var e = t.getDataSourceQuery();
                e.Type = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
            }
            ).val(void 0 !== e.Type ? e.Type : ""),
                    $('#m_datatable_reload').on('click', function () {
                t.reload();
            }),
                    $("#m_form_status, #m_form_type").selectpicker()
        };
        return {
            init: function () {
                t()
            }
        }
    }

    ();
    jQuery(document).ready(function () {
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
</script>

<script src="<?php echo base_url(); ?>assets/admin/summernote.js" type="text/javascript"></script>