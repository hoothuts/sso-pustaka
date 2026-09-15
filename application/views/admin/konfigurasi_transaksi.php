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
						
						<?php if($page_action =='list'){?>
						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											<?php echo $page_title;?>
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<!--begin: Search Form -->
								<!--begin: Datatable -->
								   <div class="m-portlet">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
												<h3 class="m-portlet__head-text">
													Konfigurasi Mahasiswa
												</h3>
											</div>
										</div>
									</div>
									<!--begin::Form-->
									<form method="post" action="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/update/" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
										<div class="m-portlet__body">
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Jumlah Maksimal Buku Dalam Sekali Peminjaman
												</label>
												<div class="col-lg-5">
													<input type="text" id="setting1" name="setting1" class="form-control m-input" value="<?php if($data)echo $data[0]->jml_buku;?>">
													<span class="m-form__help">
														
													</span>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Lama Peminjaman Buku	
												</label>
												<div class="col-lg-5">
													<input type="text" id="setting2" name="setting2" class="form-control m-input" value="<?php if($data)echo $data[0]->lama;?>">
													<span class="m-form__help">
														
													</span>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Denda Per Hari
												</label>
												<div class="col-lg-5">
													<input type="text" id="setting3" name="setting3" class="form-control m-input" value="<?php if($data)echo $data[0]->denda;?>">
													<span class="m-form__help">
														
													</span>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Jumlah Maksimal Memperpanjang Buku
												</label>
												<div class="col-lg-5">
													<input type="text" id="setting4" name="setting4" class="form-control m-input" value="<?php if($data)echo $data[0]->perpanjang;?>">
													<span class="m-form__help">
														
													</span>
												</div>
											</div>
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Masa Berlaku Kartu Anggota	
												</label>
												<div class="col-lg-5">
													<input type="text" id="setting5" name="setting5" class="form-control m-input" value="<?php if($data)echo $data[0]->masa_berlaku;?>">
													<span class="m-form__help">
														
													</span>
												</div>
											</div>
										</div>
										<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions--solid">
												<div class="row">
													<div class="col-lg-2"></div>
													<div class="col-lg-10">
													<?php if(in_array($page_name,$this->session->userdata('perm_add'))||in_array($page_name,$this->session->userdata('perm_edit'))):?>
													
														<button type="submit" class="btn btn-success">
															Simpan Konfigurasi
														</button>
													<?php endif;?>
													</div>
												</div>
											</div>
										</div>
									</form>
									<!--
									<form method="post" action="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/forceupdate/" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
										<div class="m-portlet__body">
											<div class="form-group m-form__group row">
												<label class="col-lg-5 col-form-label">
													Set Tanggal Expired Bagi Anggota
													<small>(force update date expired)</small>
												</label>
												<div class="col-lg-5">
													<input type="text" id="tgl" name="tgl" class="form-control m-input" value="">
													<span class="m-form__help">
														Ex: 25-09-2010, Jika Klik Tombol dibawah ini maka tanggal kadaluarsa mahasiswa yang aktif akan terupdate sesuai tanggal yang dimasukkan tersebut
													</span>
												</div>
											</div>
											
										</div>
										<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions--solid">
												<div class="row">
													<div class="col-lg-2"></div>
													<div class="col-lg-10">
													<?php if(in_array($page_name,$this->session->userdata('perm_edit'))):?>
														<button type="submit" class="btn btn-success">
															Force Update
														</button>
													<?php endif;?>
													</div>
												</div>
											</div>
										</div>
									</form>
									-->
									<!--end::Form-->
								</div>
								<!--end: Datatable -->
							</div>
						</div>
						<?php } ?>
						
					</div>
				</div>
			<!-- end:: Body -->