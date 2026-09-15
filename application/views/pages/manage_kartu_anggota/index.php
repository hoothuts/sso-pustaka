<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Kartu Anggota</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <div class="alert alert-info">
                    <strong>Petunjuk Upload:</strong><br>
                    • Hanya gambar (JPG, JPEG, PNG, GIF)<br>
                    • Ukuran gambar <strong>HARUS tepat 1040 × 650 piksel</strong><br>
                    • Desain lama akan otomatis dinonaktifkan saat upload desain baru
                </div>

                <div class="row">

                    <!-- Kartu Anggota Depan -->
                    <div class="col-lg-6">
                        <h4 class="m--font-bold text-center">Desain Kartu Anggota Depan</h4>
                        
                        <?php if ($kartu_depan && $kartu_depan->judul): ?>
                            <div class="text-center mb-4">
                                <img src="<?= base_url('uploads/kartu_anggota/' . $kartu_depan->judul) ?>" 
                                     class="img-fluid border rounded" 
                                     style="max-width:100%; height:auto; box-shadow:0 4px 12px rgba(0,0,0,0.15);"
                                     alt="<?= htmlspecialchars($kartu_depan->alt_teks) ?>">
                                
                                <div class="mt-3">
                                    <a href="<?= base_url('uploads/kartu_anggota/' . $kartu_depan->judul) ?>" 
                                       class="btn btn-success btn-sm" 
                                       download="Kartu_Anggota_Depan.<?= pathinfo($kartu_depan->judul, PATHINFO_EXTENSION) ?>">
                                        <i class="la la-download"></i> Download Desain Depan
                                    </a>
                                </div>
                                
                                <p class="mt-2 text-muted small">
                                    Terakhir diupdate: <?= date('d/m/Y', strtotime($kartu_depan->tgl_perubahan)) ?> 
                                    oleh <?= htmlspecialchars($kartu_depan->author) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-5 border rounded mb-4 bg-light">
                                <i class="la la-image" style="font-size:100px;color:#ccc;"></i>
                                <p class="mt-3">Belum ada desain kartu depan</p>
                            </div>
                        <?php endif; ?>

                        <form id="form_depan" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="depan">
                            <div class="form-group">
                                <label>Ganti Desain Depan (1040×650)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Upload & Ganti Desain Depan</button>
                        </form>
                    </div>

                    <!-- Kartu Anggota Belakang -->
                    <div class="col-lg-6">
                        <h4 class="m--font-bold text-center">Desain Kartu Anggota Belakang</h4>
                        
                        <?php if ($kartu_belakang && $kartu_belakang->judul): ?>
                            <div class="text-center mb-4">
                                <img src="<?= base_url('uploads/kartu_anggota/' . $kartu_belakang->judul) ?>" 
                                     class="img-fluid border rounded" 
                                     style="max-width:100%; height:auto; box-shadow:0 4px 12px rgba(0,0,0,0.15);"
                                     alt="<?= htmlspecialchars($kartu_belakang->alt_teks) ?>">
                                
                                <div class="mt-3">
                                    <a href="<?= base_url('uploads/kartu_anggota/' . $kartu_belakang->judul) ?>" 
                                       class="btn btn-success btn-sm" 
                                       download="Kartu_Anggota_Belakang.<?= pathinfo($kartu_belakang->judul, PATHINFO_EXTENSION) ?>">
                                        <i class="la la-download"></i> Download Desain Belakang
                                    </a>
                                </div>
                                
                                <p class="mt-2 text-muted small">
                                    Terakhir diupdate: <?= date('d/m/Y', strtotime($kartu_belakang->tgl_perubahan)) ?> 
                                    oleh <?= htmlspecialchars($kartu_belakang->author) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <div class="text-center p-5 border rounded mb-4 bg-light">
                                <i class="la la-image" style="font-size:100px;color:#ccc;"></i>
                                <p class="mt-3">Belum ada desain kartu belakang</p>
                            </div>
                        <?php endif; ?>

                        <form id="form_belakang" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="belakang">
                            <div class="form-group">
                                <label>Ganti Desain Belakang (1040×650)</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Upload & Ganti Desain Belakang</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    // Form Depan
    $('#form_depan').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "<?= base_url('dir/manage_kartu_anggota/save') ?>",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Gagal terhubung ke server', 'error');
            }
        });
    });

    // Form Belakang
    $('#form_belakang').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "<?= base_url('dir/manage_kartu_anggota/save') ?>",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Gagal terhubung ke server', 'error');
            }
        });
    });
});
</script>