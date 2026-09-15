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
														Transaksi
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
																Klasifikasi:
															</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-bootstrap-select" id="m_form_klasifikasi">
																<option value="">
																</option>
																<?php if($klasifikasi){?>
																	<?php foreach($klasifikasi as $row){?>
																	<option value="<?php echo $row->id;?>"><?php echo $row->nama;?> </option>
																	<?php }?>
																<?php } ?>
															</select>
														</div>
													</div>
													<div class="d-md-none m--margin-bottom-10"></div>
												</div>
												<div class="col-md-4">
													<div class="m-form__group m-form__group--inline">
														<div class="m-form__label">
															<label class="m-label m-label--single">
																Kelompok Buku:
															</label>
														</div>
														<div class="m-form__control">
															<select class="form-control m-bootstrap-select" id="m_form_kategori">
																<option value="">
																</option>
																<?php if($kategori){?>
																	<?php foreach($kategori as $row){?>
																	<option value="<?php echo $row->idkategori;?>"><?php echo $row->nmkategori;?> </option>
																	<?php }?>
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
										<?php if(in_array($page_name,$this->session->userdata('perm_add'))):?>
											<a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="tambah_hilang()">
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
                field:"buku", title:"Buku", filterable:!1, width:150
            }
            , {
                field: "tgl", title: "Tanggal"
            }
            , {
                field: "anggota", title: "No. Anggota"
            }
            , {
                field: "biaya", title: "Biaya Ganti/Harga"
            }
            , {
                field:"keterangan", title:"Keterangan", template:function(t) {
                    var e= {
                        'H': {
                            class: "Hilang"
                        }
                        , 'R': {
                            class: "Rusak"
                        }
						, 'A': {
                            class: "DiArsipkan"
                        }
						, 'L': {
                            class: "DiLelangkan"
                        }, 'K': {
                            class: "Kembali"
                        }
                    }
                    ;
                    return e[t.keterangan]
                }
            }
            , {
                field:"action", width:110, title:"Kembali", sortable: false, overflow:"visible", template:function(t) {
                    return'\t\t\t\t\t\t<div class="dropdown '+(t.getDatatable().getPageSize()-t.getIndex()<=4?"dropup": "")+'">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<?php if(in_array($page_name,$this->session->userdata('perm_delete'))):?><a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_hilang(\''+t.kd+'\')"><i class="la la-trash"></i> Hapus Transaksi</a><?php endif;?>\t\t\t\t\t\t    \t<?php if(in_array($page_name,$this->session->userdata('perm_edit'))):?><a class="dropdown-item" href="javascript:void(0)" title="Kembali" onclick="kembali_hilang(\''+t.kd+'\')"><i class="la la-save"></i> Kembali</a><?php endif;?>\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
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
        $("#m_form_klasifikasi").on("change", function(t) {
            var a=e.getDataSourceQuery();
            a.klasifikasi=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(void 0!==a.klasifikasi?a.klasifikasi:""),
        $("#m_form_kategori").on("change", function(t) {
            var a=e.getDataSourceQuery();
            a.kategori=$(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load()
        }
        ).val(void 0!==a.kategori?a.kategori:""),
        $("#m_form_klasifikasi, #m_form_kategori").selectpicker(),
        $("#m_datepicker_2_modal").datepicker( {
            format: 'yyyy-mm-dd',todayHighlight:!0, orientation:"bottom left", templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        ),
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
    Dtb.init()
}

);
function tambah_hilang()
{
    $("#inventaris").attr("enabled", "enabled"); 
	save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Form Status Buku (Hilang, Rusak, Diarsipkan, Dilelang)'); // Set Title to Bootstrap modal title
}
 
function edit_hilang(id)
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
            $('[name="id1"]').val(data[0].kd_hr);
            $('[name="tanggal"]').val(data[0].tgl_hr);
            $('[name="keterangan"]').val(data[0].ket);
            $('[name="anggota"]').val(data[0].no_anggota);
            $('[name="harga"]').val(data[0].biaya_ganti);
			$('[name="inventaris"]').val(data[0].no_inv);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Form Status Buku'); // Set title to Bootstrap modal title
 
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
function delete_hilang(id)
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
				$('.alert').children('.m-alert__text').html('Gagal Menghapus Transaksi');
			   hidealert();
				//alert('Error deleting data'+errorThrown);
            }
        });
 
    }
}
 function kembali_hilang(id)
{
	
        // ajax delete data to database
        $.ajax({
            url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/kembali/"+id,
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
				$('.alert').children('.m-alert__text').html('Gagal Mengembalikan Transaksi');
			   hidealert();
				//alert('Error deleting data'+errorThrown);
            }
        });
 
}
</script>
<!--begin::Modal-->
						<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" >
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
											<div class="form-group m-form__group">
												<label for="">
													Tanggal*
												</label>
												<div class='input-group date' id='m_datepicker_2_modal'>
												<input type='text' id="tanggal" name="tanggal" class="form-control m-input" readonly  placeholder="Masukkan tanggal"/>
												<span class="input-group-addon">
													<i class="la la-calendar-check-o"></i>
												</span>
												</div>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													No. Barcode*
												</label>
												<input type="text"  class="form-control" id="inventaris" name="inventaris">
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Keterangan
												</label>
													<select name="ket" id="ket" class="form-control m-input">
													<option value="H">Hilang</option>
													<option value="R">Rusak</option>
													<option value="A">Diarsipkan</option>
													<option value="L">Dilelang</option>
													</select>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Optional
												</label>
											</div>
											<div class="form-group m-form__group">
												<label for="">
													No. Anggota
												</label>
													<input type="text" id="anggota" name="anggota" class="form-control m-input" placeholder="Masukkan No. Anggota">
											</div>
											<div class="form-group m-form__group">
												<label for="">
													Biaya Ganti:
												</label>
													<input type="text" id="harga" name="harga" class="form-control m-input" placeholder="Masukkan Biaya Ganti">
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