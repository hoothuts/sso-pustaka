<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= $is_view_mode ? 'Detail Syarat' : (isset($edit_data) ? 'Edit Syarat' : 'Tambah Syarat Bebas Pustaka') ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_syarat" action="<?= base_url('dir/manage_syarat_bepus/save') ?>" method="post">
                    <?php if (isset($edit_data)): ?>
                        <input type="hidden" name="id_for_edit" value="<?= encrypt($edit_data->bepussyarat_id) ?>">
                    <?php endif; ?>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Level</label>
                        <div class="col-lg-4">
                            <select name="level" id="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="1" <?= (isset($edit_data) && $edit_data->level == 1) ? 'selected' : '' ?>>1 - Parent</option>
                                <option value="2" <?= (isset($edit_data) && $edit_data->level == 2) ? 'selected' : '' ?>>2 - Child</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="parent_row" style="display: <?= (isset($edit_data) && $edit_data->level == 2) ? 'flex' : 'none' ?>;">
                        <label class="col-lg-3 col-form-label">Parent</label>
                        <div class="col-lg-6">
                            <select name="parent_id" class="form-control">
                                <option value="">-- Pilih Parent --</option>
                                <?php foreach ($parents as $p): ?>
                                    <option value="<?= $p->bepussyarat_id ?>" 
                                        <?= (isset($edit_data) && $edit_data->parent_id == $p->bepussyarat_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p->persyaratan) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Persyaratan</label>
                        <div class="col-lg-8">
                            <textarea class="summernote" name="persyaratan" class="form-control" rows="3" required><?= isset($edit_data) ? htmlspecialchars($edit_data->persyaratan) : '' ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Urutan</label>
                        <div class="col-lg-2">
                            <input type="number" name="urutan" class="form-control" value="<?= isset($edit_data) ? $edit_data->urutan : '' ?>" required min="1">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Is Isian</label>
                        <div class="col-lg-4">
                            <select name="is_isian" class="form-control" required>
                                <option value="Ya" <?= (isset($edit_data) && $edit_data->is_isian == 'Ya') ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= (isset($edit_data) && $edit_data->is_isian == 'Tidak') ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Is Aktif</label>
                        <div class="col-lg-4">
                            <select name="is_aktif" class="form-control" required>
                                <option value="Ya" <?= (isset($edit_data) && $edit_data->is_aktif == 'Ya') ? 'selected' : '' ?>>Ya</option>
                                <option value="Tidak" <?= (isset($edit_data) && $edit_data->is_aktif == 'Tidak') ? 'selected' : '' ?>>Tidak</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <?php if (!$is_view_mode): ?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php endif; ?>
                    <a href="<?= base_url('dir/manage_syarat_bepus') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>/assets/admin/summernote.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#level').change(function() {
        if ($(this).val() == 2) {
            $('#parent_row').show();
        } else {
            $('#parent_row').hide();
            $('select[name="parent_id"]').val('');
        }
    });

    $('#form_syarat').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        window.location.href = '<?= base_url('dir/manage_syarat_bepus') ?>';
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