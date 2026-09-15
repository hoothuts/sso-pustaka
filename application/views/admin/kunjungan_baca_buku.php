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
						<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert')?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
									<div class="m-alert__icon">
										<i class="flaticon-exclamation-1"></i>
										<span></span>
									</div>
									<div class="m-alert__text">
										<?php echo $this->session->flashdata('flash_message') ?>
									</div>
								</div>
							<?php if(in_array($page_name,$this->session->userdata('perm_add'))):?>
							<form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" action="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/submit/" method="post">
							
								<div class="m-portlet m-portlet--mobile" id="m_portlet_tools_1">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													Buku Tamu
													
												</h3>
											</div>
										</div>
										<div class="m-portlet__head-tools">
											<ul class="m-portlet__nav">
												<li class="m-portlet__nav-item">
													<a href=""  data-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
														<i class="la la-angle-down"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
											<div class="row align-items-center">
												<div class="col-xl-8 ">
													<div class="form-group m-form__group row align-items-center">
														<div class="col-md-8">
															<div class="m-input-icon m-input-icon--left">
															<label>
																NIM/NIP:
															</label>
																<input type="text" id="nomor" name="nomor" class="form-control m-input" placeholder="Masukkan NIM/NIP">
															</div>
														</div>
													</div>
												</div>
											</div>
											
										</div>
									</div>
									<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
												<div class="m-form__actions m-form__actions--solid">
													<div class="row">
														<div class="col-lg-6">
															<button type="submit" class="btn btn-primary">
																Submit
															</button>
														</div>
													</div>
												</div>
									</div>
								</div>
							</form>
							<form class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" action="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/submitnon/" method="post">
							<div class="m-portlet m-portlet--mobile" id="m_portlet_tools_2">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
												<h3 class="m-portlet__head-text">
													Buku Tamu (Bukan Anggota)
												</h3>
											</div>
										</div>
										<div class="m-portlet__head-tools">
											<ul class="m-portlet__nav">
												<li class="m-portlet__nav-item">
													<a href=""  data-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
														<i class="la la-angle-down"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<!--begin::Form-->
										<div class="m-portlet__body">
											<div class="form-group m-form__group row">
												<div class="col-lg-4">
													<label>
														Nama:
													</label>
													<input type="text" id="nama" name="nama" class="form-control m-input" placeholder="Masukkan nama">
												</div>
												<div class="col-lg-4">
													<label class="">
														Instansi:
													</label>
													<input type="text" id="asal" name="asal" class="form-control m-input" placeholder="Masukkan Asal">
													
												</div>
												<div class="col-lg-4">
													<label class="">
														Tanggal:
													</label>
														<div class='input-group date' id='m_datepicker_presensi'>
														<input type='text' id="tgl" name="tgl" class="form-control m-input" value="<?php echo date('Y-m-d');?>" readonly />
														<span class="input-group-addon">
															<i class="la la-calendar-check-o"></i>
														</span>
														</div>
												</div>
											</div>
										</div>
										<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions--solid">
												<div class="row">
													<div class="col-lg-6">
														<button type="submit" class="btn btn-primary">
															Submit
														</button>
													</div>
												</div>
											</div>
										</div>
									<!--end::Form-->
								</div>
									</form>
							<?php endif; ?>
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
                pageSize:20,
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
		
			pagination: false,

			searchDelay: 400,

			 columns:[ {
                field: "number", title: "No.", sortable: false, width: 40, selector: !1, textAlign: "center"
            }
            , {
                field:"tanggal", title:"Tanggal", sortable: false, filterable:!1, width:150
            }
            , {
                field: "jumlah", title: "Jumlah", sortable: false
            }
            , {
                field:"action", width:110, title:"detail", sortable: false, overflow:"visible", template:function(t) {
                    return'\t\t\t\t\t\t<div class="dropdown '+(t.getDatatable().getPageSize()-t.getIndex()<=4?"dropup": "")+'">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Detail" onclick="detail(\''+t.tanggal+'\')"><i class="la la-search"></i> Detail</a>\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
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
        $('#m_datatable_reload').on('click', function () {
			//e.destroy()
			//e=$(".m_datatable").mDatatable(t)
			e.reload()
		})
        ,$("#m_datepicker_presensi").datepicker( {
            format: 'yyyy-mm-dd',todayHighlight:!0, orientation:"bottom left", templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        )
        
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

function reload_table()
{
	$( "#m_datatable_reload" ).trigger( "click" );
}
function detail(id)
{
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/detail/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
			if(data.status='TRUE'){
				
				var len=data.size;
				var HTML = "<table class='table'><thead class='thead-inverse'><tr>";
				HTML += "<th scope='row'>No.</th>";
				HTML += "<th>Tanggal</th>";
				HTML += "<th>Jam</th>";
				HTML += "<th>NIS/NIP</th>";
				HTML += "<th>NAMA</th>";
				HTML += "</tr></thead><tbody>";
				for(j=0;j<len;j++)
				{
					HTML += "<tr>";
					HTML +="<td scope='row'>"+ (j+1)+"</td>";
					HTML +="<td>"+ data.table[j].tgl+"</td>";
					HTML +="<td>"+ data.table[j].jam+"</td>";
					HTML +="<td>"+ data.table[j].nomor+"</td>";
					HTML +="<td>"+ data.table[j].nama+"</td>";
					HTML += "</tr>";
				}
				HTML += "</tbody></table>";
				document.getElementById("outputDiv").innerHTML = HTML;
				
				$('#modal_detail').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Detail Presensi '+data.tanggal); // Set title to Bootstrap modal title
			}
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
        }
    });
}
</script>
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog"aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">
											<i class="m-menu__link-icon flaticon-add"></i> Detail
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
											  <div id="outputDiv"></div>
										</div>
								</div>
							</div>
						</div>