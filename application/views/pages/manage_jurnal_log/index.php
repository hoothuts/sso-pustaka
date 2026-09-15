<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">

        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">

                    <div class="m-portlet__head-title">

                        <h3 class="m-portlet__head-text">
                            Manage Jurnal Log
                        </h3>

                    </div>

                </div>
                
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <button class="btn btn-success" id="btn_export">
                                <i class="fa fa-file-excel-o"></i>
                                Export Excel
                            </button>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="m-portlet__body">

                <!-- FILTER -->
                <div class="m-form m-form--label-align-right m--margin-bottom-30">

                    <div class="row align-items-center">
                        
                        <div class="col-xl-12">

                            <div class="row align-items-center">
                                
                                <!-- SEARCH -->
                                <div class="col-md-3">

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
                                <div class="col-md-3">

                                    <select id="filter_vendor"
                                            class="form-control">

                                    </select>

                                </div>

                                <!-- FILTER AKTIVITAS -->
                                <div class="col-md-2">

                                    <select id="filter_aktivitas"
                                            class="form-control">

                                        <option value="">
                                            Semua Aktivitas
                                        </option>

                                        <option value="SHOW_ACCOUNT">
                                            SHOW_ACCOUNT
                                        </option>

                                        <option value="VIEW_PASSWORD">
                                            VIEW_PASSWORD
                                        </option>

                                        <option value="COPY_ACCOUNT">
                                            COPY_ACCOUNT
                                        </option>

