<?php
if (isset($export)) {
    if ($export == 'excel') {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$title.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    if ($export == 'web') {
        ?>
        <!--<body onload="window.print();">-->
    <?php } ?>
    <table border="1">
        <tr>
            <th>NO.</th>
            <th>NO ID</th>
            <th>Nama</th>
            <th>Status Anggota</th>
            <th>Berlaku Sampai.</th>
        </tr>
        <?php
        $count = 0;
        foreach ($data as $row) :
            $count += 1;
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row->nis; ?></td>
                <td><?php echo $row->nama; ?></td>
                <td><?php
                    if ($row->status == 1) {
                        echo 'Aktif';
                    } else {
                        echo 'Tidak Aktif';
                    }
                    ?></td>
                <td><?php echo $row->berlaku_sampai; ?></td>
            </tr>
    <?php endforeach; ?>
    </table>

    <?php
} else {
    ?><div class="m-grid__item m-grid__item--fluid m-wrapper">
        <!-- BEGIN: Subheader -->
        <div class="m-subheader ">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h3 class="m-subheader__title m-subheader__title--separator"> <?php echo $page_title; ?></h3>
                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                        <li class="m-nav__item m-nav__item--home">
                            <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                                <i class="m-nav__link-icon la la-home"></i>
                            </a>
                        </li>
                        <li class="m-nav__separator"> -</li>
                        <li class="m-nav__item">
                            <a href="" class="m-nav__link">
                                <span class="m-nav__link-text">
                                    Data Induk
                                </span>
                            </a>
                        </li>
                        <li class="m-nav__separator">
                            -
                        </li>
                        <li class="m-nav__item">
                            <a href="" class="m-nav__link">
                                <span class="m-nav__link-text">
                                <?php echo $page_title; ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END: Subheader -->
        <div class="m-content">
            <?php if ($this->session->flashdata('alert') != '') : ?>
                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade show">
                    <div class="m-alert__icon">
                        <i class="flaticon-exclamation-1"></i>
                        <span></span>
                    </div>
                    <div class="m-alert__text">
                        <?php echo $this->session->flashdata('flash_message') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text"><?php echo $page_title; ?></h3>
                        </div>
                    </div>
                    <form target="_blank" id="exportweb" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/export/web" method="post">
                        <input type="hidden" name="wpilih" id="wpilih" value="<?php echo $jenis; ?>">
                        <input type="hidden" name="wsearch" id="wsearch">
                    </form>
                    <form target="_blank" id="exportexcel" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/export/excel" method="post">
                        <input type="hidden" name="epilih" id="epilih" value="<?php echo $jenis; ?>">
                        <input type="hidden" name="esearch" id="esearch">
                    </form>
                    <?php if (in_array('laporan', $this->session->userdata('perm_print'))) : ?>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                        <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--pill  m-dropdown__toggle">
                                            <i class="la la-clone m--font-brand"></i> Export
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav">
                                                            <li class="m-nav__item">
                                                                <a href="#" onclick="document.getElementById('exportweb').submit();return false;" class="m-nav__link" target="_blank">
                                                                    <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Web
                                                                    </span>
                                                                </a>
                                                                </form>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="#" onclick="document.getElementById('exportexcel').submit();return false;" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-share"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Excel
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__separator m-nav__separator--fit m--hide"></li>
                                                            <li class="m-nav__item m--hide">
                                                                <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
                                                                    Submit
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                <?php endif; ?>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-4">
                                        <select class="form-control m-bootstrap-select" id="m_form_jenis">
                                            <option value=""></option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                            <option value="pegawai">Pegawai</option>
                                        </select>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                    </div>
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
                        </div>
                    </div>
                    <div class="m-demo-icon__preview">
                        <i class="la la-info-circle m--font-danger"></i>
                        <font class="m--font-danger"> Pilih/Centang Anggota Untuk Cetak:</font>
                    </div>

                    <div class="m--space-10"></div>
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
                                            <button id="cetak_barcode" type="button" class="btn btn-accent btn-sm">
                                                Cetak Barcode
                                            </button>
                                            &nbsp;&nbsp;&nbsp;
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
                    <div class="m_datatable" id="record_selection">
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
    let jenis = "<?php echo $jenis; ?>";

    // =============================================
    // KONFIGURASI DATATABLE
    // =============================================
    const datatableConfig = {
          data: {
              type: "remote",
              source: {
                  read: { url: `${BASE_URL}${PAGE_PATH}/fetch/` }
              },
              saveState:       { cookie: false, webstorage: false },
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
                  field:    "nis1",
                  class:    "tes",
                  title:    "#",
                  sortable: false,
                  width:    3,
                  selector: { class: "m-checkbox--solid m-checkbox--brand" }
              },
              { field: "number", title: "No",            sortable: false, width: 40, textAlign: "center" },
              { field: "nis",    title: "NIP/NIS",        filterable: false, width: 100 },
              { field: "nama",   title: "Nama" },
              { field: "kelas",  title: "Program Studi" },
              { field: "status", title: "Status",         width: 130 },
              { field: "berlaku",title: "Berlaku Sampai" },
              {
                  field:    "action",
                  title:    "Actions",
                  sortable: false,
                  width:    110,
                  overflow: "visible",
                  template: (row) => `
                      <a href="javascript:void(0)"
                         onclick="view('${row.nis}', '${jenis}')"
                         class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                         title="Edit details">
                          <i class="la la-search"></i>
                      </a>
                      <a href="data_anggota/trx_history/${jenis}/${row.nis}"
                         target="_blank"
                         class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                         title="Riwayat Transaksi">
                          <i class="la la-book"></i>
                      </a>
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
    // HELPER: AMBIL NIS YANG DIPILIH
    // =============================================
    function getSelectedNIS() {
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
    // CETAK BARCODE
    // =============================================
    function cetakBarcode(selectedNIS) {
          const scriptTag = `<script type="text/javascript" src="${BASE_URL}assets/JsBarcode.code39.min.js"><\/script>`;
          const style     = `<style>#barcodeView { width: 200px !important; }</style>`;

          const barcodeItems = selectedNIS.map(nis => `
              <div style="margin:1px; margin-top:7px; margin-right:5px; padding-left:7px;
                          padding-right:2px; border:1px solid #000; float:left;">
                  <font face="courier new" size="1">No.Inv.${nis}</font><br>
                  <img id="barcodeView" class="barcode"
                       jsbarcode-format="CODE39"
                       jsbarcode-height="90"
                       jsbarcode-width="1"
                       jsbarcode-fontSize="10"
                       jsbarcode-textMargin="0"
                       jsbarcode-value="${nis}"/>
              </div>
          `).join("");

          openPrintWindow(`${scriptTag}${style}${barcodeItems}<script>JsBarcode(".barcode").init();<\/script>`);
      }

      // =============================================
      // CETAK KARTU ANGGOTA
      // =============================================
    function cetakKartu(selectedNIS) {
        $.ajax({
            url:      `${BASE_URL}${PAGE_PATH}/cetak_kartu/${jenis}`,
            type:     "POST",
            data:     { final: selectedNIS },
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
                      <img src="${anggota.pasfoto}" style="height:25mm; width:20mm;">
                  </div>
                  <div style="float:left; padding:2px 1px 0 2px; margin-top:75px; width:45mm;">
                      <div style="padding-left:8px;">
                          <table cellpadding="1" cellspacing="1" border="0">
                              <tr valign="top"><td>Nama</td><td>:</td><td>${anggota.nm_angg}</td></tr>
                              <tr valign="top"><td>NO Anggota</td><td>:</td><td>${anggota.no_angg}</td></tr>
                              <tr valign="top"><td>Prodi</td><td>:</td><td>${anggota.prodi}</td></tr>
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
    // DOWNLOAD KARTU ANGGOTA (PDF)
    // =============================================
    function downloadKartuPDF(selectedNIS) {
        $.ajax({
            url:      `${BASE_URL}${PAGE_PATH}/cetak_kartu/${jenis}`,
            type:     "POST",
            data:     { final: selectedNIS },
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
    // Ukuran kartu dalam px (resolusi asli image)
    const CARD_W = 1040;
    const CARD_H = 650;

    // Ukuran kartu asli dalam mm: 85mm x 55mm
    // Konversi: px per mm = 1040/85 = 12.24
    const PX_PER_MM = CARD_W / 85;

    // Posisi elemen (dalam mm, sesuai desain cetak kartu asli)
    const FOTO_ML  = Math.round(7  * PX_PER_MM);   // margin-left foto
    const FOTO_MT  = Math.round(19 * PX_PER_MM);   // margin-top foto (~19mm dari atas)
    const FOTO_W   = Math.round(20 * PX_PER_MM);   // lebar foto
    const FOTO_H   = Math.round(25 * PX_PER_MM);   // tinggi foto
    const INFO_W   = Math.round(45 * PX_PER_MM);   // lebar area info
    const INFO_MT  = Math.round(20 * PX_PER_MM);   // margin-top info
    const FONT_SZ  = Math.round(9  * PX_PER_MM / 3.78); // konversi pt ke px (1pt = 3.78px, 9pt font)
    const BAR_LEFT = Math.round(50 * PX_PER_MM);   // posisi barcode dari kiri
    const BAR_H    = Math.round(8  * PX_PER_MM);   // tinggi barcode ~8mm

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
                height: ${CARD_H}px;
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
                height: ${CARD_H}px;
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
            .kartu-foto img {
                height: 100%;
                width: 100%;
                display: block;
            }
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
            .kartu-table td {
                padding: 2px 3px;
                vertical-align: top;
            }
            .barcode-img {
                position: absolute;
                bottom: ${Math.round(3 * PX_PER_MM)}px;
                left: ${BAR_LEFT}px;
            }
            * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important }
        </style>
        ${data.map((anggota) => `
            <div class="kartu-wrapper">
                <div class="kartu-depan">
                    <div class="kartu-foto">
                        <img src="${anggota.pasfoto}" crossorigin="anonymous">
                    </div>
                    <div class="kartu-info">
                        <table class="kartu-table" cellpadding="0" cellspacing="0">
                            <tr><td>Nama</td><td>:</td><td>${anggota.nm_angg}</td></tr>
                            <tr><td>NO Anggota</td><td>:</td><td>${anggota.no_angg}</td></tr>
                            <tr><td>Prodi</td><td>:</td><td>${anggota.prodi}</td></tr>
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

    // Render barcode ke canvas lalu masukkan ke img tag
    const barcodePromises = Array.from(container.querySelectorAll('.barcode-img')).map(img => {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            JsBarcode(canvas, img.dataset.value, {
                format:       "CODE39",
                height:       Math.round(4  * PX_PER_MM),  // turunkan tinggi, ~4mm
                width:        1.5,                            // naikkan bar multiplier jadi 2
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

    // Tunggu semua barcode selesai, baru capture
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
                    pdf.save('kartu_anggota.pdf');
                    document.body.removeChild(container);
                }
            });
        });
    });
}

      // =============================================
      // RELOAD DATATABLE
      // =============================================
      function reload_table() {
          $("#m_datatable_reload").trigger("click");
          table.ajax.reload();
      }

      // =============================================
      // VIEW DETAIL ANGGOTA
      // =============================================
      function view(id, jenis) {
          $.ajax({
              url:      `${BASE_URL}${PAGE_PATH}/get/${jenis}/${id}`,
              type:     "GET",
              dataType: "JSON",
              success: function (data) {
                  const anggota = data[0];
                  $('#nis').text(anggota.nis);
                  $('#nama').text(anggota.nama);
                  $('#jk').text(anggota.jk === 'P' ? 'Perempuan' : 'Laki-Laki');
                  $('#ttl').text(`${anggota.tempatlahir} / ${anggota.tgllahir}`);
                  $('.modal-title').text('Lihat Data Anggota');
                  $('#m_Modal').modal('show');
              },
              error: () => alert('Error get data from ajax')
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
                      $("#esearch, #wsearch").val(q.generalSearch);
                  })
                  .val(query.generalSearch);

              // Filter Jenis
              $("#m_form_jenis")
                  .on("change", function () {
                      const q = dt.getDataSourceQuery();
                      q.jenis = $(this).val().toLowerCase();
                      dt.setDataSourceQuery(q);
                      dt.load();
                      $("#epilih, #wpilih").val(q.jenis);
                      jenis = q.jenis;
                  })
                  .val(query.jenis !== undefined ? query.jenis : "")
                  .selectpicker();

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

              // Tombol Cetak Barcode
              $("#cetak_barcode").on("click", function () {
                  const selected = getSelectedNIS();
                  if (selected.length) cetakBarcode(selected);
              });

              // Tombol Cetak Kartu
              $("#cetak_kartu").on("click", function () {
                  const selected = getSelectedNIS();
                  if (selected.length) cetakKartu(selected);
              });
                // Tombol Download Kartu PDF
                $("#download_kartu").on("click", function () {
                    const selected = getSelectedNIS();
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
  </script>
    <!--begin::Modal-->

    <div class="modal fade" id="m_Modal" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModal"></h5>
                </div>
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">ID </div>:
                            <div class="col-lg-5">
                                <p id="nis"></p>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">Nama </div>:
                            <div class="col-lg-5">
                                <b>
                                    <p id="nama"></p>
                                </b>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">Jenis Kelamin </div>:
                            <div class="col-lg-5">
                                <p id="jk"></p>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">Tempat/Tgl Lahir </div>:
                            <div class="col-lg-5">
                                <p id="ttl"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->

<?php } ?>