<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Edit Penghapusan Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_penghapusan" method="post" action="<?= base_url('dir/manage_penghapusan/save') ?>">
                    <input type="hidden" name="penghapusan_id" value="<?= encrypt($edit_data->penghapusan_id) ?>">

                    <!-- Header Info -->
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
                            <input type="date" name="tgl_penghapusan" class="form-control" 
                                   value="<?= $edit_data->tgl_penghapusan ?>" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan</label>
                        <div class="col-lg-6">
                            <textarea name="catatan" class="form-control" rows="5"><?= htmlspecialchars($edit_data->catatan ?? '') ?></textarea>
                        </div>
                    </div>

                    <hr>
                    <h4>Detail Buku dari Penyiangan</h4>

                    <!-- Tombol Check All / Uncheck All -->
                    <div class="m--margin-bottom-10">
                        <button type="button" id="check_all" class="btn btn-success btn-sm">Check All</button>
                        <button type="button" id="uncheck_all" class="btn btn-warning btn-sm">Uncheck All</button>
                    </div>

                    <!-- Detail Buku - Card Layout (sama seperti Add) -->
                    <div id="detail_container">
                        <?php if (!empty($edit_details)): ?>
                            <?php foreach ($edit_details as $index => $detail): ?>
                                <div class="m-portlet m-portlet--bordered-semi m-portlet--rounded m-portlet--shadow-sm m--margin-bottom-20">
                                    <div class="m-portlet__body" style="padding-bottom: 5px !important;">
                                        <div class="row align-items-center">
                                            <!-- Kolom Kecil: Checkboxt -->
                                            <div class="col-md-1 text-center" style="padding-left:0px !important;padding-right:0px !important;top:-4px !important;">
                                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand m-checkbox--solid">
                                                    <input type="checkbox" name="penyiangandetail_id[]" value="<?= $detail->penyiangandetail_id ?>" checked>
                                                    <span></span>
                                                </label>
                                            </div>
                                            
                                            <!-- Info Buku -->
                                            <div class="col-md-6" style="padding-left:0px !important;">
                                                <div class="d-flex align-items-center">
                                                    <div class="m--margin-right-15">
                                                        <span class="m-badge m-badge--primary m-badge--wide m-badge--rounded"><?= $index + 1 ?></span>
                                                    </div>
                                                    <div>
                                                        <h5 class="m--font-weight-600 m--no-margin">
                                                            <?= htmlspecialchars(strtoupper($detail->no_inv ?? '')) ?>
                                                        </h5>
                                                        <span class="text-muted">
                                                            Barcode: <strong><?= htmlspecialchars($detail->no_barcode ?? '-') ?></strong>
                                                        </span>
                                                        <p class="m--margin-top-5 m--no-margin-bottom text-muted" style="font-size: 13px;">
                                                            <?= htmlspecialchars($detail->judul ?? 'Judul tidak tersedia') ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-2 text-center">
                                                <span class="m-badge m-badge--focus m-badge--wide m-badge--rounded">
                                                    <?= htmlspecialchars($detail->status_buku ?? '-') ?>
                                                </span>
                                            </div>

                                            <!-- Keterangan Tambahan -->
                                            <div class="col-md-3">
                                                <input type="text" name="keterangan_tambahan[]" 
                                                       value="<?= htmlspecialchars($detail->keterangan_tambahan ?? '') ?>"
                                                       placeholder="Keterangan tambahan (opsional)" 
                                                       class="form-control m-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted">Tidak ada detail buku.</p>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= base_url('dir/manage_penghapusan') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Tombol Check All & Uncheck All
        $('#check_all').click(function() {
            $('#detail_container input[type="checkbox"]').prop('checked', true);
        });

        $('#uncheck_all').click(function() {
            $('#detail_container input[type="checkbox"]').prop('checked', false);
        });

        // Submit form
        $('#form_penghapusan').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('dir/manage_penghapusan') ?>';
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