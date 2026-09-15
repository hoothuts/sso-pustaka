<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Edit Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_inventaris" method="post" action="<?= base_url('dir/manage_inventaris/save') ?>">
                    <input type="hidden" name="no_inv" value="<?= $edit_data->no_inv ?>">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No Inventaris</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?= $edit_data->no_inv ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No Barcode</label>
                        <div class="col-lg-4">
                            <input type="text" name="no_barcode" class="form-control" value="<?= $edit_data->no_barcode ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Inventaris</label>
                        <div class="col-lg-4">
                            <input type="date" name="tgl_inv" class="form-control" value="<?= $edit_data->tgl_inv ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Judul Buku</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?= htmlspecialchars($edit_data->judul ?? '-') ?>" disabled>
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
                        <label class="col-lg-3 col-form-label">Asal Buku</label>
                        <div class="col-lg-4">
                            <select name="asal" class="form-control" required>
                                <option value="">-- Pilih Asal Buku --</option>
                                <?php foreach ($asal_buku as $asal): ?>
                                    <option value="<?= htmlspecialchars($asal->id) ?>" 
                                            <?= ($edit_data->asal == $asal->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($asal->nama) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="col-lg-3 col-form-label">
                            NO. Rak :
                        </label>
                        <div class="col-lg-4">
                            <select class="form-control select2" name="lokasirak_id" style="width:100%">
                                <option value="">-- Pilih Rak --</option>
                                <?php foreach ($data['lokasi'] as $group => $raks): ?>
                                    <optgroup label="<?php echo $group; ?>">
                                        <?php foreach ($raks as $rak): ?>
                                            <option value="<?php echo $rak['value']; ?>" <?= ($edit_data->lokasirak_id == $rak['value']) ? 'selected' : '' ?>>
                                                <?php echo $rak['text']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dir/manage_inventaris') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form_inventaris').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        window.location.href = '<?= base_url('dir/manage_inventaris') ?>';
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal terhubung ke server', 'error');
            }
        });
    });
</script>