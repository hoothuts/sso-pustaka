<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN: Subheader -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title m-subheader__title--separator">
					Halaman
				</h3>
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
						<a href="#" class="m-nav__link m-nav__link--icon">
							<i class="m-nav__link-icon flaticon-layers"></i>
						</a>
					</li>
					<li class="m-nav__separator">
						-
					</li>
					<li class="m-nav__item">
						<a href="" class="m-nav__link">
							<span class="m-nav__link-text">
								Halaman
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
			<?php if ($this->session->flashdata('alert') != '') : ?>
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
							Manajemen Halaman
						</h3>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					<a href="<?= base_url('admin/manage_halaman/add'); ?>" class="btn btn-info">
						<i class="la la-plus"></i>
						Tambah Halaman
					</a>
				</div>
			</div>
			<!-- Begin halaman data-->
			<div class="m-portlet__body">
				<!--begin: Search Form -->
				<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
					<div class="row align-items-center">
						<div class="col-xl-8 order-1 order-xl-1 m--align-left">
							<div class="form-group m-form__group row align-items-center">
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
								<div class="col-md-4">
									<div class="m-form__group m-form__group--inline">
										<div class="m-form__label">
											<label class="m-label m-label--single">
												Jenis:
											</label>
										</div>
										<div class="m-form__control">
											<select class="form-control m-bootstrap-select" id="m_form_jenis">
												<option value=""></option>
												<option value="statis">Statis</option>
												<option value="dinamis">Dinamis</option>
											</select>
										</div>
									</div>
									<div class="d-md-none m--margin-bottom-10"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end: Search Form -->
				<div class="m_datatable" id="ajax_data"></div>
			</div>
			<!-- End halaman data -->
		</div>
	</div>
</div>
<script type="text/javascript">
	var DatatableRemoteAjaxDemo = function() {
		var t = function() {
			var t = {
					data: {
						type: "remote",
						source: {
							read: {
								url: "<?php echo base_url(); ?>admin/manage_halaman/list/"
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
						field: "judul_halaman",
						title: "Judul Halaman",
						sortable: !1,
						selector: !1,
						textAlign: "left"
					}, {
						field: "link_halaman",
						title: "Link",
						width: 400,
						filterable: !1
					}, {
						field: "jenis",
						title: "Jenis",
						width: 80,
					}, {
						field: "tgl_post",
						title: "Update Terakhir",
						filterable: !1,
						width: 100,
					}, {
						field: "action",
						width: 60,
						title: "Actions",
						sortable: !1,
						overflow: "visible",
						template: function(t) {
							if (t.jenis.toLowerCase() == `statis`) {
								return ``;
							}

							return `
								<div class="dropdown dropup">
									<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown"> <i class="la la-gear"></i> </a>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="<?= base_url('admin/manage_halaman/edit/'); ?>${t.id}">
											<i class="la la-edit"></i> Edit Data
										</a>
										<a class="dropdown-item" href="<?= base_url('admin/manage_halaman/delete/'); ?>${t.id}">
											<i class="la la-remove"></i> Hapus Data
										</a>
									</div>
								</div>
							`;
						}
					}],
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
				$("#m_form_jenis").on("change", function(t) {
					var a = e.getDataSourceQuery();
					a.jenis = $(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
				}).val(void 0 !== a.jenis ? a.jenis : ""),
				$("#m_form_jenis").selectpicker()
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