<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Detail Penyiangan Koleksi Buku</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="<?= base_url('dir/manage_penyiangan/export_excel/' . encrypt($edit_data->penyiangan_id)) ?>" 
                        class="btn btn-success">
                        <i class="la la-file-excel-o"></i> Export Excel
                     </a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No Dokumen</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->no_dokumen ?? '-') ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Penyiangan</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($edit_data->tgl_penyiangan)) ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan</label>
                    <div class="col-lg-6">
                        <textarea class="form-control" rows="5" disabled><?= htmlspecialchars($edit_data->catatan ?? '') ?></textarea>
                    </div>
                </div>

                <hr>
                <h4>Detail Buku</h4>

                <!-- Tabel Estetik (sama seperti di penghapusan_view) -->
                <table class="table table-sm m-table m-table--head-bg-brand m-table--responsive">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NO Inventaris</th>
                            <th>NO Barcode</th>
                            <th>ISBN</th>
                            <th>Judul Buku</th>
                            <th>Tahun Terbit</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Asal Buku</th>
                            <th>Tgl Inventaris</th>
                            <th>Status Buku</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($edit_details as $detail): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($detail->no_inv) ?></strong></td>
                            <td><strong><?= htmlspecialchars($detail->no_barcode) ?></strong></td>
                            <td><strong><?= htmlspecialchars($detail->ISBN) ?></strong></td>
                            <td><?= htmlspecialchars($detail->judul ?? '-') ?></td>
                            <td><?= htmlspecialchars($detail->thn_terbit ?? '-') ?></td>
                            <td><?= htmlspecialchars($detail->penulis ?? '-') ?></td>
                            <td><?= htmlspecialchars($detail->penerbit ?? '-') ?></td>
                            <td><?= htmlspecialchars($detail->asal_buku ?? '-') ?></td>
                            <td><?= date('d/M/Y', strtotime($detail->tgl_inv)) ?>
                            <td><span class="m-badge m-badge--focus m-badge--wide"><?= htmlspecialchars($detail->status_buku) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <hr>
                <a href="<?= base_url('dir/manage_penyiangan') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>