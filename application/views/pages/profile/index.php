<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Profile</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <form id="form_profile" enctype="multipart/form-data">

                    <div class="form-group col-md-4">
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?= $user->idsysuser ?>" readonly disabled>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Nama Lengkap (dengan gelar)</label>
                        <input type="text" name="name" class="form-control" value="<?= $user->name ?>" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label>NIP (NO Induk Pegawai)</label>
                        <input type="text" name="nip_pegawai" class="form-control" value="<?= $user->nip_pegawai ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Tanda Tangan Digital</label>
                        <input type="file" name="ttd_digital" class="form-control col-">
                         <small class="form-text text-muted">Hanya jpg/jpeg/png • Maksimal 5 MB</small>
                        <?php if (!empty($user->ttd_digital)): ?>
                            <br>
                            <img src="<?= base_url('uploads/ttd/' . $user->ttd_digital) ?>" width="200">
                             <?php endif; ?>
                    </div>
                    <hr>
                    
                    <div class="m-form__actions m-form__actions--solid">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {

        $('#form_profile').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "<?= base_url('dir/profile/save') ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',

                success: function (res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success')
                                .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }
            });

        });

    });
</script>