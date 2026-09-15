// alert(idisbn)
var DatatableRemoteAjaxDemo=function() {
  if (page_action=='list') {
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
                field: "jml_buku", title: "Jumlah Buku", width:70, textAlign: "center"
            }
            , {
                field: "jml_pinjam", title: "Dipinjam", width:70, textAlign: "center"
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
                                <a class="dropdown-item" href="${base_site}/set_inv/${id}/${x.no_klas}">
                                    <i class="la la-book"></i> Set Inventori
                                </a>
                                <a class="dropdown-item" href="${base_site}/edit/${id}/${x.no_klas}">
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
        },
        t = $("#m_datatable").mDatatable(x),
        a=t.getDataSourceQuery();
        $("#m_form_search").on("keyup", function(x) {
            var a=t.getDataSourceQuery();
            a.generalSearch=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(a.generalSearch),
        $("#m_form_kel").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.kel=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.kel?a.kel:""),
        $("#m_form_thn_terbit").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.thn_terbit=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.thn_terbit?a.thn_terbit:""),
        $("#m_form_klas").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.klas=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.klas?a.klas:""),
        $("#m_form_klas, #m_form_kel, #m_form_thn_terbit").selectpicker(),
        $('#m_datatable_reload').on('click', function () {
          t.reload()
        }),
        $(".m_datatable").on("m-datatable--on-check", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show")
         }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide")
        }),
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
        }),
        $('#cetak_callnumber').on('click',function() {
            var final=[];
            var data=[];
            var i= 0;
            $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
                var values = $(this).val();
                data=values.split(",");
                final[i++] = data;
            });
            var url = base_site+"/cetak_callnumber/";
            var newWin = window.open();
            $.ajax({
                url: url,
                type: "POST",
                data: { final: final },
                dataType: "JSON",
                success: function(data) {
                    // Gunakan Template Literals (backtick) agar lebih rapi
                    let doc = '<div style="padding: 10px;"></div>';

                    if (data && data.length > 0) {
                        $.each(data, function(i, item) {
                            // Asumsi data[i] atau 'item' berisi jumlah perulangan/copy
                            // Dan n_klas diambil dari array 'final' (pastikan sinkron)
                            let n_klas = final[i] ? final[i].split(" ") : ['', '', ''];
                            let k0 = n_klas[0] || '';
                            let k1 = n_klas[1] || '';
                            let k2 = n_klas[2] || '';

                            for (let j = 1; j <= item; j++) {
                                doc += `
                                    <div align="center" style="margin:1px; padding:5px 0; border:1px solid #000; width:45mm; height:28mm; float:left;">
                                        <div style="border-bottom:dashed 1px #000; height:12mm; margin-bottom:4px;">
                                            <div style="font-size:9px; font-family:Verdana; font-weight:bold;">P E R P U S T A K A A N</div>
                                            <div style="font-size:9px; font-family:Verdana; font-weight:bold;">POLITEKNIK KESEHATAN RIAU</div>
                                            <div style="font-size:8px; font-family:Verdana; font-weight:bold;">PEKANBARU</div>
                                        </div>
                                        <div style="text-align:center; padding-top: 5px;background-color:${item.kode_warna || ''};">
                                            <span style="font-size:11px; font-weight:bold;">
                                                ${k0}<br>${k1}<br>${k2}
                                            </span><br>
                                            <font style="font-size:9px; font-weight:bold;">c.${j}</font>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>`;
                            }
                        });
                    }

                    // Proses Cetak
                    newWin.document.write(doc);
                    newWin.document.close();
                    newWin.focus();

                    setTimeout(function() {
                        newWin.print();
                        // newWin.close(); // Aktifkan jika ingin menutup jendela otomatis setelah print
                    }, 250);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Gagal memproses data: ", textStatus);
                }
            });
        });
        $('#cetak_barcode').on('click',function() {
         var final=[];
         var data=[];
         var i= 0;
         $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
             var values = $(this).val();
             data=values.split(",");
             final[i++] = data;
         });
         var url = base_site+"/cetak_barcode/";
         // alert("oke")
         var newWin = window.open();
         $.ajax({
             url : url,
             type: "POST",
             data: {final:final},
             dataType: "JSON",
             success: function(data)
             {
               // console.log(data)
               var doc ='';
               var open=true;
               var kolom=4;
               var cur=0;
               doc+="<script type=\"text/javascript\" src=\""+barcode+"/JsBarcode.code39.min.js\"></script>";
               doc+="<style>#barcodeView{ width:170px !important; height:85px !important; }</style>";
               doc+="<table border=1>";
               for (var i = 0; i < data.length; i++) {
                 for (var j = 0; j < data[i].length; j++) {
                   if(open){
                     doc+="<tr>";
                     open=false;
                   }
                   doc+="<td align='center'>";
                   doc+="<font face=\"courier new\" size=\"1\">No.Inv."+data[i][j].no_inv;
                   doc+="<font><br><img id=\"barcodeView\" class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"90\" jsbarcode-fontSize=\"20\" jsbarcode-textMargin=\"0\" jsbarcode-value=\""+data[i][j].no_barcode+"\" jsbarcode-background=\"#FFFFFF\" jsbarcode-lineColor=\"" + data[i][j].kode_warna + "\" \/></font></font>";
                   doc+="</td>";
                   cur=cur+1;
                   if(cur==4){
                     doc+="</tr>";
                     open=true;
                     cur=0;
                   }
                 }
               }
               if(open=false){
                 doc+="</tr>";
               }
               doc+="</table>";
               doc+="<script>JsBarcode(\".barcode\").init();</script>";
               newWin.document.write(doc);
                setTimeout(function() {
                    newWin.print();
                }, 200);
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
             }
         });
        });
    };
    return {init:function() {t()}}
  }
  if (page_action=='set_inv') {
   var z=function() {
     var i= {
         data: {
             type:"remote",
             source: {
                 read: {
                     url: get_inv
                 }
             },
             saveState: {
                 cookie: !1, webstorage: !1
             },
             serverPaging:true, serverFiltering:true,serverSorting:true
         }
         , layout: {
             theme: "default", class: "", scroll: !0, height: 550, footer: !1
         }
         , sortable: true,
         filterable: false,
         pagination: true,
         searchDelay: 5500,

         columns:[{
             field:"no_barcode2", title:"", sortable:!1, width:10,
             selector: {
               class: "m-checkbox--solid m-checkbox--brand"
             }
         },
         {
             field:"no_inv", title:"No. Inventori", filterable:!1, width:100
         }
         , {
             field: "isbn", title: "ISBN", width:200
         }
         , {
             field: "tgl_inv", title: "Tgl Inventori", width:130
         }
         , {
             field: "asal", title: "Asal"
         }
         , {
             field: "no_barcode", title: "No. Barcode", width:70, textAlign: "center"
         }
         , {
             field:"action", title:"Actions", sortable: false, width:100, overflow:"visible", template:function(i) {
                 return '\t\t\t\t\t\t<a href="javascript:void(0)" onclick="edit_inv(\''+i.no_barcode+'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t</a>\t\t\t\t\t\t<a href="javascript:void(0)" onclick="delete_inv(\''+i.no_barcode+'\',\''+i.no_klas+'\',\''+i.isbn+'\')"  class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus">\t\t\t\t\t\t\t<i class="la la-trash"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
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
     },
     z = $("#set_inv").mDatatable(i),
     b = z.getDataSourceQuery();
     $('#m_datatable_reload').on('click', function () {
       z.reload();
     }),
     $(".m_datatable").on("m-datatable--on-check", function(b, e) {
        var l=z.setSelectedRecords().getSelectedRecords().length;
        $("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show")
      }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(b, e) {
        var l=z.setSelectedRecords().getSelectedRecords().length;
        $("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide")
     }),
     $('#cetak_barcode').on('click',function() {
        var final=[];
        var i= 0;
        $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
            var values = $(this).val();
            final[i++] = values;
        });
        var url = barcode2+"manage_inventaris/cetak_barcode/";
        console.log(final);
        var newWin = window.open();
        $.ajax({
           url : url+"/cetak_barcode/",
           type: "POST",
           data: {final:final},
           dataType: "JSON",
           success: function(data)
           {
             // console.log(data)
             var doc ='';
             var open=true;
             var kolom=4;
             var cur=0;
             doc+="<script type=\"text/javascript\" src=\""+barcode+"/JsBarcode.code39.min.js\"></script>";
             doc+="<style>#barcodeView{ width:170px !important; height:85px !important; }</style>";
             doc+="<table border=1>";
             for (var i = 0; i < data.length; i++) {
                // Tidak perlu inner loop (j), langsung pakai data[i]
                if (open) {
                    doc += "<tr>";
                    open = false;
                }

                doc += "<td align='center'>";
                doc += "<font face=\"courier new\" size=\"1\">No.Inv." + data[i].no_inv;
                doc += "<font><br><img id=\"barcodeView\" class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"90\" jsbarcode-fontSize=\"20\" jsbarcode-textMargin=\"0\" jsbarcode-value=\"" + data[i].no_barcode + "\" jsbarcode-background=\"#FFFFFF\" jsbarcode-lineColor=\"" + data[i].kode_warna + "\" \/><\/font><\/font>";
                doc += "<\/td>";

                cur = cur + 1;
                if (cur == kolom) {
                    doc += "<\/tr>";
                    open = true;
                    cur = 0;
                }
             }
             if(open=false){
               doc+="</tr>";
             }
             doc+="</table>";
             doc+="<script>JsBarcode(\".barcode\").init();</script>";
             newWin.document.write(doc);
           },
           error: function (jqXHR, textStatus, errorThrown)
           {
           }
        });
     }),
     $('#cetak_callnumber').on('click',function() {
       var final=[];
       var i= 0;
       $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
           var values = $(this).val();
           final[i++] = values;
       });
       // console.log(final);
       var url = barcode2+"manage_inventaris/";
       var newWin = window.open();
       $.ajax({
          url : url+"/cetak_callnumber/",
          type: "POST",
          data: {final:final},
          dataType: "JSON",
          success: function(data)
          {
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
            win.print();
            //win.document.close();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
          }
       });
     });
   };
   return {init:function() {z()}}
  }
  if (page_action=='ubah_rak') {
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
                field:"no_klas", title:"No. Klas", filterable:!1, width:100, template:function(x) {
                  return'<input type="hidden" name="no_klas[]" value="'+x.no_klas+'"><span style="width: 100px;">'+x.no_klas+'</span>'
                }
            }
            , {
                field: "judul", title: "Judul", width:200
            }
            , {
                field: "penulis", title: "Penulis", width:130, sortable: false
            }
            , {
                field: "no_rak", title: "Rak Awal", textAlign: "center"
            }
            , {
                field: "", title: "Rak Baru", sortable: false, template:function(x) {
                  var id = x.isbn.split('/').join('_');
                    return'\t\t\t\t\t\t<div class="dropdown '+(x.getDatatable().getPageSize()-x.getIndex()<=4?"dropup": "")+'">\t\t\t\t\t\t\t <input type="text" class="form-control m-input" name="no_rak[]" value=""> \t\t\t\t\t'
                }
            }
            , {
                field:"action", title:"Actions", sortable: false, width:100, overflow:"visible", template:function(x) {
                  var id = x.isbn.split('/').join('_');
                    return'\t\t\t\t\t\t<div class="dropdown '+(x.getDatatable().getPageSize()-x.getIndex()<=4?"dropup": "")+'">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                           </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" onclick="edit_inv_page(\''+id+'\',\''+x.no_klas+'\')"><i class="la la-edit"></i> Edit Data</a>\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_buku(\''+id+'\',\''+x.no_klas+'\')"><i class="la la-trash"></i> Hapus Data</a>\t\t\t\t\t\t  \t</div>\t\t\t\t\t\t</div>\t\t\t\t\t\t<a href="'+base_site+'/get/'+id+'/'+x.no_klas+'" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-search"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
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
        },
        t = $("#table_ubah_rak").mDatatable(x),
        a=t.getDataSourceQuery();
        $("#m_form_search").on("keyup", function(x) {
            var a=t.getDataSourceQuery();
            a.generalSearch=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(a.generalSearch),
        $("#m_form_kel").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.kel=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.kel?a.kel:""),
        $("#m_form_klas").on("change", function(x) {
            var a=t.getDataSourceQuery();
            a.klas=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
        }).val(void 0!==a.klas?a.klas:""),
        $("#m_form_klas, #m_form_kel").selectpicker(),
        $('#m_datatable_reload').on('click', function () {
          t.reload()
        }),
        $(".table_ubah_rak").on("m-datatable--on-check", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show")
         }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(a, e) {
           var l=t.setSelectedRecords().getSelectedRecords().length;
           $("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide")
        }),
        $('#buttin_id').on('click', function(){
          var rak = [];
          var no_klas = [];
          var data = [];
          var i= 0;
          $('.m-datatable__row input[name^="no_rak"]').each(function() {
            var values = $(this).val();
            rak.push(values);

          });
          $('.m-datatable__row input[name^="no_klas"]').each(function() {
            var values = $(this).val();
            no_klas.push(values);
          });
          for (var j = 0; j < rak.length; j++) {
            data[j]=[no_klas[j],rak[j]]
          }
          // alert(data)
          // return;
          $.ajax({
              url : simpan_ubah_rak,
              type: "POST",
              data: {data:data},
              dataType: "JSON",
              success: function(data)
              {
                if(data.status == true){ // if true (1)
                    swal({
                      title : 'Success',
                      text: 'Rak Buku Berhasil Diedit',
                      type: 'success',
                      showConfirmButton: false,
                      timer: 1500
                    });
                    reload_table();
                 }
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
              }
          });

        }),
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
        }),
        $('#cetak_callnumber').on('click',function() {
         var final=[];
         var data=[];
         var i= 0;
         $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
             var values = $(this).val();
             data=values.split(",");
             final[i++] = data[0];
         });
         var url = base_site+"/cetak_callnumber/";
         var newWin = window.open();
         $.ajax({
             url : url,
             type: "POST",
             data: {final:final},
             dataType: "JSON",
             success: function(data)
             {
               var doc ='<div style=\"padding: 10px\";></div>';
               for (var i = 0; i < data.length; i++) {
                 for (var j = 1; j <= data[i]; j++) {
                   var n_klas= final[i].split(" ");
                   n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                   doc+="<div align=\"center\" style=\"margin:1px; padding-top:5px; padding-bottom:5px; border:1px solid #000000; width:57mm; height:35mm; float:left;\"><div style=\"border-bottom:dashed 1px #000000; height:15mm; margin-bottom:7px;\"><div style=\"font-size:11px; font-family:verdana; padding:1px; font-weight:bold;\">P E R P U S T A K A A N</div><div style=\"font-size:10px; font-family:verdana; padding:1px; font-weight:bold;\">POLITEKNIK KESEHATAN RIAU</div><div style=\"font-size:9px; font-family:verdana; padding:1px; font-weight:bold;\">PEKANBARU</div></div><div style=\"text-align:center; width:auto; padding-top:7px;\"><span style=\"font-size:11px; font-weight:bold;\">"+n_klas[0]+"<br>"+n_klas[1]+"<br>"+n_klas[2]+"</span><br><font style=\"font-size:9px; font-weight:bold;\">c."+j+"</font></div><div style=\"clear:both;\"></div></div>"
                 }
                 // doc +="<br>";
               }
               newWin.document.write(doc);
               newWin.document.close();
               newWin.focus();
               // newWin.print();
               // newWin.close();
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
             }
         });
        });
        $('#cetak_barcode').on('click',function() {
         var final=[];
         var data=[];
         var i= 0;
         $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
             var values = $(this).val();
             data=values.split(",");
             final[i++] = data;
         });
         var url = base_site+"/cetak_barcode/";
         // alert("oke")
         var newWin = window.open();
         $.ajax({
             url : url,
             type: "POST",
             data: {final:final},
             dataType: "JSON",
             success: function(data)
             {
               // console.log(data)
               var doc ='';
               var open=true;
               var kolom=4;
               var cur=0;
               doc+="<script type=\"text/javascript\" src=\""+barcode+"/JsBarcode.code39.min.js\"></script>";
               doc+="<style>#barcodeView{ width:170px !important; height:85px !important; }</style>";
               doc+="<table border=1>";
               for (var i = 0; i < data.length; i++) {
                 for (var j = 0; j < data[i].length; j++) {
                   if(open){
                     doc+="<tr>";
                     open=false;
                   }
                   doc+="<td align='center'>";
                   doc+="<font face=\"courier new\" size=\"1\">No.Inv."+data[i][j].no_inv;
                   doc+="<font><br><img id=\"barcodeView\" class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"90\" jsbarcode-fontSize=\"20\" jsbarcode-textMargin=\"0\" jsbarcode-value=\""+data[i][j].no_barcode+"\"/></font></font>";
                   doc+="</td>";
                   cur=cur+1;
                   if(cur==4){
                     doc+="</tr>";
                     open=true;
                     cur=0;
                   }
                 }
               }
               if(open=false){
                 doc+="</tr>";
               }
               doc+="</table>";
               doc+="<script>JsBarcode(\".barcode\").init();</script>";
               newWin.document.write(doc);
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
             }
         });
        });
    };
    return {init:function() {t()}}
  }

}();
jQuery(document).ready(function() {
   DatatableRemoteAjaxDemo.init();
});
