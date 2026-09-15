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
								Administrator
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
									<div class="m-form__group m-form__group--inline">
										<div class="m-form__label">
											<label class="m-label m-label--single">
												Group:
											</label>
										</div>
										<div class="m-form__control">
											<?php if ($group) { ?>
												<select class="form-control m-bootstrap-select" name="selgroup" id="m_form_group" onchange="get_modul()" class="form-control m-input">
													<?php foreach ($group as $row) { ?>

														<option value="<?php echo $row->idsysgroup; ?>" <?php if ($row->idsysgroup == 'A') echo 'selected'; ?>><?php echo $row->name; ?> </option>
													<?php } ?>
												</select>
											<?php } ?>
											</select>
										</div>
									</div>
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
							<?php if (in_array($page_name, $this->session->userdata('perm_add'))) : ?>
								<a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="tambah_akses()">
									<span>
										<i class="flaticon-add"></i>
										<span>
											Tambah
										</span>
									</span>
								</a>
								<div class="m-separator m-separator--dashed d-xl-none"></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="m_datatable" id="ajax_data"></div>
				<!--begin: Datatable -->

				<!--end: Datatable -->
			</div>
		</div>
	</div>
</div>

<!-- end:: Body -->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<i class="m-menu__link-icon flaticon-add"></i>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_edit" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
					<input type="hidden" name="eid1" id="eid1">
					<input type="hidden" name="egroup" id="egroup">
					<div class="form-group m-form__group">
						<label for="">
							Nama Modul
						</label>
						<?php if ($modul) { ?>
							<select name="emodul" id="emodul" class="form-control m-input">
								<?php foreach ($modulall as $row) { ?>
									<option value="<?php echo $row->idsysmodul; ?>"><?php echo $row->name; ?> </option>
								<?php } ?>
							</select>
						<?php } ?>
					</div>
					<div class="m-checkbox-inline">
						<label class="m-checkbox">
							<input type="checkbox" id="eadd" name="eadd" value="1">
							Add
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="eedit" name="eedit" value="1">
							Edit
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="edelete" name="edelete" value="1">
							Delete
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="eview" name="eview" value="1">
							View
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="eprint" name="eprint" value="1">
							Print
							<span></span>
						</label>

					</div>

			</div>
			<div class="modal-footer">
				<input id="btnSave" onclick="save()" type="submit" class="btn btn-primary" value="Simpan">
			</div>
			</form>
		</div>
	</div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<i class="m-menu__link-icon flaticon-add"></i>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
					<input type="hidden" name="id1" id="id1">
					<input type="hidden" name="group" id="group">
					<div class="form-group m-form__group">
						<label for="">
							Nama Modul
						</label>
						<?php if ($modul) { ?>
							<select name="modul" id="modul" class="form-control m-input">
								<?php foreach ($modul as $row) { ?>
									<option value="<?php echo $row->idsysmodul; ?>"><?php echo $row->name; ?> </option>
								<?php } ?>
							</select>
						<?php } ?>
					</div>
					<div class="m-checkbox-inline">
						<label class="m-checkbox">
							<input type="checkbox" id="add" name="add" value="1">
							Add
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="edit" name="edit" value="1">
							Edit
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="delete" name="delete" value="1">
							Delete
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="view" name="view" value="1">
							View
							<span></span>
						</label>
						<label class="m-checkbox">
							<input type="checkbox" id="print" name="print" value="1">
							Print
							<span></span>
						</label>

					</div>

			</div>
			<div class="modal-footer">
				<input id="btnSave" onclick="save()" type="submit" class="btn btn-primary" value="Simpan">
			</div>
			</form>
		</div>
	</div>
</div>
<!--end::Modal-->

