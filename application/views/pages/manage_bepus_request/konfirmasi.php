<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Konfirmasi Request Bebas Pustaka</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <?php if (isset($request)): ?>
                    <form id="form_konfirmasi" action="<?= base_url('dir/manage_bepus_request/save_konfirmasi') ?>" method="post">
                        <input type="hidden" name="id_for_edit" value="<?= encrypt($request->bepusrequest_id) ?>">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">NO Request :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($request->no_request ?? '') ?>" readonly disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Nama :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($request->nama ?? '') ?>" readonly disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">NIM :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($request->nim ?? '') ?>" readonly disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Program Studi :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($request->kelas ?? '') ?>" readonly disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Tahun Akademik :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars($request->tahun_akademik ?? '') ?>" readonly disabled/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Tanggal Request :</label>
                            <div class="col-lg-4">
                                <input class="form-control" type="text" value="<?= htmlspecialchars(date('d-M-Y H:i:s', strtotime($request->tgl_post ?? ''))) ?>" readonly disabled/>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Status Pengajuan</label>
                            <div class="col-lg-4">
                                <select name="status_pengajuan" id="status_pengajuan" class="form-control" required>
                                    <option value="Approve" <?= ($request->status_pengajuan == 'Approve') ? 'selected' : '' ?>>Approve</option>
                                    <option value="Reject"  <?= ($request->status_pengajuan == 'Reject')  ? 'selected' : '' ?>>Reject</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Catatan Umum (Kepala Perpustakaan)</label>
                            <div class="col-lg-8">
                                <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($request->notes ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <hr>
                        <h5>Data Hibah Buku</h5>

                        <?php if(!empty($request->hibah)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Pengarang</th>
                                        <th>Penerbit</th>
                                        <th>Tempat Terbit</th> 
                                        <th>Tahun</th>
                                        <th>ISBN</th>
                                        <th>Harga Buku/<br/>Harga Taksiran</th>
                                        <th>Keterangan</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($request->hibah as $h): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($h->judul_buku) ?></td>
                                        <td><?= htmlspecialchars($h->pengarang) ?></td>
                                        <td><?= htmlspecialchars($h->penerbit) ?></td>
                                        <td><?= htmlspecialchars($h->tempat_terbit) ?></td>
                                        <td><?= htmlspecialchars($h->tahun_terbit) ?></td>
                                        <td><?= htmlspecialchars($h->isbn) ?></td>
                                        <td><?= !empty($h->harga_buku) ? number_format($h->harga_buku,0,',','.') : '' ?></td>
                                        <td><?= htmlspecialchars($h->keterangan) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php else: ?>
                        <div class="alert alert-info">Tidak ada data hibah buku</div>
                        <?php endif; ?>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Detail Persyaratan</h5>
                            
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Persyaratan</th>
                                        <th width="100" class="text-center">Status Syarat</th>
                                        <th>Catatan Pustakawan</th>
                                        <th>Pustakawan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($request->details as $detail): 
                                        $is_parent = ($detail->level == 1);
                                        $show_checkbox = ($detail->is_isian == 'Ya');
                                    ?>
                                        <tr <?= $is_parent ? 'class="parent-row"' : '' ?>>
                                            <td class="text-center"><?= $is_parent ? $no++ : '' ?></td>
                                            <td>
                                                <?= $is_parent ? '<strong>' : '↳ ' ?>
                                                <?= $detail->persyaratan ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($show_checkbox): ?>
                                                    <!-- Hidden field agar nilai selalu terkirim -->
                                                    <input type="hidden" 
                                                           name="detail_status[<?= $detail->bepusrequestdetail_id ?>]" 
                                                           value="<?= ($detail->status_syarat == 'OK') ? 'OK' : 'NOT OK' ?>">

                                                    <label class="m-checkbox m-checkbox--success">
                                                        <input type="checkbox" 
                                                               disabled
                                                               <?= ($detail->status_syarat == 'OK') ? 'checked' : '' ?>>
                                                        <span></span>
                                                    </label>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?php if ($show_checkbox): ?>
                                                    <input type="text" 
                                                           readonly 
                                                           class="form-control" 
                                                           value="<?= htmlspecialchars($detail->note_pustakawan ?? '') ?>">
                                                    <!-- Kirim catatan sebagai hidden agar tidak perlu diubah -->
                                                    <input type="hidden" 
                                                           name="detail_note[<?= $detail->bepusrequestdetail_id ?>]" 
                                                           value="<?= htmlspecialchars($detail->note_pustakawan ?? '') ?>">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?= $detail->check_by ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Simpan Konfirmasi</button>
                        <a href="<?= base_url('dir/manage_bepus_request') ?>" class="btn btn-secondary">Kembali</a>
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

    $('#form_konfirmasi').submit(function(e) {
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
table td {
    vertical-align: middle;
    font-size: 13px;
}
.parent-row {
    background-color: #f0f4f8 !important;
}
.parent-row td {
    color: #2c3e50;
}
</style>