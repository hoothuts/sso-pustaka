<!-- View: manage_penyiangan.php (daftar/list) -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Daftar Penyiangan Koleksi</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-info m-btn m-btn--pill m-btn--air" onclick="window.location.href='<?= base_url('dir/manage_penyiangan/form/add_page'); ?>'">
                        <i class="la la-plus"></i> Tambah Penyiangan
                    </button>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Cari No Dokumen atau Catatan..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m_datatable" id="penyiangan_server_side"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var DatatablePenyiangan = {
        init: function() {
            var datatable = $("#penyiangan_server_side").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_penyiangan/fetch') ?>",
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
                        field: "no_dokumen",
                        title: "No Dokumen",
                        width: 150
                    },
                    {
                        field: "tgl",
                        title: "Tanggal",
                        width: 120
                    },
                    {
                        field: "catatan",
                        title: "Catatan",
                        width: 300,
                        sortable: false
                    },
                    {
                        field: "jumlah_buku",
                        title: "Jumlah Buku",
                        width: 100
                    },
                    {
                        field: "author",
                        title: "Author",
                        width: 100
                    },
                     {
                        field: "tgl_post",
                        title: "Tgl Input",
                        width: 100
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 190,  // Lebar lebih besar karena ada 4 tombol
                        template: function(t) {
                            var buttonHapus = '';
                            var buttonHapusPenyiangan = '';
                            var buttonEdit = '';
                            
                            // Asumsikan Anda tambah field 'has_penghapusan' di fetch data (0/1)
                            if (t.has_penghapusan == 0) {
                                buttonHapus = `<a href="<?= base_url('dir/manage_penghapusan/form/add_page?penyiangan_id=') ?>${t.id}" class="btn btn-sm btn-warning btn-icon" title="Buat Penghapusan"><i class="la la-minus-circle"></i></a>`;
                                buttonHapusPenyiangan = `<button onclick="hapus('${t.id}')" class="btn btn-sm btn-danger btn-icon" title="Hapus"><i class="la la-trash"></i></button>`;
                                buttonEdit = `<a href="<?= base_url('dir/manage_penyiangan/form/edit_page/') ?>${t.id}" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>`;
                            }
                            return `
                                ${buttonHapus}
                                <a href="<?= base_url('dir/manage_penyiangan/form/view_page/') ?>${t.id}" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                ${buttonEdit}
                                ${buttonHapusPenyiangan}
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
            title: "Hapus Penyiangan?",
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
                    url: "<?= base_url('dir/manage_penyiangan/delete/') ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(res) {
                        mApp.unblockPage();
                        if (res.data == true) {
                            Swal.fire("Berhasil", "Data telah dihapus", "success");
                            $("#penyiangan_server_side").mDatatable("reload");
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        DatatablePenyiangan.init();
    });
</script>