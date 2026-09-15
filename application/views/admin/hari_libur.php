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
								Konfigurasi Transaksi
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
			<div class="m-portlet__body">
				<div class="form-group m-form__group m--margin-top-10">
					<div class="alert m-alert m-alert--default" role="alert">
						<center><h3 class="m-portlet__head-text"><a class="btn" href="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/year/<?php echo $year-1;?>"><i class="la la-angle-left"></i></a><?php echo $year;?><a class="btn" href="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/year/<?php echo $year+1;?>"><i class="la la-angle-right"></i></a></h3></center>
					</div>
				</div>
				<div class="row">
					
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_januari"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_februari"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_maret"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_april"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_mei"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_juni"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_juli"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_agustus"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_september"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_oktober"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_november"></div>
					</div>
					<div class="col-lg-4 col-md-9 col-sm-12">
						<div class id="dp_desember"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
					<!-- end:: Body -->

<script type="text/javascript">
var BootstrapDatepicker=function() {
    var t=function() {
        $("#dp_januari").datepicker( {
			changeMonth:!0,multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 1, 1),startDate: new Date(<?php echo $year;?>, 0, 1),
			endDate:  new Date(<?php echo $year;?>, 0, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_februari").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 2, 1),startDate: new Date(<?php echo $year;?>, 1, 1),
			endDate:  new Date(<?php echo $year;?>, 1, <?php if($year%4==0){echo '29';} else{echo '28';}?>), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_maret").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 3, 1),startDate: new Date(<?php echo $year;?>, 2, 1),
			endDate:  new Date(<?php echo $year;?>, 2, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_april").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 4, 1),startDate: new Date(<?php echo $year;?>, 3, 1),
			endDate:  new Date(<?php echo $year;?>, 3, 30), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_mei").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 5, 1),startDate: new Date(<?php echo $year;?>, 4, 1),
			endDate:  new Date(<?php echo $year;?>, 4, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_juni").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 6, 1),startDate: new Date(<?php echo $year;?>, 5, 1),
			endDate:  new Date(<?php echo $year;?>, 5, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_juli").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 7, 1),startDate: new Date(<?php echo $year;?>, 6, 1),
			endDate:  new Date(<?php echo $year;?>, 6, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_agustus").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 8, 1),startDate: new Date(<?php echo $year;?>, 7, 1),
			endDate:  new Date(<?php echo $year;?>, 7, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_september").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 9, 1),startDate: new Date(<?php echo $year;?>, 8, 1),
			endDate:  new Date(<?php echo $year;?>, 8, 30), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_oktober").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 10, 1),startDate: new Date(<?php echo $year;?>, 9, 1),
			endDate:  new Date(<?php echo $year;?>, 9, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_november").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 11, 1),startDate: new Date(<?php echo $year;?>, 10, 1),
			endDate:  new Date(<?php echo $year;?>, 10, 30), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
        ,$("#dp_desember").datepicker( {
            multidate: !0,todayHighlight:!1,defaultViewDate: new Date(<?php echo $year;?>, 12, 1),startDate: new Date(<?php echo $year;?>, 11, 1),
			endDate:  new Date(<?php echo $year;?>, 11, 31), templates: {
                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
            }
        }
        )
		}
    ;
    return {
        init:function() {
            t()
        }
    }
}

();
jQuery(document).ready(function() {
    BootstrapDatepicker.init();
	<?php if($dates && count($dates)>0)
		{
			?>
			$('#dp_januari').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_februari').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_maret').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_april').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_mei').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_juni').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_juli').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_agustus').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_september').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_oktober').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_november').datepicker('setDates',[<?php echo $dates?>]);
			$('#dp_desember').datepicker('setDates',[<?php echo $dates?>]);
			<?php
		}
	?>
	$('#dp_januari').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:1,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_februari').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:2,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_maret').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:3,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_april').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:4,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_mei').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:5,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_juni').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:6,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_juli').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:7,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_agustus').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:8,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_september').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:9,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_oktober').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:10,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_november').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:11,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
	$('#dp_desember').datepicker()
    .on('changeDate', function(e) {
        // `e` here contains the extra attributes
		 $.ajax({
             url : "<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/change/",
             type: "POST",
             data: {date:e.dates,month:12,year:<?php echo $year;?>},
             dataType: "JSON",
             success: function(data)
             {
				// alert('success to change dates');
             },
             error: function (jqXHR, textStatus, errorThrown)
             {
				// alert('failed to change dates'+errorThrown);
             }
         });
    });
}

);
</script>