<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Tambah Penyiangan Koleksi Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_penyiangan" method="post" action="<?= base_url('dir/manage_penyiangan/save') ?>">
                    <input type="hidden" name="penyiangan_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No Dokumen</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="Akan digenerate otomatis" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Penyiangan</label>
                        <div class="col-lg-4">
                            <input type="date" name="tgl_penyiangan" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan</label>
                        <div class="col-lg-6">
                            <textarea name="catatan" class="form-control" rows="5"></textarea>
                        </div>
                    </div>

                    <hr>
                    <h4>Detail Buku</h4>

                    <div class="m--margin-bottom-20">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" id="barcode_input" class="form-control" placeholder="Scan No Barcode kemudian tekan Enter" autocomplete="off" autofocus>
                            </div>
                            <div class="col-md-7">
                                <button type="button" id="check_all" class="btn btn-success btn-sm">
                                    Check All
                                </button>
                                <button type="button" id="uncheck_all" class="btn btn-warning btn-sm">
                                    Uncheck All
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="detail_container">
                        <!-- Row awal akan ditambahkan via JS -->
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dir/manage_penyiangan') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
       

        // Fungsi update nomor urut
        function updateRowNumbers() {
            $('#detail_container .detail_row').each(function(index) {
                $(this).find('.m-badge').text(index + 1);
            });
        }
        
        $('#barcode_input').keypress(function(e){
            if(e.which==13){
                e.preventDefault();
                var barcode=$(this).val().trim();
                if(barcode=='')
                    return;

                $.ajax({
                    url:"<?=base_url('dir/manage_penyiangan/get_inventaris_barcode')?>",
                    type:"POST",
                    data:{
                        no_barcode:barcode
                    },
                    dataType:"json",
                    success:function(res){
                        if(res.status=='success'){
                            tambahRow(res.data);
                        }else{
                            Swal.fire('Gagal',res.message,'error');
                            $('#barcode_input').select().focus();
                        }
                    },

                    error:function(){
                        Swal.fire('Error','Gagal terhubung ke server','error');
                    }
                });
            }
        });

        // Tambah row baru
        function tambahRow(data)
        {
            if ($('input[name="no_inv[]"][value="' + data.no_inv + '"]').length)
            {
                Swal.fire(
                    'Peringatan',
                    'Barcode sudah ada pada daftar.',
                    'warning'
                );

                $('#barcode_input').val('').focus();
                return;
            }

            var nomor = $('.detail_row').length + 1;

            var row = `
            <div class="m-portlet m-portlet--bordered-semi
                        m-portlet--rounded
                        m-portlet--shadow-sm
                        m--margin-bottom-20 detail_row">
        
                <div class="m-portlet__body" style="padding:15px 20px;">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center" style="padding-left:0;padding-right:0;">
                            <label class="m-checkbox
                                          m-checkbox--bold
                                          m-checkbox--state-brand
                                          m-checkbox--solid">
                                <input type="checkbox" name="checked[]" checked>
                                <span></span>
                            </label>

                            <span class="m-badge m-badge--metal m-badge--wide m-badge--rounded" style="font-size:10px; padding:3px 6px; margin-left:60px;">
                                ${nomor}
                            </span>
                        </div>

                        <div class="col-md-7">
                            <input
                                type="hidden"
                                name="no_inv[]"
                                value="${data.no_inv}">
                            <strong>${data.no_barcode}</strong>
                            <br>
                            ${data.judul}
                        </div>

                        <div class="col-md-2">
                            <select name="status_buku[]" class="form-control">
                                <option value="Rusak Ringan">
                                    Rusak Ringan
                                </option>
                                <option value="Rusak Berat">
                                    Rusak Berat
                                </option>
                                <option value="Hilang">
                                    Hilang
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 text-right">
                            <button
                                type="button"
                                class="btn btn-danger btn-sm remove_row">
                                <i class="la la-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            `;

            $('#detail_container').append(row);
            updateRowNumbers();
            $('#barcode_input').val('').focus();
        }

        // Hapus row
        $(document).on('click', '.remove_row', function() {
            if ($('.detail_row').length > 1) {
                $(this).closest('.detail_row').remove();
                updateRowNumbers();
            } else {
                Swal.fire('Peringatan', 'Minimal harus ada 1 detail buku', 'warning');
            }
        });

        // Check All / Uncheck All
        $('#check_all').click(() => $('#detail_container input[name="checked[]"]').prop('checked', true));
        $('#uncheck_all').click(() => $('#detail_container input[name="checked[]"]').prop('checked', false));

        // Submit AJAX
        $('#form_penyiangan').submit(function(e) {
            e.preventDefault();

            // Validasi minimal 1 detail terpilih
            var hasChecked = false;
            $('input[name="checked[]"]').each(function() {
                if ($(this).is(':checked')) {
                    hasChecked = true;
                    return false;
                }
            });

            if (!hasChecked) {
                Swal.fire('Peringatan', 'Minimal satu buku harus dipilih', 'warning');
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('dir/manage_penyiangan') ?>';
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