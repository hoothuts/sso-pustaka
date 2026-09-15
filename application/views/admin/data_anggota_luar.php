<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator"><?php echo $page_title; ?></h3>
                <small>
                    <i class="glyphicon glyphicon-refresh"></i>
                    <button style="display:none" class="btn btn-default" id="m_datatable_reload">
                        <i class="fa fa-refresh"></i> Reload</button>
                </small>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">Data Induk</span>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text"><?php echo $page_title; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <?php echo $this->session->flashdata('flash_message') ?>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text"><?php echo $page_title; ?></h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="form-group m-form__group row align-items-center">
                                <!-- <div class="col-md-4">
                                        <div class="m-form__group m-form__group--inline">
                                                <div class="m-form__label">
                                                        <label class="m-label m-label--single">
                                                                Nama:
                                                        </label>
                                                </div>
                                                <div class="m-form__control">
                                                        <select class="form-control m-bootstrap-select" id="m_form_nama">
                                                                <option value="jurnal">
                                                                        Jurnal
                                                                </option>
                                                                <option value="kaset">
                                                                        Kaset
                                                                </option>
                                                        </select>
                                                </div>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="Search..." id="m_form_search">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="tambah_buku()">
                                <span>
                                    <i class="flaticon-add"></i>
                                    <span>
                                        Tambah
                                    </span>
                                </span>
                            </a>
                            <div class="m-separator m-separator--dashed d-xl-none"></div>
                        </div>
                    </div>
                </div>
                <!--begin: Selected Rows Group Action Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label m-form__label-no-wrap">
                                    <label class="m--font-bold m--font-danger-">
                                        Selected
                                        <span id="m_datatable_selected_number"></span>
                                        records:
                                    </label>
                                </div>
                                <div class="m-form__control">
                                    <div class="btn-toolbar">
                                        <button id="cetak_kartu" class="btn btn-sm btn-accent" type="button">
                                            Cetak Kartu
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="download_kartu" class="btn btn-sm btn-primary" type="button">
                                            <i class="la la-download"></i> Download Kartu
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->
                <div class="m_datatable" id="ajax_data">
                    <!-- Here is Data Table Begin -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>

