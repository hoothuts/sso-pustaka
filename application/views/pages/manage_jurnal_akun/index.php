<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">

        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">

                    <div class="m-portlet__head-title">

                        <h3 class="m-portlet__head-text">
                            Manage Akun Jurnal
                        </h3>

                    </div>

                </div>

            </div>

            <div class="m-portlet__body">

                <!-- FILTER -->
                <div class="m-form m-form--label-align-right m--margin-bottom-30">

                    <div class="row align-items-center">

                        <div class="col-xl-8 order-2 order-xl-1">

                            <div class="row align-items-center">

                                <!-- SEARCH -->
                                <div class="col-md-4">

                                    <div class="m-input-icon m-input-icon--left">

                                        <input type="text"
                                               id="generalSearch"
                                               class="form-control"
                                               placeholder="Cari...">

                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>

                                    </div>

                                </div>

                                <!-- FILTER VENDOR -->
                                <div class="col-md-4">

                                    <select id="filter_vendor"
                                            class="form-control">

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">

                            <button class="btn btn-primary"
                                    id="btn_tambah">

                                <i class="la la-plus"></i>
                                Tambah Akun

                            </button>

                        </div>

                    </div>

                </div>

                <!-- DATATABLE -->
                <div class="m_datatable"
                     id="datatable_akun">

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->
<div class="modal fade"
     id="modal_akun"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form id="form_akun">

                <div class="modal-header m--bg-brand">

                    <h5 class="modal-title m--font-light"
                        id="modal_title">

                        Form Akun Jurnal

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

                    <!-- VENDOR -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Vendor
                            <span class="text-danger">*</span>
                        </label>

                        <div class="col-lg-9">

                            <select name="jurnalvendor_id"
                                    id="jurnalvendor_id"
                                    class="form-control"
                                    required>

                            </select>

                        </div>

                    </div>

                    <!-- USERNAME -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Username
                            <span class="text-danger">*</span>
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="username"
                                   id="username"
                                   class="form-control"
                                   required>

                        </div>

                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Password
                            <span class="text-danger">*</span>
                        </label>

                        <div class="col-lg-9">

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control">

                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti password saat edit.
                            </small>

                        </div>

                    </div>

                    <!-- CATATAN -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Catatan
                        </label>

                        <div class="col-lg-9">

                            <textarea name="catatan"
                                      id="catatan"
                                      rows="4"
                                      class="form-control"></textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <div class="mr-auto">
                        <small class="text-danger">
                            * Wajib diisi
                        </small>
                    </div>

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

    $(document).ready(function(){

        /* ================= FILTER SELECT2 ================= */

        $('#filter_vendor').select2({

            placeholder: 'Semua Vendor',
            allowClear: true,

            ajax: {

                url: "<?= base_url('dir/manage_jurnal_akun/search_vendor') ?>",

                dataType: 'json',

                delay: 250,

                data: function(params){

                    return {
                        searchtext: params.term
                    };
                },

                processResults: function(data){

                    return {
                        results: data.items
                    };
                }

            }

        });

        /* ================= FORM SELECT2 ================= */

        $('#jurnalvendor_id').select2({

            placeholder: 'Pilih Vendor',

            dropdownParent: $('#modal_akun'),

            ajax: {

                url: "<?= base_url('dir/manage_jurnal_akun/search_vendor') ?>",

                dataType: 'json',

                delay: 250,

                data: function(params){

                    return {
                        searchtext: params.term
                    };
                },

                processResults: function(data){

                    return {
                        results: data.items
                    };
                }

            }

        });

        /* ================= DATATABLE ================= */

        datatable = $("#datatable_akun").mDatatable({

            data: {

                type: "remote",

                source: {

                    read: {

                        url: "<?= base_url('dir/manage_jurnal_akun/fetch') ?>",

                        method: "POST",

                        params: {

                            vendor: function(){
                                return $('#filter_vendor').val();
                            }

                        }

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
                    field: "nama_vendor",
                    title: "Vendor Jurnal"
                },

                {
                    field: "username",
                    title: "Username"
                },

                {
                    field: "password",
                    title: "Password",
                    width: 140,
                    sortable: false,

                    template: function(row){

                        return `
                            <button class="btn btn-sm btn-warning btn-show-password"
                                    data-id="${row.id_enc}">

                                <i class="la la-eye"></i>
                                Show Password

                            </button>
                        `;
                    }
                },

                {
                    field: "is_used",
                    title: "Status",
                    width: 100,

                    template: function(row){

                        if(row.is_used == 1){

                            return `
                                <span class="m-badge m-badge--danger m-badge--wide">
                                    Dipakai
                                </span>
                            `;

                        } else {

                            return `
                                <span class="m-badge m-badge--success m-badge--wide">
                                    Idle
                                </span>
                            `;
                        }

                    }

                },

                {
                    field: "last_used",
                    title: "Last Used",
                    width: 130
                },

                {
                    field: "total_used",
                    title: "Total Used",
                    width: 90
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
                    width: 150,
                    sortable: false,

                    template: function(row){

                        let btn_release = '';

                        if(row.is_used == 1){

                            btn_release = `

                                <button type="button"
                                        class="btn btn-sm btn-warning btn-icon release-account"
                                        data-id="${row.id_enc}"
                                        title="Release Account">

                                    <i class="fa fa-unlock"></i>

                                </button>

                            `;
                        }

                        return `

                            <button type="button"
                                    class="btn btn-sm btn-info btn-icon edit"
                                    data-id="${row.id_enc}" title="Edit Account">

                                <i class="la la-edit"></i>

                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-danger btn-icon delete"
                                    data-id="${row.id_enc}" title="Delete Account">

                                <i class="la la-trash"></i>

                            </button>

                            ${btn_release}

                        `;
                    }

                }

            ]

        });

        /* ================= FILTER CHANGE ================= */

        $('#filter_vendor').change(function(){

            datatable.reload();

        });

        /* ================= TAMBAH ================= */

        $('#btn_tambah').click(function(){

            $('#form_akun')[0].reset();

            $('#id_for_edit').val('');

            $('#jurnalvendor_id')
                .val(null)
                .trigger('change');

            $('#modal_title').text('Tambah Akun Jurnal');

            $('#modal_akun').modal('show');

        });

        /* ================= EDIT ================= */

        $(document).on('click', '.edit', function(){

            let id = $(this).data('id');

            $.post(

                "<?= base_url('dir/manage_jurnal_akun/get_detail') ?>",

                {id:id},

                function(res){

                    if(res.status == 'success'){

                        let d = res.data;

                        $('#id_for_edit').val(id);

                        $('#username').val(d.username);

                        $('#password').val('');

                        $('#catatan').val(d.catatan);

                        $.ajax({

                            url: "<?= base_url('dir/manage_jurnal_akun/search_vendor') ?>",

                            dataType: 'json',

                            success: function(result){

                                let vendor = result.items.find(
                                    x => x.id == d.jurnalvendor_id
                                );

                                if(vendor){

                                    let option = new Option(
                                        vendor.text,
                                        vendor.id,
                                        true,
                                        true
                                    );

                                    $('#jurnalvendor_id')
                                        .append(option)
                                        .trigger('change');

                                }

                            }

                        });

                        $('#modal_title').text('Edit Akun Jurnal');

                        $('#modal_akun').modal('show');

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

        $('#form_akun').submit(function(e){

            e.preventDefault();

            $.ajax({

                url: "<?= base_url('dir/manage_jurnal_akun/save') ?>",

                type: "POST",

                data: $(this).serialize(),

                dataType: "json",

                success: function(res){

                    if(res.status == 'success'){

                        $('#modal_akun').modal('hide');

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

        $(document).on('click', '.delete', function(){

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

                        "<?= base_url('dir/manage_jurnal_akun/delete') ?>",

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

        /* ================= SHOW PASSWORD ================= */

        $(document).on('click', '.btn-show-password', function(){

            let id = $(this).data('id');

            $.post(

                "<?= base_url('dir/manage_jurnal_akun/show_password') ?>",

                {id:id},

                function(res){

                    if(res.status == 'success'){

                        Swal.fire({

                            title: 'Credential Account',

                            html: `

                                <div style="
                                    text-align:left;
                                    font-size:14px;
                                ">

                                    <b>Vendor Jurnal :</b>
                                    <br>
                                    ${res.vendor}

                                    <br><br>

                                    <b>Username :</b>
                                    <br>
                                    ${res.username}

                                    <br><br>

                                    <b>Password :</b>
                                    <br>
                                    ${res.password}

                                    <br><br>

                                    <small style="
                                        color:#dc3545;
                                    ">
                                        Password akan otomatis disembunyikan
                                        dalam 5 detik
                                    </small>

                                </div>

                            `,

                            icon: 'info',

                            timer: 5000,

                            timerProgressBar: true,

                            showConfirmButton: false

                        });

                    } else {

                        Swal.fire(
                            'Error',
                            res.message,
                            'error'
                        );

                    }

                },

                'json'

            );

        });
        
        /* ================= RELEASE ACCOUNT ================= */

        $(document).on('click', '.release-account', function(){

            let id = $(this).data('id');

            Swal.fire({

                title: 'Release Account?',

                text: 'Account akan dilepas manual.',

                type: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, Release',

                cancelButtonText: 'Batal'

            }).then((result) => {

                if(result.value){

                    $.post(

                        "<?= base_url('dir/manage_jurnal_akun/release_account') ?>",

                        {id:id},

                        function(res){

                            if(res.status == 'success'){

                                Swal.fire({

                                    type: 'success',

                                    title: 'Berhasil',

                                    text: res.message

                                });

                                datatable.reload();

                            } else {

                                Swal.fire({

                                    type: 'error',

                                    title: 'Gagal',

                                    text: res.message

                                });

                            }

                        },

                        'json'

                    );

                }

            });

        });


    });

</script>