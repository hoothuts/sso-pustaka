<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= $is_view_mode ? 'Detail Opname' : (isset($edit_data) ? 'Edit Opname' : 'Tambah Opname') ?>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <?php if ($is_view_mode): ?>
                        <a href="<?= base_url('dir/manage_opname/export_excel_raw/' . urlencode(base64_encode($edit_data->opname_id))) ?>" 
                            class="btn btn-info mb-3">
                            Export Excel (Detail)
                        </a>
                        <a href="<?= base_url('dir/manage_opname/export_excel/' . urlencode(base64_encode($edit_data->opname_id))) ?>" 
                           class="btn btn-success mb-3">
                            Export Excel
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_opname" action="<?= base_url('dir/manage_opname/save') ?>" method="post" enctype="multipart/form-data">
                    <?php if (isset($edit_data)): ?>
                        <input type="hidden" name="id_for_edit" value="<?= urlencode(base64_encode($edit_data->opname_id)) ?>">
                    <?php endif; ?>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No BA</label>
                        <div class="col-lg-6">
                            <input type="text" name="no_ba" class="form-control" value="<?= $edit_data->no_ba ?? 'Nomor opname dibuat otomatis oleh sistem' ?>" <?= $is_view_mode ? 'readonly' : '' ?> required maxlength="50">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Opname</label>
                        <div class="col-lg-3">
                            <input type="date" name="tgl_opname" class="form-control" value="<?= $edit_data->tgl_opname ?? date('Y-m-d') ?>" <?= $is_view_mode ? 'readonly' : '' ?> required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Lokasi Kampus</label>
                        <div class="col-lg-6">
                            <select name="lokasikampus_id" class="form-control" <?= $is_view_mode ? 'disabled' : '' ?> required>
                                <option value="">-- Pilih Kampus --</option>
                                <?php foreach ($kampus as $k): ?>
                                    <option value="<?= $k->lokasikampus_id ?>"
                                        <?= (isset($edit_data) && $edit_data->lokasikampus_id == $k->lokasikampus_id) ? 'selected' : '' ?>>
                                        <?= $k->nama_kampus ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>    
<!--                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">File Berita Acara (PDF)</label>
                        <div class="col-lg-6">
                            <?php if (isset($edit_data) && $edit_data->file_ba): ?>
                                <p class="mb-2">
                                    File saat ini: 
                                    <a href="<?= base_url('uploads/berita_acara/' . $edit_data->file_ba) ?>" target="_blank"><?= $edit_data->file_ba ?></a>
                                </p>
                            <?php endif; ?>

                            <?php if (!$is_view_mode): ?>
                                <input type="file" name="file_ba" class="form-control" accept="application/pdf" <?= !isset($edit_data) ? 'required' : '' ?>>
                                <small class="form-text text-muted">Hanya PDF • Maksimal 5 MB</small>
                            <?php endif; ?>
                        </div>
                    </div>-->

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan</label>
                        <div class="col-lg-9">
                            <textarea name="catatan" class="form-control" rows="5" <?= $is_view_mode ? 'readonly' : '' ?>><?= $edit_data->catatan ?? '' ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Scan Barcode</label>
                        <div class="col-lg-6">
                            <input type="text" id="input_barcode" class="form-control" <?= $is_view_mode ? 'readonly' : '' ?> placeholder="Scan / input barcode">
                        </div>
                    </div>    
                        
                    <hr>
                    <h5>Inventaris Buku</h5>
                    <table class="table table-sm m-table m-table--head-bg-brand m-table--responsive" id="table_inventaris">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl Pendataan</th>
                                <th>No Inv</th>
                                <th>No Barcode</th>
                                <th>Judul Buku</th>
                                <th>Gedung</th>
                                <th>Rak</th>
                                <th>Keterangan</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($detail_data)): ?>
                            <?php $no = 1; foreach ($detail_data as $d): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d-M-Y', strtotime($d->tgl_post)) ?></td>
                                    <td><?= $d->no_inv ?></td>
                                    <td><?= $d->no_barcode ?></td>
                                    <td><?= $d->judul ?></td>
                                    <td><?= $d->nama_gedung ?></td>
                                    <td><?= $d->nama_rak ?></td>
                                    <td>
                                        <?php if ($is_view_mode): ?>
                                            <?= $d->keterangan ?>
                                        <?php else: ?>
                                            <input type="text" name="keterangan[]" class="form-control" value="<?= $d->keterangan ?>">
                                            <input type="hidden" name="no_inv[]" value="<?= $d->no_inv ?>">
                                            <input type="hidden" name="no_barcode[]" value="<?= $d->no_barcode ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$is_view_mode): ?>
                                            <button type="button" class="btn btn-danger btn-sm remove">X</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if (isset($edit_data)): ?>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Author</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" style="background-color: #e9ecef; color: #495057;" value="<?= $edit_data->author ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Tgl Post</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" style="background-color: #e9ecef; color: #495057;" value="<?= $edit_data->tgl_post ?>" readonly>
                            </div>
                        </div>
                        <!--<div class="form-group row">
                            <label class="col-lg-3 col-form-label">Status</label>
                            <div class="col-lg-4">
                                <span class="m-badge m-badge--wide m-badge--rounded <?= $edit_data->status == 1 ? 'm-badge--success' : 'm-badge--danger' ?>">
                                    <?= $edit_data->status == 1 ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </div>
                        </div>-->
                    <?php endif; ?>

                    <hr>
                    <?php if (!$is_view_mode): ?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php endif; ?>
                    <a href="<?= base_url('dir/manage_opname') ?>" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
    <?php if (!$is_view_mode): ?>    
        $('#input_barcode').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === 'Tab') {
                e.preventDefault(); // cegah submit form

                let barcode = $(this).val();

                if (!barcode) return;
                
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = today.toLocaleString('id-ID', { month: 'short' }); // hasil: "Jul"
                const dd = String(today.getDate()).padStart(2, '0');

                const formattedDate = `${dd}-${mm}-${yyyy}`;

                $.post("<?= base_url('dir/manage_opname/get_barcode') ?>", {barcode: barcode}, function(res) {
                    if (res.status == 'success') {
                        let row = res.data;

                        // cek duplikat
                        if ($(`input[name="no_barcode[]"][value="${row.no_barcode}"]`).length) {
                            Swal.fire('Warning', 'Barcode sudah ditambahkan', 'warning');
                            return;
                        }

                        let no = $('#table_inventaris tbody tr').length + 1;

                        let html = `
                        <tr>
                            <td>${no}</td>
                            <td>${formattedDate}</td>
                            <td>${row.no_inv}</td>
                            <td>${row.no_barcode}</td>
                            <td>${row.judul}</td>
                            <td>${row.nama_gedung}</td>
                            <td>${row.nama_rak}</td>
                            <td>
                                <input type="text" name="keterangan[]" class="form-control">
                                <input type="hidden" name="no_inv[]" value="${row.no_inv}">
                                <input type="hidden" name="no_barcode[]" value="${row.no_barcode}">
                            </td>
                            <td><button type="button" class="btn btn-danger btn-sm remove">X</button></td>
                        </tr>`;

                        $('#table_inventaris tbody').append(html);
                        $('#input_barcode').val('').focus();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    <?php endif; ?>    

        $(document).on('click', '.remove', function() {
            $(this).closest('tr').remove();
        });

        $('#form_opname').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('dir/manage_opname') ?>';
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