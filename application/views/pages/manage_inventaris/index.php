<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Inventaris Buku</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <!-- Form Group Action (muncul jika ada checkbox yang dicek) -->
                <!--begin: Selected Rows Group Action Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label m-form__label-no-wrap">
                                    <label class="m--font-bold m--font-danger-">
                                        Selected <span id="m_datatable_selected_number">0</span> records:
                                    </label>
                                </div>
                                <div class="m-form__control">
                                    <div class="btn-toolbar">
                                        <button id="cetak_barcode" type="button" class="btn btn-accent btn-sm">
                                            <i class="la la-barcode"></i> Cetak Barcode
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                            <i class="la la-file-text"></i> Cetak Callnumber
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_aktif">Inventaris Aktif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_dihapus">Inventaris Dihapus</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_aktif">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-2">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari Data Buku..." id="generalSearch">
                                        <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="m-input-icon m-input-icon--left">
                                        <select class="form-control" id="m_form_klas">
                                            <option value="">Pilih Klasifikasi</option>
                                            <?php if ($klasifikasi) { ?>
                                                <?php foreach ($klasifikasi as $row) { ?>
                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?> </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control m-bootstrap-select" id="m_form_kampus">
                                        <option value="">Pilih Kampus</option>
                                        <?php
                                        if ($kampus) {
                                            foreach ($kampus as $k) {
                                                ?>
                                                <option value="<?= $k->lokasikampus_id ?>"><?= $k->nama_kampus ?></option>
                                            <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control m-bootstrap-select" id="m_form_gedung" disabled>
                                        <option value="">Pilih Gedung</option>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control m-bootstrap-select" id="m_form_rak" disabled>
                                        <option value="">Pilih Rak</option>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                 <div class="col-md-2">
                                    <button id="export_excel" class="btn btn-success">
                                        <i class="la la-file-excel-o"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="m_datatable" id="datatable_aktif"></div>
                    </div>
                    <div class="tab-pane" id="tab_dihapus">
                        <div class="m-form m-form--label-align-right m--margin-bottom-30">
                            <div class="row align-items-center">
                                <div class="col-xl-2">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Cari Data Buku..." id="generalSearch2">
                                        <span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m_datatable" id="datatable_dihapus"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tab Aktif
    var DatatableAktif = {
        init: function () {
            var datatable1 = $("#datatable_aktif").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_inventaris/fetch') ?>",
                            method: 'POST',
                            params: {status_filter: 'A'}
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
                columns: [
                    // Kolom checkbox (paling kiri)
                    {
                        field: "checkbox",
                        title: `<input type="checkbox" id="check_all_aktif">`,
                        width: 20,
                        sortable: false,
                        textAlign: "center",
                        template: function (row) {
                            return `<input type="checkbox" class="row-checkbox" value="${row.no_barcode}">`;
                        }
                    },
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 90,
                        template: function (t) {
                            return `
                                <a href="<?= base_url('dir/manage_inventaris/form/view_page/') ?>${t.id}" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                                <a href="<?= base_url('dir/manage_inventaris/form/edit_page/') ?>${t.id}" class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                            `;
                        }
                    },
                    {field: "number", title: "#", width: 40, sortable: false, textAlign: "center"},
                    { field: "no_inv", title: "No Inventaris",  width: 100 },
                    { field: "no_barcode", title: "Barcode", width: 80 },
                    { field: "tgl_inv", title: "Tgl Inventaris" , width: 90},
                    { field: "judul", title: "Judul Buku",width: 270 },
                    { field: "isbn", title: "ISBN",width: 190 },
                    { field: "no_klas", title: "NO Klas" },
                    { field: "penulis", title: "Penulis" },
                    { field: "nama_kampus", title: "Kampus" ,width: 130,sortable: false },
                    { field: "no_rak", title: "Rak Buku" ,width: 130,sortable: false },
                    { field: "thn_terbit", title: "Tahun Terbit", width: 60 },
                    { field: "penerbit", title: "Penerbit" },
                    { field: "asal_buku", title: "Asal Buku" ,width: 130,sortable: false }
                    
                ]
            });
            const datatable = datatable1;
            const query = datatable.getDataSourceQuery();
            
            $('#generalSearch').on('keyup', function() {
                datatable1.search($(this).val().toLowerCase(), 'generalSearch');
            });
            
             $("#m_form_klas, #m_form_kampus, #m_form_gedung, #m_form_rak").selectpicker();
             
            // Onchange select m_form_klas
            $('#m_form_klas').on('change', function() {
                var klas = $(this).val();
                datatable1.setDataSourceParam('klas_filter', klas); // Kirim params tambahan ke fetch
                datatable1.load(); // Reload datatable
            });
            
            // Load gedung saat pilih kampus
            $('#m_form_kampus').on('change', function() {
                var kampus_id = $(this).val();
                $('#m_form_gedung').prop('disabled', true).val('').trigger('change');
                $('#m_form_rak').prop('disabled', true).val('').trigger('change');

                if (kampus_id) {
                    $.ajax({
                        url: '<?= base_url('dir/manage_lokasi/get_gedung_options') ?>',
                        type: 'POST',
                        data: { kampus_id: kampus_id },
                        dataType: 'json',
                        success: function(res) {
                            $('#m_form_gedung').empty().append('<option value="">Semua Gedung</option>');
                            $.each(res, function(i, item) {
                                $('#m_form_gedung').append('<option value="' + item.lokasigedung_id + '">' + item.nama_gedung + '</option>');
                            });
                            // Aktifkan select dan refresh selectpicker
                            $('#m_form_gedung').prop('disabled', false).selectpicker('refresh').trigger('change');
                        }
                    });
                } else {
                   $('#m_form_gedung').prop('disabled', true).selectpicker('refresh').val('').trigger('change');
                }

                const q = datatable.getDataSourceQuery();
                q.kampus = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.kampus || "");

            // Load rak saat pilih gedung
            $('#m_form_gedung').on('change', function() {
                var gedung_id = $(this).val();
                $('#m_form_rak').prop('disabled', true).val('').trigger('change');

                if (gedung_id) {
                    $.ajax({
                        url: '<?= base_url('dir/manage_lokasi/get_rak_options') ?>',
                        type: 'POST',
                        data: { gedung_id: gedung_id },
                        dataType: 'json',
                        success: function(res) {
                            $('#m_form_rak').empty().append('<option value="">Semua Rak</option>');
                            $.each(res, function(i, item) {
                                $('#m_form_rak').append('<option value="' + item.lokasirak_id + '">' + item.nama_rak + '</option>');
                            });
                            // Aktifkan select dan refresh selectpicker
                            $('#m_form_rak').prop('disabled', false).selectpicker('refresh').trigger('change');
                        }
                    });
                } else {
                    $('#m_form_rak').prop('disabled', true).selectpicker('refresh').val('').trigger('change');
                }

                const q = datatable.getDataSourceQuery();
                q.gedung = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.gedung || "");

            // Reload saat pilih rak
            $('#m_form_rak').on('change', function() {
                const q = datatable.getDataSourceQuery();
                q.rak = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.rak || "");
            
            // Checkbox logic (check all & update selected count)
            $(document).on('change', '#check_all_aktif', function () {
                $('.row-checkbox').prop('checked', this.checked);
                updateSelected();
            });

            $(document).on('change', '.row-checkbox', function () {
                updateSelected();
            });

            function updateSelected() {
                var selected = $('.row-checkbox:checked').length;
                $('#m_datatable_selected_number').text(selected);
                if (selected > 0) {
                    $('#m_datatable_group_action_form').collapse('show');
                } else {
                    $('#m_datatable_group_action_form').collapse('hide');
                }
            }

            // Cetak Barcode
            $('#cetak_barcode').on('click', function () {

                var selected = [];
                $('.row-checkbox:checked').each(function () {
                    selected.push($(this).val());
                });

                if (selected.length === 0) {
                    Swal.fire('Peringatan', 'Pilih minimal satu inventaris', 'warning');
                    return;
                }

                var newWin = window.open();

                $.ajax({
                    url: "<?= base_url('dir/manage_inventaris/cetak_barcode') ?>",
                    type: 'POST',
                    data: { final: selected },
                    dataType: 'json',
                    success: function (data) {

                        var doc = '';
                        var open = true;
                        var kolom = 4;
                        var cur = 0;

                        doc += "<script src=\"<?php echo base_url(); ?>assets/JsBarcode.code39.min.js\"><\/script>";

                        doc += `
                            <style>
                                #barcodeView {
                                    width:170px !important;
                                    height:85px !important;
                                }

                                .inv-text {
                                    font-family: 'Courier New';
                                    font-size: 11px;
                                    margin-bottom: 0px;
                                }

                                .barcode-row {
                                    width:160px;
                                    margin:2px auto 0;
                                    display:flex;
                                    justify-content:space-between;
                                    font-family:'Courier New';
                                    font-size:11px;
                                }

                                .barcode-left {
                                    text-align:left;
                                }

                                .barcode-right {
                                    text-align:right;
                                }

                                .color-box {
                                    width:160px;
                                    height:10px;
                                    margin:2px auto 0;
                                }

                                @media print {
                                    * {
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                }
                            </style>
                        `;

                        doc += "<table border='1'>";

                        for (var i = 0; i < data.length; i++) {

                            if (open) {
                                doc += "<tr>";
                                open = false;
                            }

                            doc += "<td align='center'>";

                            // No Inv
                            doc += "<div class='inv-text'>No.Inv." + data[i].no_inv + "</div>";

                            // BARCODE TANPA TEXT
                            doc += "<img id=\"barcodeView\" class=\"barcode\" " +
                                    "jsbarcode-format=\"CODE39\" " +
                                    "jsbarcode-height=\"70\" " +
                                    "jsbarcode-fontSize=\"20\" " +
                                    "jsbarcode-textMargin=\"0\" " +
                                    "jsbarcode-displayValue=\"false\" " +
                                    "jsbarcode-value=\"" + data[i].no_barcode + "\" " +
                                    "jsbarcode-background=\"#FFFFFF\" " +
                                    "jsbarcode-lineColor=\"#000000\" />";

                            // TEXT BARCODE + NAMA RAK
                            doc += "<div class='barcode-row'>";
                            doc += "<div class='barcode-left'>" + data[i].no_barcode + "</div>";
                            doc += "<div class='barcode-right'>" +
                                    (data[i].nama_rak && data[i].nama_rak.trim() !== '' 
                                        ? data[i].nama_rak 
                                        : '-') +
                                   "</div>";
                            doc += "</div>";

                            // COLOR BOX (SVG)
                            doc += "<div class='color-box'>";
                            doc += "<svg width='160' height='10'>";
                            doc += "<rect width='160' height='10' fill='" + data[i].kode_warna + "'/>";
                            doc += "</svg>";
                            doc += "</div>";

                            doc += "</td>";

                            cur++;

                            if (cur == kolom) {
                                doc += "</tr>";
                                open = true;
                                cur = 0;
                            }
                        }

                        if (!open) {
                            doc += "</tr>";
                        }

                        doc += "</table>";
                        doc += "<script>JsBarcode('.barcode').init();<\/script>";

                        newWin.document.write(doc);
                        newWin.document.close();
                        newWin.focus();

                        setTimeout(function () {
                            newWin.print();
                        }, 300);
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal mengambil data barcode', 'error');
                    }
                });
            });


            // Cetak Callnumber
            $('#cetak_callnumber').on('click', function () {
                var selected = [];
                $('.row-checkbox:checked').each(function () {
                    selected.push($(this).val());
                });

                if (selected.length === 0) {
                    Swal.fire('Peringatan', 'Pilih minimal satu inventaris', 'warning');
                    return;
                }

                $.ajax({
                    url: "<?= base_url('dir/manage_inventaris/cetak_callnumber') ?>",
                    type: 'POST',
                    data: {final: selected},
                    dataType: 'json',
                    success: function (data) {
                        var win = window.open('', '_blank');
                        win.document.write(`
                            <html><head><title>Cetak Callnumber</title>
                            </head><body onload="window.print(); setTimeout(window.close, 1000);">
                        `);

                        data.forEach(function (item) {
                            var n_klas = item.no_klas.split(" ");
                            n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                            win.document.write(`
                                <div style="
                                    display: inline-block;
                                    width: 45mm; 
                                    height: 31mm; 
                                    margin: 2px; 
                                    padding-top: 5px; 
                                    padding-bottom: 5px; 
                                    border: 1px solid #000; 
                                    text-align: center; 
                                    font-family: verdana; 
                                    box-sizing: border-box; 
                                    float: left;
                                    -webkit-print-color-adjust: exact; 
                                    print-color-adjust: exact;
                                ">
                                    <div style="border-bottom: dashed 1px #000; height: 12mm; margin-bottom: 4px;">
                                        <div style="font-size: 9px; font-weight: bold; padding: 1px;">P E R P U S T A K A A N</div>
                                        <div style="font-size: 9px; font-weight: bold; padding: 1px;">POLITEKNIK KESEHATAN RIAU</div>
                                        <div style="font-size: 8px; font-weight: bold; padding: 1px;">PEKANBARU</div>
                                    </div>

                                    <div style="padding-top: 5px;background-color:${item.kode_warna || ''};">
                                        <span style="font-size: 11px; font-weight: bold; display: block; line-height: 1.2;">
                                            ${n_klas[0]}<br>
                                            ${n_klas[1]}<br>
                                            ${n_klas[2]}
                                        </span>
                                        <span style="font-size: 9px; font-weight: bold;">
                                            c.${item.no_inv || ''}
                                        </span>
                                    </div>
                                </div>
            
                            `);
                        });

                        win.document.write('</body></html>');
                        // Beri sedikit delay agar gambar/font terload sebelum print
                        setTimeout(function() {
                            win.print();
                        }, 200);
                        //win.document.close();
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal mengambil data callnumber', 'error');
                    }
                });
            });
        }
    };

    // Tab Dihapus
    var DatatableDihapus = {
        init: function () {
            var datatable2 = $("#datatable_dihapus").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?= base_url('dir/manage_inventaris/fetch') ?>",
                            method: 'POST',
                            params: {status_filter: 'D'}
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
                columns: [
                    {field: "number", title: "#", width: 40, sortable: false, textAlign: "center"},
                    {field: "no_inv", title: "No Inventaris", width: 100},
                    {field: "no_barcode", title: "Barcode", width: 80},
                    {field: "tgl_inv", title: "Tgl Inventaris", width: 90},
                    {field: "judul", title: "Judul Buku", width: 270},
                    {field: "isbn", title: "ISBN"},
                    {field: "no_klas", title: "NO Klas"},
                    {field: "penerbit", title: "Penerbit"},
                    {field: "penulis", title: "Penulis"},
                    {field: "thn_terbit", title: "Tahun Terbit", width: 60},
                    //{ field: "status_buku", title: "Status Buku" },
                    {field: "no_penghapusan", title: "No Penghapusan", width: 100},
                    {field: "tgl_penghapusan", title: "Tgl Hapus", width: 80},
                    {
                        field: "action",
                        title: "Aksi",
                        sortable: false,
                        width: 80,
                        template: function (t) {
                            return `
                                <a href="<?= base_url('dir/manage_inventaris/form/view_page/') ?>${t.id}" class="btn btn-sm btn-info btn-icon" title="View"><i class="la la-eye"></i></a>
                            `;
                        }
                    }
                ]
            });
            $('#generalSearch2').on('keyup', function () {
                datatable2.search($(this).val().toLowerCase(), 'generalSearch');
            });
        }
    };

    $(document).ready(function () {
        DatatableAktif.init();
        DatatableDihapus.init();
    });
    
    $('#export_excel').on('click', function(){

        var klas   = $('#m_form_klas').val() || '';
        var kampus = $('#m_form_kampus').val() || '';
        var gedung = $('#m_form_gedung').val() || '';
        var rak    = $('#m_form_rak').val() || '';
        var search = $('#generalSearch').val() || '';

        var datatable = $("#datatable_aktif").mDatatable();

        // Ambil sort dengan fallback agar tidak null
        var sort = datatable.getDataSourceParam('sort') || {};
        var sort_field = sort.field || '';
        var sort_sort  = sort.sort  || '';

        var url = "<?= base_url('dir/manage_inventaris/export_excel') ?>?" +
            "status_filter=A" +
            "&klas_filter=" + encodeURIComponent(klas) +
            "&kampus=" + encodeURIComponent(kampus) +
            "&gedung=" + encodeURIComponent(gedung) +
            "&rak=" + encodeURIComponent(rak) +
            "&search=" + encodeURIComponent(search) +
            "&sort_field=" + encodeURIComponent(sort_field) +
            "&sort_sort=" + encodeURIComponent(sort_sort);

        window.open(url, '_blank');
    });

</script>