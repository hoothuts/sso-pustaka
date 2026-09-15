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
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Laporan
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                <?php echo $page_title; ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <?php echo $this->session->flashdata('flash_message') ?>
            </div>
        </div>
        <form target="_blank" id="exportweb" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/export/web" method="post">
            <input type="hidden" name="wpilih" value="<?php if (isset($jenis)) echo $jenis; ?>">
            <input type="hidden" name="wtanggalawal" value="<?php if (isset($tanggalawal)) echo $tanggalawal; ?>">
            <input type="hidden" name="wtanggalakhir" value="<?php if (isset($tanggalakhir)) echo $tanggalakhir; ?>">
            <input type="hidden" name="wsearch" id="wsearch" value="<?php if ($src) echo $src; ?>">
            <input type="hidden" name="wpst" id="wpst" value="<?php if ($prodi) echo $prodi; ?>">
        </form>
        <form target="_blank" id="exportexcel" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/export/excel" method="post">
            <input type="hidden" name="epilih" value="<?php if (isset($jenis)) echo $jenis; ?>">
            <input type="hidden" name="etanggalawal" value="<?php if (isset($tanggalawal)) echo $tanggalawal; ?>">
            <input type="hidden" name="etanggalakhir" value="<?php if (isset($tanggalakhir)) echo $tanggalakhir; ?>">
            <input type="hidden" name="esearch" id="esearch" value="<?php if ($src) echo $src; ?>">
            <input type="hidden" name="epst" id="epst" value="<?php if ($prodi) echo $prodi; ?>">
        </form>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?php echo $page_title; ?>
                        </h3>
                    </div>
                </div>
                <?php if (in_array('laporan', $this->session->userdata('perm_print'))): ?>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--pill  m-dropdown__toggle">
                                        <i class="la la-clone m--font-brand"></i> Export
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__item">
                                                            <a href="#"  onclick="document.getElementById('exportweb').submit();return false;" class="m-nav__link" target="_blank">
                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                <span class="m-nav__link-text">
                                                                    Web
                                                                </span>
                                                            </a>
                                                            </form>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="#" onclick="document.getElementById('exportexcel').submit();return false;" class="m-nav__link" >
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text">
                                                                    Excel
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit m--hide"></li>
                                                        <li class="m-nav__item m--hide">
                                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
                                                                Submit
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                    <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>" method="post">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-2">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <label class="m-label m-label--single">
                                                    Tanggal Daftar:
                                                </label>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="m-form__control">
                                            <div class='input-group date' id='m_datepicker_1'>
                                                <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly  placeholder="Tanggal Awal"/>
                                                <span class="input-group-addon">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <label class="m-label m-label--single">
                                                    S/D
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="m-form__control">
                                            <div class='input-group date' id='m_datepicker_2'>
                                                <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly  placeholder="Tanggal Akhir"/>
                                                <span class="input-group-addon">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function cleardate() {
                                $('#tanggalawal').datepicker('setDate', null);
                                $('#tanggalakhir').datepicker('setDate', null);
                            }
                        </script>

                        <div class="row align-items-center m--margin-top-10 ">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-4">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <label class="m-label m-label--single">
                                                    Pilih:
                                                </label>
                                            </div>
                                            <div class="m-form__controlv">
                                                <select class="form-control" name="pilih" id="pilih">
                                                    <option value="mahasiswa" <?php if ($jenis == 'mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                                                    <option value="pegawai" <?php if ($jenis == 'pegawai') echo 'selected'; ?>>Pegawai</option>
                                                    <option value="anggota+luar" <?php if ($jenis == 'anggota+luar') echo 'selected'; ?>>Anggota Luar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-4" id="prodi_wrapper">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <label class="m-label m-label--single">
                                                    Prodi:
                                                </label>
                                            </div>
                                            <div class="m-form__control">
                                                <select class="form-control" id="m_form_prodi" name="prodi">
                                                    <option value="">-- Pilih Program Studi --</option>
                                                    <?php if (!empty($prodi_list)) { ?>   
                                                        <?php foreach ($prodi_list as $row) { ?>
                                                            <option value="<?php echo htmlspecialchars($row->kdpst); ?>"
                                                                <?php echo ($prodi === $row->kdpst) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($row->nmmspst); ?>
                                                            </option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-xl-4 m--align-right order-1 order-xl-2">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <input type="submit" class="btn btn-primary" value="Submit">
                                                <a href="#" class="btn btn-secondary" onClick="cleardate()">
                                                    Cancel
                                                </a>
                                            </div>

                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center m--margin-top-10 m--margin-bottom-30">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">								
                                    <div class="col-md-4">
                                        <div class="m-input-icon m-input-icon--left">
                                            <input type="text" value="" class="form-control m-input" name="src" placeholder="<?php if ($src) echo $src; else echo 'Search....' ?>" id="m_form_search">
                                            <span class="m-input-icon__icon m-input-icon__icon--left">
                                                <span>
                                                    <i class="la la-search"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="m_datatable" id="ajax_data">
                    <!-- Here is Data Table Begin -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->
<?php if ($jenis == 'mahasiswa'): ?>
    <script type="text/javascript">

        var save_method; //for save method string
        var table;
        var DatatableRemoteAjaxDemo = function () {
            var t = function () {
                var t = {
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/mahasiswa",
                                params: {
                                    // custom query params
                                    query: {
                                        generalSearch: '<?php if ($src) echo $src; ?>',
                                        tanggalawal: '<?php if ($tanggalawal) echo $tanggalawal; ?>',
                                        tanggalakhir: '<?php if ($tanggalakhir) echo $tanggalakhir; ?>',
                                        prodi: $('#m_form_prodi').val()
                                    }
                                }
                            }
                        }
                        ,
                        saveState: {
                            cookie: !1, webstorage: !1
                        }
                        ,
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true
                    }
                    , layout: {
                        theme: "default", class: "", scroll: !1, footer: !1
                    }
                    , sortable: true,
                    filterable: false,
                    pagination: true,
                    searchDelay: 400,
                    columns: [{
                            field: "number", title: "#", sortable: false, width: 40, selector: !1, textAlign: "center"
                        }
                        , {
                            field: "nis", title: "NIM", filterable: !1, width: 100
                        }
                        , {
                            field: "nama", title: "Nama"
                        }
                        , {
                            field: "kelas", title: "Program Studi", width: 130
                        }
                        , {
                            field: "jenis", title: "Jenis Kelamin", width: 130
                        }
                        , {
                            field: "status_siswa", title: "Status Studi"
                        }
                        , {
                            field: "status_anggota", title: "Status Anggota", width: 120
                        }
                        , {
                            field: "tgl", title: "Tgl Daftar"
                        }
//                        , {
//                            field: "berlaku", title: "Berlaku Sampai", width: 120
//                        }
                    ],
                    toolbar: {
                        layout: ['pagination', 'info'],
                        placement: ['bottom'], //'top', 'bottom'
                        items: {
                            pagination: {
                                type: 'default',
                                pages: {
                                    desktop: {
                                        layout: 'default',
                                        pagesNumber: 6
                                    },
                                    tablet: {
                                        layout: 'default',
                                        pagesNumber: 3
                                    },
                                    mobile: {
                                        layout: 'compact'
                                    }
                                },
                                navigation: {
                                    prev: true,
                                    next: true,
                                    first: true,
                                    last: true
                                },
                                pageSizeSelect: [10, 20, 30, 50, 100]
                            },
                            info: true
                        }
                    },
                    translate: {
                        records: {
                            processing: 'Please wait...',
                            noRecords: 'No records found'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    default: {
                                        first: 'First',
                                        prev: 'Previous',
                                        next: 'Next',
                                        last: 'Last',
                                        more: 'More pages',
                                        input: 'Page number',
                                        select: 'Select page size'
                                    },
                                    info: 'Displaying {{start}} - {{end}} from {{total}} records'
                                }
                            }
                        }
                    }
                }
                ,
                        e = $(".m_datatable").mDatatable(t),
                        a = e.getDataSourceQuery();
                $("#m_form_search").on("keyup", function (t) {
                    $("#esearch").val($(this).val().toLowerCase());
                    $("#wsearch").val($(this).val().toLowerCase());
                }
                ).val(a.generalSearch),
                        $("#m_datepicker_1").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                ),
                        $("#m_datepicker_2").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                )
            };
            return {
                init: function () {
                    t()
                }
            }
        }();
        jQuery(document).ready(function () {
            DatatableRemoteAjaxDemo.init()
        });

    </script>
