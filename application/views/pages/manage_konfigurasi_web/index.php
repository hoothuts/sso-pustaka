<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <h3 class="m-portlet__head-text">
                        Konfigurasi Beranda
                    </h3>
                </div>
            </div>

            <div class="m-portlet__body">

                <div class="alert alert-info">
                    Konfigurasi ini digunakan untuk menentukan data yang tampil di halaman beranda.
                </div>

                <form id="form-konfigurasi">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="20%">Jenis</th>
                                <th>Keterangan</th>
                                <th width="12%">Tanggal Awal</th>
                                <th width="12%">Tanggal Akhir</th>
                                <th width="10%">Jumlah Data</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($data as $i => $row): ?>

                                <tr>
                                    <td>
                                        <b><?= $row->jenis_konfigurasi ?></b>
                                        <input type="hidden" name="id[]" value="<?= $row->konfigurasiweb_id ?>">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" name="keterangan[]" 
                                               value="<?= $row->keterangan ?>">
                                    </td>

                                    <td>
                                        <input type="date" class="form-control" name="tgl_awal[]" 
                                               value="<?= $row->tgl_awal ?>">
                                    </td>

                                    <td>
                                        <input type="date" class="form-control" name="tgl_akhir[]" 
                                               value="<?= $row->tgl_akhir ?>">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control" name="jumlah_data[]" 
                                               value="<?= $row->jumlah_data ?>">
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="la la-save"></i> Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-konfigurasi').on('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Simpan perubahan?',
            text: "Pastikan data sudah benar",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#047d78',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan'
        }).then((result) => {

            if (result.isConfirmed) {

                // loading
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "<?= base_url('dir/manage_konfigurasi_web/save') ?>",
                    type: "POST",
                    data: $('#form-konfigurasi').serialize(),
                    dataType: "json",
                    success: function (res) {

                        if (res.status === 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                confirmButtonColor: '#047d78'
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: res.message,
                                confirmButtonColor: '#d33'
                            });

                        }

                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem',
                            confirmButtonColor: '#d33'
                        });
                    }
                });

            }

        });

    });
</script>