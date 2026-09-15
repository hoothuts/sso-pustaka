<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Konfirmasi Request Penghapusan Inventaris</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_konfirmasi" method="post" action="<?= base_url('dir/konfirmasi_penghapusan/konfirmasi_save') ?>">
                    <input type="hidden" name="penghapusan_id" value="<?= encrypt($edit_data->penghapusan_id) ?>">

                    <!-- Detail Header -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No Penghapusan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?= $edit_data->no_penghapusan ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Penghapusan</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($edit_data->tgl_penghapusan)) ?>" disabled>
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
                    <div class="m_datatable m-datatable--default m-datatable--loaded" id="detail_table">
                        <table class="table table-sm m-table m-table--head-bg-brand">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>NO</th>
                                    <th>NO Inventaris</th>
                                    <th>ISBN</th>
                                    <th>NO Barcode</th>
                                    <th>Judul Buku</th>
                                    <th>Status Penyiangan</th>
                                    <th>Keterangan Penghapusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($edit_details) && !empty($edit_details)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($edit_details as $detail): ?>
                                        <tr>
                                            <td align="center"><?= $no++ ?></td>
                                            <td><strong><?= htmlspecialchars(strtoupper($detail->no_inv) ?? '-') ?></strong></td>
                                            <td><strong><?= htmlspecialchars($detail->ISBN ?? '-') ?></strong></td>
                                            <td><strong><?= htmlspecialchars($detail->no_barcode ?? '-') ?></strong></td>
                                            <td><?= htmlspecialchars($detail->judul ?? 'Judul tidak tersedia') ?></td>
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
                                        <td colspan="5" class="text-center">Tidak ada detail buku</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Form Konfirmasi -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Status Konfirmasi <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select name="status_penghapusan" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Approve">Approve</option>
                                <option value="Reject">Reject</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan Kepala Perpustakaan <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <textarea name="catatan_kaperpus" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="m--margin-top-20">
                        <button type="submit" class="btn btn-primary">Submit Konfirmasi</button>
                        <a href="<?= base_url('dir/konfirmasi_penghapusan') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#form_konfirmasi').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('dir/konfirmasi_penghapusan') ?>';
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