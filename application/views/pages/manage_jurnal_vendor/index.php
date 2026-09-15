<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">

        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">

                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Manage Vendor Jurnal Berlangganan
                        </h3>
                    </div>

                </div>

            </div>

            <div class="m-portlet__body">

                <!-- SEARCH -->
                <div class="m-form m-form--label-align-right m--margin-bottom-30">

                    <div class="row align-items-center">

                        <div class="col-xl-8 order-2 order-xl-1">

                            <div class="row align-items-center">

                                <div class="col-md-4">

                                    <div class="m-input-icon m-input-icon--left">

                                        <input type="text"
                                               id="generalSearch"
                                               class="form-control"
                                               placeholder="Cari Vendor...">

                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                            <button class="btn btn-primary" id="btn_tambah">
                                <i class="la la-plus"></i> Tambah Vendor
                            </button>

                        </div>

                    </div>

                </div>

                <!-- DATATABLE -->
                <div class="m_datatable" id="datatable_vendor"></div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modal_vendor" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form id="form_vendor" enctype="multipart/form-data">

                <div class="modal-header m--bg-brand">

                    <h5 class="modal-title m--font-light" id="modal_title">
                        Form Vendor
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        &times;
                    </button>

                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="id_for_edit"
                           id="id_for_edit">

                    <!-- NAMA -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Nama Vendor <span class="text-danger">*</span>
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="nama_vendor"
                                   id="nama_vendor"
                                   class="form-control"
                                   required>

                        </div>

                    </div>

                    <!-- URL -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            URL Vendor <span class="text-danger">*</span>
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="url_vendor"
                                   id="url_vendor"
                                   class="form-control"
                                   required>

                        </div>

                    </div>
                    
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Multi Login
                        </label>

                        <div class="col-lg-9">

                            <span class="m-switch m-switch--outline m-switch--icon m-switch--success">

                                <label>

                                    <input type="checkbox"
                                           name="is_multi_login"
                                           id="is_multi_login"
                                           value="1">

                                    <span></span>

                                </label>

                            </span>

                            <small class="form-text text-muted">
                                Jika aktif, satu account vendor dapat digunakan
                                oleh banyak mahasiswa secara bersamaan.
                            </small>

                        </div>

                    </div>

                    <!-- KATEGORI -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Kategori
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="kategori"
                                   id="kategori"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- DESKRIPSI -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Deskripsi
                        </label>

                        <div class="col-lg-9">

                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                    <!-- LOGO -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Logo
                        </label>

                        <div class="col-lg-9">

                            <input type="file"
                                   name="logo"
                                   id="logo"
                                   class="form-control"
                                   accept=".jpg,.jpeg,.png,.webp">

                            <small class="text-muted">
                                Format: jpg, jpeg, png, webp.
                                Maksimal 2MB.
                            </small>

                            <div class="mt-3">
                                <img id="preview_logo"
                                     src=""
                                     style="max-height:120px;display:none;border:1px solid #ddd;padding:5px;border-radius:6px;">
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan
                    </button>
                    
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    var datatable;

    $(document).ready(function () {

        /* ================= DATATABLE ================= */

        datatable = $("#datatable_vendor").mDatatable({

            data: {
                type: "remote",
                source: {
                    read: {
                        url: "<?= base_url('dir/manage_jurnal_vendor/fetch') ?>",
                        method: "POST"
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },

            search: {
                input: $('#generalSearch')
            },

            columns: [

                {
                    field: "number",
                    title: "#",
                    width: 40
                },

                {
                    field: "logo",
                    title: "Logo",
                    width: 90,
                    template: function(row){

                        if(row.logo){

                            let img = "<?= base_url('uploads/jurnal_vendor/') ?>" + row.logo;

                            return `
                                <img src="${img}"
                                     style="
                                        width:60px;
                                        height:60px;
                                        object-fit:contain;
                                        border:1px solid #ddd;
                                        padding:4px;
                                        border-radius:6px;
                                        background:#fff;
                                     ">
                            `;

                        } else {

                            return `
                                <div style="
                                    width:60px;
                                    height:60px;
                                    border:1px solid #ddd;
                                    border-radius:6px;
                                    background:#f8f9fa;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    color:#999;
                                    font-size:24px;
                                ">
                                    <i class="la la-image"></i>
                                </div>
                            `;
                        }
                    }
                },

                {
                    field: "nama_vendor",
                    title: "Vendor"
                },

                {
                    field: "kategori",
                    title: "Kategori"
                },

                {
                    field: "url_vendor",
                    title: "URL"
                },
                        
                {
                    field: "is_multi_login",
                    title: "Multi Login",
                    width: 120,

                    template: function(row){

                        if(row.is_multi_login == 1){

                            return `
                                <span class="m-badge m-badge--success m-badge--wide">
                                    Ya
                                </span>
                            `;

                        } else {

                            return `
                                <span class="m-badge m-badge--danger m-badge--wide">
                                    Tidak
                                </span>
                            `;
                        }

                    }
                },        

                {
                    field: "author",
                    title: "Author",
                    width: 80
                },

                {
                    field: "tgl_post",
                    title: "Tanggal",
                    width: 120
                },

                {
                    field: "action",
                    title: "Aksi",
                    width: 130,
                    sortable: false,

                    template: function(row){

                        return `

                            <button class="btn btn-sm btn-info btn-icon edit"
                                    data-id="${row.id_enc}"
                                    title="Edit">
                                <i class="la la-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-danger btn-icon hapus"
                                    data-id="${row.id_enc}"
                                    title="Hapus">
                                <i class="la la-trash"></i>
                            </button>

                        `;
                    }
                }

            ]

        });

        /* ================= TAMBAH ================= */

        $('#btn_tambah').click(function(){

            $('#form_vendor')[0].reset();

            $('#id_for_edit').val('');

            $('#preview_logo')
                .hide()
                .attr('src', '');

            $('#modal_title').text('Tambah Vendor');

            $('#modal_vendor').modal('show');

        });

        /* ================= PREVIEW IMAGE ================= */

        $('#logo').change(function(){

            let reader = new FileReader();

            reader.onload = function(e){

                $('#preview_logo')
                    .attr('src', e.target.result)
                    .show();

            }

            reader.readAsDataURL(this.files[0]);

        });

        /* ================= EDIT ================= */

        $(document).on('click', '.edit', function(){

            let id = $(this).data('id');

            $.post(
                "<?= base_url('dir/manage_jurnal_vendor/get_detail') ?>",
                {id:id},
                function(res){

                    if(res.status == 'success'){

                        let d = res.data;

                        $('#id_for_edit').val(id);
                        $('#nama_vendor').val(d.nama_vendor);
                        $('#url_vendor').val(d.url_vendor);
                        $('#kategori').val(d.kategori);
                        $('#deskripsi').val(d.deskripsi);
                        $('#is_multi_login').prop('checked', d.is_multi_login == 1);

                        if(d.logo){

                            $('#preview_logo')
                                .attr(
                                    'src',
                                    "<?= base_url('uploads/jurnal_vendor/') ?>" + d.logo
                                )
                                .show();

                        } else {

                            $('#preview_logo')
                                .hide()
                                .attr('src', '');

                        }

                        $('#modal_title').text('Edit Vendor');

                        $('#modal_vendor').modal('show');

                    } else {

                        Swal.fire(
                            'Error',
                            'Data tidak ditemukan',
                            'error'
                        );

                    }

                },
                'json'
            );

        });

        /* ================= SAVE ================= */

        $('#form_vendor').submit(function(e){

            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({

                url: "<?= base_url('dir/manage_jurnal_vendor/save') ?>",

                type: "POST",

                data: formData,

                processData: false,
                contentType: false,

                dataType: "json",

                success: function(res){

                    if(res.status == 'success'){

                        $('#modal_vendor').modal('hide');

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {

                            datatable.reload();

                        });

                    } else {

                        Swal.fire({
                            title: 'Gagal',
                            text: res.message,
                            icon: 'error'
                        });

                    }

                },

                error: function(){

                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal terhubung ke server',
                        icon: 'error'
                    });

                }

            });

        });

        /* ================= DELETE ================= */

        $(document).on('click', '.hapus', function(){

            let id = $(this).data('id');

            Swal.fire({

                title: 'Hapus data?',
                text: 'Data akan dinonaktifkan',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'

            }).then((result) => {

                if(result.isConfirmed){

                    $.post(

                        "<?= base_url('dir/manage_jurnal_vendor/delete') ?>",

                        {id:id},

                        function(res){

                            if(res.status == 'success'){

                                Swal.fire(
                                    'Berhasil',
                                    res.message,
                                    'success'
                                );

                                datatable.reload();

                            } else {

                                Swal.fire(
                                    'Gagal',
                                    res.message,
                                    'error'
                                );

                            }

                        },

                        'json'

                    ).fail(function(){

                        Swal.fire(
                            'Error',
                            'Gagal terhubung ke server',
                            'error'
                        );

                    });

                }

            });

        });

    });

</script>