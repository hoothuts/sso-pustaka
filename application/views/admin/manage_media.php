<link href="<?php echo base_url(); ?>assets/media/pagination.css" rel="stylesheet" type="text/css">

<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN: Subheader -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<a href="" class="m-nav__link">
					<h3 class="m-subheader__title m-subheader__title--separator">
						Media
					</h3>
				</a>
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
						<a href="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>" class="m-nav__link m-nav__link--icon">
							<i class="m-nav__link-icon flaticon-graphic"></i>
						</a>
					</li>
					<li class="m-nav__separator">
						-
					</li>
					<li class="m-nav__item">
						<a href="" class="m-nav__link">
							<span class="m-nav__link-text">
								Media
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- END: Subheader -->
	<div class="m-content">
		<?php if ($this->session->flashdata('alert') != '') : ?>
			<script>
				$(document).ready(function() {
					setTimeout(function() {
						document.getElementById('flashmsg').style.display = 'none';
					}, 5000);
				});
			</script>
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
		<!-- Begin Galery -->
		<div class="row">
			<div class="col-xl-12">
				<div class="m-portlet m-portlet--full-height ">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h3 class="m-portlet__head-text">
									Manajemen Media
								</h3>
							</div>
						</div>
						<div class="m-portlet__head-tools">
							<?php if (in_array($page_name, $this->session->userdata('perm_add'))) : ?>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#tambah_modal">
									<i class="fa fa-plus"></i>
									Tambah Media
								</button>
							<?php endif; ?>
						</div>
					</div>
					<div class="m-portlet__body">
						<div class="row">
							<div class="col-xl-12">
								<div id="pagination">
									<?php echo $links; ?>
								</div>
							</div>
						</div>

						<div id="js-grid-juicy-projects" class="cbp">
							<?php foreach ($media as $row) {
							?>

								<div class="cbp-item">
									<div class="cbp-caption">
										<div class="cbp-caption-defaultWrap">
											<div class="penutup">
												<img src="<?php echo base_url() . 'uploads/small/small_' . $row['judul']; ?>" alt="">
											</div>
										</div>
										<div class="cbp-caption-activeWrap">
											<div class="cbp-l-caption-alignCenter">
												<div class="cbp-l-caption-body">
													<a href="<?php echo base_url() . "admin/details/" . $row['media_id']; ?>" class="cbp-singlePage btn btn-primary btn-sm m-btn--pill" rel="nofollow" data-toggle="m-tooltip" title="" data-original-title="Info Detail">
														<i class="la la-comment"></i>
													</a>
													<a target='_blank' href="<?php echo base_url() . 'uploads/' . $row['judul']; ?>" class="cbp-lightbox btn btn-danger btn-sm m-btn--pill" data-toggle="m-tooltip" title="" data-original-title="Perbesar" data-toggle="m-tooltip" title="" data-original-title="Perbesar" data-title="<?php echo base_url() . 'uploads/' . $row['judul']; ?>">
														<i class="la la-search"></i>
													</a>
												</div>
											</div>
										</div>
									</div>
									<?php if (in_array($page_name, $this->session->userdata('perm_delete'))) : ?>
										<div class="uppercase text-center uppercase text-center"><?php echo $row['judul']; ?>
											<a href="<?php echo base_url() . 'admin/manage_media/delete/' . $row['media_id']; ?>" class="btn btn-danger btn-sm m-btn--pill" onclick="return confirm('Are you sure you want to delete this item?');">
												<i class="la la-remove"></i>
											</a>
										</div>
									<?php endif; ?>
								</div>
							<?php } ?>

						</div>
						<div class="row">
							<div class="col-xl-12">
								<div id="pagination">
									<?php echo $links; ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- End Galery -->

		<!--begin::Modal-->
		<div class="modal fade" id="tambah_modal" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="labelModalTambah">
							Tambah Media
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">
								&times;
							</span>
						</button>
					</div>
					<form action="<?php echo base_url('admin/manage_media/'); ?>" method="POST" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="form-group m-form__group row">
								<label for="m-dropzone-two">
									Multiple File Upload
								</label>
								<div class="form-control">
									<div class="m-dropzone dropzone m-dropzone--primary" action="<?php echo base_url('admin/manage_media/add'); ?>" id="m-dropzone-two">
										<div class="m-dropzone__msg dz-message needsclick">
											<h3 class="m-dropzone__msg-title">
												Drag File Kesini atau Klik untuk Upload.
											</h3>
											<span class="m-dropzone__msg-desc">
												Upload file maksimal 10 & Max File Size : 2 MB
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								OKE
							</button>
							<button type="submit" class="btn btn-secondary">
								Batal
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end::Modal-->
	</div>
</div>