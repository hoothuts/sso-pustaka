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
				<?php echo $this->session->flashdata('flash_message') ?>
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
								<div class="col-md-4">
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
                                                        <button id="cetak_barcode" type="button" class="btn btn-accent btn-sm">
                                                            Cetak Barcode
                                                        </button>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                                            Cetak Callnumber
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
				<div class="m_datatable" id="m_datatable">
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

	var DatatableRemoteAjaxDemo = function() {
		var t = function() {
			var x = {
					data: {
						type: "remote",
						source: {
							read: {
								url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/list/"
							}
						},
						saveState: {
							cookie: !1,
							webstorage: !1
						},
						serverPaging: true,
						serverFiltering: true,
						serverSorting: true
					},
					layout: {
						theme: "default",
						class: "",
						scroll: !1,
						footer: !1
					},
					sortable: true,
					filterable: false,
					pagination: true,
					searchDelay: 400,
					columns: [{
							field: "no_barcode",
							title: "",
							sortable: !1,
							width: 10,
							selector: {
								class: "m-checkbox--solid m-checkbox--brand"
							},
						},
						{
							field: "number",
							title: "No",
							sortable: false,
							width: 40,
							textAlign: "center"
						},
						{
							field: "no_inv",
							title: "No. Inventori",
							filterable: !1,
							width: 100
						},
						{
							field: "barcode",
							title: "No. Barcode",
							filterable: !1,
							width: 100
						},
						{
							field: "judul",
							title: "Judul Buku"
						},
						{
							field: "tgl_inv",
							title: "Tanggal Inventori"
						},
						{
							field: "status",
							title: "Status"
						},
						{
							field: "asal",
							title: "Asal"
						},
						{
							field: "action",
							title: "Actions",
							sortable: false,
							width: 110,
							overflow: "visible",
							template: function(x) {
								var id = x.id.split('/').join('_');
								return '\t\t\t\t\t\t<a href="javascript:void(0)" onclick="edit_buku(\'' + x.no_barcode + '\')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t</a>\t\t\t\t\t\t<a href="data_buku/get/' + id + '/' + x.no_klas + '" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">\t\t\t\t\t\t\t<i class="la la-search"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
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
				},
				t = $("#m_datatable").mDatatable(x),
				a = t.getDataSourceQuery();
			$("#m_form_search").on("keyup", function(x) {
					var a = t.getDataSourceQuery();
					a.generalSearch = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
				}).val(a.generalSearch),
				$("#m_form_klas").on("change", function(x) {
					var a = t.getDataSourceQuery();
					a.klas = $(this).val().toLowerCase(), t.setDataSourceQuery(a), t.load()
				}).val(void 0 !== a.klas ? a.klas : ""),
				$("#m_form_klas, #m_form_kel").selectpicker(),
				$('#m_datatable_reload').on('click', function() {
					t.reload()
				}),
				$(".m_datatable").on("m-datatable--on-check", function(a, e) {
					var l = t.setSelectedRecords().getSelectedRecords().length;
					$("#m_datatable_selected_number").html(l), l > 0 && $("#m_datatable_group_action_form").collapse("show")
				}).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(a, e) {
					var l = t.setSelectedRecords().getSelectedRecords().length;
					$("#m_datatable_selected_number").html(l), 0 === l && $("#m_datatable_group_action_form").collapse("hide")
				}),
				$('#cetak_barcode').on('click', function() {
					var final = [];
					var i = 0;
					$('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function() {
						var values = $(this).val();
						final[i++] = values;
					});
					var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>";
					console.log(final);
					var newWin = window.open();
					$.ajax({
						url: url + "/cetak_barcode/",
						type: "POST",
						data: {
							final: final
						},
						dataType: "JSON",
						success: function(data) {
							// console.log(final);
							var doc = '';
							var open = true;
							var kolom = 4;
							var cur = 0;
							doc += "<script type=\"text\/javascript\" src=\"<?php echo base_url(); ?>assets\/JsBarcode.code39.min.js\"><\/script>";
							doc += "<style>#barcodeView{ width:170px !important; height:85px !important; }</style>";
							doc += "<table border=1>";
							for (var i = 0; i < data.length; i++) {
								for (var j = 0; j < data[i].length; j++) {
									if (open) {
										doc += "<tr>";
										open = false;
									}
									doc += "<td align='center'>";
									doc += "<font face=\"courier new\" size=\"1\">No.Inv." + data[i][j].no_inv;
									doc += "<font><br><img id=\"barcodeView\" class=\"barcode\" jsbarcode-format=\"CODE39\" jsbarcode-height=\"90\" jsbarcode-fontSize=\"20\" jsbarcode-textMargin=\"0\" jsbarcode-value=\"" + data[i][j].no_barcode + "\"\/><\/font><\/font>";
									doc += "<\/td>";
									cur = cur + 1;
									if (cur == 4) {
										doc += "<\/tr>";
										open = true;
										cur = 0;
									}
								}
							}
							if (open = false) {
								doc += "<\/tr>";
							}
							doc += "<\/table>";
							doc += "<script>JsBarcode(\".barcode\").init();<\/script>";
							newWin.document.write(doc);
							newWin.document.close();
							newWin.focus();
						},
						error: function(jqXHR, textStatus, errorThrown) {}
					});
				}),
				$('#cetak_callnumber').on('click', function() {
					var final = [];
					var i = 0;
					$('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function() {
						var values = $(this).val();
						final[i++] = values;
					});
					// console.log(final);
					var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>";
					var newWin = window.open();
					$.ajax({
						url: url + "/cetak_callnumber/",
						type: "POST",
						data: {
							final: final
						},
						dataType: "JSON",
						success: function(data) {

							var doc = '';
							for (var i = 0; i < data.length; i++) {
								var n_klas = data[i].no_klas.split(" ");
								n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
								doc += "<div align=\"center\" style=\"margin:1px; padding-top:5px; padding-bottom:5px; border:1px solid #000000; width:45mm; height:28mm; float:left;\"><div style=\"border-bottom:dashed 1px #000000; height:12mm; margin-bottom:4px;\"><div style=\"font-size:9px; font-family:verdana; padding:1px; font-weight:bold;\">P E R P U S T A K A A N</div><div style=\"font-size:9px; font-family:verdana; padding:1px; font-weight:bold;\">POLITEKNIK KESEHATAN RIAU</div><div style=\"font-size:8px; font-family:verdana; padding:1px; font-weight:bold;\">PEKANBARU</div></div><div style=\"text-align:center; width:auto; padding-top:5px;\"><span style=\"font-size:11px; font-weight:bold;\">" + n_klas[0] + "<br>" + n_klas[1] + "<br>" + n_klas[2] + "</span><br><font style=\"font-size:9px; font-weight:bold;\">c." + data[i].no_inv + "</font></div><div style=\"clear:both;\"></div></div>"
							}
							newWin.document.write(doc);
							newWin.document.close();
							newWin.focus();
							newWin.print();
						},
						error: function(jqXHR, textStatus, errorThrown) {}
					});
				});
		};
		return {
			init: function() {
				t()
			}
		}
	}();
	jQuery(document).ready(function() {
		DatatableRemoteAjaxDemo.init()
	});

	function reload_table() {
		$("#m_datatable_reload").trigger("click");
	}

	function view(id, no_klas) {
		var id1 = id.split('/').join('_');
		var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/data_buku/get/" + id1 + "/" + no_klas;
		$.ajax({
			url: url,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
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
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function edit_buku(no_barcode) {
		save_method = 'update';
		$('#form_edit')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		//Ajax Load data from ajax
		$.ajax({
			url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/edit/" + no_barcode,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
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
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function save() {
		$('#btnSave').text('saving...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable
		var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update/";
		// ajax adding data to database
		$.ajax({
			url: url,
			type: "POST",
			data: $('#form_edit').serialize(),
			dataType: "JSON",
			success: function(data) {
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
				} else {
					for (var i = 0; i < data.inputerror.length; i++) {
						$('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
						$('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
					}
				}
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			}
		});
	}
</script>

<div class="modal fade" id="m_Modal" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="labelModal"></h5>
			</div>
			<div class="modal-body">
				<div class="m-portlet__body">
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Judul Buku </div>:
						<div class="col-lg-5">
							<p id="judul"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Tajuk </div>:
						<div class="col-lg-5">
							<b>
								<p id="tajuksubyek"></p>
							</b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">No. Klasifikasi </div>:
						<div class="col-lg-5">
							<p id="no_klas"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Penulis </div>:
						<div class="col-lg-5">
							<p id="penulis"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Edisi </div>:
						<div class="col-lg-5">
							<p id="edisi"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Cetakan </div>:
						<div class="col-lg-5">
							<p id="cetakan"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Penerbit </div>:
						<div class="col-lg-5">
							<b>
								<p id="penerbit"></p>
							</b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Kota Terbit </div>:
						<div class="col-lg-5">
							<p id="kota"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Tahun Terbit </div>:
						<div class="col-lg-5">
							<p id="thn_terbit"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Bahasa </div>:
						<div class="col-lg-5">
							<p id="bahasa"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">ISBN </div>:
						<div class="col-lg-5">
							<b>
								<p id="ISBN"></p>
							</b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Jumlah Halaman </div>:
						<div class="col-lg-5">
							<p id="jml_hal"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Ukuran Fisik </div>:
						<div class="col-lg-5">
							<p id="ukuran_fisik"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Jumlah Stok Buku </div>:
						<div class="col-lg-5">
							<p id="jml_buku"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Jumlah Buku Dipinjam </div>:
						<div class="col-lg-5">
							<b>
								<p id="Tajuk"></p>
							</b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Nomor Rak </div>:
						<div class="col-lg-5">
							<p id="no_rak"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Referensi Matakuliah </div>:
						<div class="col-lg-5">
							<p id="penulis"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Tanggal Input </div>:
						<div class="col-lg-5">
							<p id="tanggal"></p>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Komentar Review </div>:
						<div class="col-lg-5">
							<b>
								<p id="review"></p>
							</b>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<div class="col-lg-3">Deskripsi </div>:
						<div class="col-lg-5">
							<p id="deskripsi"></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!--end::Modal-->


<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="labelModalTambah">
					<i class="m-menu__link-icon flaticon-add"></i> Edit Inventori
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_edit" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
					<table class="table table-borderless table-hover">
						<tbody>
							<tr>
								<th scope="row" style="width:15%">No. Klasifikasi</th>
								<td style="width:3%">:</td>
								<td>
									<p id="no_klas_e"></p>
								</td>
								<input type="hidden" name="no_klas_e">
							</tr>
							<tr>
								<th scope="row" style="width:15%">ISBN</th>
								<td style="width:3%">:</td>
								<td>
									<p id="isbn_e"></p>
								</td>
								<input type="hidden" name="isbn_e">
							</tr>
							<tr>
								<th scope="row" style="width:15%">Judul Buku</th>
								<td style="width:3%">:</td>
								<td>
									<p id="judul_e"></p>
								</td>
								<input type="hidden" name="status_e">
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="no_barcode">
					<input type="hidden" name="tanggal_e">
					<input type="hidden" name="ket_e">
					<div class="form-group m-form__group">
						<label for="">
							No. Inventaris:
						</label>
						<input type="text" id="no_inv" name="no_inv" class="form-control m-input" placeholder="Masukkan Nomor Inventaris">
						<span class="m-form__help">
							Masukkan Nomor Inventaris
						</span>
					</div>
					<div class="form-group m-form__group">
						<label for="">
							Tanggal Inventaris:
						</label>
						<input type="date" id="tgl_inv" name="tgl_inv" class="form-control m-input" placeholder="Masukkan Tanggal Inventaris">
						<span class="m-form__help">
							Masukkan Tanggal Inventaris
						</span>
					</div>
					<div class="form-group m-form__group">
						<label for="">
							Asal:
						</label>
						<!-- <input type="text" id="asal" name="asal" class="form-control m-input" placeholder="Masukkan Asal"> -->
						<select class="form-control" name="asal">
							<?php
							$x = 0;
							foreach ($data['asal_buku'] as $i) {
								$x++; ?>
								<option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
							<?php } ?>
						</select>
						<span class="m-form__help">
							Masukkan Asal
						</span>
					</div>
			</div>
			<div class="modal-footer">
				<input id="btnSave" onclick="save()" type="submit" class="btn btn-primary" value="Simpan">
			</div>
			</form>
		</div>
	</div>
</div>