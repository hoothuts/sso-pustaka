<style>
	.table-container {
		max-height: 300px;
		overflow-y: auto;
	}

	table {
		width: 100%;
		border-collapse: collapse;
	}

	th,
	td {
		padding: 8px;
		text-align: left;
		border-bottom: 1px solid #ddd;
	}

	th {
		position: sticky;
		top: 0;
		background-color: #f2f2f2;
	}

	tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	tr:hover {
		background-color: #ddd;
	}
</style>
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
								Data Anggota
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

		<script type="text/javascript">
			var page_action = 'list';
		</script>
		<div class="m-portlet m-portlet--mobile">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							<?php echo $page_title; ?> [ <?php echo $no_anggota ?> - <?php echo $nm_anggota ?> ]

						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">

				<!--begin::Section-->
				<div class="m-section">
					<div class="m-section__content">
						<div class="table-container">
							<table class="table m-table m-table--head-bg-info">
								<thead>
									<tr>
										<th>NO</th>
										<th>NO Inv</th>
										<th>Judul</th>
										<th>Tgl. Pinjam</th>
										<th>Tgl. Kembali</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if ($data_trx) {


										foreach ($data_trx as $datatrx) { ?>
											<tr>
												<td><?php echo $datatrx['no'] ?></td>
												<td><?php echo $datatrx['no_inv'] ?></td>
												<td><?php echo $datatrx['judul'] ?></td>
												<td><?php echo $datatrx['tgl_pinjam'] ?></td>
												<td><?php echo $datatrx['tgl_kembali'] ?></td>
											</tr>
										<?php }
									} else { ?>
										<tr>
											<td colspan="5">
												<center>Tidak ada data</center>
											</td>
										</tr>
									<?php } ?>


								</tbody>
							</table>
						</div>
					</div>
				</div>

				<!--end::Section-->


			</div>
		</div>
	</div>
</div>