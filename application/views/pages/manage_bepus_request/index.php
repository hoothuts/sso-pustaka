<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Request Bebas Pustaka</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="row align-items-center">
                                <!-- Search -->
                                <div class="col-md-4">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari NIM, Nama..." id="generalSearch">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Filter Kelas -->
                                <div class="col-md-3">
                                    <select class="form-control" id="filter_kelas">
                                        <option value="">Semua Prodi</option>
                                        <?php foreach ($kelas_list as $k): ?>
                                            <option value="<?= htmlspecialchars($k->kelas) ?>" 
                                                    <?= ($current_kelas == $k->kelas) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($k->kelas) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Filter Tahun Masuk -->
                                <div class="col-md-3">
                                    <select class="form-control" id="filter_tahun">
                                        <option value="">Semua Tahun Akademik</option>
                                        <?php 
                                        if(!empty($tahun_list)){
                                            foreach ($tahun_list as $t): ?>
                                            <option value="<?= $t->tahun_akademik ?>" 
                                                    <?= ($current_tahun == $t->tahun_akademik) ? 'selected' : '' ?>>
                                                <?= $t->tahun_akademik ?>
                                            </option>
                                        <?php endforeach;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 m--align-right">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#flowchartModal">
                                <i class="fa fa-sitemap"></i> Lihat Flowchart
                            </button>
                            <a href="<?= base_url('dir/manage_bepus_request/form') ?>" 
                               class="btn btn-primary btn-sm">
                                <span><i class="la la-plus"></i><span>Tambah Request</span></span>
                            </a>
                            <button id="btn_export_excel" 
                                class="btn btn-success btn-sm">
                                <span><i class="la la-file-excel-o"></i><span>Export Excel</span></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="m_datatable" id="datatable_request"></div>
            </div>
        </div>
    </div>
</div>
<!-- ==================== MODAL FLOWCHART ==================== -->
<div class="modal fade" id="flowchartModal" tabindex="-1" role="dialog" aria-labelledby="flowchartModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="bebasPustakaModalLabel">
                    <i class="fa fa-sitemap"></i> Alur Pengajuan Bebas Pustaka
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background:#f8f9fa; padding:25px;">
                
                <div id="mermaidBebasPustaka" class="mermaid text-center">
                    flowchart TD
                        Start([Mulai]) --> C[Mahasiswa mengisi Form Bebas Pustaka secara online<br>Pada Sistem Informasi Akademik Mahasiswa]
                        C --> F[Mahasiswa Menyerahkan Dokumen Fisik ke Pustakawan]
                        F --> G[Pustakawan Melakukan Validasi Persyaratan]

                        G --> H{Ada Persyaratan yang<br>Tidak Sesuai?}
                        H -->|Ya| I[Pustakawan Memberikan Catatan Perbaikan]
                        I --> J[Mahasiswa Memperbaiki / Melengkapi Persyaratan]
                        J --> F

                        H -->|Tidak| K[Semua Persyaratan Sudah Valid]
                        K --> L[Kepala Perpustakaan Melakukan Review & Persetujuan]

                        L --> M{Disetujui?}
                        M -->|Ya| N[Mahasiswa Mendownload Surat Bebas Pustaka]
                        M -->|Tidak| O[Pengajuan Ditolak]
                        O --> P[Mahasiswa Dapat Mengajukan Ulang dari Awal]

                        N --> Q([Proses Selesai])
                        P --> Q

                        %% Pewarnaan & Gaya Visual Modern
                        style Start fill:#4caf50,stroke:#2e7d32,stroke-width:2px,color:#fff
                        style C fill:#e3f2fd,stroke:#1976d2,stroke-width:1.5px
                        style F fill:#edf2f7,stroke:#4a5568
                        style G fill:#fff3e0,stroke:#f57c00
                        style H fill:#fffaf0,stroke:#dd6b20
                        style I fill:#fff3e0,stroke:#f57c00
                        style J fill:#edf2f7,stroke:#4a5568
                        style K fill:#e8f5e9,stroke:#388e3c
                        style L fill:#e8f5e9,stroke:#388e3c
                        style M fill:#fffaf0,stroke:#dd6b20
                        style N fill:#f3e5f5,stroke:#7b1fa2
                        style O fill:#ffebee,stroke:#c62828
                        style P fill:#ffebee,stroke:#c62828
                        style Q fill:#e0f2f1,stroke:#00695c,stroke-width:2px,color:#004d40
                </div>

                <!-- Penjelasan Alur -->
                <div style="margin-top: 30px; padding: 20px; background: white; border: 1px solid #ddd; border-radius: 8px;">
                    <h5 style="margin-bottom: 15px; color: #333;">Penjelasan Alur Pengajuan Bebas Pustaka:</h5>
                    <ol style="line-height: 1.8; padding-left: 20px;">
                        <li>Mahasiswa login dan mengajukan permohonan secara daring</li>
                        <li>Menyerahkan dokumen persyaratan fisik ke pustakawan</li>
                        <li><strong>Pustakawan melakukan validasi</strong>. Jika ada persyaratan yang tidak sesuai, pustakawan memberikan catatan perbaikan</li>
                        <li>Mahasiswa memperbaiki/melengkapi persyaratan sesuai catatan</li>
                        <li>Jika semua persyaratan sudah lengkap, kepala perpustakaan melakukan review</li>
                        <li><strong>Kepala Perpustakaan dapat menyetujui atau menolak</strong></li>
                        <li>Jika ditolak, mahasiswa dapat mengajukan ulang dari awal</li>
                        <li>Jika disetujui, mahasiswa dapat mendownload surat keterangan Bebas Pustaka</li>
                    </ol>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/mermaid@10.6.1/dist/mermaid.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var DatatableRequest = {

    datatable: null,

    init: function() {
        this.initDatatable();
        this.initFilterState();
        this.bindEvents();
        this.datatable.load();
    },

    // ================= INIT DATATABLE =================
    initDatatable: function() {
        this.datatable = $("#datatable_request").mDatatable({
            data: {
                type: "remote",
                source: {
                    read: {
                        url: "<?= base_url('dir/manage_bepus_request/fetch') ?>",
                        method: 'POST',
                        params: {
                            query: {
                                kelas_filter: "",
                                tahun_filter: ""
                            }
                        }
                    }
                },
                pageSize: 10,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },
            layout: { scroll: false, footer: false },
            sortable: true,
            pagination: true,
            search: { input: $("#generalSearch"), delay: 400 },

            columns: this.getColumns()
        });
    },

    // ================= COLUMN CONFIG =================
    getColumns: function() {
        return [
            { field: "number", title: "#", width: 40, textAlign: "center" },
            { field: "no_request", title: "No Request", width: 110 },
            { field: "nim", title: "NIM", width: 110 },
            { field: "nama_siswa", title: "Nama", width: 250 },
            { field: "kelas", title: "Prodi", width: 210 },
            { field: "tahun_akademik", title: "Tahun Akademik", width: 90 },
            
            {
                field: "kelengkapan",
                title: "Status Kelengkapan",
                width: 140,
                template: function(row) {
                    return row.kelengkapan === 'Lengkap'
                        ? `<span class="m-badge m-badge--success m-badge--wide">Lengkap</span>`
                        : `<span class="m-badge m-badge--warning m-badge--wide">Belum Lengkap</span>`;
                }
            },

            {
                field: "status_pengajuan",
                title: "Status Pengajuan",
                width: 90,
                template: function(row) {
                    var badge =
                        row.status_pengajuan == 'Approve' ? 'success' :
                        row.status_pengajuan == 'Reject' ? 'danger' : 'warning';

                    return `<span class="m-badge m-badge--${badge} m-badge--wide">${row.status_pengajuan}</span>`;
                }
            },

            { field: "tgl_post", title: "Tanggal Request", width: 100 },

            {
                field: "action",
                title: "Aksi",
                sortable: false,
                width: 200,
                template: function(t) {
                    /*
                     <a href="<?= base_url('dir/manage_bepus_request/edit/') ?>${t.id_enc}" 
                           class="btn btn-sm btn-primary btn-icon"><i class="la la-edit"></i></a>
                     */
                    let html = `
                        <a href="<?= base_url('dir/manage_bepus_request/validasi/') ?>${t.id_enc}" 
                           class="btn btn-sm btn-info btn-icon" title='Validasi Request'><i class="la la-clipboard"></i></a>
                    `;
                    if(t.is_kepala == 1){
                        html += `
                        <a href="<?= base_url('dir/manage_bepus_request/konfirmasi/') ?>${t.id_enc}" 
                           class="btn btn-sm btn-success btn-icon" title='Konfirmasi Request'><i class="la la-check"></i></a>
                        `;
                    }
                    if (t.status_pengajuan == 'Approve') {
                        html += `
                         <a href="<?= base_url('dir/manage_bepus_request/download/') ?>${t.id_enc}" 
                            class="btn btn-sm btn-info" target="_blank" title='Download Form'>
                             <i class="la la-download"></i>
                         </a>
                         `;
                    }
                    if (t.status == 1 && t.status_pengajuan != 'Approve') {
                        html += `
                            <button class="btn btn-sm btn-danger btn-icon hapus" data-id="${t.id_enc}" title='Hapus Request'>
                                <i class="la la-trash"></i>
                            </button>
                        `;
                    }

                    return html;
                }
            }
        ];
    },

    // ================= FILTER STATE =================
    initFilterState: function() {
        const kelas = this.getStorage('filter_kelas');
        const tahun = this.getStorage('filter_tahun');

        if (kelas !== null) {
            $('#filter_kelas').val(kelas);
            this.setFilter('kelas_filter', kelas);
        }

        if (tahun !== null) {
            $('#filter_tahun').val(tahun);
            this.setFilter('tahun_filter', tahun);
        }
    },

    // ================= EVENT BINDING =================
    bindEvents: function() {
        const self = this;

        $('#filter_kelas').on('change', function() {
            self.handleFilterChange('kelas_filter', 'filter_kelas', $(this).val());
        });

        $('#filter_tahun').on('change', function() {
            self.handleFilterChange('tahun_filter', 'filter_tahun', $(this).val());
        });
    },

    // ================= FILTER HANDLER =================
    handleFilterChange: function(param, storageKey, value) {
        this.setStorage(storageKey, value);
        this.setFilter(param, value);
        this.datatable.load();
    },

    setFilter: function(param, value) {
        this.datatable.setDataSourceParam('query.' + param, value);
    },

    // ================= STORAGE HELPER =================
    getStorage: function(key) {
        return localStorage.getItem(key);
    },

    setStorage: function(key, value) {
        localStorage.setItem(key, value);
    }
};

// ================= INIT =================
$(document).ready(function() {
    DatatableRequest.init();

    // DELETE ACTION
    $(document).on('click', '.hapus', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus request?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.value) {
                $.post("<?= base_url('dir/manage_bepus_request/delete') ?>", {id: id}, function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success')
                            .then(() => $("#datatable_request").mDatatable('reload'));
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });
    
    $('#btn_export_excel').on('click', function () {
        let search = $('#generalSearch').val();
        let kelas  = $('#filter_kelas').val();
        let tahun  = $('#filter_tahun').val();

        let url = "<?= base_url('dir/manage_bepus_request/export_excel') ?>?" +
            "search=" + encodeURIComponent(search) +
            "&kelas_filter=" + encodeURIComponent(kelas) +
            "&tahun_filter=" + encodeURIComponent(tahun);

        window.open(url, '_blank');
    });
    
    mermaid.initialize({
        startOnLoad: false,
        theme: 'default',
        securityLevel: 'loose',
        flowchart: { 
            useMaxWidth: true,
            htmlLabels: true,
            curve: 'basis'
        }
    });

    // Render ulang Mermaid saat modal muncul
    $('#flowchartModal').on('shown.bs.modal', function () {
        mermaid.init(undefined, document.querySelectorAll('.mermaid'));
    });
    
});
</script>