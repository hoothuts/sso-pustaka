<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Menu
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
                                Menu
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

            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Manajemen Menu
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="<?= base_url('admin/manage_menu/add'); ?>" class="btn btn-info">
                        <i class="la la-plus"></i>
                        Tambah Menu
                    </a>
                    <button type="button" onclick="preview()" class="btn btn-warning">
                        <i class="la la-eye"></i>
                        Preview
                    </button>
                </div>
            </div>
            <!-- Begin Data Menu -->
            <div class="m-portlet__body">
                <?php if ($this->session->flashdata('alert') != '') : ?>
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
                <?php endif; ?>
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-4">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <div class="m_datatable" id="ajax_data"></div>
            </div>
        </div>
    </div>
</div>

<!--begin::Modal-->
<div class="modal fade" id="modal-preview" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelModalTambah">
                    Preview Menu
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach (get_menus() as $key => $value) : ?>

                    <?php if (count($value->children) == 0) : ?>
                        <button class="btn btn-outline-brand mt-2"><?= ucwords(strtolower($value->nama_menu)) ?></button>
                    <?php else : ?>
                        <div class="btn-group mt-2">
                            <button type="button" class="btn btn-outline-brand dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= ucwords(strtolower($value->nama_menu)) ?>
                            </button>
                            <div class="dropdown-menu">
                                <?php foreach ($value->children as $child) : ?>
                                    <?php if (count($child->child) == 0) : ?>
                                    <a class="dropdown-item" href="#"><?= ucwords(strtolower($child->nama_menu)) ?></a>
                                    <?php else : ?>
                                        <button type="button" class="btn btn-outline-brand dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?= ucwords(strtolower($child->nama_menu)) ?>
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php foreach ($child->child as $value2) : ?>
                                                <a class="dropdown-item" href="#"><?= ucwords(strtolower($value2->nama_menu)) ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
<script type="text/javascript">
    var datatable = function() {
        var t = function() {
            var t = $(".m_datatable").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url(); ?>admin/manage_menu/list/"
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
                        field: "no",
                        title: "#",
                        sortable: !1,
                        width: 50,
                    },
                    {
                        field: "nama_menu",
                        title: "Menu",
                        sortable: !1,
                        width: 100,
                    }, {
                        field: "link",
                        title: "Link",
                        sortable: !1,
                        width: 250,
                    }, {
                        field: "level",
                        title: "Level",
                        sortable: !1,
                        width: 50,
                    }, {
                        field: "urutan",
                        title: "Urutan",
                        sortable: !1,
                        width: 50,
                        template: function(d) {
                            let id = d.menu_id
                            return `<input type="number" style="width: 100%" name="urutan" value="${d.urutan}" data-id="${id}">`
                        }
                    }
//                    , {
//                        field: "is_active",
//                        title: "Is Active",
//                        sortable: !1,
//                        width: 100,
//                        template: function(d) {
//                            let id = d.menu_id
//                            let checked = d.is_active == 1 ? 'checked' : '';
//
//                            return `
//                                            <div class="m-checkbox-list">
//                                                <label class="m-checkbox">
//                                                    <input type="checkbox" name="is_active" ${checked} value="1" data-id="${id}">Aktif
//                                                    <span></span>
//                                                </label>
//                                            </div>`
//                        }
//                    }
                    , {
                        field: "action",
                        width: 80,
                        title: "Actions",
                        sortable: !1,
                        overflow: "visible",
                        template: function(t) {
                            return `
                                                <div class="dropdown dropup">
                                                    <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown"> <i class="la la-gear"></i> </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <?php if (in_array($page_name, $this->session->userdata('perm_edit'))) : ?>
                                                            <a class="dropdown-item" href="<?= base_url('admin/manage_menu/edit/'); ?>${t.menu_id}">
                                                                <i class="la la-edit"></i> Edit Data
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (in_array($page_name, $this->session->userdata('perm_delete'))) : ?>
                                                            <a class="dropdown-item" onClick="hapus('${t.menu_id}')" href="#">
                                                                <i class="la la-remove"></i> Hapus Data
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            `;
                        }
                    }
                ]
            });

            e = t.getDataSourceQuery();
            $("#m_form_search").on("keyup", function(e) {
                var a = t.getDataSourceQuery();
                a.generalSearch = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
            }).val(e.generalSearch);

            $("#m_form_status").on("change", function() {
                var e = t.getDataSourceQuery();
                e.Status = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
            }).val(void 0 !== e.Status ? e.Status : "");

            $("#m_form_type").on("change", function() {
                var e = t.getDataSourceQuery();
                e.Type = $(this).val().toLowerCase(), t.setDataSourceQuery(e), t.load()
            }).val(void 0 !== e.Type ? e.Type : "");

            $('#m_datatable_reload').on('click', function() {
                t.reload();
            });

            $(document).on('change', '[name="urutan"]', function(e) {
                let id = $(this).data('id')
                mApp.blockPage()
                $.ajax({
                    url: "<?= base_url('admin/manage_menu/update_urutan') ?>",
                    dataType: 'JSON',
                    type: "POST",
                    data: {
                        id: id,
                        urutan: this.value
                    },
                    success: function(res) {
                        mApp.unblockPage()
                        if (!res.success) {
                            return alert('Oops!! something goes wrong, please refresh the page or contact the administrator.');
                        }
                        location.reload();

                    },
                    error: function(err) {
                        alert('Oops!! something goes wrong, please refresh the page or contact the administrator.');
                    }
                });
            });

            $(document).on('change', '[name="is_active"]', function(e) {
                let id = $(this).data('id')
                let value = $(this).is(":checked")

                mApp.blockPage()
                $.ajax({
                    url: "<?= base_url('admin/manage_menu/update_status_aktif') ?>",
                    dataType: 'JSON',
                    type: "POST",
                    data: {
                        id: id,
                        is_active: value ? 1 : 0
                    },
                    success: function(res) {
                        mApp.unblockPage()
                        if (!res.success) {
                            return alert('Oops!! something goes wrong, please refresh the page or contact the administrator.');
                        }
                        location.reload();

                    },
                    error: function(err) {
                        alert('Oops!! something goes wrong, please refresh the page or contact the administrator.');
                    }
                });
            });

            $("#m_form_status, #m_form_type").selectpicker();

        };
        return {
            init: function() {
                t()
            }
        }
    }();

    $(document).ready(function() {
        datatable.init()
    });

    function hapus(id) {
        if (confirm("Ingin menghapus data ini ?") == true) {
            $.ajax({
                url: '<?= base_url() ?>admin/manage_menu/delete/',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id
                },
                success: function(res) {
                    if (!res.success) {
                        return alert('Gagal Menghapus Data');
                    }

                    location.reload();
                }
            });
        }
    }

    function preview() {
        $('#modal-preview').modal('show');
    }
</script>