<?php endif; ?>
<?php if ($jenis == 'pegawai'): ?>
    <script type="text/javascript">

        var save_method; //for save method string
        var table;
        var DatatableRemoteAjaxDemo = function () {
            var t = function () {
                var t = {
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/pegawai",
                                params: {
                                    // custom query params
                                    query: {
                                        generalSearch: '<?php if ($src) echo $src; ?>',
                                        tanggalawal: '<?php if ($tanggalawal) echo $tanggalawal; ?>',
                                        tanggalakhir: '<?php if ($tanggalakhir) echo $tanggalakhir; ?>'
                                    }
                                }
                            }
                        }
                        ,
                        saveState: {
                            cookie: !1, webstorage: !1
                        }
                        ,
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true
                    }
                    , layout: {
                        theme: "default", class: "", scroll: !1, footer: !1
                    }
                    , sortable: true,
                    filterable: false,
                    pagination: true,
                    searchDelay: 400,
                    columns: [{
                            field: "number", title: "#", sortable: false, width: 40, selector: !1, textAlign: "center"
                        }
                        , {
                            field: "nip", title: "No. Anggota", filterable: !1, width: 100
                        }
                        , {
                            field: "nama", title: "Nama Lengkap"
                        }
                        , {
                            field: "jenis", title: "Jenis Kelamin", width: 130
                        }
//                        , {
//                            field: "tgl", title: "Tgl.daftar"
//                        }
//                        , {
//                            field: "berlaku", title: "Berlaku Sampai.", width: 120
//                        }
                    ],
                    toolbar: {
                        layout: ['pagination', 'info'],
                        placement: ['bottom'], //'top', 'bottom'
                        items: {
                            pagination: {
                                type: 'default',
                                pages: {
                                    desktop: {
                                        layout: 'default',
                                        pagesNumber: 6
                                    },
                                    tablet: {
                                        layout: 'default',
                                        pagesNumber: 3
                                    },
                                    mobile: {
                                        layout: 'compact'
                                    }
                                },
                                navigation: {
                                    prev: true,
                                    next: true,
                                    first: true,
                                    last: true
                                },
                                pageSizeSelect: [10, 20, 30, 50, 100]
                            },
                            info: true
                        }
                    },
                    translate: {
                        records: {
                            processing: 'Please wait...',
                            noRecords: 'No records found'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    default: {
                                        first: 'First',
                                        prev: 'Previous',
                                        next: 'Next',
                                        last: 'Last',
                                        more: 'More pages',
                                        input: 'Page number',
                                        select: 'Select page size'
                                    },
                                    info: 'Displaying {{start}} - {{end}} from {{total}} records'
                                }
                            }
                        }
                    }
                }
                ,
                        e = $(".m_datatable").mDatatable(t),
                        a = e.getDataSourceQuery();
                $("#m_form_search").on("keyup", function (t) {
                    $("#esearch").val($(this).val().toLowerCase());
                    $("#wsearch").val($(this).val().toLowerCase());
                }
                ).val(a.generalSearch),
                        $("#m_datepicker_1").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                ),
                        $("#m_datepicker_2").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                )
            };
            return {
                init: function () {
                    t()
                }
            }
        }();
        jQuery(document).ready(function () {
            DatatableRemoteAjaxDemo.init()
        });

    </script>
