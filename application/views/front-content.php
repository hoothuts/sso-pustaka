<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Section: tabel | Kumpulan Tabel -->
<?php if ($page_content == 'bukutamu') { ?>
	<!-- Section: inner-header -->
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Buku Tamu</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/bukutamu">Buku Tamu</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<center>
						<h3>Scan Your ID</h3>
						<h5>Gunakan fasilitas ini sebagai buku tamu</h5>
					</center>
					<!-- Mailchimp Subscription Form-->
					<form action="<?= base_url(); ?>home/bukutamu/submit/" method="post" class="newsletter-form mt-40">
						<label for="mce-EMAIL"></label>
						<div class="input-group">
							<input type="text" data-height="45px" class="form-control input-lg" placeholder="ID NUMBER" name="nomor" required value="">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-colored btn-dark btn-lg m-0" data-height="45px">Scanning</button>
							</span>
						</div>

					</form>
				</div>
			</div>
			<?php if ($this->session->flashdata('alert') != '') : ?>
				<div class="alert <?= $this->session->flashdata('alert') ?>" role="alert" align="center"><?= $this->session->flashdata('flash_message') ?></div>
			<?php endif; ?>

			<div class="blog-posts">
				<div class="row list-dashed">

					<table class="table table-striped">
						<tbody>
							<tr>
								<th>No.</th>
								<th>Hari</th>
								<th>Tanggal</th>
								<th>Jumlah</th>
							</tr>
							<?php
							$presensi = $this->Md_siperpus_presensi->getPresensi7Hari();
							if ($presensi) {
								$day = array(
									'Mon' => 'Senin',
									'Tue' => 'Selasa',
									'Wed' => 'Rabu',
									'Thu' => 'Kamis',
									'Fri' => 'Jumat',
									'Sat' => 'Sabtu',
									'Sun' => 'Minggu',
								);
								$count = 0;
								foreach ($presensi as $row) {
									$count++;
									$hari = date('D', strtotime($row->tanggal));
									//$jumlah = $this->Md_siperpus_presensi->getJumlah(substr($row->tanggal, 0, 10));

							?>
									<tr>
										<td><?= $count; ?></td>
										<td><?= $day[$hari]; ?></td>
										<td><?= date('d-M-Y', strtotime($row->tanggal)); ?></td>
										<td><?= $row->jumlah; ?></td>
									</tr>
							<?php
								}
							}
							?>
							<tr>

							</tr>
						</tbody>
					</table>
					<div class="text-center">
						<canvas id="lineChart" height="200" width="600"></canvas>
					</div>

				</div>
			</div>
		</div>
	</section>

	<script src="<?= base_url(); ?>assets/front/js/chart.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			// Line Chart
			var lineChartData = {
				labels: [<?= $pre_hari; ?>],
				datasets: [{
					label: "My First dataset",
					fillColor: "rgba(220,220,220,0.2)",
					strokeColor: "rgba(220,220,220,1)",
					pointColor: "rgba(220,220,220,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [<?= $pre_val; ?>]
				}]
			}
			window.onload = function() {
				var chart_lineChart = document.getElementById("lineChart").getContext("2d");
				window.myLine = new Chart(chart_lineChart).Line(lineChartData, {
					responsive: true
				});
			};

		});
	</script>
