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
		<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
			<div class="m-alert__icon">
				<i class="flaticon-exclamation-1"></i>
				<span></span>
			</div>
			<div class="m-alert__text">
				<?php echo $this->session->flashdata('flash_message') ?>
			</div>
		</div>
		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							<?php echo $page_title; ?>
						</h3>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<?php if (in_array($page_name, $this->session->userdata('perm_add'))) : ?>
						<button type="button" class="btn btn-info" onclick="window.location.href='<?php echo base_url('admin/manage_bebas_pustaka/add_page'); ?>'">
							<i class="la la-plus"></i>
							Tambah Bebas Pustaka
						</button>
						<div class="m-separator m-separator--dashed d-xl-none"></div>
					<?php endif; ?>
				</div>
			</div>
			<div class="m-portlet__body">
				<!--begin: Search Form -->
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-8 order-2 order-xl-1">
							<div class="form-group m-form__group row align-items-center">
								<!-- <div class="col-md-4">
									<div class="m-form__group m-form__group--inline">
										<div class="m-form__label">
											<label class="m-label m-label--single">
												Nama:<?php //$prodi= json_decode($output);
														//echo $prodi->nmmspst; 
														?>
											</label>
										</div>
										<div class="m-form__control">
											<select class="form-control m-bootstrap-select" id="m_form_nama">
												<option value="jurnal">
													Jurnal
												</option>
												<option value="kaset">
													Kaset
												</option>
											</select>
										</div>
									</div>
									<div class="d-md-none m--margin-bottom-10"></div>
								</div> -->
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
				<div class="m_datatable" id="ajax_data">
					<!-- Here is Data Table Begin -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end:: Body -->
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
							Anggota<font class="m--font-danger">*</font>
						</label>
						<div class="col-md-6">
							<select class="form-control pegawai" name="anggota_id">
								<option></option>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
	var save_method; //for save method string
	var table;
	var DatatableRemoteAjaxDemo = function() {
		var t = function() {
			var t = {
					data: {
						type: "remote",
						source: {
							read: {
								url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/"
							}
						},
						pageSize: 20,
						saveState: {
							cookie: !0,
							webstorage: !0
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
							field: "number",
							title: "#",
							sortable: false,
							width: 40,
							selector: !1,
							textAlign: "center"
						}, {
							field: "no_surat_bebaspustaka",
							title: "No Surat Bebas Pustaka",
							filterable: !1,
							width: 150
						},
						{
							field: "no_surat_hibahbuku",
							title: "No Surat Hibah Buku",
							filterable: !1,
							width: 150
						},
						{
							field: "nama",
							title: "Nama"
						}, {
							field: "tgl_bebas_pustaka",
							title: "Tgl Bebas Pustaka",
							width: 130
						}, {
							field: "author",
							title: "Author"
						}, {
							field: "action",
							title: "Actions",
							sortable: false,
							width: 110,
							overflow: "visible",
							template: function(t) {
								return `
								<div class="dropdown ${t.getDatatable().getPageSize() - t.getIndex() <= 4 ? "dropup" : ""}">
									<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">
										<i class="la la-gear"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="<?php echo base_url(); ?>admin/manage_bebas_pustaka/cetak/${t.id}">
											<i class="la la-file-word-o"></i> Cetak Surat
										</a>
										<a class="dropdown-item" href="<?php echo base_url(); ?>admin/manage_bebas_pustaka/edit_page/${t.id}">
											<i class="la la-edit"></i> Edit Data
										</a>
										<a class="dropdown-item" href="javascript:hapus('${t.id}')" title="Delete">
											<i class="la la-trash"></i> Hapus Data
										</a>
									</div>
								</div>
								`;
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
				e = $(".m_datatable").mDatatable(t),
				a = e.getDataSourceQuery();
			$("#m_form_search").on("keyup", function(t) {
					var a = e.getDataSourceQuery();
					a.generalSearch = $(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
				}).val(a.generalSearch),
				// $("#m_form_nama").on("change", function(t) {
				//     var a=e.getDataSourceQuery();
				//     a.nama=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
				// }).val(void 0!==a.nama?a.nama:""),
				$("#m_form_status, #m_form_nama").selectpicker()
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
	// Function to get base URL

	// Additional function for delete confirmation
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
					url: "<?php echo base_url() . 'admin/manage_bebas_pustaka/delete' ?>/" + id,
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