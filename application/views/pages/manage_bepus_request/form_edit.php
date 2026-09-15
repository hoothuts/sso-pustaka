<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Edit Request Bebas Pustaka</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <?php if (isset($siswa)): ?>
                    <div class="alert alert-info">
                        <strong>Data Mahasiswa:</strong><br>
                        NIM: <?= htmlspecialchars($siswa->nis ?? '') ?> | 
                        Nama: <?= htmlspecialchars($siswa->nama ?? '') ?> | 
                        Kelas: <?= htmlspecialchars($siswa->kelas ?? '-') ?>
                    </div>

                    <form id="form_request" action="<?= base_url('dir/manage_bepus_request/save') ?>" method="post">
                        <input type="hidden" name="nim" value="<?= htmlspecialchars($nim) ?>">
                        <input type="hidden" name="is_edit" value="1">
                        <input type="hidden" name="id_for_edit" value="<?= $id_enc ?>">

                        <h4 class="mb-4">Persyaratan Bebas Pustaka</h4>

                        <div class="syarat-form">
                            <?php 
                            $no_induk = 1;
                            foreach ($syarat_list as $row): 
                                if ($row->level == 1): 
                            ?>
                                <div class="parent-item mb-3">
                                    <div class="d-flex align-items-start">
                                        <strong class="mr-3"><?= $no_induk++ ?>.</strong>
                                        <div class="flex-grow-1">
                                            <strong><?= $row->persyaratan ?></strong>
                                            <?php if ($row->is_isian == 'Ya'): ?>
                                                <div class="mt-2">
                                                    <label class="m-checkbox m-checkbox--success">
                                                        <input type="checkbox" name="checkbox[]" 
                                                               value="<?= $row->bepussyarat_id ?>"
                                                               <?= $this->Md_bepus_request->is_checked($request->bepusrequest_id, $row->bepussyarat_id) ? 'checked' : '' ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="child-item ml-5 mb-2">
                                    <div class="d-flex align-items-start">
                                        <span class="mr-3">↳</span>
                                        <div class="flex-grow-1">
                                            <?= $row->persyaratan ?>
                                            <?php if ($row->is_isian == 'Ya'): ?>
                                                <div class="mt-1">
                                                    <label class="m-checkbox m-checkbox--success">
                                                        <input type="checkbox" name="checkbox[]" 
                                                               value="<?= $row->bepussyarat_id ?>"
                                                               <?= $this->Md_bepus_request->is_checked($request->bepusrequest_id, $row->bepussyarat_id) ? 'checked' : '' ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Update Request</button>
                        <a href="<?= base_url('dir/manage_bepus_request') ?>" class="btn btn-secondary">Batal</a>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">Data request tidak ditemukan.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#form_request').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success').then(() => {
                        window.location.href = '<?= base_url('dir/manage_bepus_request') ?>';
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }
        });
    });
});
</script>

<style>
.parent-item { padding: 12px 15px; background: #f8f9fa; border-left: 4px solid #34bfa3; border-radius: 4px; }
.child-item { padding: 8px 15px; background: #fff; border-left: 3px solid #ccc; }
</style>