<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN: Subheader -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title m-subheader__title--separator">
					<?php echo $page_title;?>
				</h3>
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
						<a href="<?php echo base_url();?>admin/<?php echo $this->session->userdata('default');?>" class="m-nav__link m-nav__link--icon">
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
								<?php echo $page_title;?>
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- END: Subheader -->
	<div class="m-content">
		<?php if($this->session->flashdata('alert')!=''):?>
						<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert')?> alert-dismissible fade show">
									<div class="m-alert__icon">
										<i class="flaticon-exclamation-1"></i>
										<span></span>
									</div>
									<div class="m-alert__text">
										<?php echo $this->session->flashdata('flash_message') ?>
									</div>
								</div>
					<?php endif;?>
		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							<?php echo $page_title;?>
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
								<div class="col-md-4">
									<select class="form-control m-bootstrap-select" id="m_form_nama">
										<option value=""></option>
										<option value="nis">Mahasiswa</option>
										<option value="nip">Dosen</option>
										<option value="noid">Anggota Luar</option>
									</select>
									<div class="d-md-none m--margin-bottom-10"></div>
								</div>
								<div class="col-md-4">
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
										<button id="cetak_barcode" type="button" class="btn btn-accent btn-sm">
											Cetak Barcode
										</button>
										&nbsp;&nbsp;&nbsp;
										<button id="cetak_kartu" class="btn btn-sm btn-accent" type="button">
											Cetak Kartu
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end: Selected Rows Group Action Form -->
				<div class="m_datatable" id="record_selection">
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
 var DatatableRemoteAjaxDemo=function() {
    var t=function() {
			var t= $(".m_datatable").mDatatable({
					responsive: {
							details: {
									display: $.fn.dataTable.Responsive.display.modal( {
											header: function () {
													// var data = row.data();
													return 'Details for ';
											}
									} )
							}
					},
			    data: {
			        type:"remote",
							source: {
			            read: {
			                url: "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/fetch/"
			            }
			        }
			        ,
			        pageSize:20,
			        saveState: {
			            cookie: !0, webstorage: !0
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
					searchDelay: 400,
						columns:[ {
			        field:"nis1", class:"tes", title:"#", sortable:!1, width:3, selector: {
			            class: "m-checkbox--solid m-checkbox--brand"
			        },
			    }
			    ,	{
			        field: "number", title: "No", sortable: false, width: 40, textAlign: "center"
			    }
			    , {
			        field:"nis", title:"NIS", filterable:!1, width:100
			    }
			    , {
			        field: "nama", title: "Nama"
			    }
					, {
							field: "status", title: "Status", width:130
					}
					, {
							field: "berlaku_sampai", title: "Berlaku Sampai"
					}
					, {
			        field:"action", title:"Actions", sortable: false, width:110, overflow:"visible", template:function(t) {
			            return'\t\t\t\t\t\t<a href="javascript:void(0)" onclick="view(\''+t.nis+'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-search"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
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
			}
			),
      a=t.getDataSourceQuery();
      $("#m_form_search").on("keyup", function(t) {
          var a=t.getDataSourceQuery();
          a.generalSearch=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
      }).val(a.generalSearch),
      $("#m_form_nama").on("change", function(t) {
        var a=t.getDataSourceQuery();
        a.nama=$(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
      }).val(void 0!==a.nama?a.nama:""),
      $("#m_form_nama").selectpicker(),
			$(".m_datatable").on("m-datatable--on-check", function(a, e) {
				var l=t.setSelectedRecords().getSelectedRecords().length;
				$("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show")
			}).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(a, e) {
				var l=t.setSelectedRecords().getSelectedRecords().length;
				$("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide")
			}),
			$('#cetak_barcode').on('click',function() {
				var final=[];
				var i= 0;
				$('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
						var values = $(this).val();
						final[i++] = values;
				});
				var newWin = window.open();
							var doc ='';
							doc+="<script type=\"text/javascript\" src=\"<?php echo base_url();?>assets/JsBarcode.code39.min.js\"><\/script>";
							for (var i = 0; i < final.length; i++) {
									doc+="<div style=\"margin:1px; margin-top:7px; margin-right:5px; padding-left:7px; padding-right:2px; border-bottom:1px solid #000000; border-top:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000; float:left;\"><font face=\"courier new\" size=\"1\">No.Inv."+final[i]+"<font><br><img class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"90\" jsbarcode-width=\"1\" jsbarcode-fontSize=\"10\" jsbarcode-textMargin=\"0\" jsbarcode-value=\""+final[i]+"\"/></font></font></div>"
							}
							doc+="<script>JsBarcode(\".barcode\").init();<\/script>";
							newWin.document.write(doc);
							newWin.document.close();
							newWin.focus();
				}),
			$('#cetak_kartu').on('click',function() {
				var final=[];
				var i= 0;
				$('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
						var values = $(this).val();
						final[i++] = values;
				});
				var url ="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/cetak_kartu/";
				var gambar1 = "<?php echo base_url('uploads/data_anggota/')."Logo2.jpg"; ?>";
				var gambar2 = "<?php echo base_url('uploads/data_anggota/')."Logo1.png"; ?>";
				var cover = "<?php echo base_url('uploads/data_anggota/')."Depan.jpg"; ?>";
				var back = "<?php echo base_url('uploads/data_anggota/')."Belakang.jpg"; ?>";
				var newWin = window.open();
				$.ajax({
						url : url,
						type: "POST",
						data: {final:final},
						dataType: "JSON",
						success: function(data)
						{
							// console.log(data);return;
							var i = 0;
							var doc ='';
							doc +="<style> body{margin: 0px}table,td,body,div{font-family: arial;font-size: 11px}td{padding-top: 4px}hr{border: 0px;border-top: 1px solid #000000;border-bottom: 1px solid #343434}.draft{border-left: 1px solid #000000;border-top: 1px solid #000000;width: 80mm;height: 88mm}.draft .head,.draft .isi{border-right: 1px solid #000000;border-bottom: 1px solid #000000}#belakang{background-image: url('"+back+"');background-size:cover;background-position: center top}#depan{background-image: url('"+cover+"');background-size:cover;background-position: center top}@media print{*{-webkit-print-color-adjust: exact !important;color-adjust: exact !important}}</style>";
							doc+="<script type=\"text/javascript\" src=\"<?php echo base_url();?>assets/JsBarcode.code39.min.js\"><\/script>";
							$.each(data,function(key, val){
								doc+="<div style=\"margin:1px; float:left;\"><div id=\"depan\" style=\"border:1px solid #000000; border-right:none; float:left; width:100mm; height:70mm;\"><div style=\"float:left; padding:5px; padding-left:2mm; padding-bottom:1px;\"><img src=\""+gambar1+"\" border=\"0\" style=\" width:40px; height:40px;\"></div><div style=\"float:left; padding-top:4px; width:65mm; \" align=\"center\"><div style=\"font-size:9px;\">POLITEKNIK KESEHATAN RIAU</div><div style=\"font-size:9px;\">PERPUSTAKAAN</div><div style=\"font-size:8px;\">Jl. Melur No. 103 Pekanbaru Telp.(0761) 36581, Fax. 20656, E-mail: pkr.riau@gmail.com</div></div><div style=\"float:left; padding:5px; padding-left:2mm; padding-bottom:1px;\"><img src=\""+gambar2+"\" border=\"0\" style=\" width:65px; height:40px;\"></div><div style=\"clear:both\"></div><hr><div style=\"font-size:11px; font-family:arial; font-weight:bold; padding-bottom:7px;\" align=\"center\">KARTU ANGGOTA PERPUSTAKAAN</div><div style=\"float:left; margin-left:7px; height:32mm; width:25mm; border:1px solid #000000;\"><div align=\"center\" style=\"font-size:12px; padding-top:13mm;\">foto<br>2x3</div></div><div style=\"float:left; padding:4px; padding-right:3px; padding-top:0px; padding-bottom:0px; width:65mm;\"><div style=\"padding-left:14px;\"><table cellpadding=\"1\" cellspacing=\"1\" border=\"0\"><tbody><tr valign=\"top\"><td>Nama</td><td>:</td><td>"+data[i][0].nama+"</td></tr><tr valign=\"top\"><td>NIM</td><td>:</td><td>"+data[i][0].nis+"</td></tr><tr valign=\"top\"><td>Prodi</td><td>:</td><td>"+data[i][0].kelas+"</td></tr><tr valign=\"top\"><td nowrap=\"\">Berlaku Sd</td><td>:</td><td>Selama Aktif</td></tr></tbody></table><img class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"33\" jsbarcode-width=\"1\" jsbarcode-fontSize=\"10\" jsbarcode-textMargin=\"0\" jsbarcode-value=\""+data[i][0].nis+"\"/></div></div><div style=\"clear:both\"></div></div><div id=\"belakang\" style=\"float:left; border:1px solid #000000; border-left:1px dotted #000000;  width:100mm; height:70mm;\"><div style=\"font-size:11px; padding:7px 12px 0px 12px; font-family:arial;\" align=\"center\"><br><br><b>ATURAN PEMINJAMAN</b></div><div style=\"padding:7px 12px;\"><table cellpadding=\"2\" cellspacing=\"1\" border=\"0\"><tbody><tr valign=\"top\"><td style=\"font-size:10px;\">1.</td><td style=\"font-size:10px;\">Kartu ini harus dibawa pada waktu berkunjung ke Perpustakaan POLITEKNIK KESEHATAN RIAU.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">2.</td><td style=\"font-size:10px;\">Kartu ini tidak boleh dipergunakan orang lain.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">3.</td><td style=\"font-size:10px;\">Peminjam dilayani setiap jam kerja.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">4.</td><td style=\"font-size:10px;\">Hanya diijinkan meminjam max. 3 buku.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">5.</td><td style=\"font-size:10px;\">Lama peminjaman max. 7 hari.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">6.</td><td style=\"font-size:10px;\">Pengembalian buku hendaknya tepat pada waktunya, keterlambatan pengembalian dikenakan denda.</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">7.</td><td style=\"font-size:10px;\">Pengembalian buku masih dalam keadaan bersih serta utuh</td></tr><tr valign=\"top\"><td style=\"font-size:10px;\">8.</td><td style=\"font-size:10px;\">Kerusakan yang diakibatkan peminjam menjadi tanggung jawab peminjam</td></tr></tbody></table></div></div><div style=\"clear:left\"></div></div>"
								i++;
							})
							doc+="<script>JsBarcode(\".barcode\").init();<\/script>";
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
    };
    return {
        init:function() {
            t()
        }
    }
}();
jQuery(document).ready(function() {
    DatatableRemoteAjaxDemo.init()
});
function reload_table()
{
	$( "#m_datatable_reload" ).trigger( "click" );
    //table.ajax.reload(); //reload datatable ajax
}
function view(id)
{
	var url = "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/get/" + id;
	$.ajax({
			url : url,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('#nis').text(data[0].nis);
				$('#nama').text(data[0].nama);
				if (data[0].jk=='P') {
					data[0].jk="Perempuan";
				}else {
					data[0].jk="Laki-Laki";
				}
				$('#jk').text(data[0].jk);
				$('#ttl').text(data[0].tempatlahir);
				$('#m_Modal').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Lihat Data Anggota'); // Set title to Bootstrap modal title
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
					alert('Error get data from ajax');
			}
	});
}


</script>
<!--begin::Modal-->
<div class="modal fade" id="m_Modal" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="labelModal"></h5>
			</div>
			<div class="modal-body">
				<div class="m-portlet__body">
					<div class="form-group m-form__group row">
						<div class="col-lg-3">ID </div>:
						<div class="col-lg-5">
							<p id="nis">ID</p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Nama </div>:
						<div class="col-lg-5">
							<b><p id="nama">NAMA</p></b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Jenis Kelamin </div>:
						<div class="col-lg-5">
							<p id="jk">Jenis Kelamin</p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Tempat/Tgl Lahir </div>:
						<div class="col-lg-5">
							<p id="ttl">Tempat/Tgl Lahir</p>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
</div>
						<!--end::Modal-->
