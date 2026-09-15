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
                        <a href="<?php echo base_url(); ?>dir/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
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
        
        <form target="_blank" id="exportweb" action="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/index/export/web" method="post"></form>
        <form target="_blank" id="exportexcel" action="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/index/export/excel" method="post"></form>
        
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
                                                            <a href="#"  onclick="exportData('web');return false;" class="m-nav__link" target="_blank">
                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                <span class="m-nav__link-text">
                                                                    Web
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="#" onclick="exportData('excel');return false;" class="m-nav__link" >
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
                    <form action="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>" method="post">
                        <div class="row align-items-center">
                            <div class="col-xl-12 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-1">
                                        <div class="m-form__group m-form__group--inline">
                                            <div class="m-form__label">
                                                <label class="m-label m-label--single">
                                                    Tanggal :
                                                </label>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="m-form__control">
                                            <div class='input-group date' id='m_datepicker_1'>
                                                <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly  placeholder="Tgl Awal"/>
                                                <span class="input-group-addon">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label class="m-label m-label--single">
                                            S/D
                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="m-form__control">
                                            <div class='input-group date' id='m_datepicker_2'>
                                                <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly  placeholder="Tgl Akhir"/>
                                                <span class="input-group-addon">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="klasifikasi" id="klasifikasi">
                                            <option value='-'>Pilih Klasifikasi</option>
                                            <?php if ($klasifikasi): ?>
                                                <?php foreach ($klasifikasi as $kl): ?>
                                                    <option value="<?php echo $kl->id; ?>" <?php if ($kl->id == $klas) echo 'selected'; ?>><?php echo $kl->nama; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" name="kategori" id="kategori">
                                            <option value='-'>Pilih Kelompok Buku</option>
                                            <?php if ($kategori): ?>
                                                <?php foreach ($kategori as $kt): ?>
                                                    <option value="<?php echo $kt->idkategori; ?>" <?php if ($kt->idkategori == $ktg) echo 'selected'; ?>><?php echo $kt->nmkategori; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                   
                                    <div class="col-md-2">
                                        <select name="asal" class="form-control" id="asal">
                                            <option value="">Pilih Asal Buku</option>
                                            <?php foreach ($asal_buku as $asal): ?>
                                                <option value="<?= htmlspecialchars($asal->id) ?>"  <?= ($asal_bk == $asal->id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($asal->nama) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 m-form__group-sub">
                                        <select class="form-control m-select2" id="m_select2_3" name="prodi[]" multiple="multiple">
                                            <?php 
                                            $prodi_bk = $prodi_bk ?? [];
                                            foreach ($prodi as $i): ?>
                                                <option value="<?= htmlspecialchars($i->idmspst) ?>"
                                                    <?= (is_array($prodi_bk) && in_array($i->idmspst, $prodi_bk)) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($i->nmmspst) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
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
                            <div class="col-xl-12 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    
                                    <div class="col-md-2">
                                        <select class="form-control" name="thn_terbit" id="thn_terbit">
                                            <option value='-'>Pilih  Tahun Terbit</option>
                                            <?php if ($tahun_terbit): ?>
                                                <?php foreach ($tahun_terbit as $tht): ?>
                                                    <option value="<?php echo $tht->thn_terbit; ?>" <?php if ($tht->thn_terbit == $thn_terbit) echo 'selected'; ?>><?php echo $tht->thn_terbit; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control " id="m_form_kampus" name="m_form_kampus">
                                            <option value="">Pilih Kampus</option>
                                            <?php
                                            if ($kampus) {
                                                foreach ($kampus as $k) {
                                                    if(isset($m_form_kampus)){
                                                        ?>
                                                    <option value="<?= $k->lokasikampus_id ?>" <?php if ($k->lokasikampus_id == $m_form_kampus) echo 'selected'; ?>><?= $k->nama_kampus ?></option>
                                                <?php
                                                    }else{
                                                        ?>
                                                    <option value="<?= $k->lokasikampus_id ?>" ><?= $k->nama_kampus ?></option>
                                                <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control " id="m_form_gedung"  name="m_form_gedung"  <?php if (!isset($list_gedung)) { echo "disabled"; }?>>
                                            <option value="">Pilih Gedung</option>
                                            <?php
                                            $m_form_gedung = $m_form_gedung ?? null;
                                            if ($list_gedung) {
                                                foreach ($list_gedung as $g) {
                                                    ?>
                                                    <option value="<?= $g->lokasigedung_id ?>" <?php if ($g->lokasigedung_id == $m_form_gedung) echo 'selected'; ?>><?= $g->nama_gedung ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" id="m_form_rak" name="m_form_rak"  <?php if (!isset($list_rak)) { echo "disabled"; }?>>
                                            <option value="">Pilih Rak</option>
                                            <?php
                                            $m_form_rak = $m_form_rak ?? null;
                                            if (isset($list_rak)) {
                                                foreach ($list_rak as $r) {
                                                    ?>
                                                    <option value="<?= $r->lokasirak_id ?>" <?php if ($r->lokasirak_id == $m_form_rak) echo 'selected'; ?>><?= $r->nama_rak ?></option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
                                    <div class="col-xl-2 m--align-right order-1 order-xl-2">
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
<!--                        <div class="row align-items-center m--margin-top-10 m--margin-bottom-30">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">								
                                    <div class="col-md-4">
                                        <div class="m-input-icon m-input-icon--left">
                                            <input type="text" class="form-control m-input" name="src" placeholder="<?php //if ($src) echo $src; else echo 'Search....' ?>" id="m_form_search">
                                            <span class="m-input-icon__icon m-input-icon__icon--left">
                                                <span>
                                                    <i class="la la-search"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
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
                            url: "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/index/fetch",
                            params: {
                                // custom query params
                                query: {
                                    generalSearch: $('#generalSearch').val(),
                                    klasifikasi: $('#klasifikasi').val(),
                                    kategori: $('#kategori').val(),
                                    tanggalawal: $('#tanggalawal').val(),
                                    tanggalakhir: $('#tanggalakhir').val(),
                                    thn_terbit: $('#thn_terbit').val(),
                                    m_form_kampus: $('#m_form_kampus').val(),
                                    m_form_gedung: $('#m_form_gedung').val(),
                                    m_form_rak: $('#m_form_rak').val(),
                                    asal: $('#asal').val(),
                                    prodi: $('#m_select2_3').val()
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
                        field: "cover", title: "Cover", width: 90
                    }
                    , {
                        field: "noklas", title: "No. Klas", width: 120
                    }
                    , {
                        field: "ISBN", title: "ISBN"
                    }
                    , {
                        field: "judul", title: "Judul Buku", width: 300
                    }
                    , {
                        field: "penulis", title: "Penulis", filterable: !1, width: 100
                    }
//                    , {
//                        field: "edisi", title: "Edisi"
//                    }
                    , {
                        field: "penerbit", title: "Penerbit"
                    }
                    , {
                        field: "tahun", title: "Tahun Terbit",width: 70
                    }
                    , {
                        field: "jumlah", title: "Eks",width: 70
                    }
                    , {
                        field: "hr", title: "Hil/Rsk",width: 60
                    }
                    , {
                        field: "tanggal", title: "Tgl. Inv", width: 90
                    }
                    , {
                        field: "nama_kampus", title: "Kampus",sortable: false
                    }
                    , {
                        field: "nama_gedung", title: "Gedung",sortable: false
                    }
                    , {
                        field: "nama_rak", title: "Rak",sortable: false
                    }
                    , {
                        field: "asal_buku", title: "Asal Buku"
                    }
                    , {
                        field: "prodi", title: "Program Studi",sortable: false,width: 350
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
            }),
            $("#m_datepicker_2").datepicker({
                format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                    leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                }
            }),
            
            // Load gedung saat pilih kampus
            $('#m_form_kampus').on('change', function() {
                var kampus_id = $(this).val();
                $('#m_form_gedung').prop('disabled', true).val('').trigger('change');
                $('#m_form_rak').prop('disabled', true).val('').trigger('change');

                if (kampus_id) {
                    $.ajax({
                        url: '<?= base_url('dir/manage_lokasi/get_gedung_options') ?>',
                        type: 'POST',
                        data: { kampus_id: kampus_id },
                        dataType: 'json',
                        success: function(res) {
                            $('#m_form_gedung').empty().append('<option value="">Semua Gedung</option>');
                            $.each(res, function(i, item) {
                                $('#m_form_gedung').append('<option value="' + item.lokasigedung_id + '">' + item.nama_gedung + '</option>');
                            });
                            // Aktifkan select dan refresh selectpicker
                            $('#m_form_gedung').prop('disabled', false).selectpicker('refresh').trigger('change');
                        }
                    });
                } else {
                   $('#m_form_gedung').prop('disabled', true).selectpicker('refresh').val('').trigger('change');
                }
            }),

            // Load rak saat pilih gedung
            $('#m_form_gedung').on('change', function() {
                var gedung_id = $(this).val();
                $('#m_form_rak').prop('disabled', true).val('').trigger('change');

                if (gedung_id) {
                    $.ajax({
                        url: '<?= base_url('dir/manage_lokasi/get_rak_options') ?>',
                        type: 'POST',
                        data: { gedung_id: gedung_id },
                        dataType: 'json',
                        success: function(res) {
                            $('#m_form_rak').empty().append('<option value="">Semua Rak</option>');
                            $.each(res, function(i, item) {
                                $('#m_form_rak').append('<option value="' + item.lokasirak_id + '">' + item.nama_rak + '</option>');
                            });
                            // Aktifkan select dan refresh selectpicker
                            $('#m_form_rak').prop('disabled', false).selectpicker('refresh').trigger('change');
                        }
                    });
                } else {
                    $('#m_form_rak').prop('disabled', true).selectpicker('refresh').val('').trigger('change');
                }
            }),
            
            $('#m_select2_3').select2({
                placeholder: "Pilih Prodi",
                width: '100%'
            })
            
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
    
    function exportData(type){

        var datatable = $('#ajax_data').mDatatable();
        var query = datatable.getDataSourceQuery();
        var sort  = datatable.getDataSourceParam('sort');

        var form = (type === 'excel') ? $('#exportexcel') : $('#exportweb');

        form.html('');

        // kirim filter datatable
        $.each(query, function(key,value){

            if(Array.isArray(value)){

                value.forEach(function(v,i){
                    form.append(
                        '<input type="hidden" name="datatable[query]['+key+']['+i+']" value="'+v+'">'
                    );
                });

            }else{

                form.append(
                    '<input type="hidden" name="datatable[query]['+key+']" value="'+value+'">'
                );

            }

        });

        // kirim sorting
        if(sort){
            form.append('<input type="hidden" name="datatable[sort][field]" value="'+sort.field+'">');
            form.append('<input type="hidden" name="datatable[sort][sort]" value="'+sort.sort+'">');
        }

        form.submit();
    }

</script>