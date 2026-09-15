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
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Data Induk
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
            <!-- <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
                    <div class="m-alert__icon">
                            <i class="flaticon-exclamation-1"></i>
                            <span></span>
                    </div>
                    <div class="m-alert__text">
        <?= $this->session->flashdata('flash_message') ?>
                    </div>
            </div> -->
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?php echo $page_title; ?>
                            <small>
                                <i class="glyphicon glyphicon-refresh"></i>
                                <button style="display:none" class="btn btn-default" id="m_datatable_reload">
                                    <i class="fa fa-refresh"></i> Reload</button>
                            </small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-3">
                                    <select class="form-control m-bootstrap-select" id="m_form_klas">
                                        <option value="">Klasifikasi</option>
                                        <?php if ($klasifikasi) { ?>
                                            <?php foreach ($klasifikasi as $row) { ?>
                                                <option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control m-bootstrap-select" id="m_form_kel">
                                        <option value="">Kategori</option>
                                        <?php if ($kategori) { ?>
                                            <?php foreach ($kategori as $row) { ?>
                                                <option value="<?php echo $row->idkategori; ?>"><?php echo $row->nmkategori; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control m-bootstrap-select" id="m_form_prodi">
                                        <option value="">Prodi</option>
                                        <?php if ($prodi) { ?>
                                            <?php foreach ($prodi as $row) { ?>
                                                <option value="<?php echo $row->idmspst; ?>"><?php echo $row->nmmspst; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Search..." id="m_form_search">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                            <a href="<?php echo base_url(); ?><?php echo $page_access; ?>/data_buku/tambah/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="flaticon-add"></i>
                                    <span>
                                        Tambah
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <!--begin: Selected Rows Group Action Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label m-form__label-no-wrap">
                                    <label class="m--font-bold m--font-danger-">
                                        Selected
                                        <span id="m_datatable_selected_number"></span>
                                        records:
                                    </label>
                                </div>
                                <div class="m-form__control">
                                    <div class="btn-toolbar">
                                        <button id="cetak_katalog" type="button" class="btn btn-accent btn-sm">
                                            Cetak Katalog
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                            Cetak Call Number
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
                                            Cetak Barcode
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->
                <div class="m_datatable" id="m_datatable">
                    <!-- Here is Data Table Begin -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->
<script type="text/javascript">
    var base_site = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>";
        var barcode = "<?php echo base_url(); ?>assets";

        var save_method; //for save method string
        var table;
        var DatatableRemoteAjaxDemo = function ()
        {
            var t = function () {
                var x = {
                    data: {
                        type: "remote",
                        source: {
                            read: {
                                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/list/"
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
                            field: "id", title: "#", sortable: !1, width: 3, selector: {
                                class: "m-checkbox--solid m-checkbox--brand"
                            },
                        }
                        , {
                            field: "number", title: "No", sortable: false, width: 40, textAlign: "center"
                        }
                        , {
                            field: "no_klas", title: "No. Klasifikasi", filterable: !1, width: 100
                        }
                        , {
                            field: "judul", title: "Judul Buku"
                        }
                        , {
                            field: "penerbit", title: "Penerbit"
                        }
                        , {
                            field: "prodi", title: "Jurusan(Matakuliah)", width: 250
                        }
                        , {
                            field: "jml_buku", title: "Jml Buku"
                        }
                        , {
                            field: "action", title: "Actions", sortable: false, width: 110, overflow: "visible", template: function (x) {
                                var id = x.id[1].split('/').join('_');
                                return'\t\t\t\t\t\t<a href="data_buku/edit/' + id + '/' + x.no_klas + '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t</a>\t\t\t\t\t\t<a href="data_buku/get/' + id + '/' + x.no_klas + '" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-search"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
                            }
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
                };
                t = $("#m_datatable").mDatatable(x);
                a = t.getDataSourceQuery();
                $("#m_form_search").on("keyup", function (x) {
                    var a = t.getDataSourceQuery();
                    a.generalSearch = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
                }).val(a.generalSearch);
                $("#m_form_kel").on("change", function (x) {
                    var a = t.getDataSourceQuery();
                    a.kel = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
                }).val(void 0 !== a.kel ? a.kel : "");
                $("#m_form_klas").on("change", function (x) {
                    var a = t.getDataSourceQuery();
                    a.klas = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
                }).val(void 0 !== a.klas ? a.klas : "");
                $("#m_form_prodi").on("change", function (x) {
                    var a = t.getDataSourceQuery();
                    a.prodi = $(this).val(), t.setDataSourceQuery(a), t.load()
                }).val(void 0 !== a.prodi ? a.prodi : "");
                $("#m_form_klas, #m_form_kel, #m_form_prodi").selectpicker(),
                        $('#m_datatable_reload').on('click', function () {
                    t.reload();
                });
                $(".m_datatable").on("m-datatable--on-check", function (a, e) {
                    var l = t.setSelectedRecords().getSelectedRecords().length;
                    $("#m_datatable_selected_number").html(l), l > 0 && $("#m_datatable_group_action_form").collapse("show");
                }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function (a, e) {
                    var l = t.setSelectedRecords().getSelectedRecords().length;
                    $("#m_datatable_selected_number").html(l), 0 === l && $("#m_datatable_group_action_form").collapse("hide");
                });
                
                $('#cetak_katalog').on('click', function () {
                    var final = [];
                    var data = [];
                    var i = 0;
                    $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function () {
                        var values = $(this).val();
                        data = values.split(",");
                        final[i++] = data;
                    });
                    var url = base_site + "/cetak_katalog/";
                    var newWin = window.open();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {final: final},
                        dataType: "JSON",
                        success: function (data)
                        {
                            var i = 0;
                            var doc = '';
                            $.each(data, function (key, val) {
                                var n_klas = data[i][0].no_klas.split(" ");
                                var penulis = {marga: data[i][0].penulis.split(" "), depan: data[i][0].penulis.split(" ")};
                                penulis.depan.splice(penulis.depan.length - 1, 1);
                                penulis.depan = penulis.depan.join(" ");
                                n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                                doc += "<div style=\"x-index:9999; border:1px #AAAAAA solid; padding-left:5px; padding-top:5px; padding-bottom:5px; width:125mm; min-height:75mm; margin:2px;\"><table width=\"100%\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" style=\"height:75mm;  font-family:'Times New Roman'; font-size:12pt; font-weight:normal;\"> <tbody><tr align=\"left\">  </tr><tr valign=\"top\">	<td valign=\"top\" width=\"60\">" + n_klas[0] + "<br>" + n_klas[1] + "<br>" + n_klas[2] + "</td>	<td>		<br>" + penulis.marga[penulis.marga.length - 1].toUpperCase() + ", " + penulis.depan + " <br><div style=\"float:left;\"><font color=\"#FFFFFF\"></font></div><div style=\"float:left; width:90%;\"><font color=\"#FFFFFF\">" + penulis.marga[penulis.marga.length - 1].toUpperCase().substring(0, 4) + "</font>" + data[i][0].judul + "  /  " + data[i][0].penulis + ".-- " + data[i][0].kota + " : " + data[i][0].nama_penerbit + ", " + data[i][0].thn_terbit + ".<br><br></div><div style=\"clear:left\"></div><div style=\"float:left;\"><font color=\"#FFFFFF\">" + penulis.marga[penulis.marga.length - 1].toUpperCase().substring(0, 4) + "</font></div><div style=\"float:left; width:87%;\">" + data[i][0].jml_hal + " hal.; " + data[i][0].ukuran_fisik + " cm.<br><br>ISBN : " + data[i][0].ISBN + "<br><br><div style=\"font-size:89%; width:100mm\"><div style=\"width:110%; float:left;\"><div style=\"float:left; width:7%\">1.</div><div style=\"float:left; width:80%\">" + data[i][0].tajuksubyek.toUpperCase() + "</div><div style=\"clear:both\"></div><div style=\"float:left; width:7%\">I.</div><div style=\"float:left; width:80%\">Judul</div><div style=\"clear:both\"></div></div></div></div><div style=\"clear:left\"></div>	</td>  </tr></tbody></table></div><br>"
                                i++;
                            })
                            newWin.document.write("<br><br>" + doc);
                            newWin.document.close();
                            newWin.focus();
                            newWin.print();
                            // newWin.close();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                        }
                    });
                });

                $('#cetak_callnumber').on('click', function () {
                    var final = [];
                    var data = [];
                    var i = 0;
                    $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function () {
                        var values = $(this).val();
                        data = values.split(",");
                        final[i++] = data[0];
                    });
                    var url = base_site + "/cetak_callnumber/";
                    var newWin = window.open();
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {final: final},
                        dataType: "JSON",
                        success: function (data)
                        {
                            var doc = '<div style=\"padding: 10px\";></div>';
                            for (var i = 0; i < data.length; i++) {
                                for (var j = 1; j <= data[i]; j++) {
                                    var n_klas = final[i].split(" ");
                                    n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                                    doc += "<div align=\"center\" style=\"margin:1px; padding-top:5px; padding-bottom:5px; border:1px solid #000000; width:45mm; height:28mm; float:left;\"><div style=\"border-bottom:dashed 1px #000000; height:12mm; margin-bottom:4px;\"><div style=\"font-size:9px; font-family:verdana; padding:1px; font-weight:bold;\">P E R P U S T A K A A N</div><div style=\"font-size:9px; font-family:verdana; padding:1px; font-weight:bold;\">POLITEKNIK KESEHATAN RIAU</div><div style=\"font-size:8px; font-family:verdana; padding:1px; font-weight:bold;\">PEKANBARU</div></div><div style=\"text-align:center; width:auto; padding-top:5px;\"><span style=\"font-size:11px; font-weight:bold;\">" + n_klas[0] + "<br>" + n_klas[1] + "<br>" + n_klas[2] + "</span><br><font style=\"font-size:9px; font-weight:bold;\">c." + j + "</font></div><div style=\"clear:both;\"></div></div>"
                                }
                                // doc +="<br>";
                            }
                            newWin.document.write(doc);
                            newWin.document.close();
                            newWin.focus();
                            newWin.print();
                            // newWin.close();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                        }
                    });
                });

                $('#cetak_barcode').on('click', function () {
                    var final = [];
                    var data = [];
                    var i = 0;

                    $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function () {
                        var values = $(this).val();
                        data = values.split(",");
                        final[i++] = data;
                    });

                    var url = base_site + "/cetak_barcode/";
                    var newWin = window.open();

                    $.ajax({
                        url: url,
                        type: "POST",
                        data: { final: final },
                        dataType: "JSON",
                        success: function (data) {

                            var doc = '';
                            var open = true;
                            var kolom = 4;
                            var cur = 0;

                            doc += "<script src=\"" + barcode + "/JsBarcode.code39.min.js\"><\/script>";

                            doc += `
                                <style>
                                    #barcodeView {
                                        width:170px !important;
                                        height:85px !important;
                                    }

                                    .inv-text {
                                        font-family: 'Courier New';
                                        font-size: 11px;
                                        margin-bottom: 0px;
                                    }

                                    .barcode-row {
                                        width:160px;
                                        margin:2px auto 0;
                                        display:flex;
                                        justify-content:space-between;
                                        font-family:'Courier New';
                                        font-size:11px;
                                    }

                                    .barcode-left {
                                        text-align:left;
                                    }

                                    .barcode-right {
                                        text-align:right;
                                    }

                                    .color-box {
                                        width:160px;
                                        height:10px;
                                        margin:2px auto 0;
                                    }

                                    @media print {
                                        * {
                                            -webkit-print-color-adjust: exact;
                                            print-color-adjust: exact;
                                        }
                                    }
                                </style>
                            `;

                            doc += "<table border='1'>";

                            for (var i = 0; i < data.length; i++) {
                                for (var j = 0; j < data[i].length; j++) {

                                    if (open) {
                                        doc += "<tr>";
                                        open = false;
                                    }

                                    doc += "<td align='center'>";

                                    // No Inv
                                    doc += "<div class='inv-text'>No.Inv." + data[i][j].no_inv + "</div>";

                                    // BARCODE TANPA TEXT
                                    doc += "<img id=\"barcodeView\" class=\"barcode\" " +
                                            "jsbarcode-format=\"CODE39\" " +
                                            "jsbarcode-height=\"70\" " +
                                            "jsbarcode-fontSize=\"20\" " +
                                            "jsbarcode-textMargin=\"0\" " +
                                            "jsbarcode-displayValue=\"false\" " +
                                            "jsbarcode-value=\"" + data[i][j].no_barcode + "\" " +
                                            "jsbarcode-background=\"#FFFFFF\" " +
                                            "jsbarcode-lineColor=\"#000000\" />";

                                    // TEXT BARCODE + NAMA RAK
                                    doc += "<div class='barcode-row'>";
                                    doc += "<div class='barcode-left'>" + data[i][j].no_barcode + "</div>";
                                    doc += "<div class='barcode-right'>" +
                                            (data[i][j].nama_rak && data[i][j].nama_rak.trim() !== '' 
                                                ? data[i][j].nama_rak 
                                                : '-') +
                                           "</div>";
                                    doc += "</div>";

                                    // COLOR BOX (SVG)
                                    doc += "<div class='color-box'>";
                                    doc += "<svg width='160' height='10'>";
                                    doc += "<rect width='160' height='10' fill='" + data[i][j].kode_warna + "'/>";
                                    doc += "</svg>";
                                    doc += "</div>";

                                    doc += "</td>";

                                    cur++;

                                    if (cur == kolom) {
                                        doc += "</tr>";
                                        open = true;
                                        cur = 0;
                                    }
                                }
                            }

                            if (!open) {
                                doc += "</tr>";
                            }

                            doc += "</table>";
                            doc += "<script>JsBarcode('.barcode').init();<\/script>";

                            newWin.document.write(doc);
                            newWin.document.close();
                            newWin.focus();

                            setTimeout(function () {
                                newWin.print();
                            }, 300);
                        }
                    });
                });
                
            };
            return {init: function () {
                    t()
                }}
        }();
        jQuery(document).ready(function () {
            DatatableRemoteAjaxDemo.init()
        });
        function reload_table()
        {
            $("#m_datatable_reload").trigger("click");
        }
        function view(id, no_klas)
        {
            var id1 = id.split('/').join('_');
            var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/data_buku/get/" + id1 + "/" + no_klas;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function (data)
                {
                    // alert("here")
                    $('#ISBN').text(data[0].ISBN);
                    $('#judul').text(data[0].judul);
                    $('#jml_buku').text(data[0].jml_buku);
                    $('#penulis').text(data[0].penulis);
                    $('#tajuksubyek').text(data[0].tajuksubyek);
                    $('#no_klas').text(data[0].no_klas);
                    $('#edisi').text(data[0].edisi);
                    $('#cetakan').text(data[0].cetakan);
                    $('#penerbit').text(data[0].nama_penerbit);
                    $('#kota').text(data[0].kota);
                    $('#thn_terbit').text(data[0].thn_terbit);
                    if (data[0].bahasa == 'I') {
                        data[0].bahasa = "Bahasa Indonesia"
                    } else if (data[0].bahasa == 'A') {
                        data[0].bahasa = "Bahasa Inggris"
                    } else if (data[0].bahasa = "S") {
                        data[0].bahasa = "Bahasa Sunda"
                    } else {
                        data[0].bahasa = "Bahasa Lainnya"
                    }
                    $('#bahasa').text(data[0].bahasa);
                    $('#jml_hal').text(data[0].jml_hal);
                    $('#ukuran_fisik').text(data[0].ukuran_fisik);
                    $('#dipinjam').text(data[0].dipinjam);
                    $('#no_rak').text(data[0].no_rak);
                    $('#deskripsi').text(data[0].deskripsi);
                    $('#tanggal').text(data[0].tanggal);
                    if (data[0].review == 0) {
                        data[0].review = "-"
                    }
                    $('#review').text(data[0].review);
                    $('#matakuliah').text(data[0].matakuliah);
                    $('#m_Modal').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Lihat Data Anggota'); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
        function edit_buku(no_barcode)
        {
            save_method = 'update';
            $('#form_edit')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/edit/" + no_barcode,
                type: "GET",
                dataType: "JSON",
                success: function (data)
                {
                    $('#no_klas_e').text(data[0].no_klas);
                    $('#isbn_e').text(data[0].ISBN);
                    $('#judul_e').text(data[0].judul);

                    $('[name="tanggal_e"]').val(data[0].tanggal);
                    $('[name="ket_e"]').val(data[0].ket);
                    $('[name="no_klas_e"]').val(data[0].no_klas);
                    $('[name="isbn_e"]').val(data[0].ISBN);
                    $('[name="status_e"]').val(data[0].status);
                    $('[name="no_inv"]').val(data[0].no_inv);
                    $('[name="tgl_inv"]').val(data[0].tgl_inv);
                    $('[name="asal"]').val(data[0].asal);
                    $('[name="no_barcode"]').val(data[0].no_barcode);
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit Buku'); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
        function save()
        {
            $('#btnSave').text('saving...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update/";
            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_edit').serialize(),
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        if (data.status = 'TRUE') {
                            toastr.options = {
                                "positionClass": "toast-top-right",
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
                            toastr.success("Data Berhasil Diubah");
                            //if success reload ajax table
                            $('#modal_form').modal('hide');
                            reload_table();
                        }
                    } else
                    {
                        for (var i = 0; i < data.inputerror.length; i++)
                        {
                            $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }
                    }
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }
</script>