<?php endif; ?>
<?php if ($jenis == 'anggota+luar'): ?>
    <script type="text/javascript">

        var save_method; //for save method string
        var table;
        var DatatableRemoteAjaxDemo = function () {
            var t = function () {
                var t = {
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/anggota+luar",
                                params: {
                                    // custom query params
                                    query: {
                                        generalSearch: '<?php if ($src) echo $src; ?>',
                                        tanggalawal: '<?php if ($tanggalawal) echo $tanggalawal; ?>',
                                        tanggalakhir: '<?php if ($tanggalakhir) echo $tanggalakhir; ?>'
                                    }
                                }
                            }
                        }
                        ,
                        saveState: {
                            cookie: !1, webstorage: !1
                        }
                        ,
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true
                    }
                    , layout: {
                        theme: "default", class: "", scroll: !1, footer: !1
                    }
                    , sortable: true,
                    filterable: false,
                    pagination: true,
                    searchDelay: 400,
                    columns: [{
                            field: "number", title: "#", sortable: false, width: 40, selector: !1, textAlign: "center"
                        }
                        , {
                            field: "noid", title: "NO ID", filterable: !1, width: 140
                        }
                        , {
                            field: "nama", title: "Nama Lengkap", filterable: !1, width: 180
                        }
                        , {
                            field: "jenis", title: "Jen Kelamin", filterable: !1, width: 60
                        }
                        , {
                            field: "alamat", title: "Alamat", filterable: !1, width: 200
                        }
                        , {
                            field: "telepon", title: "Telp", filterable: !1, width: 100
                        }
                        , {
                            field: "instansi", title: "Instansi Asal", filterable: !1, width: 180
                        }
                        , {
                            field: "tgl_post", title: "Tgl Daftar", filterable: !1, width: 80
                        }
                    ],
                    toolbar: {
                        layout: ['pagination', 'info'],
                        placement: ['bottom'], //'top', 'bottom'
                        items: {
                            pagination: {
                                type: 'default',
                                pages: {
                                    desktop: {
                                        layout: 'default',
                                        pagesNumber: 6
                                    },
                                    tablet: {
                                        layout: 'default',
                                        pagesNumber: 3
                                    },
                                    mobile: {
                                        layout: 'compact'
                                    }
                                },
                                navigation: {
                                    prev: true,
                                    next: true,
                                    first: true,
                                    last: true
                                },
                                pageSizeSelect: [10, 20, 30, 50, 100]
                            },
                            info: true
                        }
                    },
                    translate: {
                        records: {
                            processing: 'Please wait...',
                            noRecords: 'No records found'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    default: {
                                        first: 'First',
                                        prev: 'Previous',
                                        next: 'Next',
                                        last: 'Last',
                                        more: 'More pages',
                                        input: 'Page number',
                                        select: 'Select page size'
                                    },
                                    info: 'Displaying {{start}} - {{end}} from {{total}} records'
                                }
                            }
                        }
                    }
                }
                ,
                        e = $(".m_datatable").mDatatable(t),
                        a = e.getDataSourceQuery();
                $("#m_form_search").on("keyup", function (t) {
                    $("#esearch").val($(this).val().toLowerCase());
                    $("#wsearch").val($(this).val().toLowerCase());
                }
                ).val(a.generalSearch),
                        $("#m_datepicker_1").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                ),
                        $("#m_datepicker_2").datepicker({
                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                )
            };
            return {
                init: function () {
                    t()
                }
            }
        }();
        jQuery(document).ready(function () {
            DatatableRemoteAjaxDemo.init()
        });

    </script>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        var selectPilih = document.getElementById('pilih');
        var wrapperProdi = document.getElementById('prodi_wrapper');
        var selectProdi = document.getElementById('m_form_prodi');
        var hiddenWpst = document.getElementById('wpst');
        var hiddenEpst = document.getElementById('epst');

        // Fungsi untuk show/hide
        function toggleProdi() {
            if (selectPilih.value === 'mahasiswa') {
                wrapperProdi.style.display = 'block';   // atau '' (default flex/grid)
            } else {
                wrapperProdi.style.display = 'none';
                // reset nilai prodi supaya tidak ikut terkirim
                if (selectProdi) {
                    selectProdi.value = '';
                }
                // update hidden field export
                if (hiddenWpst) hiddenWpst.value = '';
                if (hiddenEpst) hiddenEpst.value = '';
            }
        }

        // Jalankan pertama kali saat halaman dibuka
        if (selectPilih && wrapperProdi) {
            toggleProdi();
        }

        // Dengarkan perubahan pada select "Pilih"
        if (selectPilih) {
            selectPilih.addEventListener('change', function() {
                toggleProdi();

                // Optional: update hidden field export sesuai nilai prodi terbaru
                if (selectPilih.value === 'mahasiswa' && selectProdi) {
                    var prodiValue = selectProdi.value;
                    if (hiddenWpst) hiddenWpst.value = prodiValue;
                    if (hiddenEpst) hiddenEpst.value = prodiValue;
                }
            });
        }

        // Optional: jika prodi berubah, update hidden field juga
        if (selectProdi) {
            selectProdi.addEventListener('change', function() {
                if (selectPilih.value === 'mahasiswa') {
                    var prodiValue = this.value;
                    if (hiddenWpst) hiddenWpst.value = prodiValue;
                    if (hiddenEpst) hiddenEpst.value = prodiValue;
                }
            });
        }

    });
</script>