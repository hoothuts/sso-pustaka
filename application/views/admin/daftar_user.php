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
						<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert')?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
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
											<?php echo $page_title;?>
											
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
																Status:
															</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-bootstrap-select" id="m_form_status">
																<option value="">
																</option>
																<option value="1">
																	Aktif
																</option>
																<option value="0">
																	Tidak Aktif
																</option>
															</select>
														</div>
													</div>
													<div class="d-md-none m--margin-bottom-10"></div>
												</div>
												<div class="col-md-4">
													<div class="m-form__group m-form__group--inline">
														<div class="m-form__label">
															<label class="m-label m-label--single">
																Group:
															</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-bootstrap-select" id="m_form_group">
																<option value="">
																</option>
																<?php if($sysgroup){?>
																	<?php foreach($sysgroup as $row){?>
																	<option value="<?php echo $row->idsysgroup;?>"><?php echo $row->name;?> </option>
																	<?php }?>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="d-md-none m--margin-bottom-10"></div>
												</div>
												<div class="col-md-4">
													<select class="form-control m-bootstrap-select m--margin-bottom-10" id="m_form_tipe">
														<option value="">Tipe Akun: Semua</option>
														<option value="sso">SSO</option>
														<option value="manual">Manual</option>
													</select>
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
										<?php if(in_array($page_name,$this->session->userdata('perm_add'))):?>
											<a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="tambah_user()">
												<span>
													<i class="flaticon-add"></i>
													<span>
														Tambah
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
											<?php endif;?>
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
 
<script type="text/javascript">
 
var save_method; //for save method string
var table;
 
 var Dtb=function() {
    var t=function() {
        var t= {
            data: {
                type:"remote", source: {
                    read: {
                        url: "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/fetch/"
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
            , 
			sortable: true,

			filterable: false,
		
			pagination: true,

			searchDelay: 400,

			 columns:[ {
                field: "number", title: "No.", sortable: false, width: 40, selector: !1, textAlign: "center"
            }
            , {
                field:"id", title:"ID User", filterable:!1, width:150
            }
            , {
                field: "nama", title: "Nama"
            }
            , {
                field: "tipe", title: "Tipe Akun", template: function(t) {
                    return t.tipe == 'SSO' ? '<span class="m-badge m-badge--info m-badge--wide">SSO</span>' : '<span class="m-badge m-badge--metal m-badge--wide">Manual</span>';
                }
            }
            , {
                field: "group", title: "Group"
            }
            , {
                field: "active", title: "Status"
            }
            , {
                field:"action", width:110, title:"Actions", sortable: false, overflow:"visible", template:function(t) {
                    var extra_action = t.tipe == 'SSO'
                        ? '<a class="dropdown-item" href="javascript:void(0)" title="Lihat Data Pegawai" onclick="detail_pegawai(\''+t.id+'\')"><i class="la la-id-card"></i> Lihat Data Pegawai</a>'
                        : '<a class="dropdown-item" href="javascript:void(0)" title="Show Password" onclick="show_pass(\''+t.id+'\')"><i class="la la-key"></i> Show Password</a>';
                    return'\t\t\t\t\t\t<div class="dropdown '+(t.getDatatable().getPageSize()-t.getIndex()<=4?"dropup": "")+'">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<?php if(in_array($page_name,$this->session->userdata('perm_edit'))):?><a class="dropdown-item" href="javascript:void(0)" onclick="edit_user(\''+t.id+'\')"><i class="la la-edit"></i> Edit User</a><?php endif;?><?php if(in_array($page_name,$this->session->userdata('perm_delete'))):?><a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_user(\''+t.id+'\')"><i class="la la-trash"></i> Hapus User</a><?php endif;?>\t\t\t\t\t\t    \t'+extra_action+'\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
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
        ,
        e=$(".m_datatable").mDatatable(t),
        a=e.getDataSourceQuery();
        $("#m_form_search").on("keyup", function(t) {
            var a=e.getDataSourceQuery();
            a.generalSearch=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(a.generalSearch),
        $("#m_form_status").on("change", function(t) {
            var a=e.getDataSourceQuery();
            a.status=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(void 0!==a.status?a.status:""),
        $("#m_form_group").on("change", function(t) {
            var a=e.getDataSourceQuery();
            a.group=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(void 0!==a.group?a.group:""),
        $("#m_form_tipe").on("change", function(t) {
            var a=e.getDataSourceQuery();
            a.tipe=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(void 0!==a.tipe?a.tipe:""),
        $("#m_form_status, #m_form_group, #m_form_tipe").selectpicker(),
        $('#m_datatable_reload').on('click', function () {
			//e.destroy()
			//e=$(".m_datatable").mDatatable(t)
			e.reload()
		})
        
    };
    return {
        init:function() {
            t()
        }
    }
}

();
jQuery(document).ready(function() {
    Dtb.init();

    $('#pegawai_picker').select2({
        width: "100%",
        placeholder: "Cari NIP atau nama pegawai",
        minimumInputLength: 1,
        dropdownParent: $('#modal_form'),
        ajax: {
            url: "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/search_pegawai/",
            dataType: "json",
            type: "GET",
            delay: 500,
            data: function(params) {
                return { searchtext: params.term };
            },
            processResults: function(data) {
                $(data.items).each(function() {
                    this.text = this.nama;
                });
                return { results: data.items };
            },
            cache: true
        }
    }).on('select2:select', function(e) {
        var item = e.params.data;
        if (save_method == 'add') {
            // ID User cuma auto-diisi saat bikin akun baru; saat edit akun lama, username tidak boleh berubah
            $('#id').val(item.nip);
        }
        $('#nama').val(item.nama.split(' — ')[0]);
        $('#nip_pegawai').val(item.nip);
    });
}

);
function toggle_user_source()
{
    if ($('#source_pegawai').is(':checked')) {
        $('#group_pegawai_picker').show();
        $('#group_password, #group_password2').hide();
        $('#password, #password2').val('');
        $('#id, #nama').prop('readonly', true);
    } else {
        $('#group_pegawai_picker').hide();
        $('#group_password, #group_password2').show();
        $('#pegawai_picker').val(null).trigger('change');
        $('#nip_pegawai').val('');
        $('#id, #nama').prop('readonly', false).val('');
    }
}
function tambah_user()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#source_pegawai').prop('checked', true);
    $('#pegawai_picker').val(null).trigger('change');
    $('#nip_pegawai').val('');
    $('#sso_badge_notice').hide();
    toggle_user_source();
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah User'); // Set Title to Bootstrap modal title
}
 
function edit_user(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
     $('.modal-title').text('Edit User'); // Set Title to Bootstrap modal title

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/edit/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id1"]').val(data[0].idsysuser);
            $('[name="id"]').val(data[0].idsysuser);
            $('[name="nama"]').val(data[0].name);
            $('[name="password"]').val(data[0].pass);
            $('[name="group"]').val(data[0].idsysgroup);
            $('[name="status"]').val(data[0].active);

            if (data[0].nip_pegawai) {
                $('#source_pegawai').prop('checked', true);
                toggle_user_source();
                var opt = new Option(data[0].pegawai_nama + ' — ' + data[0].nip_pegawai, data[0].nip_pegawai, true, true);
                $('#pegawai_picker').append(opt).trigger('change');
                $('#nip_pegawai').val(data[0].nip_pegawai);
                $('#sso_badge_notice').show();
            } else {
                $('#source_manual').prop('checked', true);
                toggle_user_source();
                $('[name="id"]').val(data[0].idsysuser);
                $('[name="nama"]').val(data[0].name);
                $('#sso_badge_notice').hide();
            }

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
 
function reload_table()
{
	$( "#m_datatable_reload" ).trigger( "click" );
}
 
function save()
{
	
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
	
    var url;
 
    if(save_method == 'add') {
        url = "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/submit/";
    } else {
        url = "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/update/";
    }
 
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
 
            if(data.status) //if success close modal and reload ajax table
            {
				if(data.status='TRUE'){
					document.getElementById('alertbox').style.display = 'block';
					$('.alert').addClass('show '+data.alert);
					$('.alert').children('.m-alert__text').html(data.msg);
					hidealert();
					//if success reload ajax table
					$('#modal_form').modal('hide');
					reload_table();
				}
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
			
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
			
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
		   alert('Error adding / update data'+textStatus+errorThrown);
		}
    });
}

 function hidealert(){
	setTimeout(function(){
	   document.getElementById('alertbox').style.display = 'none';
	}, 3000);
}
function delete_user(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/hapus/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
				if(data.status='TRUE'){
					document.getElementById('alertbox').style.display = 'block';
					$('.alert').addClass('show '+data.alert);
					$('.alert').children('.m-alert__text').html(data.msg);
					hidealert();
					//if success reload ajax table
					$('#modal_form').modal('hide');
					reload_table();
				}
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
				document.getElementById('alertbox').style.display = 'block';
				if ($('.alert').hasClass('show')){
					$('.alert').addClass('show data-danger');
				}else{
					$('.alert').addClass('data-danger');
				}
				$('.alert').children('.m-alert__text').html('Gagal Menghapus User');
			   hidealert();
				//alert('Error deleting data'+errorThrown);
            }
        });
 
    }
}
 function show_pass(id)
{
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/pass/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
           $('[name="show"]').val(data.pass);
            $('#modal_pass').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Show Password'); // Set title to Bootstrap modal title
			setTimeout(function() {
				$('#modal_pass').modal('hide');
			}, 3000);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function detail_pegawai(id)
{
    $.ajax({
        url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/detail_pegawai/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if (!data.status) {
                alert(data.msg);
                return;
            }
            $('[name="peg_nama"]').val(data.pegawai.nama);
            $('[name="peg_nip"]').val(data.pegawai.nip);
            $('[name="peg_email"]').val(data.pegawai.email);
            $('[name="peg_telp"]').val(data.pegawai.telp);
            $('#modal_pegawai').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
</script>
 <div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="labelModalTambah">
											<i class="m-menu__link-icon flaticon-add"></i> Show Password
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
											<div class="form-group m-form__group">
												<label for="">
													Pass*
												</label>
													<input type="text" readonly name="show" id="show" class="form-control m-input">
											</div>

										</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="modal_pegawai" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="labelModalTambah">
											<i class="m-menu__link-icon flaticon-add"></i> Data Pegawai (Akun SSO)
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<div class="form-group m-form__group">
											<label for="">Nama</label>
											<input type="text" readonly name="peg_nama" class="form-control m-input">
										</div>
										<div class="form-group m-form__group">
											<label for="">NIP</label>
											<input type="text" readonly name="peg_nip" class="form-control m-input">
										</div>
										<div class="form-group m-form__group">
											<label for="">Email</label>
											<input type="text" readonly name="peg_email" class="form-control m-input">
										</div>
										<div class="form-group m-form__group">
											<label for="">Telepon</label>
											<input type="text" readonly name="peg_telp" class="form-control m-input">
										</div>
									</div>
								</div>
							</div>
						</div>
<!--begin::Modal-->
						<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="labelModalTambah">
											<i class="m-menu__link-icon flaticon-add"></i> Tambah User Baru
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
									<!--<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/submit/-->
										 <form id="form" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
										 <input type="hidden" name="id1" id="id1">
										 <input type="hidden" name="nip_pegawai" id="nip_pegawai">
											<div class="form-group m-form__group">
												<label for="">
													Sumber Akun
												</label>
												<div>
													<label class="m-radio">
														<input type="radio" name="user_source" id="source_pegawai" value="pegawai" checked onclick="toggle_user_source()"> Pilih dari Data Pegawai
														<span></span>
													</label>
													<label class="m-radio">
														<input type="radio" name="user_source" id="source_manual" value="manual" onclick="toggle_user_source()"> Input Manual
														<span></span>
													</label>
												</div>
												<span class="m-badge m-badge--info m-badge--wide" id="sso_badge_notice" style="display:none; margin-top:5px;">🔵 Akun ini login via SSO</span>
											</div>
											<div class="form-group m-form__group" id="group_pegawai_picker">
												<label for="">
													Cari Pegawai*
												</label>
												<select id="pegawai_picker" class="form-control m-input" style="width:100%"></select>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													ID User*
												</label>
												<input type="text" id="id" name="id" class="form-control m-input" placeholder="Masukkan ID User" readonly>
													<span class="m-form__help">
													
													</span>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Nama User*
												</label>
												<input type="text" id="nama" name="nama" class="form-control m-input" placeholder="Masukkan Nama" readonly>
													<span class="m-form__help">
													
													</span>
											</div>
											<div class="form-group m-form__group" id="group_password">
												<label for="">
													Password*
												</label>
												<input type="password" id="password" name="password" class="form-control m-input" placeholder="Masukkan ID">
													<span class="m-form__help">
													
													</span>
											</div>
											<div class="form-group m-form__group" id="group_password2">
												<label for="">
													Confirm Password*
												</label>
												<input type="password" id="password2" name="password2" class="form-control m-input" placeholder="Input Ulang Password">
													<span class="m-form__help">
													
													</span>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Group User
												</label>
												<?php if($sysgroup){?>
													<select name="group" id="group" class="form-control m-input">
														<?php foreach($sysgroup as $row){?>
														<option value="<?php echo $row->idsysgroup;?>"><?php echo $row->name;?> </option>
														<?php }?>
													</select>
													<?php } ?>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Status Aktif*
												</label>
													<select name="status" id="status" class="form-control m-input">
														<option value="1">Aktif</option>
														<option value="0">Non-Aktif</option>
													</select>
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