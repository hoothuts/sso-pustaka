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
						<?php if($this->session->flashdata('alert')!=''):?>
									<script>
									$( document ).ready(function() {
										setTimeout(function(){
										  document.getElementById('flashmsg').style.display = 'none';
										}, 5000);
									});

									</script>
									<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert')?> alert-dismissible fade show" id='flashmsg'>
												<div class="m-alert__icon">
													<i class="flaticon-exclamation-1"></i>
													<span></span>
												</div>
												<div class="m-alert__text">
													<?php echo $this->session->flashdata('flash_message') ?>
												</div>
											</div>
								<?php endif;?>

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
								<div class="m-portlet__head-tools">
												<a target="_blank" href="<?php echo base_url();?>assets/media/TemplateImportBuku.xlsx" class="btn btn-info" >
												<i class="fa fa-file-excel-o"></i>
												Sample Data Buku
										</a>
										<a target="_blank" href="<?php echo base_url();?>assets/media/TemplateImportInventaris.xlsx" class="btn btn-info" >
												<i class="fa fa-file-excel-o"></i>
												Sample Data Inventaris
										</a>
										</div>
							</div>
							<div class="m-portlet__body">
								<!-- <button class="btn" data-toggle="collapse" data-target="#demo">Collapsible</button>

								<div id="demo" class="collapse">
								Some text..
								</div>
								<script type="text/javascript">
									$('.collapse').collapse();
								</script> -->
								<!--begin: Search Form -->
								<!--begin: Datatable -->
									<!--begin::Form-->
									<form method="post" action="<?php echo base_url();?><?php echo $page_access;?>/<?php echo $page_name;?>/import/" class="m-form m-form--fit m-form--label-align-right"  enctype="multipart/form-data">
											<div class="form-group m-form__group row">
												<label class="col-lg-2 col-form-label" align="left">
													Import
												</label>
												<div class="col-lg-3">
													<select id="tipe" name="tipe" class="form-control m-input" onChange="showextra()">
														<option value="buku">Buku</option>
														<option value="inventaris">Inventaris</option>
													</select>
												</div>
											</div>
											<div id="barcode" class="form-group m-form__group row">
												<label class="col-lg-2 col-form-label" align="left">
													Barcode yang dapat digunakan
												</label>
												<div class="col-lg-3">
													<?php
													$one = $lastbarcode+1;
													$two = $lastbarcode+2;
													$three = $lastbarcode+3;
													$value = $one.", ".$two.", ".$three."...";
													?>
													<input id="no_barcode" name="no_barcode" type="text" class="form-control m-input" value="<?php echo $value; ?>" readonly>
												</div>
											</div>
											<div class="form-group m-form__group row" id="extra_anggota">
												<label class="col-lg-2 col-form-label">
													File
												</label>
												<div class="col-lg-5">
													<input id="file2" name="imports" type="file">
												</div>
											</div>
											<input type="hidden" id="kolom" name="kolom" class="form-control m-input" value="1">
										<div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions--solid">
												<div class="row">
													<div class="col-lg-2"></div>
													<div class="col-lg-10">
													<?php if(in_array($page_name,$this->session->userdata('perm_add'))||in_array($page_name,$this->session->userdata('perm_edit'))):?>
														<button type="submit" class="btn btn-success">
															Submit
														</button>
														<?php endif;?>
														<button type="reset" class="btn btn-secondary">
															Cancel
														</button>
													</div>
												</div>
											</div>
										</div>
									</form>
									<!--end::Form-->
								<?php if(isset($importResult) && $importResult && count($importResult)>0){
									?>
									<?php if($importType=='buku'){ ?>
									 <div style="overflow-x:auto">
									<table class="table table-bordered table-hover table-responsive">
									<tr>
										<th>No</th>
										<th>Result</th>
										<th>Tanggal</th>
										<th>No Klas</th>
										<th>ISBN</th>
										<th>Kategori</th>
										<th>Judul</th>
										<th>Penggalan Judul</th>
										<th>Judul Asli</th>
										<th>Penulis</th>
										<th>Penyadur</th>
										<th>Penerjemah</th>
										<th>Penyusun</th>
										<th>Penyunting</th>
										<th>Illustrator</th>
										<th>Editor</th>
										<th>Edisi</th>
										<th>Cetakan</th>
										<th>Penerbit</th>
										<th>Tahun Terbit</th>
										<th>Jilid</th>
										<th>Halaman Romawi</th>
										<th>Jumlah Halaman</th>
										<th>Ukuran Fisik</th>
										<th>Bibliografi</th>
										<th>Indeks</th>
										<th>Bahasa</th>
										<th>No Rak</th>
										<th>Seri</th>
										<th>Tajuk</th>
										<th>Subjek</th>
									</tr>
									<?php
									$count=0;
									foreach($importResult as $row){
										$count+=1;
										?>
										<tr >
											<td><?php echo $count;?></td>
											<td><?php echo $row[29];?></td>
											<td><?php echo $row[28];?></td>
											<td><?php echo $row[0] ?></td>
											<td><?php echo $row[1] ?> </td>
											<td><?php echo $row[2] ?></td>
											<td><?php echo $row[3] ?> </td>
											<td><?php echo $row[4] ?></td>
											<td><?php echo $row[5] ?> </td>
											<td><?php echo $row[6] ?></td>
											<td><?php echo $row[7]?> </td>
											<td><?php echo $row[8] ?></td>
											<td><?php echo $row[9] ?></td>
											<td><?php echo $row[10] ?></td>
											<td><?php echo $row[11] ?></td>
											<td><?php echo $row[12] ?></td>
											<td><?php echo $row[13] ?></td>
											<td><?php echo $row[14] ?></td>
											<td><?php echo $row[15]?></td>
											<td><?php echo $row[16] ?></td>
											<td><?php echo $row[17];?></td>
											<td><?php echo $row[18];?></td>
											<td><?php echo $row[19];?></td>
											<td><?php echo $row[20];?></td>
											<td><?php echo $row[21];?></td>
											<td><?php echo $row[22];?></td>
											<td><?php echo $row[23];?></td>
											<td><?php echo $row[24];?></td>
											<td><?php echo $row[25];?></td>
											<td><?php echo $row[26];?></td>
											<td><?php echo $row[27];?></td>
										</tr>
										<?php } ?>
									</table>
									</div>
								<?php } ?>
									<?php if($importType=='inventaris'){ ?>

							 <div style="overflow-x:auto">
									<table class="table table-bordered table-hover table-responsive">
									<tr>
										<th>No</th>
										<th>Result</th>
										<th>No Inventaris</th>
										<th>Tanggal Inventaris</th>
										<th>No Klasifikasi</th>
										<th>Status</th>
										<th>Asal</th>
										<th>Tanggal</th>
										<th>ISBN</th>
										<th>Keterangan</th>
									</tr>
									<?php
									$count=0;
									foreach($importResult as $row){
										$count+=1;
										?>
										<tr >
											<td><?php echo $count;?></td>
											<td><?php echo $row[8] ?></td>
											<td><?php echo $row[0] ?></td>
											<td><?php echo $row[1] ?> </td>
											<td><?php echo $row[2] ?></td>
											<td><?php echo $row[3] ?> </td>
											<td><?php echo $row[4] ?></td>
											<td><?php echo $row[5] ?> </td>
											<td><?php echo $row[6] ?></td>
											<td><?php echo $row[7]?> </td>
										</tr>
										<?php } ?>
									</table>
									</div>
								<?php } ?>
									<?php
								}
								?>
								<!--end: Datatable -->
							</div>
						</div>
						<?php } ?>

					</div>
				</div>
				<script type="text/javascript">
				$( document ).ready(function() {
					$("#barcode").hide();
				});
				function showextra(){
					var tipe = document.getElementById('tipe');
						if (tipe.value=='inventaris') {
							$("#barcode").show();
							// document.getElementById('barcode').style.display='block';
						} else {
							$("#barcode").hide();
							// document.getElementById('barcode').style.display='none';
						}
					}
				</script>
			<!-- end:: Body -->
