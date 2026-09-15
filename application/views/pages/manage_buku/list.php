<style>
    .file-input-container {
        margin-bottom: 10px;
    }
    .file-input-container .btn-remove {
        margin-top: 1px;
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
                        <button style="display:none" class="btn btn-default" id="m_datatable_reload">
                            <i class="fa fa-refresh"></i> Reload</button>
                    </small>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Data Induk
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
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
            var page_action = 'list';
        </script>
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
                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success_addbuku_list')) : ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('success_addbuku_list'); ?>
                    </div>
                <?php endif; ?>
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-3">
                                    <select class="form-control m-bootstrap-select" id="m_form_klas">
                                        <option value="">Pilih Klasifikasi</option>
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
                                        <option value="">Pilih Kategori</option>
                                        <?php if ($kategori) { ?>
                                            <?php foreach ($kategori as $row) { ?>
                                                <option value="<?php echo $row->idkategori; ?>"><?php echo $row->nmkategori; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control m-bootstrap-select" id="m_form_thn_terbit">
                                        <option value="">Pilih Tahun Terbit</option>
                                        <?php if ($tahun_terbit) { ?>
                                            <?php foreach ($tahun_terbit as $row) { ?>
                                                <option value="<?php echo $row->thn_terbit; ?>"><?php echo $row->thn_terbit; ?> </option>
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
                            <a href="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/ubah_rak/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="flaticon-add"></i>
                                    <span>
                                        Ubah Rak
                                    </span>
                                </span>
                            </a>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                            <a href="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/tambah/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
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
                <div class="m-demo-icon__preview">
                    <i class="la la-info-circle m--font-danger"></i>
                    <font class="m--font-danger"> Pilih/Centang Buku Untuk Cetak</font>
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
                                        <button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
                                            Cetak Barcode
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                            Cetak Call Number
                                        </button>
                                        <!-- &nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-sm btn-accent" type="button">
                                                Set Buku Diarsipkan
                                        </button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->
                <script type="text/javascript">
                    var page = 'list';
                </script>
                <div class="m_datatable" id="m_datatable">
                    <!-- Here is Data Table Begin -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var DatatableRemoteAjaxDemo=function() {
    var t=function() {
        var x = {
            data: {
                type:"remote",
                source: {
                    read: {
                        url: site
                    }
                }
                ,
                saveState: {
                    cookie: !1, webstorage: !1
                }
                ,
                serverPaging:true,
                serverFiltering:true,
                serverSorting:true
            }
            , layout: {
                theme: "default", class: "", scroll: !1, footer: !1
            }
            , sortable: true,
            filterable: false,
            pagination: true,
            searchDelay: 5500,
		//delay:5500,
            columns:[ {
                field:"id", title:"#", sortable:!1, width:3, selector: {
                    class: "m-checkbox--solid m-checkbox--brand"
                },
            }
            ,	{
                field: "number", title: "No", sortable: false, width: 40, textAlign: "center"
            }
            , {
                field:"no_klas", title:"No. Klas", filterable:!1, width:100
            } 
            , {
                field:"isbn", title:"ISBN", filterable:!1, width:150
            }
            , {
                field: "judul", title: "Judul", width:320
            }
            , {
                field: "penulis", title: "Penulis", width:180
            }
            , {
                field: "penerbit", title: "Penerbit"
            }
             , {
                field: "thn_terbit", title: "Tahun Terbit",width:70
            }
            , {
                field: "jml_buku", title: "Eks", width:50, textAlign: "center"
            }
            , {
                field: "jml_pinjam", title: "Jml Pinjam", width:90, textAlign: "center"
            }
            , {
                field: "jml_prodi", title: "Prodi", width:50, textAlign: "center"
            }
            , {
                field: "action",
                title: "Actions",
                sortable: false,
                width: 100,
                overflow: "visible",
                template: function(x) {
                    const id = x.isbn.split('/').join('_');
                    const isLastRows = (x.getDatatable().getPageSize() - x.getIndex() <= 4);
                    const dropClass = isLastRows ? "dropup" : "";

                    return `
                        <div class="dropdown ${dropClass}">
                            <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">
                                <i class="la la-gear"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="${base_site}/set_inv/${x.buku_id}">
                                    <i class="la la-book"></i> Set Inventori
                                </a>
                                <a class="dropdown-item" href="${base_site}/edit/${x.buku_id}">
                                    <i class="la la-edit"></i> Edit Data
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_buku('${id}', '${x.no_klas}')">
                                    <i class="la la-trash"></i> Hapus Data
                                </a>
                            </div>
                        </div>
                        <a href="${base_site}/get/${id}/${x.no_klas}" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
                            <i class="la la-search"></i>
                        </a>
                    `;
                }
            }
            ],
            toolbar: {
              layout: ['pagination', 'info'],
              placement: ['bottom'],  //'top', 'bottom'
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
        a=t.getDataSourceQuery();
        $("#m_form_search").on("keyup", function(x) {
            var a=t.getDataSourceQuery();
            a.generalSearch=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(a.generalSearch);
        
        $("#m_form_kel").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.kel=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.kel?a.kel:"");
        
        $("#m_form_thn_terbit").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.thn_terbit=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.thn_terbit?a.thn_terbit:"");
        
        $("#m_form_klas").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.klas=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.klas?a.klas:"");
        
        $("#m_form_klas, #m_form_kel, #m_form_thn_terbit").selectpicker();
        $('#m_datatable_reload').on('click', function () {
            t.reload();
        });
        
        $(".m_datatable").on("m-datatable--on-check", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show");
         }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide");
        });
        
        $('#cetak_katalog').on('click',function() {
          var final=[];
          var data=[];
          var i= 0;
          $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
              var values = $(this).val();
              data=values.split(",");
              final[i++] = data;
          });
          var url = base_site+"/cetak_katalog/";
          var newWin = window.open();
          $.ajax({
              url : url,
              type: "POST",
              data: {final:final},
              dataType: "JSON",
              success: function(data)
              {
                var i = 0;
                var doc ='';
                $.each(data,function(key, val){
                  var n_klas= data[i][0].no_klas.split(" ");
                  var penulis = {marga:data[i][0].penulis.split(" "),depan:data[i][0].penulis.split(" ")};
                  penulis.depan.splice(penulis.depan.length-1,1);
                  penulis.depan = penulis.depan.join(" ");
                  n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                  doc+="<div style=\"x-index:9999; border:1px #AAAAAA solid; padding-left:5px; padding-top:5px; padding-bottom:5px; width:125mm; min-height:75mm; margin:2px;\"><table width=\"100%\" height=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" style=\"height:75mm;  font-family:'Times New Roman'; font-size:12pt; font-weight:normal;\"> <tbody><tr align=\"left\">  </tr><tr valign=\"top\">	<td valign=\"top\" width=\"60\">"+n_klas[0]+"<br>"+n_klas[1]+"<br>"+n_klas[2]+"</td>	<td>		<br>"+penulis.marga[penulis.marga.length-1].toUpperCase()+", "+penulis.depan+" <br><div style=\"float:left;\"><font color=\"#FFFFFF\"></font></div><div style=\"float:left; width:90%;\"><font color=\"#FFFFFF\">"+penulis.marga[penulis.marga.length-1].toUpperCase().substring(0, 4)+"</font>"+data[i][0].judul+"  /  "+data[i][0].penulis+".-- "+data[i][0].kota+" : "+data[i][0].nama_penerbit+", "+data[i][0].thn_terbit+".<br><br></div><div style=\"clear:left\"></div><div style=\"float:left;\"><font color=\"#FFFFFF\">"+penulis.marga[penulis.marga.length-1].toUpperCase().substring(0, 4)+"</font></div><div style=\"float:left; width:87%;\">"+data[i][0].jml_hal+" hal.; "+data[i][0].ukuran_fisik+" cm.<br><br>ISBN : "+data[i][0].ISBN+"<br><br><div style=\"font-size:89%; width:100mm\"><div style=\"width:110%; float:left;\"><div style=\"float:left; width:7%\">1.</div><div style=\"float:left; width:80%\">"+data[i][0].tajuksubyek.toUpperCase()+"</div><div style=\"clear:both\"></div><div style=\"float:left; width:7%\">I.</div><div style=\"float:left; width:80%\">Judul</div><div style=\"clear:both\"></div></div></div></div><div style=\"clear:left\"></div>	</td>  </tr></tbody></table></div><br>"
                  i++;
                })
                newWin.document.write("<br><br>"+doc);
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
        
        $('#cetak_callnumber').on('click',function() {
            var final=[];
            var data=[];
            var i= 0;
            $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
                var values = $(this).val();
                data=values.split(",");
                final[i++] = data;
            });
            var url = base_site+"/cetak_callnumber_buku/";
            $.ajax({
                url: url,
                type: "POST",
                data: { final: final },
                dataType: "JSON",
                success: function (data) {
                    var win = window.open('', '_blank');
                    win.document.write(`
                        <html><head><title>Cetak Callnumber</title>
                        </head><body onload="window.print(); setTimeout(window.close, 1000);">
                    `);

                    data.forEach(function (item) {
                        var n_klas = item.no_klas.split(" ");
                        n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                        win.document.write(`
                            <div style="
                                display: inline-block;
                                width: 45mm; 
                                height: 31mm; 
                                margin: 2px; 
                                padding-top: 5px; 
                                padding-bottom: 5px; 
                                border: 1px solid #000; 
                                text-align: center; 
                                font-family: verdana; 
                                box-sizing: border-box; 
                                float: left;
                                -webkit-print-color-adjust: exact; 
                                print-color-adjust: exact;
                            ">
                                <div style="border-bottom: dashed 1px #000; height: 12mm; margin-bottom: 4px;">
                                    <div style="font-size: 9px; font-weight: bold; padding: 1px;">P E R P U S T A K A A N</div>
                                    <div style="font-size: 9px; font-weight: bold; padding: 1px;">POLITEKNIK KESEHATAN RIAU</div>
                                    <div style="font-size: 8px; font-weight: bold; padding: 1px;">PEKANBARU</div>
                                </div>

                                <div style="padding-top: 5px;background-color:${item.kode_warna || ''};">
                                    <span style="font-size: 11px; font-weight: bold; display: block; line-height: 1.2;">
                                        ${n_klas[0]}<br>
                                        ${n_klas[1]}<br>
                                        ${n_klas[2]}
                                    </span>
                                    <span style="font-size: 9px; font-weight: bold;">
                                        c.${item.no_inv || ''}
                                    </span>
                                </div>
                            </div>

                        `);
                    });

                    win.document.write('</body></html>');
                    setTimeout(function() {
                        win.print();
                    }, 200);
                    //win.document.close();
                },
                error: function () {
                    Swal.fire('Error', 'Gagal mengambil data callnumber', 'error');
                }
            });
        });
        
        $('#cetak_barcode').on('click',function() {
            var final=[];
            var data=[];
            var i= 0;

            $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
                var values = $(this).val();
                data = values.split(",");
                final[i++] = data;
            });

            var url = base_site+"/cetak_barcode/";
            var newWin = window.open();

            $.ajax({
                url : url,
                type: "POST",
                data: {final:final},
                dataType: "JSON",
                success: function(data)
                {
                    var doc ='';
                    var open=true;
                    var kolom=4;
                    var cur=0;
                    doc+= "<script src=\"<?php echo base_url(); ?>assets/JsBarcode.code39.min.js\"><\/script>";
                    doc+= `
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

                    doc+="<table border=1>";

                    for (var i = 0; i < data.length; i++) {
                        for (var j = 0; j < data[i].length; j++) {

                            if(open){
                                doc+="<tr>";
                                open=false;
                            }
                            doc+="<td align='center'>";
                            // No Inventaris
                            doc+="<div class='inv-text'>No.Inv."+data[i][j].no_inv+"</div>";
                            // BARCODE TANPA TEXT
                            doc+="<img id=\"barcodeView\" class=\"barcode\" " +
                                  "jsbarcode-format=\"CODE39\" " +
                                  "jsbarcode-height=\"70\" " +
                                  "jsbarcode-fontSize=\"20\" " +
                                  "jsbarcode-textMargin=\"0\" " +
                                  "jsbarcode-displayValue=\"false\" " +
                                  "jsbarcode-value=\""+data[i][j].no_barcode+"\" " +
                                  "jsbarcode-background=\"#FFFFFF\" " +
                                  "jsbarcode-lineColor=\"#000000\" />";
                            // TEXT BARCODE + NAMA RAK (SEJAJAR)
                            doc+="<div class='barcode-row'>";
                            doc+="<div class='barcode-left'>"+data[i][j].no_barcode+"</div>";
                            doc+="<div class='barcode-right'>"+(data[i][j].nama_rak ? data[i][j].nama_rak : '-')+"</div>";
                            doc+="</div>";
                            // COLOR BOX (SVG)
                            doc+="<div class='color-box'>";
                            doc+="<svg width='160' height='10'><rect width='160' height='10' fill='"+data[i][j].kode_warna+"'/></svg>";
                            doc+="</div>";
                            doc+="</td>";
                            cur++;
                            if(cur==kolom){
                                doc+="</tr>";
                                open=true;
                                cur=0;
                            }
                        }
                    }

                    if(open === false){
                        doc+="</tr>";
                    }

                    doc+="</table>";
                    doc += "<script>JsBarcode('.barcode').init();<\/script>";

                    newWin.document.write(doc);
                    setTimeout(function() {
                        newWin.print();
                    }, 200);
                }
            });
        });
        
        
    };
    return {init:function() {t()}}
}();

jQuery(document).ready(function() {
   DatatableRemoteAjaxDemo.init();
});
</script>
<!-- end:: Body -->
<script type="text/javascript">
    var save_method; //for save method string
    var table;
    var barcode = "<?php echo base_url(); ?>assets";
    var barcode2 = "<?php echo base_url(); ?>dir/";
    var base_site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>";
    var site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/list/";

    function delete_buku(id, no_klas) {
        let id1 = id.split('/').join('_');
        let url = "<?= base_url(); ?>dir/<?= $page_name; ?>/hapus/" + id1 + "/" + no_klas;

        Swal.fire({
            title: 'Yakin hapus data?',
            text: "Data yang sudah dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",

                    success: function (res) {

                        if (res.status === true) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.msg,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            reload_table();

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.msg
                            });

                        }
                    },

                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal terhubung ke server'
                        });
                    }
                });

            }
        });
    }

    function reload_table() {
        $("#m_datatable_reload").trigger("click");
    }
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>
