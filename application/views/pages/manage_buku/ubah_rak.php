<style>
    .file-input-container {
        margin-bottom: 10px;
    }
    .file-input-container .btn-remove {
        margin-top: 1px;
    }
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                    <small><i class="glyphicon glyphicon-refresh"></i><button style="display:none" class="btn btn-default" id="m_datatable_reload"><i class="fa fa-refresh"></i> Reload</button> </small>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="<?php echo base_url(); ?>dir/manage_buku" class="m-nav__link"><span class="m-nav__link-text">Data Buku</span></a>
                    </li>
                    <li class="m-nav__separator">  - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"><span class="m-nav__link-text"> <?php echo $page_title; ?> </span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <?php if ($this->session->flashdata('alert')) { ?>
            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
                <div class="m-alert__icon">
                    <i class="flaticon-exclamation-1"></i>
                    <span></span>
                </div>
                <div class="m-alert__text">
                    <?php echo $this->session->flashdata('flash_message') ?>
                </div>
            </div>
        <?php } ?>
        <script type="text/javascript">
            var page_action = 'ubah_rak';
        </script>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?php echo $page_title; ?>
                            <small>
                                <i class="glyphicon glyphicon-refresh"></i>
                                <button style="display:none" class="btn btn-default" id="m_datatable_reload">Reload</button>
                            </small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-10 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-2">
                                    <select class="form-control m-bootstrap-select" id="m_form_klas">
                                        <option value="">Pilih Klasifikasi</option>
                                        <?php if ($klasifikasi) { ?>
                                            <?php foreach ($klasifikasi as $row) { ?>
                                                <option value="<?php echo $row->id; ?>"><?php echo $row->nama; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="d-md-none m--margin-bottom-10"></div>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control m-bootstrap-select" id="m_form_kel">
                                        <option value="">Pilih Kategori</option>
                                        <?php if ($kategori) { ?>
                                            <?php foreach ($kategori as $row) { ?>
                                                <option value="<?php echo $row->idkategori; ?>"><?php echo $row->nmkategori; ?> </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
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
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Search..." id="m_form_search">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span><i class="la la-search"></i> </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 order-1 order-xl-2 m--align-right">
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                            <a href="<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/tambah/" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span><i class="flaticon-add"></i><span>Tambah </span></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="m-demo-icon__preview">
                    <i class="la la-info-circle m--font-danger"></i>
                    <font class="m--font-danger"> Pilih/Centang Buku Untuk Cetak:</font>
                </div>
                <!--begin: Selected Rows Group Action Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label m-form__label-no-wrap">
                                    <label class="m--font-bold m--font-danger-">
                                        Selected <span id="m_datatable_selected_number"></span> records:
                                    </label>
                                </div>
                                <div class="m-form__control">
                                    <div class="btn-toolbar">
                                        <button id="cetak_katalog" type="button" class="btn btn-accent btn-sm">
                                            Cetak Katalog
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
                                            Cetak Barcode
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                            Cetak Call Number
                                        </button>
                                        <!-- &nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-sm btn-accent" type="button">
                                                        Set Buku Diarsipkan
                                                </button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->
                <form class="m-form m-form--fit m-form--label-align-right" id="form_ubah">
                    <div class="table_ubah_rak" id="table_ubah_rak">
                        <!-- table here -->
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="button" class="btn btn-primary" id="buttin_id">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    <!-- Here is Data Table Begin -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- end:: Body -->
<style>
    td[data-field=""] .select2-container {
    width: 100% !important;
    min-width: 220px !important;
}
td[data-field=""] .select2-selection--single {
    width: 100% !important;
}</style>

<script type="text/javascript">
    var save_method; //for save method string
    var table;
    var barcode = "<?php echo base_url(); ?>assets";
    var barcode2 = "<?php echo base_url(); ?>dir/";
    var base_site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>";
        var submit = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/submit/";
        var update = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/update/";
        var tambah_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/tambah_inv/";
        var edit_site_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/edit_inv/";
        var site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/list/";
        var simpan_ubah_rak = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/simpan_ubah_rak/";

        $("#button_tambah").click(function () {
            $('#tambah_inv').on('shown.bs.collapse', function (e) {
                var $card = $(this).closest('#tambah_inv');
                $('html,body').animate({
                    scrollTop: $card.offset().top - 25
                }, "slow");
            });
            $("#head").text(function () {
                return $("#tambah_inv").is(":visible") ? "Tambah Inventaris" : "Inventaris";
            })
        });

        function statusFile(file_id) {
            var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/setfilestatus/" + file_id;
            $.ajax({
                url: url,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    viewFile(isbn, no_klas);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error Post data from ajax');
                }
            });
        }

        function edit_inv_page(isbn, no_klas) {
            url = base_site + "/edit/" + isbn + "/" + no_klas;
            window.location.href = url;
        }

        function edit_inv(barcode) {
            $("#edit_inv").slideToggle(300, function () {
                if ($(this).is(":visible")) { //Check to see if element is visible then scroll to it
                    $('html,body').animate({//animate the scroll
                        scrollTop: $(this).offset().top - 25 // the - 25 is to stop the scroll 25px above the element
                    }, "slow"),
                            $("#head").text(function () {
                        return $("#edit_inv").is(":visible") ? "Edit Inventaris" : "Inventaris";
                    });
                }
            });

            $('#edit_inv')[0].reset();
            $.ajax({
                url: "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_data_inv/" + barcode,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="no_barcode2"]').val(data[0].no_barcode);
                    $('[name="no_inv2"]').val(data[0].no_inv);
                    $('[name="tgl_inv2"]').val(data[0].tgl_inv);
                    $('[name="asal2"]').val(data[0].asal);
                    $('[name="ket2"]').val(data[0].ket);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function view(id, no_klas) {
            var id1 = id.split('/').join('_');
            var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get/" + id1 + "/" + no_klas;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#ISBN').text(data[0].ISBN);
                    $('#judul').text(data[0].judul);
                    $('#jml_buku').text(data[0].jml_buku);
                    $('#penulis').text(data[0].penulis);
                    $('#tajuksubyek').text(data[0].tajuksubyek);
                    $('#no_klas').text(data[0].no_klas);
                    $('#edisi').text(data[0].edisi);
                    $('#cetakan').text(data[0].cetakan);
                    $('#penerbit').text(data[0].nama_penerbit);
                    $('#kota').text(data[0].kota);
                    $('#thn_terbit').text(data[0].thn_terbit);
                    if (data[0].bahasa == 'I') {
                        data[0].bahasa = "Bahasa Indonesia"
                    } else if (data[0].bahasa == 'A') {
                        data[0].bahasa = "Bahasa Inggris"
                    } else if (data[0].bahasa = "S") {
                        data[0].bahasa = "Bahasa Sunda"
                    } else {
                        data[0].bahasa = "Bahasa Lainnya"
                    }
                    $('#bahasa').text(data[0].bahasa);
                    $('#jml_hal').text(data[0].jml_hal);
                    $('#ukuran_fisik').text(data[0].ukuran_fisik);
                    $('#dipinjam').text(data[0].dipinjam);
                    $('#no_rak').text(data[0].no_rak);
                    $('#deskripsi').text(data[0].deskripsi);
                    $('#tanggal').text(data[0].tanggal);
                    if (data[0].review == 0) {
                        data[0].review = "-"
                    }
                    $('#review').text(data[0].review);
                    $('#matakuliah').text(data[0].matakuliah);
                    $('#m_Modal').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Lihat Data Buku'); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function delete_buku(id, no_klas) {
            if (confirm('Are you sure delete this data?')) {
                // ajax delete data to database
                var id1 = id.split('/').join('_');
                var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/hapus/" + id1 + "/" + no_klas;
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status = 'TRUE') {
                            swal({
                                title: 'Success',
                                text: 'Data Buku Berhasil Dihapus',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reload_table();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal({
                            title: 'Warning',
                            text: 'Data Buku Tidak Berhasil Dihapus',
                            type: 'warning',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        }

        function delete_inv(barcode, no_klas, isbn) {
            if (confirm('Are you sure delete this data?')) {
                // ajax delete data to database
                isbn = isbn.split('/').join('_');
                var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/hapus_inv/" + isbn + "/" + no_klas + "/" + barcode;
                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status = 'TRUE') {
                            swal({
                                title: 'Success',
                                text: 'Data Inventaris Berhasil Dihapus',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reload_table();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal({
                            title: 'Warning',
                            text: 'Data Inventaris Tidak Berhasil Dihapus',
                            type: 'warning',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        }

        function delete_file(isbn, no_klas, file_name) {
            if (confirm('Are you sure you want to delete this item ?')) {
                // ajax delete data to database
                var id = isbn.split('/').join('_');
                var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/delete_file/" + id + "/" + no_klas + "/" + file_name;

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status = 'TRUE') {
                            swal({
                                title: 'Success',
                                text: 'File Buku Berhasil Dihapus',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            location.reload();
                            // reload_table();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal({
                            title: 'Warning',
                            text: 'Data Buku Tidak Berhasil Dihapus',
                            type: 'warning',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        }

        function reload_table() {
            $("#m_datatable_reload").trigger("click");
        }

        function get_barcode() {
            var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_barcode/";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $("input[name='no_barcode']").val(data.barcode);
                    // document.getElementsByName('no_barcode').value=data.barcode;
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            })
        }
</script>

<script type="text/javascript">
    const DatatableUbahRak = {
        init() {
            const config = {
               data: {
                    type: "remote",
                    source: {
                        read: {
                            url: '<?= base_url('dir/manage_buku/list_ubah_rak') ?>'
                        }
                    },
                    saveState: { cookie: false, webstorage: false },
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                },
                layout: { theme: "default", class: "", scroll: false, footer: false },
                sortable: true,
                filterable: false,
                pagination: true,
                searchDelay: 5500,
                columns: [
                    {
                        field: "id",
                        title: "#",
                        sortable: false,
                        width: 3,
                        selector: { class: "m-checkbox--solid m-checkbox--brand" }
                    },
                    {
                        field: "number",
                        title: "No",
                        sortable: false,
                        width: 40,
                        textAlign: "center"
                    },
                    {
                        field: "no_inv",
                        title: "No Inv",
                        filterable: false,
                        width: 90,
                        template: function(row) {
                            // Tambahkan hidden input untuk isbn & no_klas di sini
                            return `<input type="hidden" name="isbn_hidden" value="${row.isbn}">
                                    <input type="hidden" name="no_klas_hidden" value="${row.no_klas}">
                                    <input type="hidden" name="no_inv_hidden" value="${row.no_inv}">
                                    <span style="width: 100px;">${row.no_inv}</span>`;
                        }
                    },
                    {
                        field: "no_barcode",
                        title: "No Barcode",
                        width: 80
                    },
                    {
                        field: "judul",
                        title: "Judul Buku",
                        width: 300
                    },
                    {
                        field: "penulis",
                        title: "Penulis",
                        width: 170,
                        sortable: false
                    },
                    {
                        field: "nama_kampus",
                        title: "Kampus",
                        textAlign: "center",
                        width: 150
                    },
                    {
                        field: "no_rak",
                        title: "Rak Sekarang",
                        textAlign: "center",
                        width: 100
                    },
                    {
                        field: "",
                        title: "Rak Baru",
                        width: 280,
                        sortable: false,
                        template: function(row) {
                            const id = row.isbn ? row.isbn.split('/').join('_') : 'no-isbn-' + row.no_inv;
                            return `<div class="dropdown ${row.getDatatable().getPageSize() - row.getIndex() <= 4 ? 'dropup' : ''}">
                                        <select class="m-select2 form-control lokasi-rak" data-placeholder="Pilih Rak" name="lokasirak_id[${row.no_inv}]">
                                        </select>
                                    </div>`;
                        }
                    },
                    {
                        field: "action",
                        title: "Actions",
                        sortable: false,
                        width: 100,
                        overflow: "visible",
                        template: function(row) {
                            const id = row.isbn ? row.isbn.split('/').join('_') : 'no-isbn-' + row.no_inv;
                            return `<div class="dropdown ${row.getDatatable().getPageSize() - row.getIndex() <= 4 ? 'dropup' : ''}">
                                        <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">
                                            <i class="la la-gear"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="edit_inv_page('${id}', '${row.no_klas}')">
                                                <i class="la la-edit"></i> Edit Data
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="delete_buku('${id}', '${row.no_klas}')">
                                                <i class="la la-trash"></i> Hapus Data
                                            </a>
                                        </div>
                                    </div>
                                    <a href="${base_site}/get/${id}/${row.no_klas}" target="_blank" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
                                        <i class="la la-search"></i>
                                    </a>`;
                        }
                    }
                ],
                toolbar: {
                    layout: ['pagination', 'info'],
                    placement: ['bottom'],
                    items: {
                        pagination: {
                            type: 'default',
                            pages: {
                                desktop: { layout: 'default', pagesNumber: 6 },
                                tablet: { layout: 'default', pagesNumber: 3 },
                                mobile: { layout: 'compact' }
                            },
                            navigation: { prev: true, next: true, first: true, last: true },
                            pageSizeSelect: [10, 20, 30, 50, 100]
                        },
                        info: true
                    }
                },
                translate: {
                    records: { processing: 'Please wait...', noRecords: 'No records found' },
                    toolbar: {
                        pagination: {
                            items: {
                                default: {
                                    first: 'First', prev: 'Previous', next: 'Next', last: 'Last',
                                    more: 'More pages', input: 'Page number', select: 'Select page size'
                                },
                                info: 'Displaying {{start}} - {{end}} from {{total}} records'
                            }
                        }
                    }
                }
            };

            const datatable = $("#table_ubah_rak").mDatatable(config);
            const query = datatable.getDataSourceQuery();
            
            datatable.on('m-datatable--on-layout-updated', function() {
                // 1. Init Select2 di kolom Rak Baru (tetap sama)
                $('.lokasi-rak').select2({
                    placeholder: "Pilih Rak",
                    allowClear: true,
                    ajax: {
                        url: '<?= base_url('dir/manage_buku/get_lokasi_rak_options') ?>',
                        dataType: 'json',
                        processResults: function(data) {
                            let groups = [];
                            for (let group in data) {
                                let children = data[group].map(opt => ({
                                    id: opt.value,
                                    text: opt.text
                                }));
                                groups.push({
                                    text: group,
                                    children: children
                                });
                            }
                            return { results: groups };
                        }
                    }
                });

                // 2. Row Grouping: Kumpulkan dulu semua row per grup, insert header hanya sekali
                const $tableBody = $('#table_ubah_rak tbody');
                const groupRows = {};

                $tableBody.find('tr').each(function() {
                    const $row = $(this);

                    // Ambil data dari cell (sesuaikan index berdasarkan inspect Anda)
                    const judul = $row.find('td[data-field="judul"] span').text().trim(); // kolom Judul
                    const isbn = $row.find('input[name="isbn_hidden"]').val() || '';
                    const no_klas = $row.find('input[name="no_klas_hidden"]').val() || '';

                    if (!judul || !isbn) return; // skip row kosong

                    const groupKey = isbn + '|' + no_klas; // kunci unik: ISBN + no_klas

                    if (!groupRows[groupKey]) {
                        groupRows[groupKey] = {
                            title: `${judul} | ISBN : ${isbn} | No Klas : ${no_klas}`,
                            firstRow: $row
                        };
                    }
                });

                // 3. Render header grup hanya sekali di depan row pertama grup
                for (let key in groupRows) {
                    const group = groupRows[key];
                    const $firstRow = group.firstRow;

                    const $groupRow = $(`
                        <tr class="group-header" style="background:#f5f5f5; font-weight:bold; cursor:pointer;">
                            <td colspan="8" style="padding:10px;">
                                ${group.title}
                            </td>
                        </tr>
                    `);

                    $groupRow.insertBefore($firstRow);
                }

                // Optional: Toggle klik header grup untuk hide/show detail rows
                $('.group-header').on('click', function() {
                    const $this = $(this);
                    const $nextRows = $this.nextUntil('.group-header');
                    $nextRows.toggle();
                    $this.toggleClass('collapsed');
                });
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
            
            $("#m_form_search").on("keyup", function() {
                const q = datatable.getDataSourceQuery();
                q.generalSearch = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.generalSearch);

            $("#m_form_kel").on("change", function() {
                const q = datatable.getDataSourceQuery();
                q.kel = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.kel || "");

            $("#m_form_klas").on("change", function() {
                const q = datatable.getDataSourceQuery();
                q.klas = $(this).val().toLowerCase();
                datatable.setDataSourceQuery(q);
                datatable.load();
            }).val(query.klas || "");

            $("#m_form_klas, #m_form_kel,#m_form_kampus, #m_form_gedung, #m_form_rak").selectpicker();

            $('#m_datatable_reload').on('click', () => datatable.reload());

            $(".table_ubah_rak").on("m-datatable--on-check", (a, e) => {
                const selected = datatable.setSelectedRecords().getSelectedRecords().length;
                $("#m_datatable_selected_number").html(selected);
                if (selected > 0) $("#m_datatable_group_action_form").collapse("show");
            }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", (a, e) => {
                const selected = datatable.setSelectedRecords().getSelectedRecords().length;
                $("#m_datatable_selected_number").html(selected);
                if (selected === 0) $("#m_datatable_group_action_form").collapse("hide");
            });

            //button simpan ubah rak
            $('#buttin_id').on('click', function() {
                const data = []; // array untuk kirim: [[no_inv, lokasirak_id], ...]

                // Loop semua baris di datatable
                $('.m-datatable__row').each(function() {
                    const $row = $(this);

                    // Ambil no_inv dari cell No. Inv (td[data-field="no_inv"])
                    const no_inv = $row.find('input[name="no_inv_hidden"]').val() || '';

                    // Ambil lokasirak_id dari select2 di kolom Rak Baru
                    const lokasirak_id = $row.find('select[name^="lokasirak_id"]').val() || '';
                    console.log('Row no_inv:', no_inv, 'lokasirak_id:', lokasirak_id);
                    
                    // Hanya push jika ada perubahan (lokasirak_id tidak kosong)
                    if (no_inv && lokasirak_id) {
                        data.push([no_inv, lokasirak_id]);
                    }
                });

                // Jika tidak ada data yang diubah
                if (data.length === 0) {
                    swal({
                        title: 'Perhatian',
                        text: 'Tidak ada perubahan rak yang dipilih.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $.ajax({
                    url: simpan_ubah_rak,
                    type: "POST",
                    data: { data: data },
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == true) {
                            swal({
                                title: 'Success',
                                text: 'Rak Buku Berhasil Diedit',
                                type: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            datatable.reload(); // reload table agar tampilkan rak baru
                        } else {
                            swal({
                                title: 'Gagal',
                                text: response.message || 'Gagal menyimpan perubahan rak.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        console.error('Simpan rak gagal:', textStatus, errorThrown);
                    }
                });
            });

            // Cetak Katalog (tetap sama, tapi dengan arrow function)
            $('#cetak_katalog').on('click', function() {
                var final = {}; // Gunakan kurung kurawal (Object)

                $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(index) {
                    const values = $(this).val();
                    final[index] = values; // Mengisi properti object dengan indeks 0, 1, 2...
                });
                
                const url = base_site + "/cetak_katalog_inventaris/";
                const newWin = window.open();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: { final: final },
                    dataType: "JSON",
                    success: function(data) {
                        let doc = '';
                        // $.each(data, function(key, val) -> val adalah objek buku langsung
                        $.each(data, function(key, val) {
                            // Gunakan val.no_klas, bukan data[i][0].no_klas
                            const n_klas = val.no_klas ? val.no_klas.split(" ") : ['', '', ''];

                            const penulis_raw = val.penulis ? val.penulis : "";
                            const penulis = {
                                marga: penulis_raw.split(" "),
                                depan: penulis_raw.split(" ")
                            };

                            if (penulis.depan.length > 1) {
                                penulis.depan.splice(penulis.depan.length - 1, 1);
                                penulis.depan = penulis.depan.join(" ");
                            } else {
                                penulis.depan = "";
                            }

                            // Pastikan n_klas memiliki minimal 3 elemen untuk menghindari undefined di tampilan
                            const k0 = n_klas[0] || '';
                            const k1 = n_klas[1] || '';
                            const k2 = n_klas[2] || '';

                            doc += `<div style="x-index:9999; border:1px #AAAAAA solid; padding-left:5px; padding-top:5px; padding-bottom:5px; width:125mm; min-height:75mm; margin:2px;">
                                        <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="4" style="height:75mm; font-family:'Times New Roman'; font-size:12pt; font-weight:normal;">
                                            <tbody>
                                                <tr valign="top">
                                                    <td valign="top" width="60">${k0}<br>${k1}<br>${k2}</td>
                                                    <td>
                                                        <br>${penulis.marga[penulis.marga.length - 1].toUpperCase()}, ${penulis.depan}
                                                        <br><div style="float:left; width:90%;">
                                                            ${val.judul} / ${val.penulis}.-- ${val.kota} : ${val.nama_penerbit}, ${val.thn_terbit}.<br><br>
                                                        </div>
                                                        <div style="clear:left"></div>
                                                        <div style="float:left; width:87%;">
                                                            ${val.jml_hal} hal.; ${val.ukuran_fisik} cm.<br><br>
                                                            ISBN : ${val.ISBN}<br><br>
                                                            <div style="font-size:89%; width:100mm">
                                                                <div style="width:110%; float:left;">
                                                                    <div style="float:left; width:7%">1.</div>
                                                                    <div style="float:left; width:80%">${(val.tajuksubyek || '').toUpperCase()}</div>
                                                                    <div style="clear:both"></div>
                                                                    <div style="float:left; width:7%">I.</div>
                                                                    <div style="float:left; width:80%">Judul</div>
                                                                    <div style="clear:both"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><br>`;
                        });

                        newWin.document.write("<br><br>" + doc);
                        newWin.document.close();
                        newWin.focus();
                        newWin.print();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Cetak katalog gagal:', textStatus, errorThrown);
                    }
                });
            });

            // Cetak Callnumber (sama seperti aslinya, hanya di-refactor sedikit)
            $('#cetak_callnumber').on('click', function() {
                var final = [];
                 $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(index) {
                    final.push($(this).val());
                });

                if (final.length === 0) {
                    Swal.fire('Peringatan', 'Pilih minimal satu inventaris', 'warning');
                    return;
                }

                const url = base_site + "/cetak_callnumber_inventaris/";
                
                $.ajax({
                    url: url,
                    type: "POST",
                    data: { final: final },
                    dataType: "JSON",
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

            // Cetak Barcode (sama seperti aslinya, hanya di-refactor sedikit)
            $('#cetak_barcode').on('click', function() {
                var final = {};

                $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(index) {
                    const values = $(this).val();
                    final[index] = values;
                });

                const url = base_site + "/cetak_barcode_inventaris/";
                const newWin = window.open();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: { final: final },
                    dataType: "JSON",
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

                        setTimeout(function() {
                            newWin.print();
                        }, 200);
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal mengambil data barcode', 'error');
                    }
                });
            });
        }
    };

    // Inisialisasi saat halaman siap
    jQuery(document).ready(function() {
        DatatableUbahRak.init();
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>

