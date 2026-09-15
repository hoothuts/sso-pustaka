<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Lokasi</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <a href="<?= base_url('dir/manage_lokasi/form') ?>" class="btn btn-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air">
                                <i class="la la-plus"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="m-portlet__body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_kampus">Lokasi Kampus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_gedung">Gedung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_rak">Rak</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_kampus">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari..." id="generalSearch_kampus">
                                        <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m_datatable" id="datatable_kampus"></div>
                    </div>
                    <div class="tab-pane" id="tab_gedung">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari..." id="generalSearch_gedung">
                                        <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m_datatable" id="datatable_gedung"></div>
                    </div>
                    <div class="tab-pane" id="tab_rak">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari..." id="generalSearch_rak">
                                        <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m_datatable" id="datatable_rak"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function formatNumber(num) {
        if (num === null || num === undefined || isNaN(num)) return '0';
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    var DatatableKampus = {
        init: function() {
            var datatable = $("#datatable_kampus").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_lokasi/list_kampus') ?>",
                            method: 'POST'
                        }
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                },
                layout: {
                    scroll: false,
                    footer: false
                },
                sortable: true,
                pagination: true,
                columns: [
                    {
                        field: "number",
                        title: "#",
                        width: 40,
                        sortable: false,
                        textAlign: "center"
                    },
                    {
                        field: "nama_kampus",
                        title: "Nama Kampus"
                    },
                    {
                        field: "alamat",
                        title: "Alamat",
                         width: 350
                    },
                    {
                        field: "tgl_post",
                        title: "Tanggal Post"
                    },
                    {
                        field: "author",
                        title: "Author"
                    },
//                    {
//                        field: "status",
//                        title: "Status",
//                        width: 100,
//                        template: function(row) {
//                            return `<span class="m-badge m-badge--wide m-badge--rounded ${row.status == 1 ? 'm-badge--success' : 'm-badge--danger'}">
//                                        ${row.status == 1 ? 'Aktif' : 'Nonaktif'}
//                                    </span>`;
//                        }
//                    },
                    {
                        field: "jumlah_koleksi",
                        title: "Jumlah Koleksi",
                        width: 100,
                        textAlign: "center",
                        template: function(row) {
                            return formatNumber(row.jumlah_koleksi || 0);
                        }
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 150,
                        template: function(row) {
                            return `
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>kampus/${row.lokasikampus_id}/view" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>kampus/${row.lokasikampus_id}/edit" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-icon hapus-lokasi" data-type="kampus" data-id="${row.lokasikampus_id}" title="Hapus"><i class="la la-trash"></i></a>
                            `;
                        }
                    }
                ]
            });

            $('#generalSearch_kampus').on('keyup', function() {
                datatable.search($(this).val().toLowerCase(), 'generalSearch');
            });
        }
    };

    var DatatableGedung = {
        init: function() {
            var datatable = $("#datatable_gedung").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_lokasi/list_gedung') ?>",
                            method: 'POST'
                        }
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                },
                layout: {
                    scroll: false,
                    footer: false
                },
                sortable: true,
                pagination: true,
                columns: [
                    {
                        field: "number",
                        title: "#",
                        width: 40,
                        sortable: false,
                        textAlign: "center"
                    },
                    {
                        field: "nama_gedung",
                        title: "Nama Gedung",
                         width: 200
                    },
                    {
                        field: "nama_kampus",
                        title: "Nama Kampus",
                         width: 180
                    },
                    {
                        field: "keterangan",
                        title: "Keterangan"
                    },
                    {
                        field: "tgl_post",
                        title: "Tanggal Post"
                    },
                    {
                        field: "author",
                        title: "Author"
                    },
//                    {
//                        field: "status",
//                        title: "Status",
//                        width: 100,
//                        template: function(row) {
//                            return `<span class="m-badge m-badge--wide m-badge--rounded ${row.status == 1 ? 'm-badge--success' : 'm-badge--danger'}">
//                                        ${row.status == 1 ? 'Aktif' : 'Nonaktif'}
//                                    </span>`;
//                        }
//                    },
                    {
                        field: "jumlah_koleksi",
                        title: "Jumlah Koleksi",
                        width: 100,
                        textAlign: "center",
                        template: function(row) {
                            return formatNumber(row.jumlah_koleksi || 0);
                        }
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 150,
                        template: function(row) {
                            return `
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>gedung/${row.lokasigedung_id}/view" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>gedung/${row.lokasigedung_id}/edit" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-icon hapus-lokasi" data-type="gedung" data-id="${row.lokasigedung_id}" title="Hapus"><i class="la la-trash"></i></a>
                            `;
                        }
                    }
                ]
            });

            $('#generalSearch_gedung').on('keyup', function() {
                datatable.search($(this).val().toLowerCase(), 'generalSearch');
            });
        }
    };

    var DatatableRak = {
        init: function() {
            var datatable = $("#datatable_rak").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_lokasi/list_rak') ?>",
                            method: 'POST'
                        }
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                },
                layout: {
                    scroll: false,
                    footer: false
                },
                sortable: true,
                pagination: true,
                columns: [
                    {
                        field: "number",
                        title: "#",
                        width: 40,
                        sortable: false,
                        textAlign: "center"
                    },
                    {
                        field: "nama_rak",
                        title: "Nama Rak",
                        width: 100
                    },
                    {
                        field: "nama_gedung",
                        title: "Nama Gedung",
                        width: 200
                    },
                    {
                        field: "nama_kampus",
                        title: "Nama Kampus",
                        width: 200
                    },
                    {
                        field: "keterangan",
                        title: "Keterangan"
                    },
                    {
                        field: "tgl_post",
                        title: "Tanggal Post"
                    },
                    {
                        field: "author",
                        title: "Author"
                    },
//                    {
//                        field: "status",
//                        title: "Status",
//                        width: 80,
//                        template: function(row) {
//                            return `<span class="m-badge m-badge--wide m-badge--rounded ${row.status == 1 ? 'm-badge--success' : 'm-badge--danger'}">
//                                        ${row.status == 1 ? 'Aktif' : 'Nonaktif'}
//                                    </span>`;
//                        }
//                    },
                    {
                        field: "jumlah_koleksi",
                        title: "Jumlah Koleksi",
                        width: 100,
                        textAlign: "center",
                        template: function(row) {
                            return formatNumber(row.jumlah_koleksi || 0);
                        }
                    },   
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 150,
                        template: function(row) {
                            return `
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>rak/${row.lokasirak_id}/view" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                <a href="<?= base_url('dir/manage_lokasi/form/') ?>rak/${row.lokasirak_id}/edit" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-icon hapus-lokasi" data-type="rak" data-id="${row.lokasirak_id}" title="Hapus"><i class="la la-trash"></i></a>
                            `;
                        }
                    }
                ]
            });

            $('#generalSearch_rak').on('keyup', function() {
                datatable.search($(this).val().toLowerCase(), 'generalSearch');
            });
        }
    };

    $(document).ready(function() {
        DatatableKampus.init();
        DatatableGedung.init();
        DatatableRak.init();

        // Hapus (soft delete)
        $(document).on('click', '.hapus-lokasi', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');

            Swal.fire({
                title: 'Hapus lokasi?',
                text: "Data akan diubah menjadi nonaktif!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('dir/manage_lokasi/delete') ?>",
                        type: 'POST',
                        data: { id: id, type: type },
                        dataType: 'json',
                        success: function(res) {
                            if (res.status == 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: res.message,
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Force reload semua datatable untuk pastikan refresh
                                    $('#datatable_kampus').mDatatable('reload');
                                    $('#datatable_gedung').mDatatable('reload');
                                    $('#datatable_rak').mDatatable('reload');
                                });
                            } else {
                                Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Gagal terhubung ke server: ' + error, 'error');
                            console.error('Delete error:', xhr.responseText);
                        }
                    });
                }
            });
        });
    });
</script>