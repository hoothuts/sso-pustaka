<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Konfirmasi Penghapusan Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#m_tabs_1_1">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#m_tabs_1_2">Completed</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="m_tabs_1_1" role="tabpanel">
                        <div class="m_datatable" id="pending_server_side"></div>
                    </div>
                    <div class="tab-pane" id="m_tabs_1_2" role="tabpanel">
                        <div class="m_datatable" id="completed_server_side"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var DatatablePending = {
        init: function() {
            var datatable = $("#pending_server_side").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/konfirmasi_penghapusan/fetch') ?>",
                            method: 'POST',
                            params: { status_filter: 'Pending' }
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
                        title: "Tgl Penghapusan",
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
                        width: 200,
                        sortable: false
                    },
                    {
                        field: "jumlah_buku",
                        title: "Jumlah Buku",
                        width: 80
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
                        width: 160,
                        template: function(t) {
                            return t.is_kepala == 1
                                ? `<a href="<?= base_url('dir/konfirmasi_penghapusan/form_konfirmasi/') ?>${t.id}" 
                                      class="btn btn-sm btn-warning btn-icon" 
                                      title="Konfirmasi">
                                      <i class="la la-check"></i>
                                   </a>`
                                : `<span class="text-danger">
                                      <i class="la la-lock"></i> Konfirmasi hanya oleh Ka. Perpustakaan
                                   </span>`;
                        }
                    }
                ]
            });
        }
    };

    var DatatableCompleted = {
        init: function() {
            var datatable = $("#completed_server_side").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/konfirmasi_penghapusan/fetch') ?>",
                            method: 'POST',
                            params: { status_filter: 'Completed' } 
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
                        title: "Tgl Penghapusan",
                        width: 100
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
                        field: "tgl_approve",
                        title: "Tgl Konfirmasi",
                        width: 100
                    },
                    {
                        field: "catatan_kaperpus",
                        title: "Catatan Ka. Pustaka",
                        width: 200,
                        sortable: false
                    },
                    {
                        field: "approve_by",
                        title: "Konfirmasi Oleh",
                        width: 120
                    },
                    {
                        field: "jumlah_buku",
                        title: "Jumlah Buku",
                        width: 80
                    },
                    {
                        field: "author",
                        title: "Admin",
                        width: 120
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 110,
                        template: function(t) {
                            return `
                                <a href="<?= base_url('dir/manage_penghapusan/form/view_page/') ?>${t.id}" target="_blank" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                            `;
                        }
                    }
                ]
            });
        }
    };

    $(document).ready(function() {
        DatatablePending.init();
        DatatableCompleted.init();
    });
</script>