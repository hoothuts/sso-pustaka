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
				<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?= $this->session->flashdata('alert') ?> alert-dismissible fade show" id='flashmsg'>
					<div class="m-alert__icon">
						<i class="flaticon-exclamation-1"></i>
						<span></span>
					</div>
					<div class="m-alert__text">
						<?= $this->session->flashdata('flash_message') ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Edit Halaman
						</h3>
					</div>
				</div>
			</div>
			<?php foreach ($edit as $row) { ?>
				<!-- Begin Halaman Edit-->
				<form class="m-form m-form--fit m-form--label-align-right" id="halaman_form" action="<?= base_url('admin/manage_halaman/update/'); ?>" method="post">
					<input type="hidden" name="halaman_id" value="<?= $row->halaman_id ?>">
					<div class="m-portlet__body">
						<div class="form-group m-form__group">
							<label for="judul_halaman" class="form-control-label">
								Judul Halaman
							</label>
							<input value="<?= $row->judul_halaman ?>" type="text" class="form-control" id="judul_halaman" name="judul_halaman" required="" readonly>
						</div>

						<!-- <div class="form-group m-form__group">
							<label for="" class="form-control-label">
								Link Halaman
							</label>
							<input value="<?= $row->link_halaman ?>" type="text" class="form-control" name="link_halaman" required>
						</div> -->

						<div class="form-group m-form__group">
							<label for="" class="form-control-label">
								Link Halaman
							</label>
							<div class="input-group m-input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">lib.pkr.ac.id/halaman/</span>
								</div>
								<input type="text" id="link_halaman" value="<?= str_replace('lib.pkr.ac.id/halaman/', '', $row->link_halaman) ?>" class="form-control m-input" name="link_halaman" placeholder="..." required>
							</div>
							<span class="m-form__help">silahkan isi link halaman setelah <code>lib.pkr.ac.id/halaman/</code></span>
						</div>

						<div class="form-group m-form__group">
							<label for="isi_halaman" class="form-control-label">
								Isi Halaman
							</label>
							<textarea type="text" class="summernote" id="isi_halaman" name="isi_halaman" rows="10"><?= $row->isi_halaman; ?></textarea>
						</div>
						<div class="form-group m-form__group">
							<label for="" class="form-control-label">
								Meta Keywords
							</label>
							<input type="text" class="form-control" value="<?= $row->meta_keyword ?>" name="meta_keyword" maxlength="160">
						</div>
						<div class="form-group m-form__group">
							<label for="" class="form-control-label">
								Meta Descriptions
							</label>
							<input type="text" class="form-control" value="<?= $row->meta_desc ?>" name="meta_desc" minlength="50" maxlength="200">
						</div>
					</div>
					<div class="m-portlet__foot m-portlet__foot--fit">
						<div class="m-form__actions">
							<button class="btn btn-focus" type="button" onclick="previewForm()">
								<i class="la la-eye"></i> Preview
							</button>

							<button type="submit" class="btn btn-primary">
								Submit
							</button>
						</div>
					</div>
				</form>
				<!-- End Halaman Edit -->
			<?php } ?>
		</div>
	</div>
</div>
<script src="<?= base_url(); ?>assets/admin/summernote.js" type="text/javascript">
</script>
<script>
	function previewForm() {
		// Simpan tautan formulir yang asli
		var originalAction = document.getElementById('halaman_form').action;

		// Ubah aksi formulir untuk preview
		document.getElementById('halaman_form').action = '<?= base_url('admin/manage_halaman/preview/'); ?>';
		document.getElementById('halaman_form').target = '_blank';
		document.getElementById('halaman_form').submit();

		// Kembalikan aksi dan target formulir ke semula setelah preview
		document.getElementById('halaman_form').action = originalAction;
		document.getElementById('halaman_form').target = '_self';
	}
</script>