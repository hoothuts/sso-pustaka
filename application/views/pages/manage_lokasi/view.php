<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Detail <?= ucfirst($type) ?></h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">ID</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->id ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nama <?= ucfirst($type) ?></label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->nama ?>" disabled>
                    </div>
                </div>
                <?php if ($type == 'kampus'): ?>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Alamat</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?= $edit_data->alamat ?>" disabled>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Keterangan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?= $edit_data->keterangan ?>" disabled>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Post</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->tgl_post ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Author</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->author ?>" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-4">
                        <span class="m-badge m-badge--wide m-badge--rounded <?= $edit_data->status == 1 ? 'm-badge--success' : 'm-badge--danger' ?>">
                            <?= $edit_data->status == 1 ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                </div>
                <hr>
                <a href="<?= base_url('dir/manage_lokasi') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>