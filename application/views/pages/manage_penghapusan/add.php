<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Tambah Penghapusan Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_penghapusan" method="post" action="<?= base_url('dir/manage_penghapusan/save') ?>">
                    <input type="hidden" name="penghapusan_id" value="">

                    <!-- Pilih Penyiangan -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Pilih Penyiangan</label>
                        <div class="col-lg-6">
                            <select class="form-control m-select2" id="penyiangan_select_ajax" name="penyiangan_id" required>
                                <option value=""></option>
                            </select>
                            <input type="hidden" name="penyiangan_id" id="hidden_penyiangan_id" value="">
                            <span class="m-form__help">Cari no dokumen penyiangan yang belum punya penghapusan</span>
                        </div>
                    </div>

                    <!-- Tanggal Penghapusan -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Penghapusan</label>
                        <div class="col-lg-4">
                            <input type="date" name="tgl_penghapusan" class="form-control" required>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan</label>
                        <div class="col-lg-6">
                            <textarea name="catatan" class="form-control" rows="5"></textarea>
                        </div>
                    </div>

                    <hr>
                    <h4>Detail Buku dari Penyiangan</h4>

                    <!-- Tombol Check All / Uncheck All -->
                    <div class="m--margin-bottom-10">
                        <button type="button" id="check_all" class="btn btn-success btn-sm">Check All</button>
                        <button type="button" id="uncheck_all" class="btn btn-warning btn-sm">Uncheck All</button>
                    </div>

                    <!-- Container untuk detail yang di-load via JS -->
                    <div id="detail_container"></div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('dir/manage_penghapusan') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Select2 untuk cari penyiangan
        $('#penyiangan_select_ajax').select2({
            placeholder: "Cari penyiangan...",
            allowClear: true,
            ajax: {
                url: "<?= base_url('dir/manage_penghapusan/search_penyiangan_ajax') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { searchtext: params.term };
                },
                processResults: function(data) {
                    return { results: data.items };
                },
                cache: true
            }
        });

       // AUTO SELECT & LOAD jika datang dari tombol "Buat Penghapusan"
       var preselectedPenyiangan = '<?= $preselected_penyiangan_id ?? '' ?>';

       if (preselectedPenyiangan) {
           console.log('Preselected ID ditemukan:', preselectedPenyiangan);

           // Langkah 1: Fetch teks label penyiangan secara manual (satu kali request)
           $.ajax({
               url: "<?= base_url('dir/manage_penghapusan/search_penyiangan_ajax') ?>",
               dataType: 'json',
               data: { searchtext: preselectedPenyiangan }, // Kirim ID langsung sebagai search
               success: function(data) {
                   console.log('Fetch manual result:', data);

                   if (data.items && data.items.length > 0) {
                       var selectedItem = data.items[0]; // Ambil item pertama (harus cocok ID)

                       // Buat option dengan teks asli
                       var option = new Option(selectedItem.text, preselectedPenyiangan, true, true);
                       $('#penyiangan_select_ajax').append(option).trigger('change');

                       // Set value (sekarang teks sudah ada)
                       $('#penyiangan_select_ajax').val(preselectedPenyiangan).trigger('change.select2');
                       console.log('Select2 berhasil di-set dengan teks:', selectedItem.text);

                       // Load detail
                       loadPenyianganDetails(preselectedPenyiangan);

                       // Set hidden
                       $('#hidden_penyiangan_id').val(preselectedPenyiangan);
                   } else {
                       console.log('Tidak menemukan data untuk ID ini');
                       $('#penyiangan_select_ajax').append(new Option('Data tidak ditemukan', preselectedPenyiangan, true, true)).trigger('change');
                   }
               },
               error: function() {
                   console.log('Gagal fetch teks penyiangan');
                   $('#penyiangan_select_ajax').append(new Option('Error memuat data', preselectedPenyiangan, true, true)).trigger('change');
               }
           });
       }

        // Saat dipilih secara manual
        $('#penyiangan_select_ajax').on('select2:select', function(e) {
            var penyiangan_id = e.params.data.id;
            console.log('Manual select:', penyiangan_id);
            $('#hidden_penyiangan_id').val(penyiangan_id);
            loadPenyianganDetails(penyiangan_id);
        });

        // Tombol Check All / Uncheck All
        $('#check_all').click(function() {
            $('#detail_container input[type="checkbox"]').prop('checked', true);
        });

        $('#uncheck_all').click(function() {
            $('#detail_container input[type="checkbox"]').prop('checked', false);
        });

        // Submit form via AJAX
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

        // Fungsi load detail (kode Anda yang sudah bagus tetap utuh)
        function loadPenyianganDetails(penyiangan_id) {
            $.ajax({
                url: '<?= base_url('dir/manage_penyiangan/fetch_details') ?>',
                type: 'POST',
                data: { penyiangan_id: penyiangan_id },
                dataType: 'json',
                success: function(res) {
                    $('#detail_container').html('');

                    if (res.details && res.details.length > 0) {
                        $('#detail_container').append(`
                            <div class="m--margin-bottom-15">
                                <span class="text-gray-900">Total buku: <strong>${res.details.length}</strong></span>
                            </div>
                        `);

                        res.details.forEach(function(detail, index) {
                            var row = `
                                <div class="m-portlet m-portlet--bordered-semi m-portlet--rounded m-portlet--shadow-sm m--margin-bottom-20">
                                    <div class="m-portlet__body " style="padding-bottom: 5px !important;">
                                        <div class="row align-items-center">
                                            <!-- Checkbox & Nomor -->
                                            <div class="col-md-1 text-center" style="padding-left:0px !important;padding-right:0px !important;top:-4px !important;">
                                                <label class="m-checkbox m-checkbox--bold m-checkbox--state-brand m-checkbox--solid">
                                                    <input type="checkbox" name="penyiangandetail_id[]" value="${detail.penyiangandetail_id}" checked>
                                                    <span></span>
                                                </label>
                                            </div>
                                            <!-- Info Buku -->
                                            <div class="col-md-6" style="padding-left:0px !important;">
                                                <div class="d-flex align-items-center">
                                                    <div class="m--margin-right-15">
                                                        <span class="m-badge m-badge--primary m-badge--wide m-badge--rounded">${index + 1}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="m--font-weight-600 m--no-margin">
                                                            ${detail.no_inv.toUpperCase()}
                                                        </h6>
                                                        <span class="text-muted">
                                                            Barcode: <strong>${detail.no_barcode || '-'}</strong>
                                                        </span>
                                                        <p class="m--margin-bottom-5 ">
                                                            ${detail.judul || 'Judul tidak tersedia'}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Status -->
                                            <div class="col-md-2 text-center">
                                                <span class="m-badge m-badge--focus m-badge--wide m-badge--rounded">
                                                    ${detail.status_buku || '-'}
                                                </span>
                                            </div>
                                            <!-- Keterangan Tambahan -->
                                            <div class="col-md-3">
                                                <input type="text" name="keterangan_tambahan[]" 
                                                       placeholder="Keterangan tambahan (opsional)" 
                                                       class="form-control m-input" 
                                                       value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $('#detail_container').append(row);
                        });
                    } else {
                        $('#detail_container').html(`
                            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline-2x alert alert-info fade show" role="alert">
                                <div class="m-alert__icon">
                                    <i class="la la-info-circle"></i>
                                </div>
                                <div class="m-alert__text">
                                    Tidak ada detail buku di penyiangan ini.
                                </div>
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#detail_container').html(`
                        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline-2x alert alert-danger fade show" role="alert">
                            <div class="m-alert__icon">
                                <i class="la la-warning"></i>
                            </div>
                            <div class="m-alert__text">
                                Gagal memuat detail buku. Silakan coba lagi.
                            </div>
                        </div>
                    `);
                }
            });
        }
    });
</script>