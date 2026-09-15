<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Buku Baru</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-info" role="alert">
                    <div class="m-alert__icon">
                        <i class="la la-info-circle"></i>
                    </div>
                    <div class="m-alert__text">
                        Buku yang telah diset <b>tampil = Ya</b> maka akan muncul di halaman <b>Beranda</b> bagian depan web Pustaka!
                    </div>
                </div>
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" id="generalSearch" class="form-control" placeholder="Cari...">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <button class="btn btn-primary" id="btn_tambah">
                                <i class="la la-plus"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
                <div class="m_datatable" id="datatable_buku_baru"></div>
            </div>
        </div>
    </div>
</div>



<!-- ================= MODAL ================= -->
<div class="modal fade" id="modal_buku" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg ">
        <div class="modal-content">
            <form id="form_buku_baru">
                <div class="modal-header m--bg-brand">
                    <h5 class="modal-title m--font-light" id="modal_title">Form Buku Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_for_edit" id="id_for_edit">
                    <!-- SELECT BUKU -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Cari Buku</label>
                        <div class="col-lg-9">
                            <select class="form-control m-select2" id="buku_select_ajax" name="buku_id"></select>
                            <span class="m-form__help">Ketik judul atau ISBN buku</span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .select2-container {
        width: 100% !important;
        z-index: 9999;
    }
</style>
<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    var datatable;
    $(document).ready(function () {

        /* ================= DATATABLE ================= */
        datatable = $("#datatable_buku_baru").mDatatable({
            data: {
                type: "remote",
                source: {
                    read: {
                        url: "<?= base_url('dir/manage_buku_baru/fetch') ?>",
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
                {field: "number", title: "#", width: 40},
                {
                    field: "cover",
                    title: "Cover",
                    width: 80,
                    template: function(row){

                        let url = "<?= base_url('uploads/covers/') ?>" + (row.cover ? row.cover : 'default.jpg');

                        return `
                            <img src="${url}" 
                                 style="width:50px;height:70px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                        `;
                    }
                },
                {field: "judul", title: "Judul", width: 310},
                { field: "ISBN", title: "ISBN" },
                { field: "no_klas", title: "No Klas" },
                { field: "tahun_terbit", title: "Tahun Terbit" , width: 60},
                { field: "penulis", title: "Penulis" },
                { field: "penerbit", title: "Penerbit" },
                {field: "author", title: "Author", width: 80},
                {field: "tgl_post", title: "Tanggal", width: 90},
                {field: "is_tampil", title: "Tampil", width: 70},
                {
                    field: "action",
                    title: "Aksi",
                    width: 150,
                    sortable: false,
                    template: function(row){

                        let toggleBtn = '';

                        if(row.is_tampil == 'Ya'){
                            toggleBtn = `
                                <button class="btn btn-sm btn-warning btn-icon toggle" 
                                        data-id="${row.id_enc}" 
                                        title="Nonaktifkan">
                                    <i class="la la-eye-slash"></i>
                                </button>
                            `;
                        } else {
                            toggleBtn = `
                                <button class="btn btn-sm btn-success btn-icon toggle" 
                                        data-id="${row.id_enc}" 
                                        title="Aktifkan">
                                    <i class="la la-eye"></i>
                                </button>
                            `;
                        }

                        return `
                            <span style="width: 150px;">

                                <button class="btn btn-sm btn-info btn-icon edit" 
                                        data-id="${row.id_enc}" 
                                        title="Edit">
                                    <i class="la la-edit"></i>
                                </button>

                                ${toggleBtn}

                                <button class="btn btn-sm btn-danger btn-icon hapus" 
                                        data-id="${row.id_enc}" 
                                        title="Hapus">
                                    <i class="la la-trash"></i>
                                </button>

                            </span>
                        `;
                    }
                }
            ]
        });

        /* ================= SELECT2 ================= */
        $('#buku_select_ajax').select2({
            placeholder: "Cari judul buku...",
            allowClear: true,
            dropdownParent: $('#modal_buku'),
            ajax: {
                url: "<?= base_url('dir/manage_buku_baru/search_buku') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { searchtext: params.term };
                },
                processResults: function(data) {
                    return { results: data.items };
                }
            }
        });

        /* ================= TAMBAH ================= */
        $('#btn_tambah').click(function () {
            $('#form_buku_baru')[0].reset();
            $('#id_for_edit').val('');
            $('#buku_select_ajax').val(null).trigger('change');
            $('#modal_title').text('Tambah Buku Baru');
            $('#modal_buku').modal('show');
        });

        /* ================= EDIT ================= */
        $(document).on('click','.edit', function(){

            let id = $(this).data('id');

            $.post("<?= base_url('dir/manage_buku_baru/get_detail') ?>", {id:id}, function(res){

                if(res.status == 'success'){

                    let d = res.data;

                    $('#id_for_edit').val(id);

                    /* ===== SELECT2 PRELOAD ===== */
                    let option = new Option(
                        `[${d.ISBN}] ${d.judul}`,
                        d.buku_id,
                        true,
                        true
                    );
                    $('#buku_select_ajax').append(option).trigger('change');
                    $('#modal_title').text('Edit Buku Baru');
                    $('#modal_buku').modal('show');

                } else {
                    Swal.fire('Error','Data tidak ditemukan','error');
                }

            }, 'json');

        });

        /* ================= SUBMIT FORM ================= */
        $('#form_buku_baru').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?= base_url('dir/manage_buku_baru/save') ?>",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (res) {
                    if (res.status === 'success') {
                        // Tutup modal terlebih dahulu
                        $('#modal_buku').modal('hide');

                        // Tampilkan SweetAlert sukses
                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            datatable.reload();   // Reload setelah alert selesai
                        });

                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: res.message || 'Terjadi kesalahan saat menyimpan data',
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal terhubung ke server',
                        icon: 'error'
                    });
                }
            });
        });

        /* ================= NONAKTIF / TOGGLE STATUS ================= */
        $(document).on('click', '.toggle', function() {
            let id = $(this).data('id');
            let currentText = $(this).text().trim(); // optional: untuk keterangan

            Swal.fire({
                title: 'Ubah status?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("<?= base_url('dir/manage_buku_baru/toggle_tampil') ?>", 
                    { id: id }, 
                    function(res) {
                        if (res.status === 'success') {
                            // Tampilkan SweetAlert dengan pesan dari server
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message || 'Status berhasil diubah',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                datatable.reload();   // reload setelah alert selesai
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: res.message || 'Terjadi kesalahan',
                                icon: 'error'
                            });
                        }
                    }, 'json')
                    .fail(function() {
                        Swal.fire('Error', 'Gagal terhubung ke server', 'error');
                    });
                }
            });
        });
        
        /* ================= DELETE ================= */
        $(document).on('click','.hapus', function(){

            let id = $(this).data('id');

            Swal.fire({
                title: 'Hapus data?',
                text: "Data akan dinonaktifkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post("<?= base_url('dir/manage_buku_baru/delete') ?>",
                    {id: id},
                    function(res){

                        if(res.status == 'success'){
                            Swal.fire('Berhasil', res.message, 'success');
                            datatable.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }

                    }, 'json').fail(function() {
                        Swal.fire('Error', 'Gagal terhubung ke server', 'error');
                    });

                }

            });

        });

    });
</script>