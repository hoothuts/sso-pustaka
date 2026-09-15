<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item"><span class="m-nav__link-text">Laporan</span></li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item"><span class="m-nav__link-text"><?php echo $page_title; ?></span></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="m-content">
        <form target="_blank" id="exportweb" action="<?php echo base_url(); ?>dir/laporan_anggota_teraktif/export/web" method="post">
            <input type="hidden" name="wpilih" value="<?php if (isset($jenis)) echo $jenis; ?>">
            <input type="hidden" name="wprodi" value="<?php if (isset($pilihprodi)) echo $pilihprodi; ?>">
            <input type="hidden" name="wtanggalawal" value="<?php if (isset($tanggalawal)) echo $tanggalawal; ?>">
            <input type="hidden" name="wtanggalakhir" value="<?php if (isset($tanggalakhir)) echo $tanggalakhir; ?>">
        </form>
        <form target="_blank" id="exportexcel" action="<?php echo base_url(); ?>dir/laporan_anggota_teraktif/export/excel" method="post">
            <input type="hidden" name="epilih" value="<?php if (isset($jenis)) echo $jenis; ?>">
            <input type="hidden" name="eprodi" value="<?php if (isset($pilihprodi)) echo $pilihprodi; ?>">
            <input type="hidden" name="etanggalawal" value="<?php if (isset($tanggalawal)) echo $tanggalawal; ?>">
            <input type="hidden" name="etanggalakhir" value="<?php if (isset($tanggalakhir)) echo $tanggalakhir; ?>">
        </form>

        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Daftar Anggota Teraktif</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right" data-dropdown-toggle="hover">
                                <a href="#" class="btn btn-secondary m-btn m-btn--icon m-btn--pill m-dropdown__toggle">
                                    <i class="la la-clone"></i> Export
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <ul class="m-nav">
                                                <li class="m-nav__item">
                                                    <a href="#" onclick="$('#exportweb').submit(); return false;" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                        <span class="m-nav__link-text">Web View</span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="#" onclick="$('#exportexcel').submit(); return false;" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                        <span class="m-nav__link-text">Excel</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="m-portlet__body">
                <form action="<?php echo base_url('dir/laporan_anggota_teraktif'); ?>" method="post" class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group row align-items-center">
                                <label class="col-md-1 m-label">Tanggal :</label>
                                <div class="col-md-2">
                                    <div class='input-group date' id='m_datepicker_1'>
                                        <input type='text' name="tanggalawal" id="tanggalawal" class="form-control" value="<?php echo $tanggalawal; ?>" readonly placeholder="Awal"/>
                                        <span class="input-group-addon"><i class="la la-calendar"></i></span>
                                    </div>
                                </div>
                                <label class="col-md-1 m-label text-center">S/D</label>
                                <div class="col-md-2">
                                    <div class='input-group date' id='m_datepicker_2'>
                                        <input type='text' name="tanggalakhir" id="tanggalakhir" class="form-control" value="<?php echo $tanggalakhir; ?>" readonly placeholder="Akhir"/>
                                        <span class="input-group-addon"><i class="la la-calendar"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-2">
                                    <div class="m-form__controlv">
                                        <select class="form-control" name="pilih" id="pilih">
                                            <option value="mahasiswa" <?php if ($jenis == 'mahasiswa') echo 'selected'; ?>>Mahasiswa</option>
                                            <option value="pegawai" <?php if ($jenis == 'pegawai') echo 'selected'; ?>>Pegawai</option>
                                            <option value="anggota+luar" <?php if ($jenis == 'anggota+luar') echo 'selected'; ?>>Anggota Luar</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 p-prodi">
                                    <div class="m-form__controlv">
                                        <select class="form-control" name="pilihprodi" id="pilihprodi">
                                            <option value="All">Pilih Prodi</option>
                                            <?php foreach ($prodi as $key): ?>
                                                <option value="<?php echo $key->nmmspst ?>" <?php if ($pilihprodi == $key->nmmspst) echo 'selected'; ?>><?php echo $key->nmmspst ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="location.href='<?php echo base_url('laporan_anggota_teraktif'); ?>'">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="m_datatable" id="ajax_data"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var DatatableRemoteAjaxDemo = function() {
        var t = function() {
            var e = $(".m_datatable").mDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "<?php echo base_url('dir/laporan_anggota_teraktif/fetch/'); ?>",
                            params: {
                                query: {
                                    tanggalawal: '<?php echo $tanggalawal; ?>',
                                    tanggalakhir: '<?php echo $tanggalakhir; ?>',
                                    jenis: '<?php if ($jenis) echo $jenis; ?>',
                                    pilihprodi: '<?php if ($pilihprodi) echo $pilihprodi; ?>'
                                }
                            }
                        }
                    },
                    pageSize: 10,
                    serverPaging: !0,
                    serverFiltering: !0,
                    serverSorting: !0
                },
                layout: { theme: "default", scroll: !1, footer: !1 },
                sortable: !0,
                pagination: !0,
                columns: [
                    { field: "number", title: "#", width: 40, textAlign: "center", sortable: !1 },
                    { field: "no_anggota", title: "NO Anggota", width: 150 },
                    { field: "nama", title: "Nama Anggota", width: 250 },
                    { field: "prodi", title: "Program Studi", width: 120 },
                    { field: "total_pinjam", title: "Total Peminjaman", textAlign: "center", width: 100 }
                ]
            });
        };
        return { init: function() { t() } }
    }();

    jQuery(document).ready(function() {
        DatatableRemoteAjaxDemo.init();
        $("#m_datepicker_1, #m_datepicker_2").datepicker({
            format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", autoclose: !0
        });
        
        
    });
</script>