<!--                                        <option value="OPEN_VENDOR">
                                            OPEN_VENDOR
                                        </option>-->

                                        <option value="RELEASE_ACCOUNT">
                                            RELEASE_ACCOUNT
                                        </option>
                                        
                                        <option value="AUTO_RELEASE">
                                            AUTO_RELEASE
                                        </option>

                                    </select>

                                </div>

                                <!-- TANGGAL AWAL -->
                                <div class="col-md-2">

                                    <input type="date"
                                           id="tanggal_awal"
                                           class="form-control">

                                </div>

                                <!-- TANGGAL AKHIR -->
                                <div class="col-md-2">

                                    <input type="date"
                                           id="tanggal_akhir"
                                           class="form-control">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- DATATABLE -->
                <div class="m_datatable"
                     id="datatable_log">

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL DETAIL -->
<div class="modal fade"
     id="modal_detail"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header m--bg-brand">

                <h5 class="modal-title m--font-light">
                    Detail Log Jurnal
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
                        <th width="220">Tanggal</th>
                        <td id="d_tanggal"></td>
                    </tr>

                    <tr>
                        <th>NIM</th>
                        <td id="d_nim"></td>
                    </tr>

                    <tr>
                        <th>Nama Mahasiswa</th>
                        <td id="d_nama"></td>
                    </tr>

                    <tr>
                        <th>Prodi / Kelas</th>
                        <td id="d_kelas"></td>
                    </tr>

                    <tr>
                        <th>Vendor</th>
                        <td id="d_vendor"></td>
                    </tr>

                    <tr>
                        <th>Username Vendor</th>
                        <td id="d_username"></td>
                    </tr>

                    <tr>
                        <th>Aktivitas</th>
                        <td id="d_aktivitas"></td>
                    </tr>

                    <tr>
                        <th>IP Address</th>
                        <td id="d_ip"></td>
                    </tr>

                    <tr>
                        <th>Session ID</th>
                        <td id="d_session"></td>
                    </tr>

                    <tr>
                        <th>User Agent</th>
                        <td id="d_user_agent"></td>
                    </tr>

                    <tr>
                        <th>Keterangan</th>
                        <td id="d_keterangan"></td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

    var datatable;

    $(document).ready(function(){

        /* ================= SELECT2 VENDOR ================= */
        $('#filter_vendor').select2({

            placeholder: 'Semua Vendor',
            allowClear: true,

            ajax: {

                url: "<?= base_url('dir/manage_jurnal_log/search_vendor') ?>",

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
        datatable = $("#datatable_log").mDatatable({

            data: {

                type: "remote",

                source: {

                    read: {

                        url: "<?= base_url('dir/manage_jurnal_log/fetch') ?>",

                        method: "POST",

                        params: {

                            vendor: function(){
                                return $('#filter_vendor').val();
                            },

                            aktivitas: function(){
                                return $('#filter_aktivitas').val();
                            },

                            tanggal_awal: function(){
                                return $('#tanggal_awal').val();
                            },

                            tanggal_akhir: function(){
                                return $('#tanggal_akhir').val();
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
                    field: "tgl_post",
                    title: "Tanggal",
                    width: 140
                },

                {
                    field: "nim",
                    title: "NIM",
                    width: 100
                },

                {
                    field: "nama",
                    title: "Nama Mahasiswa"
                },

                {
                    field: "kelas",
                    title: "Prodi / Kelas",
                    width: 150
                },

                {
                    field: "nama_vendor",
                    title: "Vendor",
                    width: 130
                },

                {
                    field: "username",
                    title: "Username Vendor",
                    width: 130
                },

                {
                    field: "jenis_aktivitas",
                    title: "Aktivitas",
                    width: 140,

                    template: function(row){

                        let badge = 'm-badge--secondary';

                        if(row.jenis_aktivitas == 'SHOW_ACCOUNT'){
                            badge = 'm-badge--primary';
                        }

                        if(row.jenis_aktivitas == 'VIEW_PASSWORD'){
                            badge = 'm-badge--warning';
                        }

                        if(row.jenis_aktivitas == 'COPY_ACCOUNT'){
                            badge = 'm-badge--info';
                        }

                        if(row.jenis_aktivitas == 'OPEN_VENDOR'){
                            badge = 'm-badge--success';
                        }

                        if(row.jenis_aktivitas == 'RELEASE_ACCOUNT'){
                            badge = 'm-badge--danger';
                        }

                        return `
                            <span class="m-badge ${badge} m-badge--wide">
                                ${row.jenis_aktivitas}
                            </span>
                        `;
                    }

                },

                {
                    field: "ip_address",
                    title: "IP",
                    width: 120
                },

                {
                    field: "device",
                    title: "Device",
                    width: 100
                },

                {
                    field: "action",
                    title: "Aksi",
                    width: 90,
                    sortable: false,

                    template: function(row){

                        return `

                            <button class="btn btn-sm btn-info btn-icon detail"
                                    data-id="${row.id_enc}">

                                <i class="la la-search"></i>

                            </button>

                        `;
                    }

                }

            ]

        });

        /* ================= FILTER ================= */
        $('#filter_vendor').change(function(){

            datatable.reload();

        });

        $('#filter_aktivitas').change(function(){

            datatable.reload();

        });

        $('#tanggal_awal').change(function(){

            datatable.reload();

        });

        $('#tanggal_akhir').change(function(){

            datatable.reload();

        });

        /* ================= DETAIL ================= */
        $(document).on('click', '.detail', function(){

            let id = $(this).data('id');

            $.post(

                "<?= base_url('dir/manage_jurnal_log/get_detail') ?>",

                {id:id},

                function(res){

                    if(res.status == 'success'){

                        let d = res.data;

                        $('#d_tanggal').html(d.tgl_post);
                        $('#d_nim').html(d.nim);
                        $('#d_nama').html(d.nama);
                        $('#d_kelas').html(d.kelas);

                        $('#d_vendor').html(
                            d.nama_vendor
                        );

                        $('#d_username').html(
                            d.username
                        );

                        $('#d_aktivitas').html(
                            d.jenis_aktivitas
                        );

                        $('#d_ip').html(
                            d.ip_address
                        );

                        $('#d_session').html(
                            d.session_id
                        );

                        $('#d_user_agent').html(
                            d.user_agent
                        );

                        $('#d_keterangan').html(
                            d.keterangan
                        );

                        $('#modal_detail').modal('show');

                    }

                },

                'json'

            );

        });
        
        /* ================= EXPORT ================= */
        $('#btn_export').click(function(){

            let vendor = $('#filter_vendor').val();
            let aktivitas = $('#filter_aktivitas').val();
            let tanggal_awal = $('#tanggal_awal').val();
            let tanggal_akhir = $('#tanggal_akhir').val();
            let search = $('#generalSearch').val();

            let url =
                "<?= base_url('dir/manage_jurnal_log/export_excel?') ?>" +

                "vendor=" + encodeURIComponent(vendor) +
                "&aktivitas=" + encodeURIComponent(aktivitas) +
                "&tanggal_awal=" + encodeURIComponent(tanggal_awal) +
                "&tanggal_akhir=" + encodeURIComponent(tanggal_akhir) +
                "&search=" + encodeURIComponent(search);

            window.open(url, '_blank');

        });

    });

</script>