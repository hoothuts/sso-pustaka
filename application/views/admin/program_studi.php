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
								Data Referensi
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
						<!-- <div class="col-xl-4 order-1 order-xl-2 m--align-right">
							<a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="tambah_buku()">
								<span>
									<i class="flaticon-add"></i>
									<span>
										Tambah
									</span>
								</span>
							</a>
							<div class="m-separator m-separator--dashed d-xl-none"></div>
						</div> -->
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

<script type="text/javascript">

var save_method; //for save method string
var table;
 var DatatableRemoteAjaxDemo=function() {
    var t=function() {
        var t= {
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
                field: "number", title: "#", sortable: false, width: 40, selector: !1, textAlign: "center"
            }
            , {
                field: "kodemspst", title: "Kode", sortable: true
            }
						, {
                field: "nmmspst", title: "Nama Prodi", sortable: true
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
        e=$(".m_datatable").mDatatable(t),
        a=e.getDataSourceQuery();
        $("#m_form_search").on("keyup", function(t) {
            var a=e.getDataSourceQuery();
            a.generalSearch=$(this).val().toLowerCase(),
						e.setDataSourceQuery(a),
						e.load()
        }).val(a.generalSearch),
        $("#m_form_nama").selectpicker(),
				$('#m_datatable_reload').on('click', function () {
				e.reload()
				})
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
}


</script>
