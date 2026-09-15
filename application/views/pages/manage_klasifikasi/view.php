<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Detail Klasifikasi Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">ID Klasifikasi</label>
                    <div class="col-lg-4">
                        <input type="text" class="form-control" value="<?= $edit_data->id ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Nama Klasifikasi</label>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->nama) ?>" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Kode Warna</label>
                    <div class="col-lg-4">
                        <div style="width:100px;height:40px;background-color:<?= $edit_data->kode_warna ?>;border:1px solid #ccc;"></div>
                        <input type="text" class="form-control mt-2" value="<?= $edit_data->kode_warna ?>" disabled>
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
                <a href="<?= base_url('dir/manage_klasifikasi') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>