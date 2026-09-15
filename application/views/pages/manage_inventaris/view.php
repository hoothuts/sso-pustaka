<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Detail Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No Inventaris</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->no_inv ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">No Barcode</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->no_barcode ?? '-' ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Inventaris</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($edit_data->tgl_inv)) ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Judul Buku</label>
                    <div class="col-lg-6">
                        <textarea class="form-control" rows="3" disabled><?= htmlspecialchars($edit_data->judul ?? '-') ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">ISBN</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->ISBN ?? '-') ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">NO Klasifikasi</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->no_klas ?? '-') ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Asal</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->asal_buku ?? '-') ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Kampus</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->nama_kampus ?? '-') ?>" disabled>
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Gedung</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->nama_gedung ?? '-') ?>" disabled>
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-lg-3 col-form-label">NO Rak</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->nama_rak ?? '-') ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-4">
                        <span class="m-badge m-badge--wide m-badge--rounded <?= $edit_data->status == 'A' ? 'm-badge--success' : 'm-badge--danger' ?>">
                            <?= $edit_data->status == 'A' ? 'Aktif' : 'Dihapus' ?>
                        </span>
                    </div>
                </div>

                <hr>
                <a href="<?= base_url('dir/manage_inventaris') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>