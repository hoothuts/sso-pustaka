<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">

        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">

                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Manage Pegawai
                        </h3>
                    </div>

                </div>

            </div>

            <div class="m-portlet__body">

                <!-- ================= ALERT ================= -->
<!--                <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-info" role="alert">
                    <div class="m-alert__icon">
                        <i class="la la-info-circle"></i>
                    </div>
                    <div class="m-alert__text">
                        Modul ini digunakan untuk mengelola data pegawai aktif dan non aktif.
                    </div>
                </div>-->

                <!-- ================= TOOLBAR ================= -->
                <div class="m-form m-form--label-align-right m--margin-bottom-30">

                    <div class="row align-items-center">

                        <div class="col-xl-6 order-2 order-xl-1">

                            <div class="row align-items-center">

                                <div class="col-md-4">

                                    <div class="m-input-icon m-input-icon--left">

                                        <input type="text"
                                               id="generalSearch"
                                               class="form-control"
                                               placeholder="Cari pegawai...">

                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-xl-6 order-1 order-xl-2 m--align-right">

                            <button class="btn btn-success" id="btn_export_aktif">
                                <i class="la la-file-excel-o"></i>
                                Export Aktif
                            </button>

                            <button class="btn btn-warning" id="btn_export_nonaktif">
                                <i class="la la-file-excel-o"></i>
                                Export Non Aktif
                            </button>
                            <button class="btn btn-info" id="btn_import">
                                <i class="la la-upload"></i>
                                Import Excel
                            </button>
                            <button class="btn btn-primary" id="btn_tambah">
                                <i class="la la-plus"></i>
                                Tambah
                            </button>

                        </div>

                    </div>

                </div>

                <!-- ================= TAB ================= -->
                <ul class="nav nav-tabs m-tabs-line m-tabs-line--success" role="tablist">

                    <li class="nav-item m-tabs__item">

                        <a class="nav-link m-tabs__link active"
                           data-toggle="tab"
                           href="#tab_aktif"
                           role="tab">

                            Pegawai Aktif

                        </a>

                    </li>

                    <li class="nav-item m-tabs__item">

                        <a class="nav-link m-tabs__link"
                           data-toggle="tab"
                           href="#tab_nonaktif"
                           role="tab">

                            Pegawai Non Aktif

                        </a>

                    </li>

                </ul>

                <div class="tab-content">

                    <!-- ================= TAB AKTIF ================= -->
                    <div class="tab-pane active"
                         id="tab_aktif"
                         role="tabpanel">

                        <div class="m_datatable"
                             id="datatable_pegawai_aktif"></div>

                    </div>

                    <!-- ================= TAB NON AKTIF ================= -->
                    <div class="tab-pane"
                         id="tab_nonaktif"
                         role="tabpanel">

                        <div class="m_datatable"
                             id="datatable_pegawai_nonaktif"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- =========================================================
MODAL FORM
========================================================= -->
<div class="modal fade"
     id="modal_pegawai"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <form id="form_pegawai">

                <div class="modal-header m--bg-brand">

                    <h5 class="modal-title m--font-light"
                        id="modal_title">

                        Form Pegawai

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

                    <!-- ================= NIP ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            NIP
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="nip"
                                   id="nip"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= NAMA ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Nama
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= NIK ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            NIK
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="nik"
                                   id="nik"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= EMAIL ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Email
                        </label>

                        <div class="col-lg-9">

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= TELP ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Telp
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="telp"
                                   id="telp"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= TEMPAT LAHIR ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Tempat Lahir
                        </label>

                        <div class="col-lg-9">

                            <input type="text"
                                   name="tempat_lahir"
                                   id="tempat_lahir"
                                   class="form-control">

                        </div>

                    </div>

                    <!-- ================= TGL LAHIR ================= -->
                    <div class="form-group row">

                        <label class="col-lg-3 col-form-label">
                            Tanggal Lahir
                        </label>

                        <div class="col-lg-9">

                            <input type="date"
                                   name="tgl_lahir"
                                   id="tgl_lahir"
                                   class="form-control">

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

<!-- =========================================================
MODAL DETAIL
========================================================= -->
<div class="modal fade"
     id="modal_detail"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header m--bg-info">

                <h5 class="modal-title m--font-light">
                    Detail Pegawai
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    &times;

                </button>

            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="30%">NIP</th>
                        <td id="d_nip"></td>
                    </tr>

                    <tr>
                        <th>Nama</th>
                        <td id="d_nama"></td>
                    </tr>

                    <tr>
                        <th>NIK</th>
                        <td id="d_nik"></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td id="d_email"></td>
                    </tr>

                    <tr>
                        <th>Telp</th>
                        <td id="d_telp"></td>
                    </tr>

                    <tr>
                        <th>Tempat Lahir</th>
                        <td id="d_tempat_lahir"></td>
                    </tr>

                    <tr>
                        <th>Tanggal Lahir</th>
                        <td id="d_tgl_lahir"></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td id="d_status"></td>
                    </tr>

                    <tr>
                        <th>Author</th>
                        <td id="d_author"></td>
                    </tr>

                    <tr>
                        <th>Tanggal Post</th>
                        <td id="d_tgl_post"></td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
