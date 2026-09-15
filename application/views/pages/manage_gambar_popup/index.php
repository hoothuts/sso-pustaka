<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">Manage Gambar Pop Up</h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">

                    <div class="alert alert-info">
                        • Format gambar: JPG, PNG, GIF <br>
                        • Gambar akan otomatis di resize<br>
                        • Upload baru akan menggantikan yang lama
                    </div>

                    <?php if ($popup && $popup->judul): ?>
                        <div class="text-center mb-4">
                            <img src="<?= base_url('uploads/popup/' . $popup->judul) ?>"
                                 class="img-fluid border rounded"
                                 style="max-width:750px">

                            <p class="mt-2">
                                Update: <?= date('d/m/Y', strtotime($popup->tgl_perubahan)) ?>
                            </p>

                            <button id="btn_hapus" class="btn btn-danger btn-sm">
                                Hapus Pop Up
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-5 border">
                            Belum ada gambar popup
                        </div>
                    <?php endif; ?>

                    <form id="form_popup" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Upload Gambar Pop Up</label>
                            <input type="file" name="gambar" class="form-control" required>
                        </div>
                        <button class="btn btn-primary btn-block">Upload</button>
                    </form>

                </div>
            </div>
        </div>
        
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form_popup').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "<?= base_url('dir/manage_gambar_popup/save') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success')
                            .then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }
        });
    });

    $('#btn_hapus').click(function () {
        Swal.fire({
            title: 'Yakin?',
            text: 'Pop up akan dinonaktifkan',
            icon: 'warning',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= base_url('dir/manage_gambar_popup/delete') ?>", function (res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.message, 'success')
                                .then(() => location.reload());
                    }
                }, 'json');
            }
        });
    });
</script>