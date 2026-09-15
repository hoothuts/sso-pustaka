<div class="m-grid__item m-grid__item--fluid m-wrapper">
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">
									Dashboard
								</h3>
							</div>
						</div>
					</div>
					<!-- END: Subheader -->
					<div class="m-content">
					<div class="m-portlet ">
								<div class="m-portlet__body  m-portlet__body--no-padding">
									<div class="row m-row--no-padding m-row--col-separator-xl">
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::Total Profit-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Buku
													</h4>
													<br>
													<span class="m-widget24__desc"></span>
													<span class="m-widget24__stats m--font-brand">
														<?php echo $jml;?>
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<span class="m-widget24__change"></span><span class="m-widget24__number"></span>
												</div>
											</div>
											<!--end::Total Profit-->
										</div>
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Feedbacks-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Eksemplar Buku
													</h4>
													<br>
													<span class="m-widget24__desc"></span>
													<span class="m-widget24__stats m--font-info">
														<?php echo $eks;?>
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<span class="m-widget24__change"></span><span class="m-widget24__number"></span>
												</div>
											</div>
											<!--end::New Feedbacks-->
										</div>
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Orders-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Peminjaman Buku
													</h4>
													<br>
													<span class="m-widget24__desc"></span>
													<span class="m-widget24__stats m--font-danger">
														<?php echo $pnj;?>
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<span class="m-widget24__change"></span><span class="m-widget24__number"></span>
												</div>
											</div>
											<!--end::New Orders-->
										</div>
										<div class="col-md-12 col-lg-6 col-xl-3">
											<!--begin::New Users-->
											<div class="m-widget24">
												<div class="m-widget24__item">
													<h4 class="m-widget24__title">
														Anggota Pustaka
													</h4>
													<br>
													<span class="m-widget24__desc"></span>
													<span class="m-widget24__stats m--font-success">
														<?php echo $ang;?>
													</span>
													<div class="m--space-10"></div>
													<div class="progress m-progress--sm">
														<div class="progress-bar m--bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<span class="m-widget24__change"></span><span class="m-widget24__number"></span>
												</div>
											</div>
											<!--end::New Users-->
										</div>
									</div>
								</div>
							</div>
						
						<div class="m-portlet">
							<div class="m-portlet__body  m-portlet__body--no-padding">
								<div class="row m-row--no-padding m-row--col-separator-xl">
									<div class="col-xl-6">
										<div class="m-widget14">
											<div class="m-widget14__header">
												<h3 class="m-widget14__title">
													Peminjaman Perhari
												</h3>
											</div>
											<div class="m-widget14__chart" id="m_perhari" style="height:320px;">
											</div>
										</div>
									</div>
									<div class="col-xl-6">
										<div class="m-widget14">
											<div class="m-widget14__header">
												<h3 class="m-widget14__title">
													Kunjungan Pustaka
												</h3>
											</div>
											<div class="m-widget14__chart" id="m_kunjungan" style="height:320px;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="portlet-body">
                            <div class="table-scrollable">
                        
						</div>
						</div>
						<!--Begin::Main Portlet-->
						<div class="m-portlet m-portlet--tabs">
									<div class="m-portlet__head">
										<div class="m-portlet__head-tools">
											<ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
												<li class="nav-item m-tabs__item">
													<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_6_1" role="tab">
														<i class="la la-calendar"></i>
														Peminjaman Terakhir
													</a>
												</li>
												<li class="nav-item m-tabs__item">
													<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_3" role="tab">
														<i class="la la-bell-o"></i>
														Buku yang melewati batas peminjaman
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="tab-content">
											<div class="tab-pane active" id="m_tabs_6_1" role="tabpanel">
												<table class="table table-responsive table-striped table-bordered table-hover">
												<tr>
														<th>No</th>
														<th>No. Anggota</th>
														<th>Nama</th>
														<th>Program Studi</th>
														<th>No. Inv</th>
														<th>Judul</th>
														<th>Tgl Pinjam</th>
														<th>Batas Kembali</th>
												</tr>
												<?php 
												if($peminjaman){
													$count=0;
													foreach($peminjaman as $p){
														$count+=1;
														?>
														<tr>
														<td><?php echo $count;?></td>
														<td><?php echo $p->no_anggota;?></td>
														<td><?php echo $p->nama;?></td>
														<td><?php echo $p->kelas;?></td>
														<td><?php echo $p->no_inv;?></td>
														<td><?php echo $p->judul;?></td>
														<td><?php echo $p->tgl_pinjam;?></td>
														<td><?php echo $p->batas;?></td>
														</tr>
														<?php
													}
												}else{
													echo '<tr><td colspan="8">No Data</td></tr>';
												}
												?>
												</table>
											</div>
											<div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
											  <div class="table-scrollable">
												<table class="table table-striped table-bordered table-hover">
												<tr>
														<th>No</th>
														<th>No. Anggota</th>
														<th>Nama</th>
														<th>Program Studi</th>
														<th>No. Inv</th>
														<th>Judul</th>
														<th>Tgl Pinjam</th>
														<th>Batas Kembali</th>
												</tr>
												<?php 
												if($batas){
													$count=0;
													foreach($batas as $p){
														$count+=1;
														?>
														<tr>
														<td><?php echo $count;?></td>
														<td><?php echo $p->no_anggota;?></td>
														<td><?php echo $p->nama;?></td>
														<td><?php echo $p->kelas;?></td>
														<td><?php echo $p->no_inv;?></td>
														<td><?php echo $p->judul;?></td>
														<td><?php echo $p->tgl_pinjam;?></td>
														<td><?php echo $p->batas;?></td>
														</tr>
														<?php
													}
												}else{
													echo '<tr><td colspan="8">No Data</td></tr>';
												}
												?>
												</table>
											</div>
											</div>
										</div>
							</div>
						</div>
						
						
					</div>
				</div>
				
									<!-- end:: Body -->
<script type="text/javascript">
 var DatatableRemoteAjaxDemo=function() {
    var e=function() {
        new Morris.Bar( {
            element:"m_perhari", data:[<?php echo $pnj_hari;?>], xkey:"y", ykeys:["a"], labels:["Pengunjung"]
        }
        )
    },
    f=function() {
        new Morris.Bar( {
            element:"m_kunjungan", data:[<?php echo $pre_hari;?>], xkey:"y", ykeys:["a"], labels:["Pengunjung"]
        }
        )
    }
    ;
    return {
        init:function() {
            e(),
			f()
        }
    }
}

();
jQuery(document).ready(function() {
    DatatableRemoteAjaxDemo.init()
});
</script>