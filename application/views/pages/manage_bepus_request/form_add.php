<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?= ($mode === 'add') ? 'Tambah Request Bebas Pustaka - Step 1' : 'Tambah Request Bebas Pustaka - Step 2' ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <?php if ($mode === 'add'): // Step 1 ?>
                    <form id="form_nim">
                        <div class="form-group">
                            <label class="col-lg-2 col-form-label">Nomor Induk Mahasiswa (NIM)</label>
                            <div class="col-lg-4 input-group ">
                                <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM" maxlength="15" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Proses</button>
                                </div>
                            </div>
                        </div>
                    </form>

                <?php else: // Step 2 ?>
                    <?php if (isset($siswa) && $siswa): ?>
                        <form id="form_request" action="<?= base_url('dir/manage_bepus_request/save') ?>" method="post">
                            <input type="hidden" name="nim" value="<?= htmlspecialchars($nim) ?>">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama :</label>
                                <div class="col-lg-4">
                                    <input class="form-control" type="text" value="<?= htmlspecialchars($siswa->nama ?? '') ?>" readonly disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">NIM :</label>
                                <div class="col-lg-4">
                                    <input class="form-control" type="text" value="<?= htmlspecialchars($siswa->nis ?? '') ?>" readonly disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Program Studi :</label>
                                <div class="col-lg-4">
                                    <input class="form-control" type="text" value="<?= htmlspecialchars($siswa->kelas ?? '') ?>" readonly disabled/>
                                </div>
                            </div>
                            <!-- Input Tahun Akademik -->
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tahun Akademik :</label>
                                <div class="col-lg-2">
                                    <select class="form-control m-input m-input--square" name="tahun_akademik" required>
                                        <option value="">Pilih Tahun Akademik</option>
                                        <?php
                                        $current_year = date('Y');
                                        $current_month = date('n'); // 1-12
                                        $next_year = $current_year + 1;

                                        // Jan-Jun (1-6) → selected current_year, Jul-Dec (7-12) → selected next_year
                                        $selected_year = ($current_month >= 1 && $current_month <= 6) ? $current_year : $next_year;

                                        for ($i = $next_year; $i >= $current_year - 10; $i--) :
                                            $option_value = ($i - 1) . '/' . $i;
                                            $selected = ($i == $selected_year) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $option_value; ?>" <?php echo $selected; ?>>
                                                <?php echo $option_value; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <h4 class="mb-4">Persyaratan Bebas Pustaka</h4>

                            <div class="syarat-form">
                                <?php 
                                $no_induk = 1;
                                foreach ($syarat_list as $row): 
                                    if ($row->level == 1): 
                                ?>
                                    <div class="parent-item mb-3">
                                        <div class="d-flex align-items-start">
                                            <?php if ($row->is_isian == 'Ya'): ?>
                                                <label class="m-checkbox m-checkbox--success">
                                                    <input type="checkbox" name="checkbox[]" value="<?= $row->bepussyarat_id ?>">
                                                    <span></span>
                                                </label>
                                            <?php endif; ?>
                                            <strong class="mr-3"><?= $no_induk++ ?>.</strong>
                                            <div class="flex-grow-1">
                                                <strong><?= $row->persyaratan ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="child-item ml-5 mb-2">
                                        <div class="d-flex align-items-start">
                                            <?php if ($row->is_isian == 'Ya'): ?>
                                                <label class="m-checkbox m-checkbox--success">
                                                    <input type="checkbox" name="checkbox[]" value="<?= $row->bepussyarat_id ?>">
                                                    <span></span>
                                                </label>
                                            <?php endif; ?>
                                            <span class="mr-3">↳</span>
                                            <div class="flex-grow-1">
                                                <?= $row->persyaratan ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            
                            <hr>
                            <h4>Hibah Buku</h4>
                            <div id="hibah_container"></div>
                            <button type="button" id="add_hibah" class="btn btn-success mb-3">
                                + Tambah Buku
                            </button>
                            
                            <script type="text/template" id="hibah_template">
                            <div class="card hibah-card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>Buku <span class="nomor"></span></strong>
                                    <button type="button" class="btn btn-sm btn-danger btn-remove">Hapus</button>
                                </div>

                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-2">
                                            <label>Judul Buku</label>
                                            <input type="text" name="judul_buku[]" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label>ISBN</label>
                                            <input type="text" name="isbn[]" class="form-control">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label>Tahun</label>
                                            <input type="number" name="tahun_terbit[]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4 mb-2">
                                            <label>Pengarang</label>
                                            <input type="text" name="pengarang[]" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>Penerbit</label>
                                            <input type="text" name="penerbit[]" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>Tempat Terbit</label>
                                            <input type="text" name="tempat_terbit[]" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4 mb-2">
                                            <label>Harga Buku/Taksiran</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="text" name="harga_buku_display[]" class="form-control harga-display" placeholder="0" autocomplete="off">
                                                <input type="hidden" name="harga_buku[]" class="harga-raw">
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-2">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan[]" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </script>
                            
                            <hr>
                            <button type="submit" class="btn btn-primary">Simpan Request</button>
                            <a href="<?= base_url('dir/manage_bepus_request') ?>" class="btn btn-secondary">Batal</a>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">Data mahasiswa tidak ditemukan.</div>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    // Step 1
    $('#form_nim').submit(function(e) {
        e.preventDefault();
        var nim = $('#nim').val().trim();
        if (!nim) {
            Swal.fire('Error', 'Masukkan NIM terlebih dahulu', 'error');
            return;
        }

        $.post("<?= base_url('dir/manage_bepus_request/proses_nim') ?>", {nim: nim}, function(res) {
            if (res.status === 'success') {
                window.location.href = res.redirect;
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        }, 'json');
    });

    // Step 2
    $('#form_request').submit(function(e) {
        e.preventDefault();

        // Sebelum serialize: salin nilai display ke hidden field (strip titik)
        $('.harga-display').each(function() {
            var raw = $(this).val().replace(/\./g, '') || '0';
            $(this).closest('.input-group').find('.harga-raw').val(raw);
        });

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
    
    function renderNomor(){
        $('#hibah_container .hibah-card').each(function(i){
            $(this).find('.nomor').text(i+1);
        });
    }

    $('#add_hibah').click(function(){
        let template = $('#hibah_template').html();
        $('#hibah_container').append(template);
        renderNomor();
    });

    // default 1 buku
    $('#add_hibah').click();

    $(document).on('click','.btn-remove', function(){
        if($('.hibah-card').length > 1){
            $(this).closest('.hibah-card').remove();
            renderNomor();
        }
    });

    // Format harga: tambah titik ribuan saat input
    $(document).on('input', '.harga-display', function() {
        var pos   = this.selectionStart;
        var raw   = $(this).val().replace(/\D/g, '');           // hanya angka
        var formatted = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // tambah titik
        var oldLen = $(this).val().length;
        $(this).val(formatted);
        // Pertahankan posisi kursor
        var newLen = formatted.length;
        this.setSelectionRange(pos + (newLen - oldLen), pos + (newLen - oldLen));
    });
    
    $('#hibah_container .hibah-card:last input:first').focus();

});
</script>

<style>
.hibah-card {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.hibah-card .card-header {
    background: #f8f9fa;
    font-size: 14px;
}
.parent-item { padding: 12px 15px; background: #f8f9fa; border-left: 4px solid #34bfa3; border-radius: 4px; }
.child-item { padding: 8px 15px; background: #fff; border-left: 3px solid #ccc; }
</style>