<?php } ?>
<?php if ($page_content == 'dbook') { ?>

	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">

					<div class="col-md-12 text-center">
						<h2 class="title text-white">Buku Digital</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/digital_book">Buku Digital</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="advance" class=" panel panel-collapse collapse" role="tablist" aria-expanded="true" data-bg-img="http://placehold.it/1920x1280">
		<div class="panel-content container-fluid">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 bg-lightest-transparent p-30 pt-10">
					<h3 class="text-center text-theme-colored mb-20">Advance Search</h3>
					<form action="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>" method="post">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Judul</label>
									<input name="judul" type="text" placeholder="Enter Judul" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Penulis </label>
									<input name="penulis" class="form-control" type="text" placeholder="Enter Penulis">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label>Seri</label>
									<input name="seri" type="text" placeholder="Enter Seri" class="form-control">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>ISBN </label>
									<input name="isbn" class="form-control" type="text" placeholder="Enter ISBN">
								</div>
							</div>
						</div>

						<div class="form-group">
							<input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="" />
							<button type="submit" class="btn btn-block btn-dark btn-theme-colored btn-sm mt-20 pt-10 pb-10" data-loading-text="Please wait...">Search</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</section>
	<section>
		<form action="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>" method="post">
			<div class="container mt-30 mb-30 pt-30 pb-30">
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="blog-posts">
							<div class="col-md-6 col-md-push-3">
								<div class="row list-dashed">
									<a class="btn btn-dark btn-colored btn-theme-colored m-0" data-parent="#advance" data-toggle="collapse" href="#advance" class="" aria-expanded="true" onClick="showadvance()">Advance Search</a><br /><br />
									<div class="input-group" id="searchbox">
										<input type="search" value="<?= $search; ?>" name="search" id="search" placeholder="Penelusuran Buku" class="form-control" data-height="37px">
										<span class="input-group-btn">
											<button type="submit" class="btn btn-colored btn-theme-colored m-0"><i class="fa fa-search text-white"></i></button>
										</span>
									</div>

								</div>
							</div>
						</div>
						<script>
							function showadvance() {
								$("#searchbox").toggle();
							}
						</script>
					</div>
					<div class="col-md-9 text-center">
						<div class="blog-posts">
							<div class="col-sm-9">
								<div id="pagination">


									<!-- Show pagination links -->
									<?php if (isset($links)) { ?>
										<?= $links ?>
									<?php } ?>
								</div>
								</nav>
							</div>
							<div class="col-sm-12 col-md-12">
								<div class="row multi-row-clearfix">
									<div class="products">

										<?php

										// Show data
										if ($results) {
											foreach ($results as $data) {
												$penerbit = $this->Md_siperpus_penerbit->getPenerbitById($data->kd_penerbit);
												$isbn = str_replace('/', '_', $data->ISBN);
												$noklas = str_replace(' ', '+', $data->no_klas);
										?>
												<div class="col-sm-6 col-md-4 col-lg-4 mb-30">
													<div class="product">
														<div class="product-thumb">
															<?php if ($data->cover != '') : ?>
																<img alt="" src="<?= base_url(); ?>uploads/covers/<?= $data->cover; ?>" class="img-responsive img-fullwidth">
															<?php else : ?>
																<img alt="" src="<?= base_url(); ?>assets/media/cover-medium.jpg" class="img-responsive img-fullwidth">
															<?php endif; ?>
															<div class="overlay">
																<div class="btn-product-view-details">
																	<a class="btn btn-default btn-theme-colored btn-sm btn-flat pl-20 pr-20 btn-add-to-cart text-uppercase font-weight-700" href="<?= base_url(); ?><?= $page_access; ?>/detail/<?= $isbn; ?>/<?= $noklas; ?>">Record detail</a>
																</div>
															</div>
														</div>
														<div class="product-details text-center">
															<a href="<?= base_url(); ?><?= $page_access; ?>/detail/<?= $isbn; ?>/<?= $noklas; ?>">
																<h5 class="product-title"><?= $data->judul; ?></h5>
															</a>
															<div class="price"><?= $data->penulis; ?></div>
														</div>
													</div>
												</div>

										<?php
											}
										}
										?>
									</div>
								</div>
							</div>
							<div class="col-sm-9">
								<div id="pagination">


									<!-- Show pagination links -->
									<?php if (isset($links)) { ?>
										<?= $links ?>
									<?php } ?>
								</div>
								</nav>
							</div>

						</div>
					</div>

					<!--right sidebar-->
					<div class="col-md-3">

						<div class="widget">
							<h5 class="widget-title line-bottom">Search Result</h5>
						</div>
						<div class="widget">
							<p>
								Found <?= $num_row; ?> From Your Keywords: <?= $keyword; ?>
								<br>Query Took <?= $extime; ?> second(s) to complete
							</p>

						</div>
						<div class="widget">
							<h5 class="widget-title line-bottom">Information</h5>
						</div>
						<div class="widget">
							Web Online Public Access Catalog - Use the search options to find documents quickly
						</div>
					</div>
				</div>
				<!--paging-->

			</div>
		</form>
	</section>

<?php } ?>
<?php if ($page_content == 'pbuku') { ?>
        <section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
                <div class="container pt-60 pb-60">
                    <!-- Section Content -->
                    <div class="section-content">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2 class="title text-white">Penelusuran Buku</h2>
                                <ol class="breadcrumb text-center text-black mt-10">
                                    <li><a href="<?= base_url(); ?>home">Home</a></li>
                                    <li><a href="<?= base_url(); ?>home/penelusuran_buku">Penelusuran Buku</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="advance" class=" panel panel-collapse collapse" role="tablist" aria-expanded="true" data-bg-img="http://placehold.it/1920x1280">
                <div class="panel-content container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 bg-lightest-transparent p-30 pt-10">
                            <h3 class="text-center text-theme-colored mb-20">Advance Search</h3>
                            <form action="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>/_advance_search/" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Judul</label>
                                            <input name="judul" type="text" placeholder="Enter Judul" class="form-control" value="<?= $this->session->userdata('judul') ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Penulis </label>
                                            <input name="penulis" class="form-control" type="text" placeholder="Enter Penulis" value="<?= $this->session->userdata('penulis') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Seri</label>
                                            <input name="seri" type="text" placeholder="Enter Seri" class="form-control" value="<?= $this->session->userdata('seri') ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>ISBN </label>
                                            <input name="isbn" class="form-control" type="text" placeholder="Enter ISBN" value="<?= $this->session->userdata('isbn') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="kategori" class="form-control">
                                                <option value="all"> Semua Kategori </option>
                                                <?php
                                                if ($kategori) {
                                                    foreach ($kategori as $ktg) {
                                                        ?>
                                                        <option value="<?= $ktg->idkategori; ?>" <?php if ($ktg->idkategori == $this->session->userdata('kategori')) echo "selected"; ?>><?= $ktg->nmkategori; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="" />
                                    <button type="submit" class="btn btn-block btn-dark btn-theme-colored btn-sm mt-20 pt-10 pb-10" data-loading-text="Please wait...">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <form action="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>/_search/" method="post">
                    <div class="container mt-30 mb-30 pt-30 pb-30">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="blog-posts">
                                    <div class="col-md-6 col-md-push-3">
                                        <div class="row list-dashed">
                                            <a class="btn btn-dark btn-colored btn-theme-colored m-0" data-parent="#advance" data-toggle="collapse" href="#advance" class="" aria-expanded="true" onClick="showadvance()">Advance Search</a><br /><br />
                                            <div class="input-group" id="searchbox">
                                                <input type="search" value="<?= $this->session->userdata('cari') ?>" name="search" id="search" placeholder="Penelusuran Buku" class="form-control" data-height="37px">
                                                <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-colored btn-theme-colored m-0"><i class="fa fa-search text-white"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                        function showadvance() {
                                                $("#searchbox").toggle();
                                        }
                                </script>
                            </div>




                            <div class="col-md-9 text-center" id="isi">
                                <div class="blog-posts">
                                    <div class="col-sm-12">
                                        <div id="pagination">
                                            <!-- Show pagination links -->
                                            <?php if (isset($links)) { ?>
                                                <?= $links ?>
                                            <?php } ?>
                                        </div>
                                        </nav>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="row multi-row-clearfix">
                                            <div class="products">
                                                <?php
                                                // Show data
                                                if ($results) {
                                                    foreach ($results as $data) {
                                                        $penerbit = $this->Md_siperpus_penerbit->getPenerbitById($data->kd_penerbit);
                                                        $isbn = str_replace('/', '_', $data->ISBN);
                                                        $noklas = str_replace(' ', '+', $data->no_klas);
                                                        ?>
                                                        <div class="col-sm-6 col-md-4 col-lg-4 mb-30">
                                                            <div class="product">
                                                                <div class="product-thumb">
                                                                    <?php if ($data->cover != '') : ?>
                                                                        <img alt="" src="<?= base_url(); ?>uploads/covers/<?= $data->cover; ?>" class="img-responsive img-fullwidth">
                                                                    <?php else : ?>
                                                                        <img alt="" src="<?= base_url(); ?>assets/media/cover-medium.jpg" class="img-responsive img-fullwidth">
                                                                    <?php endif; ?>
                                                                    <div class="overlay">
                                                                        <div class="btn-product-view-details">
                                                                            <a class="btn btn-default btn-theme-colored btn-sm btn-flat pl-20 pr-20 btn-add-to-cart text-uppercase font-weight-700" href="<?= base_url(); ?><?= $page_access; ?>/detail/<?= $isbn; ?>/<?= $noklas; ?>">Record detail</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-details text-center">
                                                                    <a href="<?= base_url(); ?><?= $page_access; ?>/detail/<?= $isbn; ?>/<?= $noklas; ?>">
                                                                        <h5 class="product-title"><?= $data->judul; ?></h5>
                                                                    </a>
                                                                    <div class="price"><?= $data->penulis; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div id="pagination">
                                            <!-- Show pagination links -->
                                            <?php if (isset($links)) { ?>
                                                <?= $links ?>
    <?php } ?>
                                        </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <!--right sidebar-->
                            <div class="col-md-3">
                                <div class="widget">
                                    <h5 class="widget-title line-bottom">Search Result</h5>
                                </div>
                                <div class="widget">
                                    <p>
                                        Found <?= $num_row; ?> From Your Keywords: <?= $keyword; ?>
                                        <br>Query Took <?= $extime; ?> second(s) to complete
                                    </p>
                                </div>
                                <div class="widget">
                                    <h5 class="widget-title line-bottom">Information</h5>
                                </div>
                                <div class="widget">
                                    Web Online Public Access Catalog - Use the search options to find documents quickly
                                </div>
                            </div>
                        </div>
                        <!--paging-->
                    </div>
                </form>
            </section>

<?php } ?>
<?php if ($page_content == 'readartikel') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<?php
						$jenis_artikel = '';
						if (isset($jns_artikel)) {
							if (strtolower($jns_artikel) == 'artikel') {
								$jenis_artikel =  'Artikel';
							} elseif (strtolower($jns_artikel) == 'pengumuman') {
								$jenis_artikel = 'Pengumuman';
							} elseif (strtolower($jns_artikel) == 'berita') {
								$jenis_artikel = 'Berita';
							} else {
								$jenis_artikel = 'Jenis artikel tidak dikenal';
							}
						} else {
							$jenis_artikel = 'Jenis artikel tidak ada';
						}
						?>
						<h2 class="title text-white">Detail <?php echo $jenis_artikel ?></h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/read/<?= $page_action; ?>">Detail <?php echo $jenis_artikel ?></a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<?php
		$tglpost = new datetime($detail[0]->tgl_post);

		?>
		<div class="container mt-30 mb-30 pt-30 pb-30">
			<div class="row">
				<div class="col-md-9">
					<div class="blog-posts single-post">
						<article class="post clearfix mb-0">
							<div class="entry-header">
								<?php if (isset($is_preview)) : ?>

									<div class="post-thumb"> <img src="data:image/<?= $file_extension ?>;base64,<?= $file_base64 ?>" alt="" class="img-responsive" width="1920" height="1280"> </div>
								<?php else : ?>
									<?php if ($media == '') : ?>
										<div class="post-thumb thumb"> <img src="<?= base_url(); ?>assets/media/artikel-big.jpg" alt="" class="img-responsive img-fullwidth"> </div>
									<?php else : ?>
										<div class="post-thumb"> <img src="<?= base_url(); ?>uploads/big/big_<?= $media; ?>" alt="" class="img-responsive"> </div>

									<?php endif; ?>

								<?php endif; ?>


							</div>
							<div class="entry-content">
								<div class="entry-meta media no-bg no-border mt-15 pb-20">
									<div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
										<ul>
											<li class="font-16 text-white font-weight-600"><?= $tglpost->format('d'); ?></li>
											<li class="font-12 text-white text-uppercase"><?= $tglpost->format('M'); ?></li>
										</ul>
									</div>
									<div class="media-body pl-15">
										<div class="event-content pull-left flip">
											<h3 class="entry-title text-white text-uppercase pt-0 mt-0"><a href="#"><?= $detail[0]->judul; ?></a></h3>
										</div>
									</div>
								</div>
								<p class="mb-15"><?= $detail[0]->isi; ?></p>
							</div>
						</article>
					</div>
				</div>
				<div class="col-md-3">
					<div class="widget">
						<a href="javascript: history.back();" class="btn btn-dark btn-xs mt-15" title="Back to previous page">Back To Previous</a>
					</div>
					<center></center>
					<div class="widget">
						<h5 class="widget-title line-bottom">Search</h5>
						<div class="latest-posts">
							<article class="post media-post clearfix pb-0 mb-10">
								<form action="<?= base_url(); ?>home/artikel/search" method="get">
									<div class="form-group col mb-2">
										<input type="text" value="" class="form-control text-3 h-auto py-2" name="cari_artikel">
									</div>
									<div class="form-group col">
										<input type="submit" value="Cari" class="btn btn-primary" data-loading-text="Loading...">
									</div>
								</form>
							</article>

						</div>
					</div>
					<div class="widget">
						<h5 class="widget-title line-bottom">Kategori</h5>
						<div class="latest-posts">
							<article class="post media-post clearfix pb-0 mb-10">
								<?php foreach ($kategori_artikel as $ka) : ?>
									<div class="col-12">
										<a href="<?= base_url(); ?>home/artikel/kategori/<?php echo encrypt($ka->jenisartikel_id) ?>"><?php echo $ka->jenis_artikel ?></a>
									</div>

								<?php endforeach; ?>
							</article>

						</div>
					</div>
					<div class="widget">
						<h5 class="widget-title line-bottom">Berita Terbaru</h5>
						<div class="latest-posts">
							<?php if ($artikel && count($artikel) > 0) {
								foreach ($artikel as $art) {
									$media = $this->Md_media->getMediaById($art->media_id);

							?>
									<article class="post media-post clearfix pb-0 mb-10">
										<?php if (count($media) > 0) : ?>
											<div class="post-thumb thumb"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"><img width="75" src="<?= base_url(); ?>uploads/<?= $media[0]['judul']; ?>" alt="<?= $art->judul; ?>" class="img-responsive"> </a></div>
										<?php else : ?>
											<div class="post-thumb thumb"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"> <img width="75" src="<?= base_url(); ?>assets/media/artikel-small.jpg" alt="<?= $art->judul; ?>" class="img-responsive "></a></div>
										<?php endif; ?>
										<div class="post-right">
											<h5 class="post-title mt-0"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"><?= $art->judul; ?></a></h5>
											<p><?= substr(strip_tags($art->isi), 0, 50); ?>....</p>
										</div>
									</article>
							<?php
								}
							}
							?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'artikel') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">

						<h2 class="title text-white"><?php echo ucwords($jns_artikel) ?></h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="#"><?php echo ucwords($jns_artikel) ?></a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>

		<div class="container mt-30 mb-30 pt-30 pb-30">
			<div class="row">
				<div class="col-md-9">
					<div class="blog-posts single-post">
						<?php if ($detail) : ?>
							<?php foreach ($detail as $d) : ?>
								<article class="post clearfix mb-15">
									<div class="entry-header">
										<?php if ($d->mediajudul == '') : ?>
											<div class="post-thumb thumb"> <img src="<?= base_url(); ?>assets/media/artikel-big.jpg" alt="" class="img-responsive img-fullwidth"> </div>
										<?php else : ?>
											<div class="post-thumb"> <img src="<?= base_url(); ?>uploads/big/big_<?= $d->mediajudul; ?>" alt="" class="img-responsive"> </div>

										<?php endif; ?>
										<?php $tglpost = new datetime($d->tgl_post); ?>

									</div>
									<div class="entry-content">
										<div class="entry-meta media no-bg no-border mt-15 pb-20">
											<div class="entry-date media-left text-center flip bg-theme-colored pt-5 pr-15 pb-5 pl-15">
												<ul>
													<li class="font-16 text-white font-weight-600"><?= $tglpost->format('d'); ?></li>
													<li class="font-12 text-white text-uppercase"><?= $tglpost->format('M'); ?></li>
													<li class="font-12 text-white text-uppercase"><?= $tglpost->format('Y'); ?></li>
												</ul>
											</div>
											<div class="media-body pl-15">
												<div class="event-content pull-left flip">
													<h3 class="entry-title text-white text-uppercase pt-0 mt-0"><a href="<?= base_url(); ?>home/read/<?= $d->artikel_id; ?>"><?= $d->judul; ?></a></h3>
												</div>
											</div>
										</div>

									</div>
								</article>
							<?php endforeach ?>
							<div class="pagination-links">
								<?php echo $pagination_links; ?>
							</div>
						<?php else : ?>
							<span>Artikel tidak ditemukan</span>
						<?php endif ?>
					</div>

				</div>
				<div class="col-md-3">
					<div class="widget">
						<a href="javascript: history.back();" class="btn btn-dark btn-xs mt-15" title="Back to previous page">Back To Previous</a>
					</div>
					<div class="widget">
						<h5 class="widget-title line-bottom">Search</h5>
						<div class="latest-posts">
							<article class="post media-post clearfix pb-0 mb-10">
								<form action="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>/search" method="get">
									<div class="form-group col mb-2">
										<input type="text" value="" class="form-control text-3 h-auto py-2" name="cari_artikel">
									</div>
									<div class="form-group col">
										<input type="submit" value="Cari" class="btn btn-primary" data-loading-text="Loading...">
									</div>
								</form>
							</article>

						</div>
					</div>
					<div class="widget">
						<h5 class="widget-title line-bottom">Kategori</h5>
						<div class="latest-posts">
							<article class="post media-post clearfix pb-0 mb-10">
								<?php foreach ($kategori_artikel as $ka) : ?>
									<div class="col-12">
										<a href="<?= base_url(); ?>home/artikel/kategori/<?php echo encrypt($ka->jenisartikel_id) ?>"><?php echo $ka->jenis_artikel ?></a>
									</div>

								<?php endforeach; ?>
							</article>

						</div>
					</div>
					<div class="widget">
						<h5 class="widget-title line-bottom">Berita Terbaru</h5>
						<div class="latest-posts">
							<?php
							if ($artikel && count($artikel) > 0) {
								foreach ($artikel as $art) {
									$media = $this->Md_media->getMediaById($art->media_id);
							?>
									<article class="post media-post clearfix pb-0 mb-10">
										<?php if (count($media) > 0) : ?>
											<div class="post-thumb thumb"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"><img width="75" src="<?= base_url(); ?>uploads/<?= $media[0]['judul']; ?>" alt="<?= $art->judul; ?>" class="img-responsive"> </a></div>
										<?php else : ?>
											<div class="post-thumb thumb"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"> <img width="75" src="<?= base_url(); ?>assets/media/artikel-small.jpg" alt="<?= $art->judul; ?>" class="img-responsive "></a></div>
										<?php endif; ?>
										<div class="post-right">
											<h5 class="post-title mt-0"><a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>"><?= $art->judul; ?></a></h5>
											<p><?= substr(strip_tags($art->isi), 0, 50); ?>....</p>
										</div>
									</article>
							<?php
								}
							}
							?>

						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'pbukudetail') { ?>
        <section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
            <div class="container pt-60 pb-60">
                <!-- Section Content -->
                <div class="section-content">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2 class="title text-white">Detail Buku</h2>
                            <ol class="breadcrumb text-center text-black mt-10">
                                <li><a href="<?= base_url(); ?>home">Home</a></li>
                                <li><a href="<?= base_url(); ?>home/penelusuran_buku">Penelusuran Buku</a></li>
                                <li><a href="<?= base_url(); ?>home/detail/<?= $page_action; ?>/<?= $page_action2; ?>">Detail Buku</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container mt-30 mb-30 pt-30 pb-30">
                <div class="row">
                    <div class="product">
                        <div class="col-md-3">
                            <div class="product-image">
                                <div class="zoom-gallery">
                                    <?php if ($detail[0]->cover != '') : ?>
                                        <img alt="" src="<?= base_url(); ?>uploads/covers/<?= $detail[0]->cover; ?>" class="img-responsive img-fullwidth">
                                    <?php else : ?>
                                        <img alt="" src="<?= base_url(); ?>assets/media/cover-medium.jpg" class="img-responsive img-fullwidth">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="product-summary">
                                <h2 class="product-title"><?= $detail[0]->judul; ?></h2>
                                <div class="short-description">
                                    <p><?= $detail[0]->deskripsi; ?></p>
                                </div>
                                <div class="tags"><strong>Penulis:</strong> <?= $detail[0]->penulis; ?></div>
                                <div class="tags"><strong>Tajuk:</strong> <?= $detail[0]->tajuksubyek; ?></div>
                                <div class="tags"><strong>No Klasifikasi:</strong> <?= $detail[0]->no_klas; ?></div>
                                <div class="tags"><strong>Edisi:</strong> <?= $detail[0]->edisi; ?> | <strong>Cetakan:</strong> <?= $detail[0]->cetakan; ?></div>
                                <div class="tags"><strong>Penerbit:</strong> <?= $detail[0]->penerbit; ?> : <?= $detail[0]->kota_penerbit; ?>., <?= $detail[0]->thn_terbit; ?></div>
                                <div class="tags"><strong>Bahasa:</strong> <?= $detail[0]->bhs; ?></div>
                                <div class="tags"><strong>ISBN:</strong> <?= $detail[0]->ISBN; ?></div>
                                <div class="tags"><strong>Jumlah Halaman:</strong> <?= $detail[0]->jml_hal; ?></div>
                                <div class="tags"><strong>Ukuran Fisik:</strong> <?= $detail[0]->ukuran_fisik; ?> cm</div>
                                <div class="tags"><strong>Jumlah Stok Buku:</strong> <?= $detail[0]->jml_buku; ?></div>
                                <div class="tags"><strong>Referensi Prodi:</strong>
                                    <?php
                                    if ($referensi) {
                                        foreach ($referensi as $r) {
                                            ?>
                                            <span class="text-highlight"><?= $r->namaprodi; ?></span>&nbsp;
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="tags"><strong>Tanggal Input:</strong> <?= $detail[0]->tanggal; ?></div>
                            </div>
                            <h4 class="name font-24 mt-0 mb-0"></h4>
                            <h5 class="mt-5"></h5>
                        </div>
                        <div class="col-md-3">
                            <div class="widget">
                                <a href="javascript: history.back();" class="btn btn-dark btn-xs mt-15" title="Back to previous page">Back To Previous</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="horizontal-tab product-tab">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1" data-toggle="tab">Info Detail</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="tab1">

                                    <!-- Other version/related -->
                                    <h5><strong>Other version/related:</strong></h5>
                                    <ul class="list-group">
                                        <?php
                                        $fileshown = 0;
                                        if ($file) :
                                            if (count($lokasifile)) :
                                                foreach ($lokasifile as $lf) {
                                                    if (isset($file->file_name) && $file->file_name != '') {
                                                        $fileshown += 1;
                                                        if ($file->status_akses || $this->session->has_userdata('member')) {
                                                            ?>
                                                            <li class="list-group-item">
                                                                <?php if ($lf['is_download'] == 'Ya') : ?>
                                                                    <i class="pe-7s-file"></i>
                                                                    <a target="_blank" href="<?= base_url(); ?>uploads/files/<?= $lf['nm_file']; ?>">
                                                                        <?= substr($lf['nm_file'], 15); ?>
                                                                    </a>
                                                                <?php else : ?>
                                                                    <i class="pe-7s-file"></i>
                                                                    <span class="text-danger">File tidak dapat didownload</span>
                                                                <?php endif; ?>
                                                                <?php if ($lf['ext_file'] == 'pdf' && $lf['is_baca'] == 'Ya') : ?>
                                                                    <i class="pe-7s-notebook"></i>
                                                                    <a href="<?= base_url(); ?>home/readbook/<?= $lf['nm_file']; ?>" target="_blank">
                                                                        <font color='#00008B'>Baca Online</font>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </li>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <li class="list-group-item">
                                                                <i class="pe-7s-shield"></i>
                                                                <a href="javascript:void(0)" onclick="show_modal('<?= $lf['nm_file']; ?>')">Login To Download</a>
                                                                <?php if ($lf['ext_file'] == 'pdf' && $lf['is_baca'] == 'Ya') : ?>
                                                                    <i class="pe-7s-notebook"></i>
                                                                    <a href="<?= base_url(); ?>home/readbook/<?= $lf['nm_file']; ?>" target="_blank">
                                                                        <font color='#00008B'>Baca Online</font>
                                                                    </a>
                                                                <?php endif; ?>
                                                                <br>
                                                                <font color='#990000'><?= substr($lf['nm_file'], 15); ?></font>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            endif;
                                        endif;
                                        if ($fileshown == 0)
                                            echo '<li class="list-group-item panel-footer alert-danger"><center>Tidak ditemukan Attachment Lainnya</center></li>';
                                        ?>
                                    </ul>

                                    <hr>

                                    <!-- Availability -->
                                    <h5><strong>Availability:</strong></h5>
                                    <table class="table table-striped table-bordered table-hover">
                                        <tr>
                                            <th>No</th>
                                            <th>No Barcode</th>
                                            <th>No Klasifikasi</th>
                                            <th>Lokasi</th>
                                            <th>Gedung</th>
                                            <th>No Rak</th>
                                            <th>Status</th>
                                        </tr>
                                        <?php
                                        if (count($inv) > 0) {
                                            $count = 0;
                                            foreach ($inv as $i) {
                                                $count++;
                                                $statusPeminjaman = 'Tersedia';
                                                $statusBuku = '';
                                                $color = 'bg-theme-colored';
                                                $isPinjam = $this->Md_siperpus_transaksi->isPinjam($i->no_inv);
                                                if ($isPinjam) {
                                                    $color = '';
                                                    $tran = $this->Md_siperpus_transaksi->getTransaksiByNoInv($i->no_inv);
                                                    $statusPeminjaman = 'Buku Sedang Dipinjam (Batas :' . $tran[0]->batas . ')';
                                                } else {
                                                    $hilang = $this->Md_siperpus_hilangrusak->getHilangByInv($i->no_inv);
                                                    if (!empty($hilang)) {
                                                        if ($hilang[0]->ket == 'H') $statusBuku = ' Tapi tidak dapat dipinjam - Hilang';
                                                        if ($hilang[0]->ket == 'R') $statusBuku = ' Tapi tidak dapat dipinjam - Rusak';
                                                        if ($hilang[0]->ket == 'A') $statusBuku = ' Tapi tidak dapat dipinjam - Diarsipkan';
                                                        if ($hilang[0]->ket == 'L') $statusBuku = ' Tapi tidak dapat dipinjam - Dilelang';
                                                        $color = 'light';
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $count; ?></td>
                                                    <td><?= $i->no_barcode; ?></td>
                                                    <td><?= $detail[0]->no_klas; ?></td>
                                                    <td><?= $i->nama_kampus; ?></td>
                                                    <td><?= $i->nama_gedung; ?></td>
                                                    <td><?= $i->nama_rak; ?></td>
                                                    <td>
                                                        <span class="text-highlight <?= $color; ?>">
                                                            <?= $statusPeminjaman; ?><?= $statusBuku; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--paging-->

        </section>

<?php } ?>
<?php if ($page_content == 'pbukuprodi') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Penelusuran Buku Ref Prodi</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/penelusuran_bukuprodi">Penelusuran Buku Ref Prodi</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="container mt-30 mb-30 pt-30 pb-30">
			<div class="row">
				<div class="col-md-9">
					<div class="blog-posts">
						<div class="col-md-12">
							<div class="row list-dashed">
								<table class="table table-striped">
									<tbody>
										<tr>
											<th>No.</th>
											<th>No. Klasifikasi</th>
											<th>Judul Buku</th>
											<th>Penulis</th>
											<th>Penerbit</th>
											<th>Jumlah Buku</th>
											<th>Review</th>
											<th>Stok</th>
										</tr>
										<?php
										$buku = $this->Md_siperpus_buku->getBuku();
										if ($buku) {
											$count = 0;
											foreach ($buku as $row) {
												$count++;
												$penerbit = $this->Md_siperpus_penerbit->getPenerbitById($row->kd_penerbit);
												if ($penerbit) {
													$namapenerbit = $penerbit[0]->nama_penerbit;
												} else {
													$namapenerbit = "-";
												}
										?>
												<tr>
													<td><?= $count; ?></td>
													<td><?= $row->no_klas; ?></td>
													<td><?= $row->judul; ?></td>
													<td><?= $row->penulis; ?></td>
													<td><?= $namapenerbit; ?></td>
													<td><?= $row->jml_buku; ?></td>
													<td><?= $row->review; ?></td>
													<td>-</td>
												</tr>
										<?php
											}
										}
										?>
										<tr>

										</tr>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>
				<?php include 'front-rightsidebar.php'; ?>
			</div>
		</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'kontak') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Kontak</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/kontak">Kontak</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="divider">
		<div class="container pt-0">
			<div class="row mb-60 bg-deep">
				<div class="col-sm-12 col-md-4">
					<div class="contact-info text-center pt-60 pb-60 border-right">
						<i class="fa fa-phone font-36 mb-10 text-theme-colored"></i>
						<h4>Telepon</h4>
						<h6 class="text-gray">Phone: (0761)36581</h6>
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="contact-info text-center  pt-60 pb-60 border-right">
						<i class="fa fa-map-marker font-36 mb-10 text-theme-colored"></i>
						<h4>Alamat</h4>
						<h6 class="text-gray">Jl. Melur No. 103 Sukajadi-Pekanbaru</h6>
					</div>
				</div>
				<div class="col-sm-12 col-md-4">
					<div class="contact-info text-center  pt-60 pb-60">
						<i class="fa fa-envelope font-36 mb-10 text-theme-colored"></i>
						<h4>Email</h4>
						<h6 class="text-gray">librarypolkesri@pkr.ac.id</h6>
					</div>
				</div>
			</div>
			<div class="row pt-10">
				<div class="col-md-5">
					<h4 class="mt-0 mb-30 line-bottom">Lokasi Kami</h4>
					<div>
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.650161154832!2d101.43269641534663!3d0.5260449996162243!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac0804e5db75%3A0x3439a446e3f3989c!2sHealth+Polytechnic+Riau!5e0!3m2!1sen!2sid!4v1516695251466" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
					<!-- </div> -->
					<div class="map-popupstring hidden" id="popupstring1">
						<div class="text-center">
							<h3>Politeknik Kesehatan Riau</h3>
							<p>Jl. Melur No. 103 Sukajadi-Pekanbaru</p>
						</div>
					</div>
				</div>

				<div class="col-md-7">
					<h4 class="mt-0 mb-30 line-bottom">Hubungi Kami</h4>
					<!-- Contact Form -->
					<form id="contact_form" name="contact_form" class="" action="<?= base_url(); ?>home/sendemail/" method="post">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="form_name">Name <small>*</small></label>
									<input name="form_name" class="form-control" type="text" placeholder="Enter Name" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Email <small>*</small></label>
									<input name="form_email" class="form-control required email" type="email" placeholder="Enter Email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="form_name">Perihal <small>*</small></label>
									<input name="form_subject" class="form-control required" type="text" placeholder="Enter Subject">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="form_name">Pesan</label>
							<textarea name="form_message" class="form-control required" rows="5" placeholder="Enter Message"></textarea>
						</div>
						<div class="form-group">
							<input name="form_botcheck" class="form-control" type="hidden" value="" />
							<button type="submit" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px" data-loading-text="Please wait...">Send your message</button>
							<button type="reset" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px">Reset</button>
						</div>
					</form>

					<!-- Contact Form Validation-->
					<script type="text/javascript">
						$("#contact_form").validate({
							submitHandler: function(form) {
								var form_btn = $(form).find('button[type="submit"]');
								var form_result_div = '#form-result';
								$(form_result_div).remove();
								form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
								var form_btn_old_msg = form_btn.html();
								form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
								$(form).ajaxSubmit({
									dataType: 'json',
									success: function(data) {
										if (data.status == 'true') {
											$(form).find('.form-control').val('');
										}
										form_btn.prop('disabled', false).html(form_btn_old_msg);
										$(form_result_div).html(data.message).fadeIn('slow');
										setTimeout(function() {
											$(form_result_div).fadeOut('slow')
										}, 6000);
									}
								});
							}
						});
					</script>
				</div>
			</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'about') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Profil</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/about">Profil Perpustakaan</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="position-inherit">
		<div class="container">
			<div class="row">
				<?= $isi[0]['isi_halaman']; ?>
				<script>
					/* scrollto fixed script */
					$(document).ready(function(e) {
						if ($(window).width() >= 768) {
							$('.scrolltofixed-sidebar').scrollToFixed({
								marginTop: $('.header .header-nav').outerHeight(true) + 100,
								limit: function() {
									var limit = $('.footer').offset().top - $(this).outerHeight(true) - 10;
									return limit;
								}
							});
						}
					});
				</script>
			</div>
		</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'referensi') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Referensi</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/referensi">Referensi</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="position-inherit">
		<div class="container">
			<div class="row">
				<?= $isi[0]['isi_halaman']; ?>
				<script>
					/* scrollto fixed script */
					$(document).ready(function(e) {
						if ($(window).width() >= 768) {
							$('.scrolltofixed-sidebar').scrollToFixed({
								marginTop: $('.header .header-nav').outerHeight(true) + 100,
								limit: function() {
									var limit = $('.footer').offset().top - $(this).outerHeight(true) - 10;
									return limit;
								}
							});
						}
					});
				</script>
			</div>
		</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'dashboard') { ?>
	<section id="home">
            <?php include 'front-slider.php'; ?>
	</section>
        <script>
            function initContentCarousel(selector, itemsDesktop = 4) {
                if($(selector).length){
                    $(selector).owlCarousel({
                        loop: true,
                        margin: 15,
                        nav: false,
                        dots: true,
                        autoplay: true,
                        autoplayTimeout: 1500,
                        autoplayHoverPause: true,
                        smartSpeed: 800,
                        responsive:{
                            0:{ items:1 },
                            600:{ items:2 },
                            1000:{ items:3 },
                            1200:{ items:itemsDesktop }
                        }
                    });
                }
            }
            function loadCarousel(skeletonId, wrapperId, carouselId, maxItem){
                setTimeout(function(){
                    var totalItem = $(carouselId + " .item").length;
                    $(skeletonId).fadeOut(200, function(){
                        // ❗ CEK DATA
                        if(totalItem === 0){
                            // jangan tampilkan carousel
                            return;
                        }
                        $(wrapperId).fadeIn(200, function(){
                            if($(carouselId).hasClass('owl-loaded')){
                                $(carouselId).trigger('destroy.owl.carousel');
                                $(carouselId).removeClass('owl-loaded');
                                $(carouselId).find('.owl-stage-outer').children().unwrap();
                            }
                            $(carouselId).owlCarousel({
                                loop: totalItem > maxItem,
                                margin: 15,
                                nav: false,
                                dots: true,
                                autoplay: true,
                                autoplayTimeout: 2500,
                                smartSpeed: 800,
                                slideBy: 1,
                                responsive:{
                                    0:{ items:2 },
                                    600:{ items:3 },
                                    1000:{ items:maxItem }
                                }
                            });
                        });
                    });
                }, 800);
            }

            $(document).ready(function(){
                //Pop Up
                var popup = $('#popupModal');
                if(popup.length){
                    if(!sessionStorage.getItem('popup_shown')){
                        setTimeout(function(){
                            popup.css('display','flex').hide().fadeIn();
                            // 🔥 pindahkan ke sini
                            sessionStorage.setItem('popup_shown', 'true');
                        }, 800);
                    }
                    $('.popup-close').click(function(){
                        popup.fadeOut();
                    });
                    popup.click(function(e){
                        if(e.target === this){
                            popup.fadeOut();
                        }
                    });
                }

                //dengan skeleton
                loadCarousel('#skeleton-buku-baru', '#carousel-wrapper', '#carousel-buku-baru', 6);
                loadCarousel('#skeleton-buku-terbanyak', '#carousel-buku-terbanyak-wrapper', '#carousel-buku-terbanyak', 6);
                
                loadCarousel('#skeleton-peminjam', '#carousel-peminjam-wrapper', '#carousel-peminjam', 2);
                loadCarousel('#skeleton-pengunjung', '#carousel-pengunjung-wrapper', '#carousel-pengunjung', 2);
                //tanpa skeleton
                initContentCarousel("#carousel-pengumuman", 4);
                initContentCarousel("#carousel-berita", 4);
            });

            document.addEventListener("DOMContentLoaded", function(){
                const cards = document.querySelectorAll('.funfact-card');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if(entry.isIntersecting){
                            setTimeout(() => {
                                entry.target.classList.add('show');
                            }, index * 200); // delay bertahap
                        }
                    });
                }, {
                    threshold: 0.2
                });
                cards.forEach(card => observer.observe(card));
            });
            document.addEventListener("DOMContentLoaded", function(){
                const elements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if(entry.isIntersecting){
                            // delay lebih natural (tidak terlalu cepat)
                            const delay = index * 200;
                            setTimeout(() => {
                                entry.target.classList.add('show');
                            }, delay);
                            // stop observe agar tidak retrigger
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.25, // lebih dalam → tidak terlalu cepat trigger
                    rootMargin: "0px 0px -50px 0px" // animasi muncul sedikit lebih “masuk”
                });
                elements.forEach(el => observer.observe(el));
            });
        </script>
        
        <!-- Section: Buku Baru-->
        <section id="buku_baru" class="section-buku">
            <div class="container pt-70 pb-60">

                <div class="section-title text-center">
                    <h2 class="mt-0 text-uppercase">
                        Buku <span class="text-theme-color-2">Terbaru</span>
                    </h2>
                    <p>Koleksi terbaru yang tersedia di perpustakaan</p>
                </div>
                <div id="skeleton-buku-baru" class="row">

                    <?php for($i=0; $i<6; $i++): ?>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                            <div class="book-card">

                                <div class="skeleton skeleton-cover"></div>

                                <div class="book-info">
                                    <div class="skeleton skeleton-text"></div>
                                    <div class="skeleton skeleton-text small"></div>
                                </div>

                            </div>
                        </div>
                    <?php endfor; ?>

                </div>
                <div id="carousel-wrapper" style="display:none;">
                    <div class="owl-carousel owl-theme" id="carousel-buku-baru" >
                        <?php

                        function short_text($text, $max = 40) {
                            if (strlen($text) <= $max)
                                return $text;
                            return substr($text, 0, $max) . '...';
                        }
                        ?>
                        <?php if ($buku_baru && count($buku_baru) > 0): ?>
                            <?php foreach ($buku_baru as $b): ?>
                                <div class="item">
                                    <div class="book-card reveal">
                                        <div class="book-cover">
                                            <span class="book-badge">Baru</span>
                                            <a href="<?= base_url(); ?>home/detail/<?= $b->ISBN; ?>/<?= $b->no_klas; ?>" target="_blank">
                                                <img src="<?= base_url('uploads/covers/' . ($b->cover ?: 'cover-medium.jpg')) ?>" onerror="this.onerror=null;this.src='<?= base_url('assets/media/cover-medium.jpg') ?>';">
                                            </a>
                                        </div>
                                        <div class="book-info">

                                            <div class="book-title" title="<?= $b->judul ?>"> <?= short_text($b->judul, 40) ?></div>
                                            <div class="book-author"><?= $b->penulis ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>

                            <div class="empty-buku text-center">
                                <img src="<?= base_url('assets/media/cover-medium.jpg') ?>" style="width:120px;opacity:0.6;">
                                <h4 class="mt-15">Belum Ada Buku Terbaru</h4>
                                <p class="text-muted">Silakan cek kembali nanti untuk koleksi terbaru.</p>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </section>
        
	<!-- Section: Pengumuman -->
        <section id="pengumuman" class="section-pengumuman-carousel">
            <div class="container pt-70 pb-60">

                <div class="section-header">

                    <h2 class="title">
                        Pengumuman <span class="text-theme-color-2">Terbaru</span>
                    </h2>

                    <div class="header-actions">

                        <a href="<?= base_url(); ?>home/artikel/kategori/VxMx" class="btn-lihat-semua">
                            Lihat Semua <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>

                <?php if ($pengumuman && count($pengumuman) > 0): ?>

                    <div class="owl-carousel owl-theme" id="carousel-pengumuman">

                <?php foreach ($pengumuman as $art): 
                    $media = $this->Md_media->getMediaById($art->media_id);

                    $img = (!empty($media) && isset($media[0]['judul']))
                        ? base_url().'uploads/medium/medium_'.$media[0]['judul']
                        : base_url().'assets/media/artikel-medium.jpg';

                    $judul = (strlen($art->judul) > 60)
                        ? substr($art->judul, 0, 60).'...'
                        : $art->judul;

                    $isi = substr(strip_tags($art->isi), 0, 90).'...';
                ?>

                    <div class="item">
                        <div class="content-card reveal">

                            <div class="content-thumb">
                                <a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>">
                                    <img src="<?= $img; ?>">
                                    <div class="content-overlay"></div>
                                </a>
                            </div>

                            <div class="content-info">

                                <div class="content-meta">
                                    <i class="fa fa-calendar"></i>
                                    <?= date('d M Y', strtotime($art->tgl_post)); ?>
                                </div>

                                <h4 class="content-title">
                                    <a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>">
                                        <?= $judul; ?>
                                    </a>
                                </h4>

                                <p class="content-desc"><?= $isi; ?></p>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

                </div>

                <?php else: ?>

                    <div class="empty-pengumuman text-center">
                        <img src="<?= base_url('assets/media/artikel-medium.jpg'); ?>" style="width:120px;opacity:0.6;">
                        <h4 class="mt-15">Belum Ada Pengumuman</h4>
                        <p class="text-muted">Silakan cek kembali nanti.</p>
                    </div>

                <?php endif; ?>

            </div>
        </section>
        
	<!-- Divider: Funfact -->
        <section class="section-funfact">
            <div class="container pt-70 pb-60">
                <div class="row">
                    <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                        <div class="funfact-card">
                            <div class="funfact-icon">
                                <i class="fa fa-book"></i>
                            </div>
                            <h2 data-animation-duration="2000" data-value="<?= $jml; ?>" class="animate-number">0</h2>
                            <p>Buku</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                        <div class="funfact-card">
                            <div class="funfact-icon">
                                <i class="fa fa-files-o"></i>
                            </div>
                            <h2 data-animation-duration="2000" data-value="<?= $eks; ?>" class="animate-number">0</h2>
                            <p>Eksemplar</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                        <div class="funfact-card">
                            <div class="funfact-icon">
                                <i class="fa fa-bookmark"></i>
                            </div>
                            <h2 data-animation-duration="2000" data-value="<?= $pnj; ?>" class="animate-number">0</h2>
                            <p>Peminjaman</p>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                        <div class="funfact-card">
                            <div class="funfact-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <h2 data-animation-duration="2000" data-value="<?= $ang; ?>" class="animate-number">0</h2>
                            <p>Anggota</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 mb-30">
                        <div class="funfact-card highlight">
                            <div class="funfact-icon">
                                <i class="fa fa-history"></i>
                            </div>
                            <h2 data-animation-duration="2000" data-value="<?= $visit; ?>" class="animate-number">0</h2>
                            <p>Pengunjung Web</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Section: Berita -->
        <section id="berita" class="section-content-carousel">
            <div class="container pt-70 pb-60">

                <div class="section-header">
                    <h2 class="title">
                        Berita <span class="text-theme-color-2">Terbaru</span>
                    </h2>

                    <a href="<?= base_url(); ?>home/artikel/kategori/jxMV" class="btn-lihat-semua">
                        Lihat Semua <i class="fa fa-angle-right"></i>
                    </a>
                </div>

                <?php if ($artikel && count($artikel) > 0): ?>

                <div class="owl-carousel owl-theme" id="carousel-berita">

                    <?php foreach ($artikel as $art): 
                        $media = $this->Md_media->getMediaById($art->media_id);

                        $img = (!empty($media) && isset($media[0]['judul']))
                            ? base_url().'uploads/medium/medium_'.$media[0]['judul']
                            : base_url().'assets/media/artikel-medium.jpg';

                        $judul = (strlen($art->judul) > 60)
                            ? substr($art->judul, 0, 60).'...'
                            : $art->judul;

                        $isi = substr(strip_tags($art->isi), 0, 90).'...';
                    ?>

                    <div class="item">
                        <div class="content-card reveal">

                            <div class="content-thumb">
                                <a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>">
                                    <img src="<?= $img; ?>">
                                    <div class="content-overlay"></div>
                                </a>
                            </div>

                            <div class="content-info">

                                <div class="content-meta">
                                    <i class="fa fa-calendar"></i>
                                    <?= date('d M Y', strtotime($art->tgl_post)); ?>
                                </div>

                                <h4 class="content-title">
                                    <a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>">
                                        <?= $judul; ?>
                                    </a>
                                </h4>

                                <p class="content-desc"><?= $isi; ?></p>

                            </div>

                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>

                <?php else: ?>

                <div class="empty-content text-center">
                    <img src="<?= base_url('assets/media/artikel-medium.jpg'); ?>" style="width:120px;opacity:0.6;">
                    <h4 class="mt-15">Belum Ada Berita</h4>
                    <p class="text-muted">Silakan cek kembali nanti.</p>
                </div>

                <?php endif; ?>

            </div>
        </section>
        
        <!--  section buku populer -->
        <section class="section-buku">
            <div class="container pt-70 pb-60">
                <div class="section-title text-center">
                    <h2 class="mt-0 text-uppercase">
                        Buku <span class="text-theme-color-2">Terpopuler</span>
                    </h2>
                    <p>Buku yang paling banyak dipinjam</p>
                </div>
                <div id="skeleton-buku-terbanyak" class="row">
                <?php for($i=0; $i<6; $i++): ?>
                    <div class="col-md-2 col-sm-4 col-xs-6 mb-30">
                        <div class="book-card skeleton-card">
                            <div class="skeleton skeleton-cover"></div>
                            <div class="book-info">
                                <div class="skeleton skeleton-text"></div>
                                <div class="skeleton skeleton-text small"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                </div>
                <div id="carousel-buku-terbanyak-wrapper" style="display:none;">
                    <div class="owl-carousel owl-theme" id="carousel-buku-terbanyak">
                        <?php if (empty($buku_terbanyak)): ?>
                            <!-- EMPTY STATE -->
                            <div class="text-center pt-40 pb-40">
                                <img src="<?= base_url('assets/media/empty-data.png') ?>" style="width:120px; opacity:0.7;">
                                <h5 class="mt-20">Belum ada data buku populer</h5>
                                <p class="text-muted">Data buku populer belum dikonfigurasi, silahkan cek kembali dilain waktu!</p>
                            </div>
                        <?php endif; ?>
                        <?php 
                        if (!empty($buku_terbanyak)):
                            foreach ($buku_terbanyak as $b): ?>
                            <div class="item">
                                <div class="book-card top-book reveal">
                                    <div class="book-cover">
                                        <span class="book-badge">Top</span>
                                        <a href="<?= base_url(); ?>home/detail/<?= $b->ISBN; ?>/<?= $b->no_klas; ?>" target="_blank">
                                            <img src="<?= base_url('uploads/covers/' . ($b->cover ?: 'cover-medium.jpg')) ?>" onerror="this.src='<?= base_url('assets/media/cover-medium.jpg') ?>';">
                                        </a>
                                    </div>
                                    <div class="book-info">
                                        <div class="book-title"><?= short_text($b->judul,40) ?></div>
                                        <div class="book-author"><?= $b->penulis ?></div>
                                        <!--<div class="book-meta"><?= $b->total_pinjam ?>x dipinjam</div>-->
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach; 
                         endif;?>
                    </div>
                </div>             
            </div>
        </section>
        
        <!-- Section Aktivitas Mahasiswa -->
        <section class="section-buku">
            <div class="container pt-70 pb-60">
                <div class="section-title text-center">
                    <h2 class="mt-0 text-uppercase">
                        Aktivitas <span class="text-theme-color-2">Mahasiswa</span>
                    </h2>
                    <p>Peminjam dan pengunjung teraktif di perpustakaan</p>
                </div>
                <div class="row">
                    <!-- KIRI: PEMINJAM -->
                    <div class="col-md-6">
                        <h4 class="sub-title tengah">PEMINJAM TERAKTIF</h4>
                        <div id="skeleton-peminjam" class="row">
                            <?php for ($i = 0; $i < 2; $i++): ?>
                                <div class="col-xs-6 mb-20">
                                    <div class="book-card skeleton-card">
                                        <div class="skeleton skeleton-cover user"></div>
                                        <div class="book-info">
                                            <div class="skeleton skeleton-text"></div>
                                            <div class="skeleton skeleton-text small"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div id="carousel-peminjam-wrapper" style="display:none;">
                            <div class="owl-carousel" id="carousel-peminjam">
                                <?php foreach (($mahasiswa_terbanyak ?? []) as $i => $m): ?>
                                    <div class="item">
                                        <div class="book-card user-card reveal">
                                            <div class="book-cover thumb-info" style="height: 300px !important;">
                                                <span class="rank-badge <?= $i == 0 ? 'rank-1' : '' ?> <?= $i == 1 ? 'rank-2' : '' ?>  <?= $i == 2 ? 'rank-3' : '' ?>">
                                                    #<?= $i + 1 ?>
                                                </span>
                                                <span class="thumb-info-wrapper">
                                                    <img src="<?= base_url().'home/proxy_image?url='.urlencode('https://mahasiswa.pkr.ac.id/files/foto/mahasiswa/'.$m->no_anggota.'/'.$m->angkatan.'/0/0') ?>"
                                                         onerror="this.src='<?= base_url('assets/media/default-user.jpg') ?>';">
                                                    <span class="thumb-info-title">
                                                        <span class="thumb-info-inner"><?= $m->nama ?></span>
                                                        <span class="thumb-info-type"><?= $m->no_anggota ?></span>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="book-info text-center">
                                                <div class="book-author"><?= $m->kelas ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: PENGUNJUNG -->
                    <div class="col-md-6">
                        <h4 class="sub-title tengah">PENGUNJUNG TERAKTIF</h4>
                        <div id="skeleton-pengunjung" class="row">
                            <?php for ($i = 0; $i < 2; $i++): ?>
                                <div class="col-xs-6 mb-20">
                                    <div class="book-card skeleton-card">
                                        <div class="skeleton skeleton-cover user"></div>
                                        <div class="book-info">
                                            <div class="skeleton skeleton-text"></div>
                                            <div class="skeleton skeleton-text small"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div id="carousel-pengunjung-wrapper" style="display:none;">
                            <div class="owl-carousel" id="carousel-pengunjung">
                                <?php foreach (($pengunjung_terakitf ?? []) as $i => $m): ?>
                                    <div class="item">
                                        <div class="book-card user-card reveal">
                                            <div class="book-cover thumb-info" style="height: 300px !important;">
                                                <span class="rank-badge <?= $i == 0 ? 'rank-1' : '' ?> <?= $i == 1 ? 'rank-2' : '' ?>  <?= $i == 2 ? 'rank-3' : '' ?>">
                                                    #<?= $i + 1 ?>
                                                </span>
                                                <span class="thumb-info-wrapper">
                                                    <img src="<?= base_url().'home/proxy_image?url='.urlencode('https://mahasiswa.pkr.ac.id/files/foto/mahasiswa/'.$m->nis.'/'.$m->angkatan.'/0/0') ?>"
                                                         onerror="this.src='<?= base_url('assets/media/default-user.jpg') ?>';">
                                                    <span class="thumb-info-title">
                                                        <span class="thumb-info-inner"><?= $m->nama ?></span>
                                                        <span class="thumb-info-type"><?= $m->nis ?></span>
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="book-info text-center">
                                                <div class="book-author"><?= $m->kelas ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Pop Up-->
        <?php if ($popup && $popup->judul): ?>
        <div id="popupModal" class="popup-overlay">
            <div class="popup-content">
                <span class="popup-close">&times;</span>
                <img src="<?= base_url('uploads/popup/'.$popup->judul) ?>" 
                     class="img-fluid"
                     style="max-width:100%; border-radius:10px;">
            </div>
        </div>
        <?php endif; ?>
        
<?php } ?>
<?php if ($page_content == 'dpengunjung') { ?>
    <style>
    .form-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 2rem 2.5rem;
        max-width: 700px;
        margin: 0 auto;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .form-header { text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f0f0f0; }
    .form-header .icon-wrap { width: 52px; height: 52px; background: #e8f1fb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
    .form-header .icon-wrap i { font-size: 24px; color: #185fa5; }
    .form-header h2 { font-size: 20px; font-weight: 600; margin: 0 0 4px; }
    .form-header p { font-size: 13px; color: #888; margin: 0; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .form-group label { font-size: 13px; font-weight: 500; color: #555; display: flex; align-items: center; gap: 6px; }
    .form-group label i { font-size: 15px; color: #aaa; }
    .required-dot { width: 5px; height: 5px; background: #e24b4a; border-radius: 50%; display: inline-block; }
    .form-group input,
    .form-group textarea { border: 1px solid #e0e0e0; border-radius: 8px; padding: 10px 12px; font-size: 14px; background: #fafafa; transition: border-color 0.15s, box-shadow 0.15s; outline: none; width: 100%; box-sizing: border-box; }
    .form-group input:focus,
    .form-group textarea:focus { border-color: #378add; background: #fff; box-shadow: 0 0 0 3px rgba(55,138,221,0.1); }
    .form-group textarea { resize: vertical; min-height: 110px; }
    .btn-row { display: flex; gap: 10px; margin-top: 8px; }
    .btn-submit { flex: 1; background: #185fa5; color: #fff; border: none; border-radius: 8px; padding: 11px 20px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: #0c447c; }
    .btn-reset { background: transparent; color: #888; border: 1px solid #e0e0e0; border-radius: 8px; padding: 11px 20px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .btn-reset:hover { background: #f5f5f5; }
    .recaptcha-note { text-align: center; font-size: 11px; color: #bbb; margin-top: 14px; }
    @media (max-width: 520px) { .form-row { grid-template-columns: 1fr; } .form-card { padding: 1.5rem 1.25rem; } }        
    </style>
    
    <!-- ===== HEADER SECTION ===== -->
    <section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
        <div class="container pt-60 pb-60">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="title text-white">Pengunjung</h2>
                        <ol class="breadcrumb text-center text-black mt-10">
                            <li><a href="<?= base_url(); ?>home">Home</a></li>
                            <li><a href="<?= base_url(); ?>home/pengunjung">Pengunjung</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FORM BUKU TAMU ===== -->
    <section class="divider">
        <div class="container pt-1">
            <div class="form-card">

                <!-- Header -->
                <div class="form-header">
                    <div class="icon-wrap">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                    <h2>Buku Tamu</h2>
                    <p>Silakan isi data Anda sebelum berkunjung</p>
                </div>

                <form id="contact_form" action="<?= base_url(); ?>home/pengunjung/send" method="post">
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                    <input type="hidden" name="form_botcheck" value="">

                    <!-- Nama & Email -->
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fa fa-user"></i> Nama <span class="required-dot"></span></label>
                            <input name="form_name" type="text" placeholder="Nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-envelope"></i> Email <span class="required-dot"></span></label>
                            <input name="form_email" type="email" placeholder="contoh@email.com" required>
                        </div>
                    </div>

                    <!-- Asal Instansi & No WhatsApp -->
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fa fa-building"></i> Asal Instansi <span class="required-dot"></span></label>
                            <input name="form_asal_instansi" type="text" placeholder="Nama instansi" required> 
                        </div>
                        <div class="form-group">
                            <label><i class="fa fa-whatsapp"></i> No WhatsApp <span class="required-dot"></span></label>
                            <input name="form_nowhatsapp" type="text" placeholder="08xxxxxxxxxx" inputmode="numeric" required>
                        </div>
                    </div>

                    <!-- Tujuan Berkunjung -->
                    <div class="form-group">
                        <label><i class="fa fa-clipboard-text"></i> Tujuan Berkunjung <span class="required-dot"></span></label>
                        <textarea name="form_tujuan_berkunjung" rows="5" placeholder="Tuliskan tujuan kunjungan Anda..."></textarea>
                    </div>

                    <!-- Tombol -->
                    <div class="btn-row">
                        <button type="submit" class="btn-submit" data-loading-text="Please wait...">
                            <i class="fa fa-send"></i> Kirim
                        </button>
                        <button type="reset" class="btn-reset">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>

                    <p class="recaptcha-note">
                        <i class="fa fa-shield"></i>
                        Dilindungi oleh reCAPTCHA &nbsp;·&nbsp; Formulir ini aman dan terenkripsi
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- ===== VALIDASI & AJAX SUBMIT ===== -->
   <script>
    // Fungsi terpisah untuk proses kirim form via AJAX
    function kirimForm(form) {
        var $form      = $(form);
        var $btn       = $form.find('button[type="submit"]');
        var oldBtnText = $btn.html();

        // Hapus div hasil lama, buat baru
        $('#form-result').remove();
        $btn.before('<div id="form-result" class="alert" role="alert" style="display:none;"></div>');

        $btn.prop('disabled', true).html($btn.data('loading-text'));

        $form.ajaxSubmit({
            dataType: 'json',

            success: function (data) {
                var $result = $('#form-result');
                if (data.status === true) {
                    form.reset();
                    $result.removeClass('alert-warning alert-danger')
                           .addClass('alert-success')
                           .html(data.message)
                           .fadeIn('slow');
                } else {
                    $result.removeClass('alert-success alert-danger')
                           .addClass('alert-warning')
                           .html(data.message)
                           .fadeIn('slow');
                }
                $btn.prop('disabled', false).html(oldBtnText);
                setTimeout(function () { $result.fadeOut('slow'); }, 6000);
            },

            error: function (xhr) {
                var $result = $('#form-result');
                var errMsg  = (xhr.responseJSON && xhr.responseJSON.message)
                              ? xhr.responseJSON.message
                              : 'Terjadi kesalahan. Silakan coba lagi nanti.';
                $result.removeClass('alert-success')
                       .addClass('alert-danger')
                       .html(errMsg)
                       .fadeIn('slow');
                $btn.prop('disabled', false).html(oldBtnText);
                setTimeout(function () { $result.fadeOut('slow'); }, 6000);
            }
        });
    }

    // Validasi form + ambil token reCAPTCHA sebelum submit
    $("#contact_form").validate({
        submitHandler: function (form) {
            grecaptcha.ready(function () {
                grecaptcha.execute('<?= $recaptcha_site_key ?>', { action: 'submit_pengunjung' })
                          .then(function (token) {
                              // Sisipkan token ke hidden input, lalu kirim
                              $('#recaptcha_token').val(token);
                              kirimForm(form);
                          });
            });
        }
    });
    </script>

<?php } ?>
<?php if ($page_content == 'perpanjang_pinjam') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Perpanjang Peminjaman Buku</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/perpanjang_pinjam">Perpanjang Peminjaman Buku</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="divider">

		<div class="container pt-1">
			<u>
				<h2 style="text-align:center; margin-top:-50px;" class="mb-10">
					<strong>Perpanjang Peminjaman Buku</strong>
				</h2>
			</u>

			<div class=" row mb-60 bg-deep">
				<!-- disini -->

				<div class="col-md-12">
					<!-- <h4 class="mt-0 mb-30 line-bottom">Hubungi Kami</h4> -->
					<!-- Contact Form -->
					<form id="contact_form" name="contact_form" class="" action="<?= base_url(); ?>home/perpanjang_pinjam/cekdatapinjam" method="post">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="form_name">No anggota <small>*</small></label>
									<input name="no_anggota" class="form-control" type="text" placeholder="no anggota" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>No buku yang dipinjam <small>*</small></label>
									<input name="no_buku" class="form-control" type="text" placeholder="no buku" required="">
								</div>
							</div>
						</div>

						<div class="form-group">
							<input name="form_botcheck" class="form-control" type="hidden" value="" />
							<button type="submit" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px" data-loading-text="Please wait...">Kirim</button>
							<button type="reset" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px">Reset</button>
						</div>
					</form>

					<!-- Contact Form Validation-->
					<script type="text/javascript">
						function perpanjang_peminjaman(id) {

							$.ajax({
								url: "<?php echo base_url() . 'home/perpanjang_pinjam/perpanjang_peminjaman' ?>/" + id,
								type: "GET",
								dataType: "JSON",
								success: function(data) {
									if (data.status == false) {
										alert(data.message);
										return;
									}
									$("#msg_success").show();
									$('#isi_pinjam').empty();
									//tambahkan form 
									var html = `
														<div style="overflow-x:auto;">
														<table class="table table-bordered">
															<tr>
															<th>Judul Buku</th>
															<th>Tanggal Pinjam</th>
															<th>Tanggal Batas Peminjaman</th>
															<th>Status Perpanjangan</th>
															<th>Aksi</th>
															</tr>
															<tr>
															<td>${data.data.judul}</td>
															<td>${data.data.tgl_pinjam}</td>
															<td>${data.data.batas}</td>
															<td>${data.data.is_perpanjang}</td>
															<td>${data.data.aksi}</td>
															</tr>
														</table>
														</div>`;

									$('#isi_pinjam').html(html);

								},
								error: function(jqXHR, textStatus, errorThrown) {
									alert('Error get data from ajax');
								},
								complete: function(data) {
									//mApp.unblockPage();
								}
							});
						}
						$("#contact_form").validate({
							submitHandler: function(form) {
								var form_btn = $(form).find('button[type="submit"]');
								var form_result_div = '#form-result';
								$(form_result_div).remove();
								form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
								var form_btn_old_msg = form_btn.html();
								form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
								$(form).ajaxSubmit({
									dataType: 'json',
									success: function(data) {
										if (data.status == true) {
											console.log('aaa ntap');
											$(form).find('.form-control').val('');
											form.reset(); // Reset form after successful submission
											$(form_result_div).removeClass('alert-warning alert-danger').addClass('alert-success').html(data.message).fadeIn('slow');
											// Clear the existing content in #isi_pinjam
											$('#isi_pinjam').empty();
											$("#msg_success").hide();
											//tambahkan form 
											var html = `
														<div style="overflow-x:auto;">
														<table class="table table-bordered">
															<tr>
															<th>Judul Buku</th>
															<th>Tanggal Pinjam</th>
															<th>Tanggal Batas Peminjaman</th>
															<th>Status Perpanjangan</th>
															<th>Aksi</th>
															</tr>
															<tr>
															<td>${data.data.judul}</td>
															<td>${data.data.tgl_pinjam}</td>
															<td>${data.data.batas}</td>
															<td>${data.data.is_perpanjang}</td>
															<td>${data.data.aksi}</td>
															</tr>
														</table>
														</div>`;

											$('#isi_pinjam').html(html);
										} else if (data.status == false) {
											$(form_result_div).removeClass('alert-success alert-danger').addClass('alert-warning').html(data.message).fadeIn('slow');
										}
										console.log(data.status + ' ni apa');
										form_btn.prop('disabled', false).html(form_btn_old_msg);
										setTimeout(function() {
											$(form_result_div).fadeOut('slow')
										}, 6000);
									},
									error: function(xhr, status, error) {
										form_btn.prop('disabled', false).html(form_btn_old_msg);
										var errorMsg = 'Terjadi kesalahan. Silakan coba lagi nanti.';
										if (xhr.responseJSON && xhr.responseJSON.message) {
											errorMsg = xhr.responseJSON.message;
										}
										$(form_result_div).removeClass('alert-success').addClass('alert-danger').html(errorMsg).fadeIn('slow');
										setTimeout(function() {
											$(form_result_div).fadeOut('slow')
										}, 6000);
									}
								});
							}
						});
					</script>
				</div>



			</div>
			<div class="alert alert-success" style="display:none;" id="msg_success">
				<strong>Berhasil!</strong> Data Berhasil Disimpan.
			</div>
			<div id="isi_pinjam">

			</div>

		</div>


	</section>

<?php } ?>
<?php if ($page_content == 'daftar_anggota_luar') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Daftar Anggota Luar</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/perpanjang_pinjam">Daftar Anggota Luar</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="divider">

		<div class="container pt-1">
			<u>
				<h2 style="text-align:center; margin-top:-50px;" class="mb-10">
					<strong>Daftar Anggota Luar</strong>
				</h2>
			</u>

			<div class=" row mb-60 bg-deep">
				<!-- disini -->

				<div class="col-md-12">
					<!-- <h4 class="mt-0 mb-30 line-bottom">Hubungi Kami</h4> -->
					<!-- Contact Form -->
					<form id="anggotaluar_form" name="anggotaluar_form" class="" action="<?= base_url(); ?>home/daftar_anggota_luar/add" method="post" enctype="multipart/form-data">

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="form_name">NO KTP/NIK <small>*</small></label>
									<input name="no_anggota_al" class="form-control" type="text" placeholder="no ktp/nik" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama <small>*</small></label>
									<input name="nama_al" class="form-control" type="text" placeholder="nama" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Jenis Kelamin <small>*</small></label>
									<select class="form-select form-control h-auto py-2" name="jk_al" name="city" required="">
										<option value="lk">Laki-laki</option>
										<option value="pr">Perempuan</option>

									</select>


								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>No Telepon <small>*</small></label>
									<input name="notelp_al" class="form-control required" placeholder="0000-0000-0000" required inputmode="numeric">

								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Tempat Lahir <small>*</small></label>
									<input name="tempatlahir_al" class="form-control" type="text" placeholder="tempat lahir" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Tanggal Lahir <small>*</small></label>
									<input name="tgllahir_al" class="form-control" type="date" placeholder="tanggal lahir" required="">
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label>Alamat <small>*</small></label>
									<input name="alamat" class="form-control" type="text" placeholder="alamat" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Nama Instansi <small>*</small></label>
									<input name="instansi_asal_nama_al" class="form-control" type="text" placeholder="nama instansi" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Alamat Instansi <small>*</small></label>
									<input name="instansi_asal_alamat_al" class="form-control" type="text" placeholder="alamat instansi" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Jabatan <small>*</small></label>
									<input name="jabatan_semester_al" class="form-control" type="text" placeholder="jabatan/semester" required="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label>Pas foto <small>*</small></label>
									<input type="file" id="file_pasphoto" name="pasfoto_al" class="form-control m-input " placeholder="Pas foto" required="">

									<span class="m-form__help text-info">File type allowed: <code>JPG|JPEG</code> </span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<input name="form_botcheck" class="form-control" type="hidden" value="" />
							<button type="submit" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px" data-loading-text="Please wait...">Kirim</button>
							<button type="reset" class="btn btn-flat btn-theme-colored text-uppercase mt-10 mb-sm-30 border-left-theme-color-2-4px">Reset</button>
						</div>
					</form>

					<!-- Contact Form Validation-->
					<script type="text/javascript">
						$("#anggotaluar_form").validate({
							submitHandler: function(form) {
								var form_btn = $(form).find('button[type="submit"]');
								var form_result_div = '#form-result';
								$(form_result_div).remove();
								form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
								var form_btn_old_msg = form_btn.html();
								form_btn.html(form_btn.prop('disabled', true).data("loading-text"));

								// Menggunakan FormData untuk mengirim data termasuk file gambar
								var formData = new FormData(form);

								$.ajax({
									url: $(form).attr('action'),
									type: 'POST',
									data: formData,
									dataType: 'json',
									processData: false, // Jangan memproses data secara otomatis
									contentType: false, // Jangan menetapkan tipe konten
									success: function(data) {
										if (data.status == true) {
											console.log('aaa ntap');
											$(form).find('.form-control').val('');
											form.reset(); // Reset form setelah pengiriman berhasil
											$(form_result_div).removeClass('alert-warning alert-danger').addClass('alert-success').html(data.message).fadeIn('slow');
										} else if (data.status == false) {
											$(form_result_div).removeClass('alert-success alert-danger').addClass('alert-warning').html(data.message).fadeIn('slow');
										}
										console.log(data.status + ' ni apa');
										form_btn.prop('disabled', false).html(form_btn_old_msg);
										setTimeout(function() {
											$(form_result_div).fadeOut('slow');
										}, 6000);
									},
									error: function(xhr, status, error) {
										form_btn.prop('disabled', false).html(form_btn_old_msg);
										var errorMsg = 'Terjadi kesalahan. Silakan coba lagi nanti.';
										if (xhr.responseJSON && xhr.responseJSON.message) {
											errorMsg = xhr.responseJSON.message;
										}
										$(form_result_div).removeClass('alert-success').addClass('alert-danger').html(errorMsg).fadeIn('slow');
										setTimeout(function() {
											$(form_result_div).fadeOut('slow');
										}, 6000);
									}
								});
							}
						});
					</script>
				</div>



			</div>
			<div class="alert alert-success" style="display:none;" id="msg_success">
				<strong>Berhasil!</strong> Data Berhasil Disimpan.
			</div>
			<div id="isi_pinjam">

			</div>

		</div>


	</section>

<?php } ?>
<?php if ($page_content == 'halaman') { ?>
	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<!-- Section Content -->
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white"><?= $halaman->judul_halaman ?></h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= $halaman->judul_halaman ?>"><?= $halaman->judul_halaman ?></a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="position-inherit">
		<div class="container">
			<div class="row">
				<?= $halaman->isi_halaman; ?>
				<script>
					/* scrollto fixed script */
					$(document).ready(function(e) {
						if ($(window).width() >= 768) {
							$('.scrolltofixed-sidebar').scrollToFixed({
								marginTop: $('.header .header-nav').outerHeight(true) + 100,
								limit: function() {
									var limit = $('.footer').offset().top - $(this).outerHeight(true) - 10;
									return limit;
								}
							});
						}
					});
				</script>
			</div>
		</div>
		</div>
	</section>

<?php } ?>
<?php if ($page_content == 'resensi') { ?>


	<section class="inner-header divider parallax layer-overlay overlay-dark-8" data-bg-img="http://placehold.it/1920x1280">
		<div class="container pt-60 pb-60">
			<div class="section-content">
				<div class="row">
					<div class="col-md-12 text-center">
						<h2 class="title text-white">Resensi Buku</h2>
						<ol class="breadcrumb text-center text-black mt-10">
							<li><a href="<?= base_url(); ?>home">Home</a></li>
							<li><a href="<?= base_url(); ?>home/resensi">Resensi Buku</a></li>
						</ol>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section>
		<div class="container mt-30 mb-30 pt-30 pb-30">
			<div class="row">
				<div class="col-md-9 center-block">

					<div class="row mb-30">
						<div class="col-md-12">
							<form action="<?= base_url('home/resensi'); ?>" method="get" class="form-inline text-center">
								<div class="form-group">
									<div class="input-group">
										<input type="text" name="q" class="form-control" placeholder="Cari Judul Buku atau ISBN..." value="<?= isset($search_keyword) ? $search_keyword : '' ?>" style="width: 300px;">
										<span class="input-group-btn">
											<button type="submit" class="btn btn-theme-colored btn-flat"><i class="fa fa-search"></i> Cari Resensi</button>
										</span>
									</div>
								</div>
								<?php if (isset($search_keyword) && $search_keyword != '') : ?>
									<div class="mt-10">
										<small>Menampilkan hasil untuk: <b>"<?= $search_keyword; ?>"</b> (<a href="<?= base_url('home/resensi'); ?>">Reset</a>)</small>
									</div>
								<?php endif; ?>
							</form>
						</div>
					</div>
					<hr>
					<div class="blog-posts">
						<?php if (isset($resensi_list) && count($resensi_list) > 0) : ?>
							<?php foreach ($resensi_list as $row) : ?>
								<?php
								// --- PENENTUAN DATA (Logic Gate) ---
								// Jika buku_id > 0 (Internal), jika 0 (Manual)
								$is_manual = ($row->buku_id == 0);

								$judul_final   = $is_manual ? $row->judul_buku : $row->judul_db;
								$isbn_final    = $is_manual ? $row->isbn       : $row->isbn_db;
								$penulis_final = $is_manual ? $row->penulis    : $row->penulis_db;
								$tahun_final   = $is_manual ? $row->thn_terbit : $row->thn_db;

								// Ambil nama file cover (biasanya tersimpan di tabel resensi kolom 'cover' untuk manual, atau cover_db untuk internal jika ada join)
								// Asumsi: $row->cover menampung nama file hasil upload manual, atau nama file dari DB buku
								$cover_final   = !empty($row->cover) ? $row->cover : (isset($row->cover_db) ? $row->cover_db : '');

								// --- LOGIKA CEK GAMBAR COVER ---
								$cover_img = base_url('assets/media/cover-medium.jpg'); // Default Image

								if (!empty($cover_final)) {
									// Tentukan path folder
									if ($is_manual) {
										// Jika Input Manual (Eksternal)
										$path_folder = 'assets/media/cover_buku_manual/';
									} else {
										// Jika Dari Database (Internal)
										$path_folder = 'uploads/covers/';
									}

									// Cek fisik file di server
									if (file_exists(FCPATH . $path_folder . $cover_final)) {
										$cover_img = base_url($path_folder . $cover_final);
									}
								}
								// --------------------------------
								?>

								<article class="post clearfix mb-30 pb-30 border-bottom-theme-color-2-1px">
									<div class="row">
										<div class="col-sm-4">
											<div class="post-thumb">
												<img src="<?= $cover_img; ?>" alt="<?= $judul_final; ?>" class="img-responsive img-fullwidth img-thumbnail">
											</div>
										</div>
										<div class="col-sm-8">
											<div class="entry-content">
												<h4 class="entry-title text-capitalize m-0">
													<a href="<?= base_url('home/detail_resensi/' . encrypt($row->resensi_id)); ?>">
														<?= $judul_final; ?>
													</a>
												</h4>

												<div class="bg-lightest p-15 mt-10 mb-10 border-left-theme-color-2-3px">
													<ul class="list-unstyled font-13">
														<li><i class="fa fa-barcode text-theme-colored"></i> <b>ISBN:</b> <?= $isbn_final ? $isbn_final : '-'; ?></li>
														<li><i class="fa fa-pencil text-theme-colored"></i> <b>Penulis:</b> <?= $penulis_final ? $penulis_final : '-'; ?></li>
														<li><i class="fa fa-building text-theme-colored"></i> <b>Penerbit:</b> <?= $row->nama_penerbit ? $row->nama_penerbit : 'N/A'; ?> (<?= $tahun_final; ?>)</li>
													</ul>
												</div>

												<div class="mt-10 mb-10">
													<p><?= (strlen(strip_tags($row->isi_resensi)) > 200) ? substr(strip_tags($row->isi_resensi), 0, 200) . '...' : strip_tags($row->isi_resensi); ?></p>
												</div>

												<a href="<?= base_url('home/detail_resensi/' . encrypt($row->resensi_id)); ?>" class="btn btn-theme-colored btn-sm btn-flat">Selengkapnya</a>
											</div>
										</div>
									</div>
								</article>
							<?php endforeach; ?>

							<div class="row">
								<div class="col-md-12 text-center">
									<nav>
										<?= $links; ?>
									</nav>
								</div>
							</div>

						<?php else : ?>
							<div class="alert alert-warning text-center">
								<h4><i class="fa fa-info-circle"></i> Tidak ditemukan resensi dengan kata kunci "<b><?= $search_keyword; ?></b>".</h4>
								<p><a href="<?= base_url('home/resensi'); ?>" class="btn btn-dark btn-sm mt-10">Tampilkan Semua</a></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php } ?>

<?php if ($page_content == 'detail_resensi') {
	// Sinkronisasi Data Master vs Manual
	$judul    = !empty($row->judul_db) ? $row->judul_db : $row->judul_buku;
	$isbn     = !empty($row->isbn_db) ? $row->isbn_db : $row->isbn;
	$penulis  = !empty($row->penulis_db) ? $row->penulis_db : $row->penulis;
	$tahun    = !empty($row->thn_db) ? $row->thn_db : $row->thn_terbit;
	$tajuk    = !empty($row->tajuk_db) ? $row->tajuk_db : $row->tajuksubyek;
	$edisi    = !empty($row->edisi_db) ? $row->edisi_db : $row->edisi;
	$cetakan  = !empty($row->cetakan_db) ? $row->cetakan_db : $row->cetakan;
	$halaman  = !empty($row->hal_db) ? $row->hal_db : $row->jml_hal;

	// Kolom Baru dari Gambar Tabel Anda
	$penyadur = !empty($row->penyadur_db) ? $row->penyadur_db : $row->penyadur;
	$jenis    = !empty($row->jenis_db) ? $row->jenis_db : $row->jenis_buku;

	$kategori = !empty($row->nmkategori) ? $row->nmkategori : '-';
	$penerbit = !empty($row->nama_penerbit) ? $row->nama_penerbit : '-';

	// Gambar Cover
	// Gambar Cover Default
	$cover_img = base_url() . 'assets/media/cover-medium.jpg';

	// Ambil nama file (prioritas dari DB buku, kalau kosong ambil dari input manual)
	$file_cover = !empty($row->cover_db) ? $row->cover_db : $row->cover;

	if (!empty($file_cover)) {
		// Tentukan path folder berdasarkan Jenis Buku
		if ($jenis == 'Internal') {
			$path_folder = 'uploads/covers/';
		} else {
			// Jika Eksternal
			$path_folder = 'assets/media/cover_buku_manual/';
		}

		// Cek apakah file fisik benar-benar ada di server
		if (file_exists(FCPATH . $path_folder . $file_cover)) {
			// Jika ada, set URL gambar sesuai folder
			$cover_img = base_url() . $path_folder . $file_cover;
		}
	}
?>

	<section>
            <div class="container mt-30 mb-30 pt-30 pb-30">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="blog-posts single-post">
                            <article class="post clearfix mb-0">
                                <div class="entry-header">
                                    <div class="row">
                                        <div class="col-md-4">
                                                <img src="<?= $cover_img; ?>" alt="<?= $judul; ?>" class="img-responsive img-thumbnail" style="width:100%">
                                        </div>
                                        <div class="col-md-8">
                                            <h2 class="entry-title mt-0 pt-0"><?= $judul; ?></h2>

                                            <div class="mt-20 p-20 bg-lightest border-1px">
                                                <ul class="list-unstyled font-14" style="line-height: 2.1;">
                                                    <li><strong>Penulis:</strong> <?= $penulis; ?></li>
                                                    <?php if (!empty($penyadur)) : ?>
                                                            <li><strong>Penyadur/Editor:</strong> <?= $penyadur; ?></li>
                                                    <?php endif; ?>

                                                    <li><strong>Tajuk:</strong> <?= (!empty($tajuk)) ? $tajuk : '-'; ?></li>
                                                    <li><strong>Kategori:</strong> <?= $kategori; ?></li>
                                                    <li><strong>Edisi | Cetakan:</strong> <?= $edisi; ?> | <?= $cetakan; ?></li>
                                                    <li><strong>Penerbit:</strong> <?= $penerbit; ?>, <?= $tahun; ?></li>
                                                    <li><strong>ISBN:</strong> <?= $isbn; ?></li>
                                                    <li><strong>Jumlah Halaman:</strong> <?= $halaman; ?> hlm</li>
                                                </ul>
                                            </div>

                                            <div class="entry-meta mt-10">
                                                <small class="text-gray"><i class="fa fa-user"></i> Reviewer: <?= $row->author; ?> | <i class="fa fa-calendar"></i> <?= date('d F Y', strtotime($row->tgl_resensi)); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Availability -->
                                            <h5><strong>Availability:</strong></h5>
                                            <table class="table table-striped table-bordered table-hover">
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Barcode</th>
                                                    <th>No Klasifikasi</th>
                                                    <th>Lokasi</th>
                                                    <th>Gedung</th>
                                                    <th>No Rak</th>
                                                    <th>Status</th>
                                                </tr>
                                                <?php
                                                if (!empty($inv)) {
                                                    $count = 0;
                                                    foreach ($inv as $i) {
                                                        $count++;
                                                        $statusPeminjaman = 'Tersedia';
                                                        $statusBuku = '';
                                                        $color = 'bg-theme-colored';
                                                        $isPinjam = $this->Md_siperpus_transaksi->isPinjam($i->no_inv);
                                                        if ($isPinjam) {
                                                            $color = '';
                                                            $tran = $this->Md_siperpus_transaksi->getTransaksiByNoInv($i->no_inv);
                                                            $statusPeminjaman = 'Buku Sedang Dipinjam (Batas :' . $tran[0]->batas . ')';
                                                        } else {
                                                            $hilang = $this->Md_siperpus_hilangrusak->getHilangByInv($i->no_inv);
                                                            if (!empty($hilang)) {
                                                                if ($hilang[0]->ket == 'H') $statusBuku = ' Tapi tidak dapat dipinjam - Hilang';
                                                                if ($hilang[0]->ket == 'R') $statusBuku = ' Tapi tidak dapat dipinjam - Rusak';
                                                                if ($hilang[0]->ket == 'A') $statusBuku = ' Tapi tidak dapat dipinjam - Diarsipkan';
                                                                if ($hilang[0]->ket == 'L') $statusBuku = ' Tapi tidak dapat dipinjam - Dilelang';
                                                                $color = 'light';
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?= $count; ?></td>
                                                            <td><?= $i->no_barcode; ?></td>
                                                            <td><?= $i->no_klas; ?></td>
                                                            <td><?= $i->nama_kampus; ?></td>
                                                            <td><?= $i->nama_gedung; ?></td>
                                                            <td><?= $i->nama_rak; ?></td>
                                                            <td>
                                                                <span class="text-highlight <?= $color; ?>">
                                                                    <?= $statusPeminjaman; ?><?= $statusBuku; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="entry-content mt-30">
                                    <hr>
                                    <h4 class="text-theme-colored"><i class="fa fa-pencil"></i> Ulasan Lengkap:</h4>
                                    <div class="text-justify font-15" style="line-height: 1.8; color: #333;">
                                            <?= $row->isi_resensi; ?>
                                    </div>
                                </div>

                                <div class="mt-30">
                                    <a href="<?= base_url('home/resensi'); ?>" class="btn btn-dark btn-flat btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
	</section>
<?php } ?>
<!-- end main-content -->
<script type="text/javascript">
	function download(file_name) {
		$.ajax({
			url: "<?= base_url(); ?>home/download/",
			type: "POST",
			data: $('#form_download').serialize(),
			dataType: "JSON",
			success: function(e) {
				$("#myModal").modal('hide');
				location.reload();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$("#myModal").modal('hide');
				alert('Username atau Password Salah.');
			}
		});
	}

	function show_modal(file_name) {
		$("#downloadfooter").empty();
		$("#downloadfooter").append("<input id=\"btnSave\" onclick=\"download('" + file_name + "')\" type=\"submit\" class=\"btn btn-primary\" value=\"Download\">");
		$("#myModal").modal();
	}
</script>
</script>