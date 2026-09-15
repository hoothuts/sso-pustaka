<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Detail Penghapusan Inventaris Buku</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="<?= base_url('dir/manage_penghapusan/export_excel/' . encrypt($edit_data->penghapusan_id)) ?>" 
                        class="btn btn-success">
                        <i class="la la-file-excel-o"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="m-portlet__body">

                <!-- Informasi Utama -->
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No Penghapusan</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->no_penghapusan ?? '-') ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No Penyiangan</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->no_dokumen ?? '-') ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Penghapusan</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($edit_data->tgl_penghapusan)) ?>" disabled>
                    </div>
                </div>

                <!-- STATUS & INFORMASI KONFIRMASI -->
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status Penghapusan</label>
                    <div class="col-lg-4">
                        <?php 
                        $status = $edit_data->status_penghapusan ?? 'Pending';
                        $badgeClass = 'm-badge--warning';

                        switch ($status) {
                            case 'Approve':
                                $badgeClass = 'm-badge--success';
                                break;
                            case 'Reject':
                                $badgeClass = 'm-badge--danger';
                                break;
                            default:
                                $badgeClass = 'm-badge--warning';
                        }
                                                ?>
                        <span class="m-badge <?= $badgeClass ?> m-badge--wide m-badge--rounded">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </div>
                </div>

                <?php if ($status !== 'Pending' && !empty($edit_data->tgl_approve)): ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Konfirmasi</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($edit_data->tgl_approve)) ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Dikonfirmasi Oleh</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->approve_by ?? '-') ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan Kepala Perpustakaan</label>
                    <div class="col-lg-6">
                        <textarea class="form-control" rows="4" disabled><?= htmlspecialchars($edit_data->catatan_kaperpus ?? '-') ?></textarea>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan Penghapusan</label>
                    <div class="col-lg-6">
                        <textarea class="form-control" rows="5" disabled><?= htmlspecialchars($edit_data->catatan ?? '') ?></textarea>
                    </div>
                </div>

                <hr>
                <h4>Detail Buku</h4>

                <!-- Tabel Detail Buku -->
                <div class="m_datatable m-datatable--default">
                    <table class="table table-sm m-table m-table--head-bg-brand m-table--responsive">
                        <thead class="thead-inverse">
                            <tr>
                                <th width="50">NO</th>
                                <th>NO Inventaris</th>
                                <th>NO Barcode</th>
                                <th>ISBN</th>
                                <th>Judul Buku</th>
                                <th>Tahun Terbit</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Asal Buku</th>
                                <th>Tgl Inventaris</th>
                                <th>Status Penyiangan</th>
                                <th>Keterangan Penghapusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($edit_details)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($edit_details as $detail): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($detail->no_inv ?? '-') ?></strong></td>
                                        <td><?= htmlspecialchars($detail->no_barcode ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail->ISBN ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail->judul ?? 'Judul tidak tersedia') ?></td>
                                        <td><?= htmlspecialchars($detail->thn_terbit ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail->penulis ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail->penerbit ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail->asal_buku ?? '-') ?></td>
                                        <td><?= date('d/M/Y', strtotime($detail->tgl_inv)) ?>
                                        <td>
                                            <span class="m-badge m-badge--focus m-badge--wide">
                                                <?= htmlspecialchars($detail->status_buku ?? '-') ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($detail->keterangan_tambahan ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada detail buku</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <hr>
                <a href="<?= base_url('dir/manage_penghapusan') ?>" class="btn btn-secondary">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>