MODAL IMPORT
========================================================= -->
<div class="modal fade"
     id="modal_import"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="form_import"
                  enctype="multipart/form-data">

                <div class="modal-header m--bg-info">

                    <h5 class="modal-title m--font-light">
                        Import Excel Pegawai
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal">

                        &times;

                    </button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">

                        Format file harus sesuai template.

                        <br><br>

                        <a href="<?= base_url('dir/manage_pegawai/download_template_import') ?>"
                           class="btn btn-sm btn-success">

                            <i class="la la-download"></i>
                            Download Template

                        </a>

                    </div>

                    <div class="form-group">

                        <label>
                            File Excel
                        </label>

                        <input type="file"
                               name="file_excel"
                               class="form-control"
                               accept=".xls,.xlsx"
                               required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">

                        Import

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

    var datatableAktif;
    var datatableNonAktif;

    $(document).ready(function () {

        /* =====================================================
         * DATATABLE AKTIF
         * ===================================================== */
        datatableAktif = $("#datatable_pegawai_aktif").mDatatable({

            data: {

                type: "remote",

                source: {
                    read: {
                        url: "<?= base_url('dir/manage_pegawai/fetch_aktif') ?>",
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
                    field: "nip",
                    title: "NIP"
                },

                {
                    field: "nama",
                    title: "Nama",
                    width: 220
                },

                {
                    field: "nik",
                    title: "NIK"
                },

                {
                    field: "email",
                    title: "Email",
                    width: 220
                },

                {
                    field: "telp",
                    title: "Telp",
                    width: 110
                },

                {
                    field: "tempat_lahir",
                    title: "Tempat Lahir"
                },

                {
                    field: "tgl_lahir",
                    title: "Tgl Lahir",
                    width: 90
                },

                /*{
                    field: "author",
                    title: "Author",
                    width: 100
                },

                {
                    field: "tgl_post",
                    title: "Tgl Post",
                    width: 90
                },*/

                {
                    field: "action",
                    title: "Aksi",
                    width: 200,
                    sortable: false,

                    template: function(row){

                        return `

                            <button class="btn btn-sm btn-info btn-icon detail"
                                    data-id="${row.id_enc}"
                                    title="Detail">

                                <i class="la la-eye"></i>

                            </button>

                            <button class="btn btn-sm btn-primary btn-icon edit"
                                    data-id="${row.id_enc}"
                                    title="Edit">

                                <i class="la la-edit"></i>

                            </button>

                            <button class="btn btn-sm btn-warning btn-icon nonaktif"
                                    data-id="${row.id_enc}"
                                    title="Non Aktif">

                                <i class="la la-ban"></i>

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

        /* =====================================================
         * DATATABLE NON AKTIF
         * ===================================================== */
        datatableNonAktif = $("#datatable_pegawai_nonaktif").mDatatable({

            data: {

                type: "remote",

                source: {
                    read: {
                        url: "<?= base_url('dir/manage_pegawai/fetch_nonaktif') ?>",
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
                    field: "nip",
                    title: "NIP"
                },

                {
                    field: "nama",
                    title: "Nama",
                    width: 220
                },

                {
                    field: "nik",
                    title: "NIK",
                    width: 120
                },

                {
                    field: "email",
                    title: "Email",
                    width: 200
                },

                {
                    field: "telp",
                    title: "Telp"
                },

                {
                    field: "tgl_nonaktif",
                    title: "Tgl Non Aktif",
                    width: 90
                },

                {
                    field: "author_nonaktif",
                    title: "Update By",
                    width: 100
                },

                {
                    field: "action",
                    title: "Aksi",
                    width: 100,
                    sortable: false,

                    template: function(row){

                        return `

                            <button class="btn btn-sm btn-info btn-icon detail"
                                    data-id="${row.id_enc}"
                                    title="Detail">

                                <i class="la la-eye"></i>

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

        /* =====================================================
         * TAMBAH
         * ===================================================== */
        $('#btn_tambah').click(function(){

            $('#form_pegawai')[0].reset();

            $('#id_for_edit').val('');

            $('#modal_title').text('Tambah Pegawai');

            $('#modal_pegawai').modal('show');

        });

        /* =====================================================
         * EDIT
         * ===================================================== */
        $(document).on('click', '.edit', function(){

            let id = $(this).data('id');

            $.post(
                "<?= base_url('dir/manage_pegawai/get_detail') ?>",
                {id:id},
                function(res){

                    if(res.status == 'success'){

                        let d = res.data;

                        $('#id_for_edit').val(id);

                        $('#nip').val(d.nip);
                        $('#nama').val(d.nama);
                        $('#nik').val(d.nik);
                        $('#email').val(d.email);
                        $('#telp').val(d.telp);
                        $('#tempat_lahir').val(d.tempat_lahir);
                        $('#tgl_lahir').val(d.tgl_lahir);

                        $('#modal_title').text('Edit Pegawai');

                        $('#modal_pegawai').modal('show');

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

        /* =====================================================
         * DETAIL
         * ===================================================== */
        $(document).on('click', '.detail', function(){

            let id = $(this).data('id');

            $.post(
                "<?= base_url('dir/manage_pegawai/get_detail') ?>",
                {id:id},
                function(res){

                    if(res.status == 'success'){

                        let d = res.data;

                        $('#d_nip').html(d.nip);
                        $('#d_nama').html(d.nama);
                        $('#d_nik').html(d.nik);
                        $('#d_email').html(d.email);
                        $('#d_telp').html(d.telp);
                        $('#d_tempat_lahir').html(d.tempat_lahir);
                        $('#d_tgl_lahir').html(d.tgl_lahir_format);
                        $('#d_status').html(d.status_anggota);
                        $('#d_author').html(d.author);
                        $('#d_tgl_post').html(d.tgl_post);

                        $('#modal_detail').modal('show');

                    }

                },
                'json'
            );

        });

        /* =====================================================
         * SAVE
         * ===================================================== */
        $('#form_pegawai').submit(function(e){

            e.preventDefault();

            $.ajax({

                url: "<?= base_url('dir/manage_pegawai/save') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",

                success: function(res){

                    if(res.status == 'success'){

                        $('#modal_pegawai').modal('hide');

                        Swal.fire({
                            title: 'Berhasil',
                            html: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {

                            datatableAktif.reload();
                            datatableNonAktif.reload();

                        });

                    } else {

                        Swal.fire({
                            title: 'Gagal',
                            html: res.message,
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

        /* =====================================================
         * NON AKTIFKAN
         * ===================================================== */
        $(document).on('click', '.nonaktif', function(){

            let id = $(this).data('id');

            Swal.fire({

                title: 'Nonaktifkan pegawai?',
                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'

            }).then((result) => {

                if(result.isConfirmed){

                    $.post(
                        "<?= base_url('dir/manage_pegawai/nonaktifkan') ?>",
                        {id:id},
                        function(res){

                            if(res.status == 'success'){

                                Swal.fire(
                                    'Berhasil',
                                    html.message,
                                    'success'
                                );

                                datatableAktif.reload();
                                datatableNonAktif.reload();

                            } else {

                                Swal.fire(
                                    'Gagal',
                                    html.message,
                                    'error'
                                );

                            }

                        },
                        'json'
                    );

                }

            });

        });

        /* =====================================================
         * DELETE
         * ===================================================== */
        $(document).on('click', '.hapus', function(){

            let id = $(this).data('id');

            Swal.fire({

                title: 'Hapus data?',
                text: 'Data akan di soft delete',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'

            }).then((result) => {

                if(result.isConfirmed){

                    $.post(
                        "<?= base_url('dir/manage_pegawai/delete') ?>",
                        {id:id},
                        function(res){

                            if(res.status == 'success'){

                                Swal.fire(
                                    'Berhasil',
                                    html.message,
                                    'success'
                                );

                                datatableAktif.reload();
                                datatableNonAktif.reload();

                            } else {

                                Swal.fire(
                                    'Gagal',
                                    html.message,
                                    'error'
                                );

                            }

                        },
                        'json'
                    );

                }

            });

        });

        /* =====================================================
         * EXPORT
         * ===================================================== */
        $('#btn_export_aktif').click(function(){

            window.open(
                "<?= base_url('dir/manage_pegawai/export_excel_aktif') ?>",
                '_blank'
            );

        });

        $('#btn_export_nonaktif').click(function(){

            window.open(
                "<?= base_url('dir/manage_pegawai/export_excel_nonaktif') ?>",
                '_blank'
            );

        });
        
        
        /* =====================================================
        * OPEN MODAL IMPORT
        * ===================================================== */
        $('#btn_import').click(function(){

           $('#form_import')[0].reset();

           $('#modal_import').modal('show');

        });

        /* =====================================================
        * IMPORT EXCEL
        * ===================================================== */
        $('#form_import').submit(function(e){

           e.preventDefault();

           let formData = new FormData(this);

           $.ajax({

               url: "<?= base_url('dir/manage_pegawai/import_excel') ?>",

               type: "POST",

               data: formData,

               processData: false,
               contentType: false,

               dataType: "json",

               beforeSend: function(){

                   Swal.fire({
                       title: 'Import data...',
                       text: 'Mohon tunggu',
                       allowOutsideClick: false,
                       didOpen: () => {
                           Swal.showLoading();
                       }
                   });

               },

               success: function(res){

                   if(res.status == 'success'){

                       $('#modal_import').modal('hide');

                       Swal.fire({
                           title: 'Berhasil',
                           text: html.message,
                           icon: 'success'
                       });

                       datatableAktif.reload();
                       datatableNonAktif.reload();

                   } else {

                       Swal.fire({
                           title: 'Gagal',
                           text: html.message,
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

    });

</script>