<script type="text/javascript">
	var save_method; //for save method string
	var table;
	var group = document.getElementById("m_form_group");
	document.getElementById("group").value = group.value;

	var Dtb = function() {
		var t = function() {
			var t = {
					data: {
						type: "remote",
						source: {
							read: {
								url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/"
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
						field: "number",
						title: "No.",
						sortable: false,
						width: 40,
						selector: !1,
						textAlign: "center"
					}, {
						field: "nama",
						title: "Nama Modul",
						filterable: !1,
						width: 150
					}, {
						field: "add",
						title: "Add",
						sortable: false,
						template: function(t) {
							var e = {
								0: {
									class: ""
								},
								1: {
									class: "fa fa-check"
								}
							};
							return '<span class="' + e[t.add].class + '"></span>"'
						}
					}, {
						field: "edit",
						title: "Edit",
						sortable: false,
						template: function(t) {
							var e = {
								0: {
									class: ""
								},
								1: {
									class: "fa fa-check"
								}
							};
							return '<span class="' + e[t.edit].class + '"></span>"'
						}
					}, {
						field: "delete",
						title: "Delete",
						sortable: false,
						template: function(t) {
							var e = {
								0: {
									class: ""
								},
								1: {
									class: "fa fa-check"
								}
							};
							return '<span class="' + e[t.delete].class + '"></span>"'
						}
					}, {
						field: "view",
						title: "View",
						sortable: false,
						template: function(t) {
							var e = {
								0: {
									class: ""
								},
								1: {
									class: "fa fa-check"
								}
							};
							return '<span class="' + e[t.view].class + '"></span>"'
						}
					}, {
						field: "print",
						title: "Print",
						sortable: false,
						template: function(t) {
							var e = {
								0: {
									class: ""
								},
								1: {
									class: "fa fa-check"
								}
							};
							return '<span class="' + e[t.print].class + '"></span>"'
						}
					}, {
						field: "action",
						width: 110,
						title: "Menu",
						sortable: false,
						overflow: "visible",
						template: function(t) {
							return '\t\t\t\t\t\t<div class="dropdown ' + (t.getDatatable().getPageSize() - t.getIndex() <= 4 ? "dropup" : "") + '">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<?php if (in_array($page_name, $this->session->userdata('perm_edit'))) : ?><a class="dropdown-item" href="javascript:void(0)" onclick="edit_akses(\'' + t.id + '\')"><i class="la la-edit"></i> Edit Hak Akses</a><?php endif; ?><?php if (in_array($page_name, $this->session->userdata('perm_delete'))) : ?><a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_akses(\'' + t.id + '\')"><i class="la la-trash"></i> Hapus Akses</a><?php endif; ?>\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
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
				$("#m_form_group").on("change", function(t) {
					var a = e.getDataSourceQuery();
					a.group = $(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
				}).val(void 0 !== a.group ? a.group : ""),
				$("#m_form_group").selectpicker(),
				$('#m_datatable_reload').on('click', function() {
					//e.destroy()
					//e=$(".m_datatable").mDatatable(t)
					e.reload()
				})

		};
		return {
			init: function() {
				t()
			}
		}
	}

	();
	jQuery(document).ready(function() {
			Dtb.init()
		}

	);

	function tambah_akses() {
		save_method = 'add';
		$('#form')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Hak Akses'); // Set Title to Bootstrap modal title
	}

	function edit_akses(id) {
		save_method = 'update';
		$('#form_edit')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		$('.modal-title').text('Edit Hak Akses'); // Set Title to Bootstrap modal title

		//Ajax Load data from ajax
		$.ajax({
			url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/edit/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('[name="eid1"]').val(data[0].idsysgrant);
				$('[name="emodul"]').val(data[0].idsysmodul);
				$('[name="egroup"]').val(data[0].idsysgroup);
				if (data[0].allow_add == 1) $('[name="eadd"]').prop("checked", true);
				if (data[0].allow_edit == 1) $('[name="eedit"]').prop("checked", true);
				if (data[0].allow_delete == 1) $('[name="edelete"]').prop("checked", true);
				if (data[0].allow_view == 1) $('[name="eview"]').prop("checked", true);
				if (data[0].allow_print == 1) $('[name="eprint"]').prop("checked", true);
				$('#modal_edit').modal('show'); // show bootstrap modal
				$('.modal-title').text('Edit Modul'); // Set title to Bootstrap modal title
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function reload_table() {
		group = document.getElementById("m_form_group");
		document.getElementById("group").value = group.value;
		$("#m_datatable_reload").trigger("click");
	}

	function save() {
		$('#btnSave').text('saving...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable 
		var url;
		var formname;
		if (save_method == 'add') {
			formname = 'form';
			url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit/";
		} else {
			formname = 'form_edit';
			url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update/";
		}

		// ajax adding data to database
		$.ajax({
			url: url,
			type: "POST",
			data: $('#' + formname).serialize(),
			dataType: "JSON",
			success: function(data) {

				if (data.status) //if success close modal and reload ajax table
				{
					if (data.status = 'TRUE') {
						document.getElementById('alertbox').style.display = 'block';
						$('.alert').addClass('show ' + data.alert);
						$('.alert').children('.m-alert__text').html(data.msg);
						hidealert();
						//if success reload ajax table
						$('#modal_form').modal('hide');
						$('#modal_edit').modal('hide'); // show bootstrap modal
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
				alert('Error adding / update data' + errorThrown);
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable 

			}
		});
	}

	function delete_akses(id) {
		if (confirm('Are you sure delete this data?')) {
			// ajax delete data to database
			$.ajax({
				url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hapus/" + id,
				type: "POST",
				dataType: "JSON",
				success: function(data) {
					if (data.status = 'TRUE') {
						document.getElementById('alertbox').style.display = 'block';
						$('.alert').addClass('show ' + data.alert);
						$('.alert').children('.m-alert__text').html(data.msg);
						hidealert();
						//if success reload ajax table
						$('#modal_form').modal('hide');
						reload_table();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					document.getElementById('alertbox').style.display = 'block';
					if ($('.alert').hasClass('show')) {
						$('.alert').addClass('show data-danger');
					} else {
						$('.alert').addClass('data-danger');
					}
					$('.alert').children('.m-alert__text').html('Gagal Menghapus Group');
					hidealert();
					//alert('Error deleting data'+errorThrown);
				}
			});

		}
	}

	function hidealert() {
		setTimeout(function() {
			document.getElementById('alertbox').style.display = 'none';
		}, 3000);
	}

	function get_modul() {
		group = document.getElementById("m_form_group").value;
		$.ajax({
			url: '<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/modul/',
			type: 'POST',
			data: 'g=' + group,
			dataType: 'json',
			success: function(json) {
				$('#modul').empty();
				$.each(json, function(i, obj) {
					$('#modul').append($('<option>').text(obj.name).attr('value', obj.idsysmodul));

				});
				reload_table();
			}
		});
	};
</script>