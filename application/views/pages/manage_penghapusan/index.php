<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Daftar Penghapusan Inventaris</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button class="btn btn-info m-btn m-btn--pill m-btn--air" onclick="window.location.href='<?= base_url('dir/manage_penghapusan/form/add_page'); ?>'">
                        <i class="la la-plus"></i> Tambah Penghapusan
                    </button>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-4">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" class="form-control m-input" placeholder="Cari No Penghapusan atau Catatan..." id="generalSearch">
                                <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m_datatable" id="penghapusan_server_side"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var DatatablePenghapusan = {
        init: function() {
            var datatable = $("#penghapusan_server_side").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_penghapusan/fetch') ?>",
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
                        field: "no_penghapusan",
                        title: "No Penghapusan",
                        width: 130
                    },
                    {
                        field: "no_dokumen",
                        title: "No Penyiangan",
                        width: 130
                    },
                    {
                        field: "tgl",
                        title: "Tanggal Penghapusan",
                        width: 120
                    },
                    {
                        field: "status_penghapusan",
                        title: "Status",
                        width: 100,
                        template: function(row) {
                            var status = row.status_penghapusan;
                            var badge = '';
                            var text = status;

                            if (status === 'Pending') {
                                badge = 'm-badge--warning';
                            } else if (status === 'Approve') {
                                badge = 'm-badge--success';
                            } else if (status === 'Reject') {
                                badge = 'm-badge--danger';
                            } else {
                                badge = 'm-badge--metal'; // fallback jika status lain
                                text = status || 'Unknown';
                            }

                            return `<span class="m-badge ${badge} m-badge--wide">${text}</span>`;
                        }
                    },
                    {
                        field: "catatan",
                        title: "Catatan",
                        width: 250,
                        sortable: false
                    },
                    {
                        field: "jumlah_buku",
                        title: "Jumlah Buku",
                        width: 100
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
                        width: 140,
                        template: function(t) {
                            var buttons = `
                                <a href="<?= base_url('dir/manage_penghapusan/form/view_page/') ?>${t.id}" 
                                   class="btn btn-sm btn-info btn-icon m-btn m-btn--icon m-btn--pill" 
                                   title="Lihat Detail">
                                    <i class="la la-eye"></i>
                                </a>
                            `;

                            // Hanya tampilkan Edit & Hapus jika status = Pending
                            if (t.status_penghapusan === 'Pending') {
                                buttons += `
                                    <a href="<?= base_url('dir/manage_penghapusan/form/edit_page/') ?>${t.id}" 
                                       class="btn btn-sm btn-primary btn-icon m-btn m-btn--icon m-btn--pill" 
                                       title="Edit">
                                        <i class="la la-edit"></i>
                                    </a>

                                    <button onclick="hapus('${t.id}')" 
                                            class="btn btn-sm btn-danger btn-icon m-btn m-btn--icon m-btn--pill" 
                                            title="Hapus">
                                        <i class="la la-trash"></i>
                                    </button>
                                `;
                            }

                            return buttons;
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
            title: "Hapus Penghapusan?",
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
                    url: "<?= base_url('dir/manage_penghapusan/delete/') ?>" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(res) {
                        mApp.unblockPage();
                        if (res.data == true) {
                            Swal.fire("Berhasil", "Data telah dihapus", "success");
                            $("#penghapusan_server_side").mDatatable("reload");
                        }
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        DatatablePenghapusan.init();
    });
</script>