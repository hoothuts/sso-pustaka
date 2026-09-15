<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= $is_view_mode ? 'Detail Klasifikasi' : (isset($edit_data) ? 'Edit Klasifikasi' : 'Tambah Klasifikasi') ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_klasifikasi" action="<?= base_url('dir/manage_klasifikasi/save') ?>" method="post">
                    <?php if (isset($edit_data)): ?>
                        <input type="hidden" name="id_for_edit" value="<?= urlencode(base64_encode($edit_data->id)) ?>">
                    <?php endif; ?>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">ID Klasifikasi</label>
                        <div class="col-lg-2">
                            <input type="text" <?php if (!isset($edit_data)){ echo 'name="id"'; } ?>  class="form-control" value="<?= $edit_data->id ?? '' ?>" <?= isset($edit_data) ? 'readonly' : '' ?> required maxlength="2">
                            <small class="form-text text-muted">Maksimal 2 karakter (contoh: 10, 20, AG)</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nama Klasifikasi</label>
                        <div class="col-lg-6">
                            <input type="text" name="nama" class="form-control" value="<?= $edit_data->nama ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Kode Warna</label>
                        <div class="col-lg-2">
                            <input type="color" name="kode_warna" class="form-control" value="<?= $edit_data->kode_warna ?? '#ffffff' ?>" style="width: 50px; height: 50px; padding: 0; border: 1px solid #000;" required>
                            <small class="form-text text-muted">Contoh: #FF0000 (merah)</small>
                        </div>
                    </div>

                    <hr>
                    <?php
                    if($is_view_mode==false){?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php
                    }
                    ?>
                    <a href="<?= base_url('dir/manage_klasifikasi') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#form_klasifikasi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('dir/manage_klasifikasi') ?>';
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
    });
</script>