<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-filestyle/bootstrap-filestyle.css" rel="stylesheet" type="text/css" /> -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN: Subheader -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<h3 class="m-subheader__title m-subheader__title--separator">
					<?php echo $page_title; ?>
					<small>
						<i class="glyphicon glyphicon-refresh"></i>
						<button style="display:none" class="btn btn-default" id="m_datatable_reload">
							<i class="fa fa-refresh"></i> Reload</button>
					</small>
				</h3>
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
						<a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon">
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
		<?php if ($this->session->flashdata('alert')) { ?>
			<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
				<div class="m-alert__icon">
					<i class="flaticon-exclamation-1"></i>
					<span></span>
				</div>
				<div class="m-alert__text">
					<?php echo $this->session->flashdata('flash_message') ?>
				</div>
			</div>
		<?php } ?>
		<?php if ($page_action == 'list') { ?>
			<script type="text/javascript">
				var page_action = 'list';
			</script>
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
										<select class="form-control m-bootstrap-select" id="m_form_klas">
											<option value="">Pilih Klasifikasi</option>
											<?php if ($klasifikasi) { ?>
												<?php foreach ($klasifikasi as $row) { ?>
													<option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?> </option>
												<?php } ?>
											<?php } ?>
										</select>
										<div class="d-md-none m--margin-bottom-10"></div>
									</div>
									<div class="col-md-4">
										<select class="form-control m-bootstrap-select" id="m_form_kel">
											<option value="">Pilih Kategori</option>
											<?php if ($kategori) { ?>
												<?php foreach ($kategori as $row) { ?>
													<option value="<?php echo $row->idkategori; ?>"><?php echo $row->nmkategori; ?> </option>
												<?php } ?>
											<?php } ?>
										</select>
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
								<a href="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/ubah_rak/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
									<span>
										<i class="flaticon-add"></i>
										<span>
											Ubah Rak
										</span>
									</span>
								</a>
								<div class="m-separator m-separator--dashed d-xl-none"></div>
								<a href="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/tambah/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
									<span>
										<i class="flaticon-add"></i>
										<span>
											Tambah
										</span>
									</span>
								</a>
							</div>
						</div>
					</div>
					<!--begin: Selected Rows Group Action Form -->
					<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
						<div class="row align-items-center">
							<div class="col-xl-12">
								<div class="m-form__group m-form__group--inline">
									<div class="m-form__label m-form__label-no-wrap">
										<label class="m--font-bold m--font-danger-">
											Selected
											<span id="m_datatable_selected_number"></span>
											records:
										</label>
									</div>
									<div class="m-form__control">
										<div class="btn-toolbar">
											<button id="cetak_katalog" type="button" class="btn btn-accent btn-sm">
												Cetak Katalog
											</button>
											&nbsp;&nbsp;&nbsp;
											<button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
												Cetak Barcode
											</button>
											&nbsp;&nbsp;&nbsp;
											<button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
												Cetak Call Number
											</button>
											&nbsp;&nbsp;&nbsp;
											<button class="btn btn-sm btn-accent" type="button">
												Set Buku Diarsipkan
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Selected Rows Group Action Form -->
					<script type="text/javascript">
						var page = 'list';
					</script>
					<div class="m_datatable" id="m_datatable">
						<!-- Here is Data Table Begin -->
					</div>
				</div>
			</div>
		<?php }
		if ($page_action == 'set_inv') { ?>
			<script type="text/javascript">
				var page_action = 'set_inv';
			</script>
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
					<table class="table table-borderless table-hover">
						<tbody>
							<tr>
								<th scope="row" style="width:15%">No. Klasifikasi</th>
								<td style="width:3%">:</td>
								<td><?php echo $data[0]->no_klas; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:15%">ISBN</th>
								<td style="width:3%">:</td>
								<td><?php echo $data[0]->ISBN; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:15%">Judul Buku</th>
								<td style="width:3%">:</td>
								<td><?php echo $data[0]->judul; ?></td>
							</tr>
						</tbody>
					</table>
					<div class="m--space-10"></div>
					<input type="hidden" id="isbn" value="<?php echo $isbn; ?>">
					<input type="hidden" id="no_klas" value="<?php echo $no_klas; ?>">
					<div class="set_inv" id="set_inv"></div>
					<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
						<div class="row align-items-center">
							<div class="col-xl-12 order-2 order-xl-3 m--align-right">
								<a href="#" id="button_tambah" data-toggle="collapse" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
									<span>
										<i class="flaticon-add"></i>
										<span>
											Tambah
										</span>
									</span>
								</a>
								<div class="m-separator m-separator--dashed d-xl-none"></div>
							</div>
						</div>
					</div>
					<div class="m--space-10"></div>
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<span class="m-portlet__head-icon m--hide">
									<i class="la la-gear"></i>
								</span>
								<h3 id="head" class="m-portlet__head-text">
									Inventaris
								</h3>
							</div>
						</div>
					</div>
					<!--begin::Form-->
					<form class="m-form m-form--fit m-form--label-align-right collapse panel-collapse" id="tambah_inv">
						<div class="m--space-10"></div>
						<input name="no_klas" type="hidden" value="<?php echo $no_klas; ?>">
						<input name="isbn" type="hidden" value="<?php echo $isbn; ?>">
						<input name="status" type="hidden" value="A">

						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Barcode:</label>
							<div class="col-lg-4">
								<input id="no_barcode" name="no_barcode" type="text" class="form-control m-input" placeholder="Masukkan Nomor Barcode">
							</div>
							<div>
								<a href="javascript:void(0)" class="btn btn-metal m-btn m-btn--icon m-btn--icon-only m-btn--pill" onclick="get_barcode()">
									<i class="fa flaticon-refresh"></i>
								</a>
							</div>
							<div>
								<span class="m-form__help" style="font-size:8pt;">Klik Untuk Mendapatkan Barcode</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Inventaris:</label>
							<div class="col-lg-4">
								<input name="no_inv" type="text" class="form-control m-input" placeholder="Masukkan Nomor Inventaris">
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tgl. Inventaris:
							</label>
							<div class="col-lg-4">
								<input name="tgl_inv" type="date" class="form-control m-input" placeholder="Masukkan  Tangga Inventaris">
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Asal Buku:
							</label>
							<div class="col-lg-4">
								<select class="form-control" name="asal">
									<?php
									$x = 0;
									foreach ($data['asal_buku'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Keterangan:
							</label>
							<div class="col-lg-4">
								<!-- <input name="ket" type="text" class="form-control m-input" placeholder="Keterangan"> -->
								<textarea class="form-control m-input" name="ket2" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 140px;"></textarea>
								<!-- <textarea name="ket" type="text" rows="8" cols="80"></textarea> -->
							</div>
						</div>
						<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
							<div class="m-form__actions m-form__actions--solid">
								<div class="row">
									<div class="col-lg-2"></div>
									<div class="col-lg-6">
										<button type="submit" class="btn btn-success">
											Submit
										</button>
										<button type="reset" class="btn btn-secondary">
											Cancel
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!--end::Form-->
					<!--begin::Form-->
					<form class="m-form m-form--fit m-form--label-align-right collapse panel-collapse" id="edit_inv">
						<div class="m--space-10"></div>
						<input name="no_klas2" type="hidden" value="<?php echo $no_klas; ?>">
						<input name="isbn2" type="hidden" value="<?php echo $isbn; ?>">
						<input name="status2" type="hidden" value="A">

						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Barcode:</label>
							<div class="col-lg-4">
								<input id="no_barcode2" name="no_barcode2" type="text" class="form-control m-input" placeholder="Masukkan Nomor Barcode">
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Inventaris:</label>
							<div class="col-lg-4">
								<input name="no_inv2" type="text" class="form-control m-input" placeholder="Masukkan Nomor Inventaris">
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tgl. Inventaris:
							</label>
							<div class="col-lg-4">
								<input name="tgl_inv2" type="date" class="form-control m-input" placeholder="Masukkan  Tangga Inventaris">
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Asal Buku:
							</label>
							<div class="col-lg-4">
								<select class="form-control" name="asal2">
									<?php
									$x = 0;
									foreach ($data['asal_buku'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Keterangan:
							</label>
							<div class="col-lg-4">
								<textarea class="form-control m-input" name="ket2" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 140px;"></textarea>
							</div>
						</div>
						<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
							<div class="m-form__actions m-form__actions--solid">
								<div class="row">
									<div class="col-lg-2"></div>
									<div class="col-lg-6">
										<button type="submit" class="btn btn-success">
											Submit
										</button>
										<button type="reset" class="btn btn-secondary">
											Cancel
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!--end::Form-->
				</div>
			</div>
		<?php }
		if ($page_action == 'tambah') { ?>
			<script type="text/javascript">
				var page_action = 'tambah'
			</script>
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								<?php echo $page_title; ?>
							</h3>
						</div>
					</div>
				</div>
				<form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit" method="post" enctype="multipart/form-data">
					<div class="m-portlet__body">
						<!-- Begin alert -->
						<div class="m-form__content">
							<div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_1_msg">
								<div class="m-alert__icon">
									<i class="la la-warning"></i>
								</div>
								<div class="m-alert__text">
									Please Insert The Empty Field.
								</div>
								<div class="m-alert__close">
									<button type="button" class="close" data-close="alert" aria-label="Close"></button>
								</div>
							</div>
						</div>
						<!-- End alert -->
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Nomor Klasifikasi:
							</label>
							<div class="col-lg-3">
								<input type="text" name="no_klas" class="form-control m-input" placeholder="Nomor Klasifikasi" required>
								<span class="m-form__help">
									Masukkan Nomor Klasifikasi
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								ISBN/ISSN :
							</label>
							<div class="col-lg-3">
								<input type="text" name="isbn" class="id_isbn form-control m-input" placeholder="ISBN/ISSN" required>
								<div class="m--space-5"></div>
								<button type="button" class="btn" onclick="cek_isbn(isbn.value)">Check It ? <i class="la la-files-o"></i></button>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Kelompok Buku:
							</label>
							<div class="col-lg-3">
								<select class="form-control" name="kategori_buku" id="ktg" onchange="kti()">
									<?php
									$x = 0;
									foreach ($data['kel_buku'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->idkategori ?>"><?php echo $i->nmkategori ?></option>
									<?php } ?>
								</select>
								<span class="m-form__help">
									Masukkan Kelompok Buku
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Judul Buku:
							</label>
							<div class="col-lg-9">
								<input required type="text" name="judul" class="form-control m-input" placeholder="Masukkan Judul Buku" onkeyup="pengalInputIni(this.value,'cetakkatalog_judulpenggal')">
								<span class="m-form__help">
									Masukkan Judul Buku
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penggalan Judul Katalog:
							</label>
							<div class="col-lg-9">
								<input type="text" id="cetakkatalog_judulpenggal" name="cetakkatalog_judulpenggal" class="form-control m-input" placeholder="Masukkan Penggalan Judul Katalog">
								<span class="m-form__help">
									Pengalan Judul Katalog
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Judul Asli:
							</label>
							<div class="col-lg-9">
								<input type="text" name="judulasli" class="form-control m-input" placeholder="Masukkan Judul Asli">
								<span class="m-form__help">
									Masukkan Judul Asli
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Deskripsi Buku:
							</label>
							<div class="col-lg-6">
								<label class="m-checkbox m-checkbox--solid">
									<input type="checkbox" name="allow_review">Perbolehkan Komentar Publik
									<span></span>
								</label>
								<textarea name="deskripsi" class="form-control" rows="6" placeholder="Masukkan Deskripsi Buku"></textarea>
								<span class="m-form__help">
									Deskripsi Buku Boleh Dikosongkan
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penulis:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penulis" class="form-control m-input" placeholder="Penulis" required>
								<span class="m-form__help">
									Masukkan Penulis
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Penyadur :
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyadur" class="form-control m-input" placeholder="Masukkan Penyadur" required>
								<span class="m-form__help">
									Masukkan Penyadur
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penerjemah:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penerjemah" class="form-control m-input" placeholder="Masukkan Penerjemah">
								<span class="m-form__help">
									Masukkan Penerjemah
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Penyusun:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyusun" class="form-control m-input" placeholder="Masukkan Penyusun">
								<span class="m-form__help">
									Masukkan Penyusun
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penyunting:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyunting" class="form-control m-input" placeholder="Masukkan Penyunting">
								<span class="m-form__help">
									Masukkan Penyunting
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Illustrator:
							</label>
							<div class="col-lg-3">
								<input type="text" name="illustrator" class="form-control m-input" placeholder="Illustrator">
								<span class="m-form__help">
									Masukkan Illustrator
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Editor:
							</label>
							<div class="col-lg-3">
								<input type="text" name="editor" class="form-control m-input" placeholder="Editor">
								<span class="m-form__help">
									Masukkan Editor
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Edisi :
							</label>
							<div class="col-lg-2">
								<input type="text" name="edisi" class="form-control m-input" placeholder="Edisi">
								<span class="m-form__help">
									Masukkan Edisi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Cetakan :
							</label>
							<div class="col-lg-2">
								<input type="text" name="cetakan" class="form-control m-input" placeholder="Cetakan">
								<span class="m-form__help">
									Masukkan Cetakan
								</span>
							</div>
						</div>
						<div id="penerbit" class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penerbit :
							</label>
							<div class="col-lg-3">
								<select class="form-control m-input--fixed" name="penerbit" required>
									<?php
									$x = 0;
									foreach ($data['penerbit'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->kd_penerbit ?>"><?php echo $i->nama_penerbit ?></option>
									<?php } ?>
								</select>
								<div class="m--space-5"></div>
								<button type="button" class="btn " onclick="tambah_penerbit()">Tambah Penerbit <i class="la la-files-o"></i></button>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tahun Terbit :
							</label>
							<div class="col-lg-2">
								<input type="text" name="thn_terbit" class="form-control m-input" placeholder="Tahun Terbit" required>
								<span class="m-form__help">
									Masukkan Tahun Terbit
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Jilid :
							</label>
							<div class="col-lg-2">
								<input type="text" name="jilid" class="form-control m-input" placeholder="Jilid">
								<span class="m-form__help">
									Masukkan Jilid
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Hal. Romawi :
							</label>
							<div class="col-lg-2">
								<input type="text" name="hlm_romawi" class="form-control m-input">
								<span class="m-form__help">
									Masukkan Nomor Halaman Romawi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Jumlah Halaman :
							</label>
							<div class="col-lg-2">
								<input type="number" name="jml_hal" class="form-control m-input" required>
								<span class="m-form__help">
									Masukkan Jumlah Halaman
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Ilustrasi :
							</label>
							<div class="col-lg-2">
								<label class="m-checkbox m-checkbox--solid">
									<input name="ilustrasi" type="checkbox" value="1">Tandai jika ada ilustrasi
									<span></span>
								</label>
							</div>
							<label class="col-lg-2 col-form-label">
								Tabel :
							</label>
							<div class="col-lg-2">
								<label class="m-checkbox m-checkbox--solid">
									<input name="tabel" type="checkbox" value="1">Tandai jika ada tabel
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Ukuran Fisik :
							</label>
							<div class="col-lg-2">
								<input type="number" name="ukuran_fisik" class="form-control m-input" required>
								<span class="m-form__help">
									Masukkan Ukuran Fisik (cm)
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Bibliografi :
							</label>
							<div class="col-lg-3">
								<input type="text" name="bibliografi" class="form-control m-input" placeholder="Bibliografi">
								<span class="m-form__help">
									Masukkan Bibliografi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Index :
							</label>
							<div class="col-lg-3">
								<input type="text" name="indeks" class="form-control m-input" placeholder="Index">
								<span class="m-form__help">
									Masukkan Index
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Bahasa :
							</label>
							<div class="col-lg-3">
								<select class="form-control m-input--fixed" name="bahasa">
									<?php
									$x = 0;
									foreach ($data['bahasa'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
									<?php } ?>
								</select>

							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Rak :
							</label>
							<div class="col-lg-2">
								<input type="text" name="no_rak" class="form-control m-input">
								<span class="m-form__help">
									Masukkan No. Rak
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Seri :
							</label>
							<div class="col-lg-5">
								<input type="text" name="seri" class="form-control m-input" placeholder="Seri">
								<span class="m-form__help">
									Masukkan Seri
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tajuk Utama :
							</label>
							<div class="col-lg-5">
								<input type="text" name="tajuk" class="form-control m-input" placeholder="Tajuk Utama ">
								<span class="m-form__help">
									Masukkan Tajuk Utama
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tajuk Subyek :
							</label>
							<div class="col-lg-5">
								<input type="text" name="tajuksubyek" class="form-control m-input" placeholder="Tajuk Subyek" required>
								<span class="m-form__help">
									Masukkan Tajuk Subyek
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Mata Kuliah Terkait :
							</label>
							<div class="col-lg-5">
								<textarea name="matkul" class="form-control" rows="6" placeholder="Masukkan Mata Kuliah Terkait"></textarea>
								<span class="m-form__help">
									Masukkan Mata Kuliah Terkait
								</span>
							</div>
						</div>


						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Mata Kuliah Terkait :
							</label>
							<div class="col-lg-5">
								<select class="form-control m-select2" id="m_select2_3" name="prodi[]" multiple="multiple">
									<?php
									$x = 0;
									foreach ($data['prodi'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->idmspst ?>"><?php echo $i->nmmspst ?></option>
									<?php } ?>
								</select>
							</div>
						</div>




						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Upload Gambar Buku :
							</label>
							<div class="col-lg-5">
								<label class="custom-file">
									<input type="file" id="gambar" name="gambar" class="custom-file-input">
									<span class="custom-file-control"></span>
								</label>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Upload File Buku :
							</label>
							<div class="col-lg-5">
								<label class="custom-file">
									<!-- <input type="file" id="file" name="file" class="filestyle" data-buttonName="btn-primary"> -->
									<input type="file" id="file" name="file" class="custom-file-input">
									<span class="custom-file-control"></span>
								</label>
							</div>
						</div>
						<div id="form_kti" class="collapse" style="display:none;">
							<!-- <div class="m-portlet m-portlet--tab"> -->
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<span class="m-portlet__head-icon m--hide">
											<i class="la la-gear"></i>
										</span>
										<h3 class="m-portlet__head-text">
											Khusus Pustaka Skrispi/Tugas Akhir
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										NIM:
									</label>
									<div class="col-lg-5">
										<label class="custom-file">
											<input type="text" name="nis_ta" class="form-control m-input">
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Tabel TA:
									</label>
									<div class="col-lg-2">
										<label class="m-checkbox m-checkbox--solid">
											<input name="tabel_ta" type="checkbox" value="1">Tandai jika ada tabel
											<span></span>
										</label>
									</div>
									<label class="col-lg-2 col-form-label">
										Lampiran TA:
									</label>
									<div class="col-lg-2">
										<label class="m-checkbox m-checkbox--solid">
											<input name="lampiran_ta" type="checkbox" value="1">Tandai jika ada Lampiran
											<span></span>
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Dosen Pembimbing:
									</label>
									<div class="col-lg-5">
										<label class="custom-file">
											<input id="pembimbing_ta" type="text" name="pembimbing_ta" class="form-control m-input">
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Example textarea
									</label>
									<div class="col-lg-5">
										<textarea class="form-control m-input" id="abstrak" rows="10"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="m-portlet__foot m-portlet__foot--fit">
						<div class="m-form__actions m-form__actions">
							<div class="row">
								<div class="col-lg-9 ml-lg-auto">
									<button type="submit" class="btn btn-success">
										Submit
									</button>
									<button type="reset" class="btn btn-secondary">
										Cancel
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		<?php }
		if ($page_action == 'edit') { ?>
			<script type="text/javascript">
				var page_action = 'edit'
			</script>
			<div class="m-portlet">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								<?php echo $page_title; ?>
							</h3>
						</div>
					</div>
				</div>
				<!--begin::Form-->
				<form class="m-form m-form--fit m-form--label-align-right" id="m_form_2">
					<div class="m-portlet__body">
						<!-- Begin alert -->
						<div class="m-form__content">
							<div class="m-alert m-alert--icon alert alert-danger m--hide" role="alert" id="m_form_2_msg">
								<div class="m-alert__icon">
									<i class="la la-warning"></i>
								</div>
								<div class="m-alert__text">
									Please Insert The Empty Field.
								</div>
								<div class="m-alert__close">
									<button type="button" class="close" data-close="alert" aria-label="Close"></button>
								</div>
							</div>
						</div>
						<!-- End alert -->
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Nomor Klasifikasi:
							</label>
							<div class="col-lg-3">
								<input type="text" name="no_klas" class="form-control m-input" placeholder="Nomor Klasifikasi" value="<?php echo $data[0]->no_klas ?>">
								<span class="m-form__help">
									Masukkan Nomor Klasifikasi
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								ISBN/ISSN :
							</label>
							<div class="col-lg-3">
								<input type="hidden" id="isbn" value="<?php echo $data[0]->ISBN ?>">
								<input type="text" id="isbn2" name="isbn" class="id_isbn form-control m-input" placeholder="ISBN/ISSN" value="<?php echo $data[0]->ISBN ?>">
								<div class="m--space-5"></div>
								<button type="button" class="btn" onclick="cek_isbn(isbn2.value)">Check It ? <i class="la la-files-o"></i></button>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Kelompok Buku:
							</label>
							<div class="col-lg-3">
								<select class="form-control" name="kategori_buku" id="ktg" onchange="kti()">
									<?php
									$x = 0;
									foreach ($data['kel_buku'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->idkategori ?>" <?php if ($data[0]->idkategori == $i->idkategori) echo "selected" ?>><?php echo $i->nmkategori ?></option>
									<?php } ?>
								</select>
								<span class="m-form__help">
									Masukkan Kelompok Buku
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Judul Buku:
							</label>
							<div class="col-lg-9">
								<input type="text" name="judul" class="form-control m-input" placeholder="Masukkan Judul Buku" value="<?php echo $data[0]->judul ?>" onkeyup="pengalInputIni(this.value,'cetakkatalog_judulpenggal')">
								<span class="m-form__help">
									Masukkan Judul Buku
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penggalan Judul Katalog:
							</label>
							<div class="col-lg-9">
								<input type="text" id="cetakkatalog_judulpenggal" value="<?php echo $data[0]->cetakkatalog_judulpenggal ?>" name="cetakkatalog_judulpenggal" class="form-control m-input" placeholder="Masukkan Penggalan Judul Katalog">
								<span class="m-form__help">
									Pengalan Judul Katalog
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Judul Asli:
							</label>
							<div class="col-lg-9">
								<input type="text" name="judulasli" class="form-control m-input" placeholder="Masukkan Judul Asli" value="<?php echo $data[0]->judulasli ?>">
								<span class="m-form__help">
									Masukkan Judul Asli
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Deskripsi Buku:
							</label>
							<div class="col-lg-6">
								<label class="m-checkbox m-checkbox--solid">
									<input type="checkbox" name="allow_review" <?php if ($data[0]->allow_review == 1) echo 'checked'; ?>>Perbolehkan Komentar Publik
									<span></span>
								</label>
								<textarea name="deskripsi" class="form-control" rows="6" placeholder="Masukkan Deskripsi Buku" value=<?php echo $data[0]->deskripsi; ?>></textarea>
								<span class="m-form__help">
									Deskripsi Buku Boleh Dikosongkan
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penulis:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penulis" class="form-control m-input" placeholder="Penulis" value="<?php echo $data[0]->penulis ?>">
								<span class="m-form__help">
									Masukkan Penulis
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Penyadur :
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyadur" class="form-control m-input" placeholder="Masukkan Penyadur" value="<?php echo $data[0]->penyadur ?>">
								<span class="m-form__help">
									Masukkan Penyadur
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penerjemah:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penerjemah" class="form-control m-input" placeholder="Masukkan Penerjemah" value="<?php echo $data[0]->penerjemah ?>">
								<span class="m-form__help">
									Masukkan Penerjemah
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Penyusun:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyusun" class="form-control m-input" placeholder="Masukkan Penyusun" value="<?php echo $data[0]->penyusun ?>">
								<span class="m-form__help">
									Masukkan Penyusun
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penyunting:
							</label>
							<div class="col-lg-3">
								<input type="text" name="penyunting" class="form-control m-input" placeholder="Masukkan Penyunting" value="<?php echo $data[0]->penyunting ?>">
								<span class="m-form__help">
									Masukkan Penyunting
								</span>
							</div>
							<label class="col-lg-2 col-form-label">
								Illustrator:
							</label>
							<div class="col-lg-3">
								<input type="text" name="illustrator" class="form-control m-input" placeholder="Illustrator" value="<?php echo $data[0]->illustrator ?>">
								<span class="m-form__help">
									Masukkan Illustrator
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Editor:
							</label>
							<div class="col-lg-3">
								<input type="text" name="editor" class="form-control m-input" placeholder="Editor" value="<?php echo $data[0]->editor ?>">
								<span class="m-form__help">
									Masukkan Editor
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Edisi :
							</label>
							<div class="col-lg-2">
								<input type="text" name="edisi" class="form-control m-input" placeholder="Edisi" value="<?php echo $data[0]->edisi ?>">
								<span class="m-form__help">
									Masukkan Edisi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Cetakan :
							</label>
							<div class="col-lg-2">
								<input type="text" name="cetakan" class="form-control m-input" placeholder="Cetakan" value="<?php echo $data[0]->cetakan ?>">
								<span class="m-form__help">
									Masukkan Cetakan
								</span>
							</div>
						</div>
						<div id="penerbit" class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Penerbit :
							</label>
							<div class="col-lg-3">
								<select class="form-control m-input--fixed" name="kd_penerbit">
									<?php
									$x = 0;
									foreach ($data['penerbit'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->kd_penerbit ?>" <?php if ($data[0]->kd_penerbit == $i->kd_penerbit) echo "selected" ?>><?php echo $i->nama_penerbit ?></option>
									<?php } ?>
								</select>
								<div class="m--space-5"></div>
								<button type="button" class="btn " onclick="tambah_penerbit()">Tambah Penerbit <i class="la la-files-o"></i></button>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tahun Terbit :
							</label>
							<div class="col-lg-2">
								<input type="number" name="thn_terbit" class="form-control m-input" placeholder="Tahun Terbit" value="<?php echo $data[0]->thn_terbit ?>">
								<span class="m-form__help">
									Masukkan Tahun Terbit
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Jilid :
							</label>
							<div class="col-lg-2">
								<input type="text" name="jilid" class="form-control m-input" placeholder="Jilid" value="<?php echo $data[0]->jilid ?>">
								<span class="m-form__help">
									Masukkan Jilid
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Hal. Romawi :
							</label>
							<div class="col-lg-2">
								<input type="text" name="hlm_romawi" class="form-control m-input" value="<?php echo $data[0]->hlm_romawi ?>">
								<span class="m-form__help">
									Masukkan Nomor Halaman Romawi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Jumlah Halaman :
							</label>
							<div class="col-lg-2">
								<input type="number" name="jml_hal" class="form-control m-input" value="<?php echo $data[0]->jml_hal ?>">
								<span class="m-form__help">
									Masukkan Jumlah Halaman
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Ilustrasi :
							</label>
							<div class="col-lg-2">
								<label class="m-checkbox m-checkbox--solid">
									<input name="ilustrasi" type="checkbox" value="1" <?php if ($data[0]->ilustrasi == 1) echo "checked"; ?>>Tandai jika ada ilustrasi
									<span></span>
								</label>
							</div>
							<label class="col-lg-2 col-form-label">
								Tabel :
							</label>
							<div class="col-lg-2">
								<label class="m-checkbox m-checkbox--solid">
									<input name="tabel" type="checkbox" value="1" <?php if ($data[0]->tabel == 1) echo "checked"; ?>>Tandai jika ada tabel
									<span></span>
								</label>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Ukuran Fisik :
							</label>
							<div class="col-lg-2">
								<input type="number" name="ukuran_fisik" class="form-control m-input" value="<?php echo $data[0]->ukuran_fisik ?>">
								<span class="m-form__help">
									Masukkan Ukuran Fisik (cm)
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Bibliografi :
							</label>
							<div class="col-lg-3">
								<input type="text" name="bibliografi" class="form-control m-input" placeholder="Bibliografi" value="<?php echo $data[0]->bibliografi ?>">
								<span class="m-form__help">
									Masukkan Bibliografi
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Index :
							</label>
							<div class="col-lg-3">
								<input type="text" name="indeks" class="form-control m-input" placeholder="Index" value="<?php echo $data[0]->indeks ?>">
								<span class="m-form__help">
									Masukkan Index
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Bahasa :
							</label>
							<div class="col-lg-3">
								<select class="form-control m-input--fixed" name="bahasa">
									<?php
									$x = 0;
									foreach ($data['bahasa'] as $i) {
										$x++; ?>
										<option value="<?php echo $i->id ?>" <?php if ($data[0]->bahasa == $i->id) echo "selected" ?>><?php echo $i->nama ?></option>
									<?php } ?>
								</select>

							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								No. Rak :
							</label>
							<div class="col-lg-2">
								<input type="text" name="no_rak" class="form-control m-input" value="<?php echo $data[0]->no_rak ?>">
								<span class="m-form__help">
									Masukkan No. Rak
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Seri :
							</label>
							<div class="col-lg-5">
								<input type="text" name="seri" class="form-control m-input" placeholder="Seri" value="<?php echo $data[0]->seri ?>">
								<span class="m-form__help">
									Masukkan Seri
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tajuk Utama :
							</label>
							<div class="col-lg-5">
								<input type="text" name="tajuk" class="form-control m-input" placeholder="Tajuk Utama " value="<?php echo $data[0]->tajuk ?>">
								<span class="m-form__help">
									Masukkan Tajuk Utama
								</span>
							</div>
						</div>
						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Tajuk Subyek :
							</label>
							<div class="col-lg-5">
								<input type="text" name="tajuksubyek" class="form-control m-input" placeholder="Tajuk Subyek" value="<?php echo $data[0]->tajuksubyek ?>">
								<span class="m-form__help">
									Masukkan Tajuk Subyek
								</span>
							</div>
						</div>







						<div class="form-group m-form__group row">
							<label class="col-lg-2 col-form-label">
								Mata Kuliah Terkait :
							</label>
							<div class="col-lg-5">
								<textarea name="matkul" class="form-control" rows="6" placeholder="Masukkan Mata Kuliah Terkait"></textarea>
								<span class="m-form__help">
									Masukkan Mata Kuliah Terkait
								</span>
							</div>
						</div>








						<div id="form_kti" class="collapse" style="display:none;">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<span class="m-portlet__head-icon m--hide">
											<i class="la la-gear"></i>
										</span>
										<h3 class="m-portlet__head-text">
											Khusus Pustaka Skrispi/Tugas Akhir
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										NIM:
									</label>
									<div class="col-lg-5">
										<label class="custom-file">
											<input type="text" name="nis_ta" class="form-control m-input">
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Tabel TA:
									</label>
									<div class="col-lg-2">
										<label class="m-checkbox m-checkbox--solid">
											<input name="tabel_ta" type="checkbox" value="1">Tandai jika ada tabel
											<span></span>
										</label>
									</div>
									<label class="col-lg-2 col-form-label">
										Lampiran TA:
									</label>
									<div class="col-lg-2">
										<label class="m-checkbox m-checkbox--solid">
											<input name="lampiran_ta" type="checkbox" value="1">Tandai jika ada Lampiran
											<span></span>
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Dosen Pembimbing:
									</label>
									<div class="col-lg-5">
										<label class="custom-file">
											<input id="pembimbing_ta" type="text" name="pembimbing_ta" class="form-control m-input">
										</label>
									</div>
								</div>
								<div class="form-group m-form__group row">
									<label class="col-lg-2 col-form-label">
										Example textarea
									</label>
									<div class="col-lg-5">
										<textarea class="form-control m-input" id="abstrak" rows="10"></textarea>
									</div>
								</div>
							</div>
						</div>



					</div>
					<div class="m-portlet__foot m-portlet__foot--fit">
						<div class="m-form__actions m-form__actions">
							<div class="row">
								<div class="col-lg-9 ml-lg-auto">
									<button type="submit" class="btn btn-success">
										Submit
									</button>
									<button type="reset" class="btn btn-secondary">
										Cancel
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<!--end::Form-->
			</div>
		<?php }
		if ($page_action == 'ubah_rak') { ?>
			<script type="text/javascript">
				var page_action = 'ubah_rak';
			</script>
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								<?php echo $page_title; ?>
								<small>
									<i class="glyphicon glyphicon-refresh"></i>
									<button style="display:none" class="btn btn-default" id="m_datatable_reload">Reload</button>
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
										<select class="form-control m-bootstrap-select" id="m_form_klas">
											<option value="">Pilih Klasifikasi</option>
											<?php if ($klasifikasi) { ?>
												<?php foreach ($klasifikasi as $row) { ?>
													<option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?> </option>
												<?php } ?>
											<?php } ?>
										</select>
										<div class="d-md-none m--margin-bottom-10"></div>
									</div>
									<div class="col-md-4">
										<select class="form-control m-bootstrap-select" id="m_form_kel">
											<option value="">Pilih Kategori</option>
											<?php if ($kategori) { ?>
												<?php foreach ($kategori as $row) { ?>
													<option value="<?php echo $row->idkategori; ?>"><?php echo $row->nmkategori; ?> </option>
												<?php } ?>
											<?php } ?>
										</select>
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
								<div class="m-separator m-separator--dashed d-xl-none"></div>
								<a href="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/tambah/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
									<span>
										<i class="flaticon-add"></i>
										<span>
											Tambah
										</span>
									</span>
								</a>
							</div>
						</div>
					</div>
					<!--begin: Selected Rows Group Action Form -->
					<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
						<div class="row align-items-center">
							<div class="col-xl-12">
								<div class="m-form__group m-form__group--inline">
									<div class="m-form__label m-form__label-no-wrap">
										<label class="m--font-bold m--font-danger-">
											Selected
											<span id="m_datatable_selected_number"></span>
											records:
										</label>
									</div>
									<div class="m-form__control">
										<div class="btn-toolbar">
											<button id="cetak_katalog" type="button" class="btn btn-accent btn-sm">
												Cetak Katalog
											</button>
											&nbsp;&nbsp;&nbsp;
											<button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
												Cetak Barcode
											</button>
											&nbsp;&nbsp;&nbsp;
											<button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
												Cetak Call Number
											</button>
											&nbsp;&nbsp;&nbsp;
											<button class="btn btn-sm btn-accent" type="button">
												Set Buku Diarsipkan
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Selected Rows Group Action Form -->
					<form class="m-form m-form--fit m-form--label-align-right" id="form_ubah">
						<div class="table_ubah_rak" id="table_ubah_rak">
							<!-- table here -->
							<div class="m-portlet__foot m-portlet__foot--fit">
								<div class="m-form__actions">
									<button type="button" class="btn btn-primary" id="buttin_id">
										Submit
									</button>
									<button type="reset" class="btn btn-secondary">
										Reset
									</button>
								</div>
							</div>
					</form>
					<!-- Here is Data Table Begin -->
				</div>
			</div>
	</div>
</div>
</div>
<?php } ?>
<?php if ($page_action == "view") : ?>
	<script type="text/javascript">
		var page_action = 'view';
		var page = 'view';
	</script>
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						<?php echo $page_title; ?>
					</h3>
				</div>
			</div>
		</div>
		<div class="m-portlet__body">
			<!--begin::Section-->
			<div class="m-section">
				<div class="m-section__content">
					<table id="view_buku" class="table table-hover">
						<tbody>
							<tr>
								<td style="width:20% text-align:right">Judul Buku:</td>
								<td><?php echo $data[0]['judul']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Tajuk:</th>
								<td><?php echo $data[0]['tajuksubyek']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">No. Klasifikasi:</th>
								<td><?php echo $data[0]['no_klas']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Penulis:</th>
								<td><?php echo $data[0]['penulis']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Edisi:</th>
								<td><?php echo $data[0]['edisi']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Cetakan:</th>
								<td><?php echo $data[0]['cetakan']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Penerbit:</th>
								<td><?php echo $data[0]['nama_penerbit']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Kota Terbit:</th>
								<td><?php echo $data[0]['kota']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Bahasa:</th>
								<td><?php
									if ($data[0]['bahasa'] == 'I') {
										$data[0]['bahasa'] = "Bahasa Indonesia";
									} else if ($data[0]['bahasa'] == 'A') {
										$data[0]['bahasa'] = "Bahasa Inggris";
									} else if ($data[0]['bahasa'] = "S") {
										$data[0]['bahasa'] = "Bahasa Sunda";
									} else {
										$data[0]['bahasa'] = "Bahasa Lainnya";
									}
									echo $data[0]['bahasa'];
									?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">ISBN:</th>
								<td><?php echo $data[0]['ISBN']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Jumlah Halaman:</th>
								<td><?php echo $data[0]['jml_hal']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Ukuran Fisik:</th>
								<td><?php echo $data[0]['ukuran_fisik']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Jumlah Stok Buku:</th>
								<td><?php echo $data[0]['jml_buku']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Jumlah Buku Dipinjam:</th>
								<td><?php ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Nomor Rak:</th>
								<td><?php echo $data[0]['no_rak']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Referensi Matakuliah</th>
								<td><?php ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Tanggal Input:</th>
								<td><?php echo $data[0]['tanggal']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Komentar Review:</th>
								<td><?php
									if ($data[0]['review'] == 0) {
										$data[0]['review'] = "Tidak Ada Review";
									} else {
										$data[0]['review'] = "Ada Review";
									}
									echo $data[0]['review']; ?></td>
							</tr>
							<tr>
								<th scope="row" style="width:20%" align="right">Deskripsi:</th>
								<td><?php echo $data[0]['deskripsi']; ?></td>
							</tr>

						</tbody>
					</table>
				</div>
			</div>
			<!--end::Section-->
		</div>
		<!--end::Form-->
	</div>
	</div>
	</div>
<?php endif; ?>
<!-- end:: Body -->
<script type="text/javascript">
	var save_method; //for save method string
	var table;
	var barcode = "<?php echo base_url(); ?>assets";
	var base_site = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>";
	var submit = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit/";
	var update = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update/";
	var tambah_inv = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/tambah_inv/";
	var edit_site_inv = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/edit_inv/";
	var site = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/list/";
	var simpan_ubah_rak = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/simpan_ubah_rak/";

	$("#button_tambah").click(function() {
		$("#tambah_inv").slideToggle(300, function() {
			$("#head").text(function() {
				return $("#tambah_inv").is(":visible") ? "Tambah Inventaris" : "Inventaris";
			});
		});
	});
	if (page_action == 'set_inv') {
		var idisbn = document.getElementById('isbn').value;
		var no_klas = document.getElementById('no_klas').value;
		var get_inv = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/get_inv/" + idisbn + "/" + no_klas;
	}
	if (page_action == 'edit' || page_action == 'tambah') {
		function kti() {
			var ktg = document.getElementById('ktg');
			if (ktg.value != '') {
				if (ktg.value == 3) {
					document.getElementById('form_kti').style.display = 'block';
				} else {
					document.getElementById('form_kti').style.display = 'none';
				}
			}
		}
		kti();
	}

	function edit_inv_page(isbn, no_klas) {
		url = base_site + "/edit/" + isbn + "/" + no_klas;
		window.location.href = url;
	}

	function edit_inv(barcode) {
		$("#edit_inv").slideToggle(300, function() {
			$("#head").text(function() {
				return $("#edit_inv").is(":visible") ? "Edit Inventaris" : "Inventaris";
			});
		});
		$('#edit_inv')[0].reset();
		// alert(barcode)
		// return;
		$.ajax({
			url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/get_data_inv/" + barcode,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('[name="no_barcode2"]').val(data[0].no_barcode);
				$('[name="no_inv2"]').val(data[0].no_inv);
				$('[name="tgl_inv2"]').val(data[0].tgl_inv);
				$('[name="asal2"]').val(data[0].asal);
				$('[name="ket2"]').val(data[0].ket);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function pengalInputIni(id1, id2) {
		document.getElementById(id2).value = id1;
	}

	function cek_isbn(isbn) {
		if (isbn == '') {
			swal({
				title: 'Mohon Isi ISBN',
				type: 'warning',
				showConfirmButton: false,
				timer: 1000
			});
			return;
		}
		var isbn = isbn.split('/').join('_');
		var cek = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/cek/" + isbn;
		$.ajax({
			url: cek,
			type: "POST",
			data: $('.id_isbn').serialize(),
			dataType: "JSON",
			success: function(data) {
				if (data.status == true) { // if data is already exist
					swal({
						title: 'Oops..!! Data Sudah Ada',
						type: 'warning',
						showConfirmButton: false,
						timer: 1000
					});
				} else {
					swal({
						title: 'Dapat Digunakan',
						type: 'info',
						showConfirmButton: false,
						timer: 1000
					});
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {

			}
		});
	}

	function view(id, no_klas) {
		var id1 = id.split('/').join('_');
		var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/get/" + id1 + "/" + no_klas;
		$.ajax({
			url: url,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#ISBN').text(data[0].ISBN);
				$('#judul').text(data[0].judul);
				$('#jml_buku').text(data[0].jml_buku);
				$('#penulis').text(data[0].penulis);
				$('#tajuksubyek').text(data[0].tajuksubyek);
				$('#no_klas').text(data[0].no_klas);
				$('#edisi').text(data[0].edisi);
				$('#cetakan').text(data[0].cetakan);
				$('#penerbit').text(data[0].nama_penerbit);
				$('#kota').text(data[0].kota);
				$('#thn_terbit').text(data[0].thn_terbit);
				if (data[0].bahasa == 'I') {
					data[0].bahasa = "Bahasa Indonesia"
				} else if (data[0].bahasa == 'A') {
					data[0].bahasa = "Bahasa Inggris"
				} else if (data[0].bahasa = "S") {
					data[0].bahasa = "Bahasa Sunda"
				} else {
					data[0].bahasa = "Bahasa Lainnya"
				}
				$('#bahasa').text(data[0].bahasa);
				$('#jml_hal').text(data[0].jml_hal);
				$('#ukuran_fisik').text(data[0].ukuran_fisik);
				$('#dipinjam').text(data[0].dipinjam);
				$('#no_rak').text(data[0].no_rak);
				$('#deskripsi').text(data[0].deskripsi);
				$('#tanggal').text(data[0].tanggal);
				if (data[0].review == 0) {
					data[0].review = "-"
				}
				$('#review').text(data[0].review);
				$('#matakuliah').text(data[0].matakuliah);
				$('#m_Modal').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Lihat Data Buku'); // Set title to Bootstrap modal title
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function delete_buku(id, no_klas) {
		if (confirm('Are you sure delete this data?')) {
			// ajax delete data to database
			var id1 = id.split('/').join('_');
			var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hapus/" + id1 + "/" + no_klas;
			$.ajax({
				url: url,
				type: "POST",
				dataType: "JSON",
				success: function(data) {
					if (data.status = 'TRUE') {
						swal({
							title: 'Success',
							text: 'Data Buku Berhasil Dihapus',
							type: 'success',
							showConfirmButton: false,
							timer: 1500
						});
						reload_table();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					swal({
						title: 'Warning',
						text: 'Data Buku Tidak Berhasil Dihapus',
						type: 'warning',
						showConfirmButton: false,
						timer: 1500
					});
				}
			});
		}
	}

	function delete_inv(barcode, no_klas, isbn) {
		if (confirm('Are you sure delete this data?')) {
			// ajax delete data to database
			isbn = isbn.split('/').join('_');
			var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hapus_inv/" + isbn + "/" + no_klas + "/" + barcode;
			$.ajax({
				url: url,
				type: "POST",
				dataType: "JSON",
				success: function(data) {
					if (data.status = 'TRUE') {
						swal({
							title: 'Success',
							text: 'Data Inventaris Berhasil Dihapus',
							type: 'success',
							showConfirmButton: false,
							timer: 1500
						});
						reload_table();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					swal({
						title: 'Warning',
						text: 'Data Inventaris Tidak Berhasil Dihapus',
						type: 'warning',
						showConfirmButton: false,
						timer: 1500
					});
				}
			});
		}
	}

	function reload_table() {
		$("#m_datatable_reload").trigger("click");
	}

	function tambah_penerbit() {
		$('#form_penerbit')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		$('#modal_form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Tambah Penerbit'); // Set Title to Bootstrap modal title
	}

	function save() {
		$('#btnSave').text('saving...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable
		var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/penerbit/submit/";
		var div = $('#penerbit').html();
		// ajax adding data to database
		$.ajax({
			url: url,
			type: "POST",
			data: $('#form_penerbit').serialize(),
			dataType: "JSON",
			success: function(data) {
				if (data.status) //if success close modal and reload ajax table
				{
					if (data.status = 'TRUE') {
						if (data.hasil == 0) {
							toastr.options = {
								"positionClass": "toast-top-right",
								"preventDuplicates": false,
								"onclick": null,
								"showDuration": "300",
								"hideDuration": "1000",
								"timeOut": "5000",
								"extendedTimeOut": "1000",
								"showEasing": "swing",
								"hideEasing": "linear",
								"showMethod": "fadeIn",
								"hideMethod": "fadeOut"
							};
							toastr.warning("Empty Field");
						} else if (data.hasil == 1) {
							toastr.options = {
								"positionClass": "toast-top-right",
								"preventDuplicates": false,
								"onclick": null,
								"showDuration": "300",
								"hideDuration": "1000",
								"timeOut": "5000",
								"extendedTimeOut": "1000",
								"showEasing": "swing",
								"hideEasing": "linear",
								"showMethod": "fadeIn",
								"hideMethod": "fadeOut"
							};
							toastr.warning("Data Sudah Ada");
						} else if (data.hasil == 2) {
							swal({
								title: 'Success',
								text: 'Data Buku Berhasil Dibuat',
								type: 'success',
								showConfirmButton: false,
								timer: 1500
							});
							$('#penerbit').load(location.href + " #penerbit");
						}
						$('#modal_form').modal('hide');
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
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable
			}
		});
	}

	function get_barcode() {
		var url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/get_barcode/";
		$.ajax({
			url: url,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$("input[name='no_barcode']").val(data.barcode);
				// document.getElementsByName('no_barcode').value=data.barcode;
			},
			error: function(jqXHR, textStatus, errorThrown) {

			}
		})
	}
</script>
<?php if ($page_action == 'edit' || $page_action == 'set_inv') { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/validasi.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<?php if ($page_action == 'list' || $page_action == 'set_inv' || $page_action == 'ubah_rak') { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/data-table.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>



<!--begin::Modal Tambah Penerbit-->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="labelModalTambah">
					<i class="m-menu__link-icon flaticon-add"></i> Tambah Penerbit
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_penerbit" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
					<div class="form-group m-form__group">
						<input type="hidden" name="id1">
						<label for="">
							Penerbit:
						</label>
						<input type="text" id="nama_penerbit" name="nama_penerbit" class="form-control m-input" placeholder="Masukkan Penerbit">
						<span class="m-form__help">
							Penerbit
						</span>
					</div>
					<div class="form-group m-form__group">
						<label for="">
							Kota :
						</label>
						<input type="text" id="kota" name="kota" class="form-control m-input" placeholder="Masukkan Penerbit">
						<span class="m-form__help">
							Kota
						</span>
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