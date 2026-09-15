<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= $is_view_mode ? 'Detail Lokasi' : (isset($edit_data) ? 'Edit Lokasi' : 'Tambah Lokasi') ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_lokasi" action="<?= base_url('dir/manage_lokasi/save') ?>" method="post">
                    <?php if (isset($edit_data)): 
                        // Tentukan nama ID field berdasarkan type
                        $id_field = '';
                        if ($type == 'kampus') {
                            $id_field = 'lokasikampus_id';
                        } elseif ($type == 'gedung') {
                            $id_field = 'lokasigedung_id';
                        } elseif ($type == 'rak') {
                            $id_field = 'lokasirak_id';
                        }
                        ?>
                        <input type="hidden" name="id_for_edit" value="<?= urlencode(base64_encode($edit_data->{$id_field})) ?>">
                        <input type="hidden" name="type" value="<?= $type ?>">
                    <?php endif; ?>

                    <?php if (!isset($edit_data)): ?>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Jenis Lokasi</label>
                            <div class="col-lg-4">
                                <select name="type" id="jenis_lokasi" class="form-control m-select2" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="kampus">Lokasi Kampus</option>
                                    <option value="gedung">Gedung</option>
                                    <option value="rak">Rak</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                   <!-- Field untuk Gedung -->
                    <div id="field_gedung" style="display: <?= (isset($type) && $type == 'gedung') || (isset($edit_data) && $type == 'gedung') ? 'block' : 'none' ?>;">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Lokasi Kampus</label>
                            <div class="col-lg-4">
                                <select name="lokasikampus_id" id="lokasikampus_id" class="form-control m-select2" <?= $is_view_mode ? 'disabled' : '' ?>>
                                    <option value="">Pilih Kampus</option>
                                    <?php foreach ($kampus_options as $kampus) { ?>
                                        <option value="<?= $kampus->lokasikampus_id ?>" <?= isset($edit_data) && $edit_data->lokasikampus_id == $kampus->lokasikampus_id ? 'selected' : '' ?>>
                                            <?= $kampus->nama_kampus ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Field Rak: Kampus + Gedung -->
                    <div id="field_rak_container" style="display: <?= (isset($type) && $type == 'rak') || (isset($edit_data) && $type == 'rak') ? 'block' : 'none' ?>;">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Lokasi Kampus</label>
                            <div class="col-lg-4">
                                <select name="lokasikampus_id_rak" id="lokasikampus_id_rak" class="form-control m-select2" <?= $is_view_mode ? 'disabled' : '' ?>>
                                    <option value="">Pilih Kampus</option>
                                    <?php foreach ($kampus_options as $kampus) { ?>
                                        <option value="<?= $kampus->lokasikampus_id ?>" <?= isset($edit_data) && $edit_data->lokasikampus_id == $kampus->lokasikampus_id ? 'selected' : '' ?>>
                                            <?= $kampus->nama_kampus ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Lokasi Gedung</label>
                            <div class="col-lg-4">
                                <select name="lokasigedung_id" id="lokasigedung_id_rak" class="form-control m-select2" <?= $is_view_mode ? 'disabled' : '' ?>>
                                    <option value="">Pilih Gedung</option>
                                    <!-- Diisi via JS AJAX -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Field Nama dan Keterangan -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nama</label>
                        <div class="col-lg-4">
                            <input type="text" name="nama" class="form-control" value="<?= isset($edit_data) ? ($type == 'kampus' ? $edit_data->nama_kampus : ($type == 'gedung' ? $edit_data->nama_gedung : $edit_data->nama_rak)) : '' ?>" <?= $is_view_mode ? 'disabled' : '' ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Keterangan</label>
                        <div class="col-lg-4">
                            <?php
                            $keterangan_value = '';
                            if (isset($edit_data)) {
                                // Mapping field keterangan berdasarkan type
                                if ($type == 'kampus') {
                                    $keterangan_value = $edit_data->alamat ?? ''; // kampus pakai alamat sebagai keterangan
                                } elseif ($type == 'gedung') {
                                    $keterangan_value = $edit_data->keterangan ?? '';
                                } elseif ($type == 'rak') {
                                    $keterangan_value = $edit_data->keterangan ?? '';
                                }
                            }
                            ?>
                            <input type="text" name="keterangan" class="form-control" value="<?= $keterangan_value ?>" <?= $is_view_mode ? 'disabled' : '' ?>>
                        </div>
                    </div>

                    <hr>
                   <!-- Tombol Simpan hanya muncul di mode add/edit -->
                    <?php if ($mode != 'view'): ?>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php endif; ?>
                    <a href="<?= base_url('dir/manage_lokasi') ?>" class="btn btn-secondary">Kembali</a>

                    <!-- Disable input di mode view -->
                    <script>
                        <?php if ($mode == 'view'): ?>
                            $('input, select, textarea').prop('disabled', true);
                            $('.m-select2').select2('disable');
                        <?php endif; ?>
                    </script>
                   
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Init semua m-select2
        $('.m-select2').select2({
            placeholder: "Pilih",
            allowClear: true,
            width: '100%'
        });

        // Fungsi tampilkan field sesuai jenis
        function showFields(type) {
            $('#field_kampus, #field_gedung, #field_rak_container').hide();
            if (type == 'kampus') {
                $('#field_kampus').show();
            } else if (type == 'gedung') {
                $('#field_gedung').show();
            } else if (type == 'rak') {
                $('#field_rak_container').show();
            }
        }

        // Event change jenis
        $('#jenis_lokasi').on('change', function() {
            showFields($(this).val());
        });

        // Jika mode edit, set field
        <?php if (isset($type)): ?>
            showFields('<?= $type ?>');
        <?php endif; ?>

        // AJAX load gedung saat pilih kampus
        $('#lokasikampus_id, #lokasikampus_id_rak').on('change', function() {
            var kampus_id = $(this).val();
            var target = (this.id == 'lokasikampus_id') ? '#lokasigedung_id' : '#lokasigedung_id_rak';

            if (kampus_id) {
                $.ajax({
                    url: '<?= base_url('dir/manage_lokasi/get_gedung_options') ?>',
                    type: 'POST',
                    data: { kampus_id: kampus_id },
                    dataType: 'json',
                    success: function(res) {
                        $(target).empty();
                        $(target).append('<option value="">Pilih Gedung</option>');
                        $.each(res, function(i, item) {
                            $(target).append('<option value="' + item.lokasigedung_id + '">' + item.nama_gedung + '</option>');
                        });
                        $(target).trigger('change');
                    }
                });
            } else {
                $(target).empty().append('<option value="">Pilih Gedung</option>');
            }
        });

        // Submit form dengan validasi manual
        $('#form_lokasi').submit(function(e) {
            e.preventDefault();

            var type = $('#jenis_lokasi').val() || '<?= isset($type) ? $type : '' ?>';
            var errors = [];

            // Validasi nama
            if (!$('input[name="nama"]').val().trim()) {
                errors.push('Nama tidak boleh kosong');
            }

            // Validasi jenis
            if (type == 'gedung') {
                if (!$('#lokasikampus_id').val()) errors.push('Lokasi Kampus tidak boleh kosong');
            } else if (type == 'rak') {
                if (!$('#lokasikampus_id_rak').val()) errors.push('Lokasi Kampus tidak boleh kosong');
                if (!$('#lokasigedung_id_rak').val()) errors.push('Lokasi Gedung tidak boleh kosong');
            }

            if (errors.length > 0) {
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: errors.join('<br>'),
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
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
                            window.location.href = '<?= base_url('dir/manage_lokasi') ?>';
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
        
        // Trigger load gedung otomatis saat edit (jika ada nilai kampus)
        <?php if (isset($edit_data) && ($type == 'gedung' || $type == 'rak')): ?>
            var initialKampusId = '<?= $edit_data->lokasikampus_id ?? '' ?>';
            if (initialKampusId) {
                var target = ('<?= $type ?>' == 'gedung') ? '#lokasigedung_id' : '#lokasigedung_id_rak';
                $.ajax({
                    url: '<?= base_url('dir/manage_lokasi/get_gedung_options') ?>',
                    type: 'POST',
                    data: { kampus_id: initialKampusId },
                    dataType: 'json',
                    success: function(res) {
                        $(target).empty();
                        $(target).append('<option value="">Pilih Gedung</option>');
                        $.each(res, function(i, item) {
                            var selected = (item.lokasigedung_id == '<?= $edit_data->lokasigedung_id ?? '' ?>') ? 'selected' : '';
                            $(target).append('<option value="' + item.lokasigedung_id + '" ' + selected + '>' + item.nama_gedung + '</option>');
                        });
                        $(target).trigger('change');
                    }
                });
            }
        <?php endif; ?>
        
    });
</script>