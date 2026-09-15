<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Klasifikasi Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari ID atau Nama..." id="generalSearch">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <a href="<?= base_url('dir/manage_klasifikasi/form/add') ?>" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span><i class="la la-plus"></i><span>Tambah Klasifikasi</span></span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="m_datatable" id="datatable_klasifikasi"></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var datatable;

    var DatatableKlasifikasi = {
        init: function() {
            datatable = $("#datatable_klasifikasi").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_klasifikasi/fetch') ?>",
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
                search: {
                    input: $("#generalSearch"),
                    delay: 400
                },
                columns: [
                    { field: "number", title: "#", width: 40, sortable: false, textAlign: "center" },
                    { field: "id", title: "ID" },
                    { field: "nama", title: "Nama Klasifikasi" },
                    { 
                        field: "kode_warna", 
                        title: "Kode Warna",
                        template: function(row) {
                            if (row.kode_warna) {
                                return `<div style="width:30px;height:20px;background-color:${row.kode_warna};border:1px solid #ccc;display:inline-block;"></div> `;
                            }
                            return '-';
                        }
                    },
                    { 
                        field: "status", 
                        title: "Status",
                        template: function(row) {
                            return row.status == 1 
                                ? '<span class="m-badge m-badge--success m-badge--wide">Aktif</span>'
                                : '<span class="m-badge m-badge--danger m-badge--wide">Nonaktif</span>';
                        }
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 150,
                        template: function(t) {
                            var html = `
                                <a href="<?= base_url('dir/manage_klasifikasi/form/view/') ?>${t.id_enc}" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                <a href="<?= base_url('dir/manage_klasifikasi/form/edit/') ?>${t.id_enc}" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                            `;
                            // Hanya tampilkan tombol Hapus jika status == 1 (Aktif)
                            if (t.status == 1) {
                                html += `
                                    <button class="btn btn-sm btn-danger btn-icon hapus-klas" data-id="${t.id_enc}" title="Hapus"><i class="la la-trash"></i></button>
                                `;
                            }
                            return html;
                        }
                    }
                ]
            });
        }
    };

    $(document).ready(function() {
        DatatableKlasifikasi.init();

        // Hapus (soft delete)
        $(document).on('click', '.hapus-klas', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus klasifikasi?',
                text: "Data akan diubah menjadi nonaktif!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    $.post("<?= base_url('dir/manage_klasifikasi/delete') ?>", {id: id}, function(res) {
                        if (res.status == 'success') {
                            Swal.fire('Berhasil', res.message, 'success');
                            datatable.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    }, 'json');
                }
            });
        });
    });
</script>