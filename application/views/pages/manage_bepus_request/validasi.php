<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Validasi Request Bebas Pustaka</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <?php if (isset($request)): ?>
                    <form id="form_validasi" action="<?= base_url('dir/manage_bepus_request/save_validasi') ?>" method="post">
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
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Detail Persyaratan</h5>
                            <div>
                                <button type="button" id="checkAll" class="btn btn-sm btn-success">
                                    <i class="la la-check"></i> Centang Semua
                                </button>
                                <button type="button" id="uncheckAll" class="btn btn-sm btn-warning">
                                    <i class="la la-times"></i> Uncentang Semua
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Persyaratan</th>
                                        <th width="130" class="text-center">Status Syarat</th>
                                        <th>Catatan Pustakawan</th>
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
                                                    <label class="m-checkbox m-checkbox--success">
                                                        <input type="checkbox" 
                                                               name="detail_status[<?= $detail->bepusrequestdetail_id ?>]" 
                                                               value="OK"
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
                                                           name="detail_note[<?= $detail->bepusrequestdetail_id ?>]" 
                                                           class="form-control" 
                                                           value="<?= htmlspecialchars($detail->note_pustakawan ?? '') ?>">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <?php
                        if($request->status_pengajuan=='Approve'){?>
                        <div class="alert alert-info">Request Bebas Pustaka telah di approve!</div>
                        <?php                            
                        }else{?>
                        <button type="submit" class="btn btn-primary">Simpan Validasi</button>
                        <a href="<?= base_url('dir/manage_bepus_request') ?>" class="btn btn-secondary">Kembali</a>
                        <?php                            
                        }
                        ?>
                        
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
    $('#checkAll').click(function() {
        $('input[type="checkbox"][name^="detail_status"]').prop('checked', true);
    });

    $('#uncheckAll').click(function() {
        $('input[type="checkbox"][name^="detail_status"]').prop('checked', false);
    });

    $('#form_validasi').submit(function(e) {
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