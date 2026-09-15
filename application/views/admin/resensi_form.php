<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= isset($edit_data) ? 'Edit' : 'Tambah' ?> Resensi Buku
                        </h3>
                    </div>
                </div>
            </div>

            <form id="formResensi" class="m-form m-form--fit m-form--label-align-right" enctype="multipart/form-data">
                <input type="hidden" name="resensi_id" value="<?= isset($edit_data) ? encrypt($edit_data->resensi_id) : '' ?>">

                <input type="hidden" name="cover_lama" value="<?= isset($edit_data) ? $edit_data->cover : '' ?>">

                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">Metode Input:</label>
                        <div class="col-lg-6">
                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--state-primary">
                                    <input type="radio" name="metode_input" value="pilih" <?= (!isset($edit_data) || ($edit_data->buku_id != null)) ? 'checked' : '' ?>> Pilih dari Database
                                    <span></span>
                                </label>
                                <label class="m-radio m-radio--state-primary">
                                    <input type="radio" name="metode_input" value="manual" <?= (isset($edit_data) && ($edit_data->buku_id == null)) ? 'checked' : '' ?>> Input Manual
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="db_input" class="form-group m-form__group row" <?= (isset($edit_data) && ($edit_data->buku_id == null)) ? 'style="display:none;"' : '' ?>>
                        <label class="col-lg-2 col-form-label">Cari Buku:</label>
                        <div class="col-lg-6">
                            <select class="form-control m-select2" id="buku_select_ajax" name="buku_id">
                                <?php if (isset($edit_data) && $edit_data->buku_id != null) : ?>
                                    <option value="<?= $edit_data->buku_id ?>" selected>
                                        [<?= $edit_data->ISBN ?>] <?= $edit_data->judul_db ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                            <span class="m-form__help">Ketik judul atau ISBN buku</span>
                        </div>
                    </div>

                    <div id="manual_input" <?= (!isset($edit_data) || ($edit_data->buku_id != null)) ? 'style="display:none;"' : '' ?>>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Judul Buku:</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control m-input" name="judul_manual" placeholder="Masukkan Judul Buku" value="<?= isset($edit_data) ? $edit_data->judul_buku : '' ?>">
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Cover Buku:</label>
                            <div class="col-lg-6">
                                <input type="file" class="form-control m-input" name="cover_manual" accept="image/*" onchange="previewImage(this)">
                                <span class="m-form__help">Format: JPG, PNG. Ukuran disarankan vertikal (Potrait).</span>
                                <br>
                                <?php
                                $imgSrc = base_url('assets/media/img/no-image.jpg'); // Default
                                if (isset($edit_data) && !empty($edit_data->cover)) {
                                    $imgSrc = base_url('assets/media/cover_buku_manual/' . $edit_data->cover);
                                }
                                ?>
                                <img id="preview_cover" src="<?= $imgSrc ?>" style="max-width: 150px; margin-top: 10px; border: 1px solid #ddd; padding: 3px;">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Penulis:</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control m-input" name="penulis_manual" placeholder="Nama Penulis" value="<?= isset($edit_data) ? $edit_data->penulis : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">ISBN:</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control m-input" name="isbn" placeholder="Nomor ISBN" value="<?= isset($edit_data) ? $edit_data->isbn : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Tajuk Subyek:</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control m-input" name="tajuksubyek" value="<?= isset($edit_data) ? $edit_data->tajuksubyek : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Penyadur:</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control m-input" name="penyadur" value="<?= isset($edit_data) ? $edit_data->penyadur : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Kategori:</label>
                            <div class="col-lg-6">
                                <select class="form-control m-select2" name="idkategori">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($daftar_kategori as $kat) : ?>
                                        <option value="<?= $kat->idkategori ?>" <?= (isset($edit_data) && $edit_data->idkategori == $kat->idkategori) ? 'selected' : '' ?>>
                                            <?= $kat->nmkategori ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Penerbit:</label>
                            <div class="col-lg-6">
                                <select class="form-control m-select2" name="kd_penerbit">
                                    <option value="">-- Pilih Penerbit --</option>
                                    <?php foreach ($daftar_penerbit as $pen) : ?>
                                        <option value="<?= $pen->kd_penerbit ?>" <?= (isset($edit_data) && $edit_data->kd_penerbit == $pen->kd_penerbit) ? 'selected' : '' ?>>
                                            <?= $pen->nama_penerbit ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Tahun Terbit:</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control m-input" name="thn_terbit" value="<?= isset($edit_data) ? $edit_data->thn_terbit : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Edisi:</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control m-input" name="edisi" value="<?= isset($edit_data) ? $edit_data->edisi : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Cetakan:</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control m-input" name="cetakan" value="<?= isset($edit_data) ? $edit_data->cetakan : '' ?>">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-lg-2 col-form-label">Jml Halaman:</label>
                            <div class="col-lg-2">
                                <input type="number" class="form-control m-input" name="jml_hal" value="<?= isset($edit_data) ? $edit_data->jml_hal : '' ?>">
                            </div>
                        </div>
                    </div>



                    <div class="form-group m-form__group row">
                        <label class="col-lg-2 col-form-label">Isi Resensi:</label>
                        <div class="col-lg-9">
                            <textarea class="summernote" name="isi_resensi"><?= isset($edit_data) ? $edit_data->isi_resensi : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <button type="button" onclick="saveData()" class="btn btn-primary">Simpan</button>
                                <a href="<?= base_url('admin/manage_resensi') ?>" class="btn btn-secondary">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });
        $('.m-select2').select2();

        $('input[name="metode_input"]').change(function() {
            if ($(this).val() === 'pilih') {
                $('#db_input').show();
                $('#manual_input').hide();
            } else {
                $('#db_input').hide();
                $('#manual_input').show();
            }
        });

        $('#buku_select_ajax').select2({
            placeholder: "Cari judul buku...",
            allowClear: true,
            ajax: {
                url: "<?= base_url('admin/manage_resensi/search_buku_ajax') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchtext: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.items
                    };
                },
                cache: true
            }
        });
    });

    // --- PERUBAHAN 2: Fungsi Preview Gambar ---
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_cover').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    // ------------------------------------------

    function saveData() {
        let metode = $('input[name="metode_input"]:checked').val();
        if (metode === 'pilih' && !$('#buku_select_ajax').val()) {
            Swal.fire("Peringatan", "Silakan pilih buku terlebih dahulu", "warning");
            return;
        }

        mApp.blockPage({
            overlayColor: "#000000",
            type: "loader",
            state: "primary",
            message: "Menyimpan..."
        });

        // --- PERUBAHAN 3: Menggunakan FormData untuk support Upload File ---
        var formData = new FormData($('#formResensi')[0]);

        $.ajax({
            url: "<?= base_url('admin/manage_resensi/save') ?>",
            type: "POST",
            data: formData, // Menggunakan variable formData
            dataType: "JSON",
            contentType: false, // Wajib false agar jQuery tidak memproses content type header
            processData: false, // Wajib false agar jQuery tidak mengubah data menjadi query string
            success: function(data) {
                mApp.unblockPage();
                if (data.status == 'success') {
                    Swal.fire("Berhasil!", data.message, "success").then(() => {
                        window.location.href = "<?= base_url('admin/manage_resensi') ?>";
                    });
                } else {
                    Swal.fire("Gagal", data.message, "error");
                }
            },
            error: function() {
                mApp.unblockPage();
                Swal.fire("Error", "Terjadi kesalahan sistem", "error");
            }
        });
        // -------------------------------------------------------------------
    }
</script>