<script type="text/javascript">
    // =============================================
    // KONFIGURASI GLOBAL
    // =============================================
    const BASE_URL  = "<?php echo base_url(); ?>";
    const PAGE_PATH = "<?php echo $page_access; ?>/<?php echo $page_name; ?>";
    const ASSET_URL = "<?php echo base_url('uploads/kartu_anggota/'); ?>";

    let save_method;
    let table;

    // =============================================
    // KONFIGURASI DATATABLE
    // =============================================
    const datatableConfig = {
        data: {
            type: "remote",
            source: {
                read: { url: `${BASE_URL}${PAGE_PATH}/fetch/` }
            },
            pageSize:        20,
            saveState:       { cookie: true, webstorage: true },
            serverPaging:    true,
            serverFiltering: true,
            serverSorting:   true
        },
        layout: { theme: "default", class: "", scroll: false, footer: false },
        sortable:    true,
        filterable:  false,
        pagination:  true,
        searchDelay: 400,
        columns: [
            {
                field:    "noid1",
                title:    "#",
                sortable: false,
                width:    3,
                selector: { class: "m-checkbox--solid m-checkbox--brand" }
            },
            { field: "number",              title: "No.",         sortable: false, width: 40, selector: false, textAlign: "center" },
            { field: "noid",                title: "Nomor ID",    filterable: false, width: 150 },
            { field: "nama",                title: "Nama" },
            { field: "telepon",             title: "NO HP", width: 110 },
            { field: "alamat",              title: "Alamat", width: 250 },
            { field: "instansi_asal_nama",  title: "Instansi/PT", width: 190 },
            { field: "tgl_post",            title: "Tgl Daftar" , width: 100},
            {
                field:    "action",
                title:    "Actions",
                sortable: false,
                width:    70,
                overflow: "visible",
                template: (row) => `
                    <div class="dropdown ${row.getDatatable().getPageSize() - row.getIndex() <= 4 ? 'dropup' : ''}">
                        <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">
                            <i class="la la-gear"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#" onclick="edit_buku('${row.noid}')">
                                <i class="la la-edit"></i> Edit Data
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="delete_buku('${row.noid}')">
                                <i class="la la-trash"></i> Hapus Data
                            </a>
                        </div>
                    </div>
                `
            }
        ],
        toolbar: {
            layout:    ["pagination", "info"],
            placement: ["bottom"],
            items: {
                pagination: {
                    type: "default",
                    pages: {
                        desktop: { layout: "default", pagesNumber: 6 },
                        tablet:  { layout: "default", pagesNumber: 3 },
                        mobile:  { layout: "compact" }
                    },
                    navigation: { prev: true, next: true, first: true, last: true },
                    pageSizeSelect: [10, 20, 30, 50, 100]
                },
                info: true
            }
        },
        translate: {
            records: {
                processing: "Please wait...",
                noRecords:  "No records found"
            },
            toolbar: {
                pagination: {
                    items: {
                        default: {
                            first:  "First",
                            prev:   "Previous",
                            next:   "Next",
                            last:   "Last",
                            more:   "More pages",
                            input:  "Page number",
                            select: "Select page size"
                        },
                        info: "Displaying {{start}} - {{end}} from {{total}} records"
                    }
                }
            }
        }
    };

    // =============================================
    // HELPER: AMBIL NOID YANG DIPILIH
    // =============================================
    function getSelectedNOID() {
        const selected = [];
        $(".m-datatable__body .m-checkbox--single input:checkbox:checked").each(function () {
            selected.push($(this).val());
        });
        return selected;
    }

    // =============================================
    // HELPER: BUKA JENDELA CETAK
    // =============================================
    function openPrintWindow(htmlContent) {
        const newWin = window.open();
        newWin.document.write(htmlContent);
        newWin.document.close();
        newWin.focus();
    }

    // =============================================
    // CETAK KARTU ANGGOTA LUAR
    // =============================================
    function cetakKartu(selectedNOID) {
        $.ajax({
            url:      `${BASE_URL}${PAGE_PATH}/cetak_kartu/`,
            type:     "POST",
            data:     { final: selectedNOID },
            dataType: "JSON",
            success: function(data) {
                const imgDepan    = `${ASSET_URL}${data.kartu_depan}`;
                const imgBelakang = `${ASSET_URL}${data.kartu_belakang}`;
                const anggotaList = Object.values(data).filter(item => typeof item === 'object');

                renderKartu(anggotaList, imgDepan, imgBelakang);
            },
            error: (xhr, status, err) => console.error("Gagal cetak kartu:", err)
        });
    }

    function renderKartu(data, imgCover, imgBack) {
        const style = `
            <style>
                body { margin: 0 }
                table, td, body, div { font-family: arial; font-size: 9px }
                td { padding-top: 2px }
                #depan    { background-image: url('${imgCover}'); background-size: cover; background-position: center top }
                #belakang { background-image: url('${imgBack}');  background-size: cover; background-position: center top }
                #barcodeView { width: 130px; height: 33px !important }
                @media print { * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important } }
            </style>
        `;

        const scriptTag = `<script type="text/javascript" src="${BASE_URL}assets/JsBarcode.code39.min.js"><\/script>`;

        const kartuItems = data.map((anggota) => `
            <div style="margin:1px; float:left;">

                <!-- DEPAN -->
                <div id="depan" style="border:1px solid #000; border-right:none; float:left; width:85mm; height:55mm;">
                    <div style="clear:both"></div>
                    <div style="float:left; margin-left:7px; margin-top:73px; height:25mm; width:20mm; border:1px solid #000;">
                        ${anggota.dtpasfoto ? `<img src="${anggota.pasfoto}" style="height:25mm; width:20mm;">` : ''}
                    </div>
                    <div style="float:left; padding:2px 1px 0 2px; margin-top:75px; width:45mm;">
                        <div style="padding-left:8px;">
                            <table cellpadding="1" cellspacing="1" border="0">
                                <tr valign="top"><td>Nama</td><td>:</td><td>${anggota.nm_angg}</td></tr>
                                <tr valign="top"><td>No ID</td><td>:</td><td>${anggota.no_angg}</td></tr>
                                <tr valign="top"><td>Asal</td><td>:</td><td>${anggota.prodi}</td></tr>
                                <tr valign="top"><td nowrap>Berlaku Sd</td><td>:</td><td>Selama Aktif</td></tr>
                            </table>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                    <img id="barcodeView"
                         style="margin-left:190px; margin-top:-5px;"
                         class="barcode"
                         jsbarcode-format="CODE39"
                         jsbarcode-height="33"
                         jsbarcode-width="1"
                         jsbarcode-fontSize="10"
                         jsbarcode-textMargin="0"
                         jsbarcode-value="${anggota.no_angg}"/>
                </div>

                <!-- BELAKANG -->
                <div id="belakang" style="float:left; border:1px solid #000; border-left:1px dotted #000; width:85mm; height:55mm;">
                    <div style="clear:left"></div>
                </div>

            </div>
        `).join("");

        openPrintWindow(`${style}${scriptTag}${kartuItems}<script>JsBarcode(".barcode").init();<\/script>`);
    }

    // =============================================
    // DOWNLOAD KARTU ANGGOTA LUAR (PDF)
    // =============================================
    function downloadKartuPDF(selectedNOID) {
        $.ajax({
            url:      `${BASE_URL}${PAGE_PATH}/cetak_kartu/`,
            type:     "POST",
            data:     { final: selectedNOID },
            dataType: "JSON",
            success: function(data) {
                const imgDepan    = `${ASSET_URL}${data.kartu_depan}`;
                const imgBelakang = `${ASSET_URL}${data.kartu_belakang}`;
                const anggotaList = Object.values(data).filter(item => typeof item === 'object');

                renderKartuPDF(anggotaList, imgDepan, imgBelakang);
            },
            error: (xhr, status, err) => console.error("Gagal download kartu:", err)
        });
    }

    function renderKartuPDF(data, imgCover, imgBack) {
        const CARD_W     = 1040;
        const PX_PER_MM  = CARD_W / 85;

        const FOTO_ML  = Math.round(7  * PX_PER_MM);
        const FOTO_MT  = Math.round(19 * PX_PER_MM);
        const FOTO_W   = Math.round(20 * PX_PER_MM);
        const FOTO_H   = Math.round(25 * PX_PER_MM);
        const INFO_W   = Math.round(45 * PX_PER_MM);
        const INFO_MT  = Math.round(20 * PX_PER_MM);
        const FONT_SZ  = Math.round(9  * PX_PER_MM / 3.78);
        const BAR_LEFT = Math.round(50 * PX_PER_MM);
        const BAR_H    = Math.round(4  * PX_PER_MM);

        const container = document.createElement('div');
        container.style.cssText = 'position:fixed; left:-9999px; top:0; background:white; padding:10px;';

        container.innerHTML = `
            <style>
                .kartu-wrapper  { clear:both; margin-bottom:8px; overflow:hidden; }
                .kartu-depan {
                    background-image: url('${imgCover}');
                    background-size: 100% 100%;
                    border: 1px solid #000;
                    border-right: none;
                    float: left;
                    width: ${CARD_W}px;
                    height: 650px;
                    position: relative;
                    overflow: hidden;
                }
                .kartu-belakang {
                    background-image: url('${imgBack}');
                    background-size: 100% 100%;
                    float: left;
                    border: 1px solid #000;
                    border-left: 1px dotted #000;
                    width: ${CARD_W}px;
                    height: 650px;
                }
                .kartu-foto {
                    position: absolute;
                    left: ${FOTO_ML}px;
                    top: ${FOTO_MT}px;
                    height: ${FOTO_H}px;
                    width: ${FOTO_W}px;
                    border: 1px solid #000;
                    overflow: hidden;
                }
                .kartu-foto img { height:100%; width:100%; display:block; }
                .kartu-info {
                    position: absolute;
                    left: ${FOTO_ML + FOTO_W + Math.round(2 * PX_PER_MM)}px;
                    top: ${INFO_MT}px;
                    width: ${INFO_W}px;
                }
                .kartu-table {
                    font-family: arial;
                    font-size: ${FONT_SZ}px;
                    border-collapse: collapse;
                    line-height: 1.4;
                }
                .kartu-table td { padding:2px 3px; vertical-align:top; }
                .barcode-img {
                    position: absolute;
                    bottom: ${Math.round(3 * PX_PER_MM)}px;
                    left: ${BAR_LEFT}px;
                }
                * { -webkit-print-color-adjust:exact !important; color-adjust:exact !important }
            </style>
            ${data.map((anggota) => `
                <div class="kartu-wrapper">
                    <div class="kartu-depan">
                        <div class="kartu-foto">
                            ${anggota.dtpasfoto ? `<img src="${anggota.pasfoto}" crossorigin="anonymous">` : ''}
                        </div>
                        <div class="kartu-info">
                            <table class="kartu-table" cellpadding="0" cellspacing="0">
                                <tr><td>Nama</td><td>:</td><td>${anggota.nm_angg}</td></tr>
                                <tr><td>No ID</td><td>:</td><td>${anggota.no_angg}</td></tr>
                                <tr><td>Asal</td><td>:</td><td>${anggota.prodi}</td></tr>
                                <tr><td nowrap>Berlaku Sd</td><td>:</td><td>Selama Aktif</td></tr>
                            </table>
                        </div>
                        <img class="barcode-img" data-value="${anggota.no_angg}">
                    </div>
                    <div class="kartu-belakang"></div>
                </div>
            `).join("")}
        `;

        document.body.appendChild(container);

        const barcodePromises = Array.from(container.querySelectorAll('.barcode-img')).map(img => {
            return new Promise((resolve) => {
                const canvas = document.createElement('canvas');
                JsBarcode(canvas, img.dataset.value, {
                    format:       "CODE39",
                    height:       BAR_H,
                    width:        1.4,
                    fontSize:     Math.round(FONT_SZ * 0.8),
                    textMargin:   0,
                    displayValue: true,
                    margin:       0
                });
                img.src    = canvas.toDataURL('image/jpeg', 0.95);
                img.width  = canvas.width;
                img.height = canvas.height;
                img.onload  = resolve;
                img.onerror = resolve;
            });
        });

        Promise.all(barcodePromises).then(() => {
            const { jsPDF } = window.jspdf;
            const pdf       = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

            const kartuWrappers = container.querySelectorAll('.kartu-wrapper');
            let processed = 0;

            kartuWrappers.forEach(function(wrapper, index) {
                html2canvas(wrapper, {
                    scale:           1,
                    useCORS:         true,
                    allowTaint:      true,
                    backgroundColor: '#ffffff',
                    imageTimeout:    0,
                    logging:         false
                }).then(function(canvas) {
                    const imgData   = canvas.toDataURL('image/jpeg', 0.92);
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const imgWidth  = pageWidth - 20;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;

                    if (index > 0) pdf.addPage();
                    pdf.addImage(imgData, 'JPEG', 10, 10, imgWidth, imgHeight);

                    processed++;
                    if (processed === kartuWrappers.length) {
                        pdf.save('kartu_anggota_luar.pdf');
                        document.body.removeChild(container);
                    }
                });
            });
        });
    }

    // =============================================
    // INISIALISASI DATATABLE & EVENT LISTENER
    // =============================================
    const DatatableRemoteAjaxDemo = (function () {

        function init() {
            const dt    = $(".m_datatable").mDatatable(datatableConfig);
            const query = dt.getDataSourceQuery();

            // Search
            $("#m_form_search")
                .on("keyup", function () {
                    const q = dt.getDataSourceQuery();
                    q.generalSearch = $(this).val().toLowerCase();
                    dt.setDataSourceQuery(q);
                    dt.load();
                })
                .val(query.generalSearch);

            // Select picker
            $("#m_form_status, #m_form_nama").selectpicker();

            // Reload
            $("#m_datatable_reload").on("click", () => dt.reload());

            // Checkbox group action
            $(".m_datatable")
                .on("m-datatable--on-check", function () {
                    const count = dt.setSelectedRecords().getSelectedRecords().length;
                    $("#m_datatable_selected_number").html(count);
                    if (count > 0) $("#m_datatable_group_action_form").collapse("show");
                })
                .on("m-datatable--on-uncheck m-datatable--on-layout-updated", function () {
                    const count = dt.setSelectedRecords().getSelectedRecords().length;
                    $("#m_datatable_selected_number").html(count);
                    if (count === 0) $("#m_datatable_group_action_form").collapse("hide");
                });

            // Tombol Cetak Kartu
            $("#cetak_kartu").on("click", function () {
                const selected = getSelectedNOID();
                if (selected.length) cetakKartu(selected);
            });

            // Tombol Download Kartu PDF
            $("#download_kartu").on("click", function () {
                const selected = getSelectedNOID();
                if (selected.length) downloadKartuPDF(selected);
            });
        }

        return { init };
    })();

    // =============================================
    // DOCUMENT READY
    // =============================================
    jQuery(document).ready(function () {
        DatatableRemoteAjaxDemo.init();
    });

    function tambah_buku() {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $("#jk").selectpicker();
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Anggota Luar'); // Set Title to Bootstrap modal title
        $("#download_pasfoto").hide();

    }

    function edit_buku(id) {
        var downloadButton = document.getElementById("download_pasfoto");
        // Tambahkan atribut href
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        //Ajax Load data from ajax
        $("#download_pasfoto").show();
        $.ajax({
            url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/edit/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="id1"]').val(data[0].noid);
                $('[name="noid"]').val(data[0].noid);
                $('[name="nama"]').val(data[0].nama);
                $('[name="jk"]').val(data[0].jk);
                $("#jk").selectpicker();
                $('[name="alamat"]').val(data[0].alamat);
                $('[name="telepon"]').val(data[0].telepon);
                $('[name="tempatlahir"]').val(data[0].tempatlahir);
                $('[name="tgllahir"]').val(data[0].tgllahir);
                $('[name="instansi_asal_nama"]').val(data[0].instansi_asal_nama);
                $('[name="instansi_asal_alamat"]').val(data[0].instansi_asal_alamat);
                $('[name="jabatan_semester"]').val(data[0].jabatan_semester);
                if (data[0].pasfoto != '' && data[0].pasfoto != null) {
                    downloadButton.href = "<?php echo base_url(); ?>/uploads/pasfoto_anggotaluar/" + data[0].pasfoto;
                } else {
                    $("#download_pasfoto").hide();
                }

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Anggota Luar'); // Set title to Bootstrap modal title
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table() {
        $("#m_datatable_reload").trigger("click");
    }

    function save() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable
        var url;
        if (save_method == 'add') {
            url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit/";
        } else {
            url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update/";
        }
        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($('#form')[0]),
            processData: false,
            contentType: false,
            cache: false,
            async: true,
            dataType: "JSON",

            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    if (data.status = 'TRUE') {
                        document.getElementById('alertbox').style.display = 'block';
                        $('.alert').addClass('show ' + data.alert);
                        $('.alert').children('.m-alert__text').html(data.msg);
                        setTimeout(function () {
                            document.getElementById('alertbox').style.display = 'none';
                        }, 5000);
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) {
                        $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_buku(id) {
        if (confirm('Are you sure delete this data?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hapus/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    if (data.status = 'TRUE') {
                        document.getElementById('alertbox').style.display = 'block';
                        $('.alert').addClass('show ' + data.alert);
                        $('.alert').children('.m-alert__text').html(data.msg);
                        setTimeout(function () {
                            document.getElementById('alertbox').style.display = 'none';
                        }, 5000);
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    document.getElementById('alertbox').style.display = 'block';
                    if ($('.alert').hasClass('show')) {
                        $('.alert').addClass('show data-danger');
                    } else {
                        $('.alert').addClass('data-danger');
                    }
                    setTimeout(function () {
                        document.getElementById('alertbox').style.display = 'none';
                    }, 5000);
                    $('.alert').children('.m-alert__text').html('Gagal Menghapus Anggota Luar');
                    //alert('Error deleting data'+errorThrown);
                }
            });
        }
    }

    function reload_table() {
        $("#m_datatable_reload").trigger("click");
        //table.ajax.reload(); //reload datatable ajax
    }
</script>

<!--begin::Modal-->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="labelModalTambah" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelModalTambah">
                    <i class="m-menu__link-icon flaticon-add"></i> Tambah Anggota Luar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                    <!--<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit/-->
                <form id="form" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" enctype="multipart/form-data">
                    <div class="form-group m-form__group">
                        <input type="hidden" name="id1">
                        <label for="">
                            No.ID:
                        </label>
                        <input type="text" id="noid" name="noid" class="form-control m-input" placeholder="Masukkan ID">
                        <span class="m-form__help">
                            No.ID Anggota Luar
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Nama :
                        </label>
                        <input type="text" id="nama" name="nama" class="form-control m-input" placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Nama
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Jenis kelamin :
                        </label>
                        <select class="form-control m-bootstrap-select" id="jk" name="jk">
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <!-- <input type="text" id="jk" name="jk" class="form-control m-input" placeholder="Masukkan nama">
                                <span class="m-form__help">
                                        Jenis Kelamin
                                </span> -->
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Alamat :
                        </label>
                        <input type="text" id="alamat" name="alamat" class="form-control m-input" placeholder="Masukkan Alamat">
                        <span class="m-form__help">
                            Masukkan Alamat
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Nomor Telepon :
                        </label>
                        <input type="text" id="telepon" name="telepon" class="form-control m-input" placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Nomor Telepon
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Tempat Lahir :
                        </label>
                        <input type="text" id="tempatlahir" name="tempatlahir" class="form-control m-input" placeholder="Masukkan tempat lahir">
                        <span class="m-form__help">
                            Masukkan Tempat Lahir
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Tanggal Lahir :
                        </label>
                        <input type="date" id="tgllahir" name="tgllahir" class="form-control m-input" placeholder="Masukkan Tanggal Lahir">
                        <span class="m-form__help">
                            Masukkan Tanggal Lahir
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Nama Instansi :
                        </label>
                        <input type="text" id="instansi_asal_nama" name="instansi_asal_nama" class="form-control m-input" placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Nama Instansi
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Alamat Instansi :
                        </label>
                        <input type="text" id="instansi_asal_alamat" name="instansi_asal_alamat" class="form-control m-input" placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Alamat Instansi
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <label for="">
                            Jabatan/Semester :
                        </label>
                        <input type="text" id="jabatan_semester" name="jabatan_semester" class="form-control m-input" placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Jabatan/ Semester
                        </span>
                    </div>

                    <div class="form-group m-form__group">
                        <label for="">
                            Pas foto : <a type="button" id="download_pasfoto" class="btn m-btn m-btn--gradient-from-primary btn-sm m-btn--gradient-to-info" download><i class=" la la-download"></i></a>
                        </label>
                        <input type="file" id="file_pasphoto" name="file_pasphoto" class="form-control m-input " placeholder="Masukkan nama">
                        <span class="m-form__help">
                            Masukkan Pas foto <span class="m-form__help text-info">File type allowed: <code>JPG|JPEG</code> </span>
                        </span>
                    </div>
            </div>
            <div class="modal-footer">
                <input id="btnSave" onclick="save()" type="submit" class="btn btn-primary" value="Simpan">
            </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->