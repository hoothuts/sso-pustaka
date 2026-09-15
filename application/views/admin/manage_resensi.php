<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Daftar Resensi Buku</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-info m-btn m-btn--pill m-btn--air" onclick="window.location.href='<?= base_url('admin/manage_resensi/add_page'); ?>'">
                        <i class="la la-plus"></i> Tambah Resensi
                    </button>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Cari Judul, ISBN, atau Resensi..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m_datatable" id="resensi_server_side"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var DatatableResensi = {
        init: function() {
            var datatable = $("#resensi_server_side").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('admin/manage_resensi/fetch') ?>",
                            method: 'POST',
                        }
                    },
                    pageSize: 10,
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true,
                },
                layout: {
                    scroll: false,
                    footer: false
                },
                sortable: true,
                pagination: true,
                columns: [{
                        field: "number",
                        title: "#",
                        width: 40,
                        sortable: false,
                        textAlign: "center"
                    },
                    {
                        field: "judul",
                        title: "Informasi Buku",
                        width: 350
                        // Secara otomatis merender HTML dari Controller
                    },
                    {
                        field: "review",
                        title: "Cuplikan Resensi",
                        width: 300,
                        sortable: false
                    },
                    {
                        field: "tgl",
                        title: "Tanggal",
                        width: 120
                    },
                    {
                        field: "author",
                        title: "Admin",
                        width: 100
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 110,
                        template: function(t) {
                            return `
                                <a href="<?= base_url('admin/manage_resensi/edit_page/') ?>${t.id}" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                                <button onclick="hapus('${t.id}')" class="btn btn-sm btn-danger btn-icon" title="Hapus"><i class="la la-trash"></i></button>
                            `;
                        }
                    }
                ]
            });

            $('#generalSearch').on('keyup', function() {
                datatable.search($(this).val().toLowerCase(), 'generalSearch');
            });
        }
    };

    function hapus(id) {
        Swal.fire({
            title: "Hapus Resensi?",
            text: "Data ini akan dipindahkan ke tempat sampah.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.value) {
                mApp.blockPage({
                    overlayColor: "#000000",
                    type: "loader",
                    state: "primary",
                    message: "Menghapus..."
                });

                $.ajax({
                    url: "<?= base_url('admin/manage_resensi/delete/') ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(res) {
                        mApp.unblockPage();
                        if (res.data == true) {
                            Swal.fire("Berhasil", "Data telah dihapus", "success");
                            $("#resensi_server_side").mDatatable("reload");
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        DatatableResensi.init();
    });
</script>